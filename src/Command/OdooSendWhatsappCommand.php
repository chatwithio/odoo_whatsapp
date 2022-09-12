<?php

namespace App\Command;

use App\Entity\OdooContact;
use App\Repository\OdooBusinessRepository;
use App\Repository\OdooContactRepository;
use App\Repository\OdooSentContactRepository;
use App\Service\MessageService;
use GuzzleHttp\Exception\GuzzleException;
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

                $contacts = $client->search_read('res.partner', [], ['name', 'phone']);

                if ($contacts) {
                    foreach ($contacts as $contact) {
                        if (!empty($contact['phone'])) {
                            $odooContact = $this->odooContactRepository->findOneBy(['odoo_id' => $contact['id']]);

                            if (!$odooContact) {
                                $odooContact = new OdooContact();
                            }

                            $odooContact->setOdooBusiness($odooBusiness);
                            $odooContact->setOdooId($contact['id']);
                            $odooContact->setName($contact['name']);
                            $odooContact->setPhone($contact['phone']);

                            $this->odooContactRepository->add($odooContact, true);

                            /*$this->messageService->sendWhatsApp($contact['phone'], [], $_ENV['WHATSAPP_TEMPLATE_NAME'], 'en', $_ENV['WHATSAPP_TEMPLATE_NAMESPACE']);

                            $this->bus->dispatch(new WhatsappNotification('Whatsapp me!'));

                            $io->success('Message has been sent Successfully');*/
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
}
