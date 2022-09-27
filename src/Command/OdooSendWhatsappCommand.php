<?php

namespace App\Command;

use App\Entity\OdooContact;
use App\Entity\OdooSentContact;
use App\Message\WhatsappNotification;
use App\Repository\OdooBusinessRepository;
use App\Repository\OdooContactRepository;
use App\Repository\OdooSentContactRepository;
use App\Service\MessageService;
use GuzzleHttp\Exception\GuzzleException;
use libphonenumber\PhoneNumberUtil;
use Ripoo\OdooClient;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\Messenger\MessageBusInterface;

#[AsCommand(
    name: 'odoo:send-whatsapp',
    description: 'Retrieves Latest Odoo Contacts and Sends Whatsapp',
)]
class OdooSendWhatsappCommand extends Command
{
    private MessageService $messageService;
    private MessageBusInterface $bus;
    private OdooBusinessRepository $odooBusinessRepository;
    private OdooContactRepository $odooContactRepository;
    private OdooSentContactRepository $odooSentContactRepository;

    public function __construct(
        MessageBusInterface $bus,
        MessageService $messageService,
        OdooBusinessRepository $odooBusinessRepository,
        OdooContactRepository $odooContactRepository,
        OdooSentContactRepository $odooSentContactRepository,
    ) {
        parent::__construct();

        $this->bus = $bus;
        $this->messageService = $messageService;
        $this->odooBusinessRepository = $odooBusinessRepository;
        $this->odooContactRepository = $odooContactRepository;
        $this->odooSentContactRepository = $odooSentContactRepository;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);

        try {
            $odooBusinesses = $this->odooBusinessRepository->findAll();

            foreach ($odooBusinesses as $odooBusiness) {
                $client = new OdooClient(
                    $odooBusiness->getHost(),
                    $odooBusiness->getDb(),
                    $odooBusiness->getName(),
                    $odooBusiness->getApiKey()
                );

                $contacts = $client->search_read(
                    'res.partner',
                    [['write_date', '>', date('Y-m-d H:i:s', strtotime('-1 week'))]],
                    ['name', 'mobile', 'write_date', 'category_id']
                );

                if ($contacts) {
                    foreach ($contacts as $contact) {
                        if (!empty($contact['mobile'])) {
                            $mobile = $this->getMobileNumberWithCode($contact['mobile']);
                            $odooContact = $this->odooContactRepository->findOneBy(['odoo_id' => $contact['id']]);

                            if (!$odooContact) {
                                $odooContact = new OdooContact();
                            }

                            if (!empty($contact['category_id'])) {
                                $odooContact->setTagId($contact['category_id'][0]);
                            }

                            $odooContact->setOdooBusiness($odooBusiness);
                            $odooContact->setOdooId($contact['id']);
                            $odooContact->setName($contact['name']);
                            $odooContact->setPhone($mobile);

                            $this->odooContactRepository->add($odooContact, true);
                            $odooSentContact = $this->odooSentContactRepository->findOneBy(
                                ['odooContact' => $odooContact]
                            );

                            if (!$odooSentContact) {
                                $response = $this->messageService->sendWhatsApp(
                                    $mobile,
                                    ['name' => $odooContact->getName()],
                                    $_ENV['WHATSAPP_TEMPLATE_NAME'],
                                    $_ENV['WHATSAPP_TEMPLATE_LANGUAGE'],
                                    $_ENV['WHATSAPP_TEMPLATE_NAMESPACE']
                                );

                                if ($response) {
                                    $this->bus->dispatch(new WhatsappNotification('Whatsapp me!'));
                                    $io->success(
                                        'Message has been sent successfully to ' . $odooContact->getName(
                                        ) . ' (' . $mobile . ')'
                                    );
                                    $odooSentContact = new OdooSentContact();
                                    $odooSentContact->setOdooContact($odooContact);
                                    $odooSentContact->setMessage($response->messages[0]->id);

                                    $this->odooSentContactRepository->add($odooSentContact, true);
                                } else {
                                    $io->error(
                                        'Message has was not sent successfully to ' . $odooContact->getName(
                                        ) . ' (' . $mobile . ')'
                                    );
                                }
                            } else {
                                $io->warning('Already sent SMS to ' . $odooContact->getName() . ' (' . $mobile . ')');

                                if (!empty($contact['category_id'])) {
                                    $categoryId = $contact['category_id'][0];

                                    // status has been changed
                                    if ($categoryId !== $odooContact->getTagId()) {
                                        // query to fetch tags against the contact
                                        $tags = $client->search_read(
                                            'res.partner.category',
                                            [['id', '=', $categoryId]],
                                            ['name']
                                        );

                                        $odooContact->setTagId($categoryId); // update the db

                                        // send message status has been update
                                        $updateStatusResponse = $this->messageService->sendWhatsApp(
                                            $mobile,
                                            [$tags[0]['name']],
                                            $_ENV['WHATSAPP_TEMPLATE_ODOO_STATUS'],
                                            $_ENV['WHATSAPP_TEMPLATE_LANGUAGE'],
                                            $_ENV['WHATSAPP_TEMPLATE_NAMESPACE']
                                        );

                                        if ($updateStatusResponse) {
                                            $io->success(
                                                'Update status message has been sent successfully to ' .
                                                $odooContact->getName() . ' (' . $mobile . ')'
                                            );
                                        } else {
                                            $io->error(
                                                'Update status message has was not sent successfully to '
                                                . $odooContact->getName() . ' (' . $mobile . ')'
                                            );
                                        }
                                    }
                                }
                            }
                        }
                    }
                }
            }

            return Command::SUCCESS;
        } catch (GuzzleException $exception) {
            $io->error($exception->getMessage());

            return Command::FAILURE;
        }
    }

    private function getMobileNumberWithCode($mobile): string
    {
        $phoneNumberUtil = PhoneNumberUtil::getInstance();
        $phoneNumberObject = $phoneNumberUtil->parse($mobile, 'ES');

        return $phoneNumberObject->getCountryCode() . $phoneNumberObject->getNationalNumber();
    }
}
