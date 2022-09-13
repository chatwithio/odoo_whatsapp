<?php

namespace App\Service;

use Exception;
use Symfony\Contracts\HttpClient\HttpClientInterface;

class MessageService
{

    private $client;

    private $wa_id = false;

    private array $envs = [
        'PROD' => 'https://waba.360dialog.io',
        'DEV' => 'https://waba-sandbox.360dialog.io',

    ];

    //The api endpoints. There are more but these are the important ones
    private array $endpoint = [
        'contact' => [
            'method' => 'POST',
            'url' => '/v1/contacts'
        ],
        'message' => [
            'method' => 'POST',
            'url' => '/v1/messages'
        ]
        ,
        'template' => [
            'method' => 'GET',
            'url' => '/v1/configs/templates'
        ],
        'getWebhook' => [
            'method' => 'GET',
            'url' => '/v1/configs/webhook'
        ],
        'makeWebhook' => [
            'method' => 'POST',
            'url' => '/v1/configs/webhook'
        ],
    ];

    //The header information. It contains the auth token too
    private array $headers = [
        'Content-Type' => 'application/json',
        'D360-API-KEY' => null
    ];


    //False of the telephone number has KOed by whatsapp,
    //eg. if the user does not have whatsapp or it is a malformed number
    private bool $payloadOk = true;


    /**
     * WhatsApp360 constructor.
     */
    public function __construct(HttpClientInterface $client)
    {
        $this->client = $client;

        $this->headers['D360-API-KEY'] = $_ENV['WHATSAPP_KEY'];

        foreach ($this->endpoint as $k => $v) {
            $this->endpoint[$k]['url'] = $this->envs[$_SERVER['WHATSAPP_ENV']] . $this->endpoint[$k]['url'];
        }
    }

    /**
     * @param $endpoint (The endpoint to be used - see private $endpoint)
     * @param array $data
     * @return void (if there is any error an exception should be thrown)
     * @throws \GuzzleHttp\Exception\GuzzleException
     */

    private function send($endpoint, $data = [])
    {

        try {
            if ($this->payloadOk === true) {

                //$client = new GuzzleHttp\Client();

                $request = $this->client->request(
                    $this->endpoint[$endpoint]['method'],
                    $this->endpoint[$endpoint]['url'],
                    [
                        "headers" => $this->headers,
                        "json" => $data
                    ]
                );

                if ($request->getStatusCode() == 200 || $request->getStatusCode() == 201) {
                    return json_decode($request->getContent());
                } else {
                    throw new Exception($request->getStatusCode());
                }
            } else {
                throw new Exception('Unvalidated payload Exception');
            }
        } catch (Exception $exception) {
            $this->logErrors($exception);
        }
    }




    /**
     * @param $contact (whatapp number with no + or spacing)
     * @return bool (return true if the contact id OKed)
     * @throws \GuzzleHttp\Exception\GuzzleException
     */

    public function checkContact($contact)
    {
        try {
            $payload = [
                "blocking" => "wait",
                "contacts" => ["+" . $contact],
                "force_check" => true
            ];

            $response = $this->send('contact', $payload);

            if (!empty($response->contacts)) {
                if (isset($response->contacts[0]) && isset($response->contacts[0]->status) && $response->contacts[0]->status == 'valid') {
                    $this->wa_id = $response->contacts[0]->wa_id;
                    return true;
                }
            }
            return false;

        } catch (Exception $exception) {
            $this->logErrors($exception);
        }
    }

    /**
     * @param $placeholders (an array of text only placeholders)
     * @return array
     */

    private function buildParams($placeholders)
    {
        $arr = [];
        foreach ($placeholders as $placeholder) {
            $arr[] = [
                "type" => "text",
                "text" => $placeholder
            ];
        }
        return $arr;
    }

    /**
     * @param $to (whatapp number  - no spaces or = )
     * @param $placeholders array (of placeholders)
     * @param $template string (template name)
     * @param $language string (ie en, must match the language of the approved template)
     * @param $namespace  string (template namespace  - you can get this from the getTemplates api)
     * @throws \GuzzleHttp\Exception\GuzzleException
     *
     * NOTE, a template must be approved before it can be used
     */

    public function sendWhatsApp($to, $placeholders, $template, $language, $namespace)
    {
        $this->checkContact($to);



        $payload = [
            "to" => $to,
            "type" => "template",
            "template" => [
                "namespace" => $namespace,
                "language" => [
                    "policy" => "deterministic",
                    "code" => $language
                ],
                "name" => $template,
                "components" => [
                    [
                        "type" => "body",
                        "parameters" => $this->buildParams($placeholders)

                    ]
                ]
            ]
        ];

        return $this->send('message', $payload);
    }

    /**
     * @return array (list of templates  - with their nmespaces and approved status)
     */


    public function sendWhatsAppText($to, $message)
    {
        $payload = [
            "to" => '923241494612',
            "type" => "text",
            "text" => ["body" => $message]

        ];

        return $this->send('message', $payload);
    }


    public function makeWebhook($url)
    {
        $payload = [
            "url" => $url
        ];

        return $this->send('makeWebhook', $payload);
    }

    public function getWebhook()
    {
        return $this->send('getWebhook');
    }


    public function getTemplates()
    {
        return $this->send('template');
    }

    private function logErrors(Exception $exception)
    {
        print "ERROR LOGGED:" . $exception->getMessage() . "\n";
    }
}


