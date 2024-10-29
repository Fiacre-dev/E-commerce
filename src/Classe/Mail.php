<?php

namespace App\Classe;

use Mailjet\Client;
use Mailjet\Resources;

class Mail
{

    private $api_key = "d4a0a144e4aa1abdc45ea3a59b549b07";
    private $api_key_secret = "bc1c8e5ab3f5a71d32926d41c0d2337d";
    public function send($to_email,$to_name,$subject,$content)
    {
        $mj=new Client($this->api_key,$this->api_key_secret ,true,['version' => 'v3.1']);
        $body = [
            'Messages' => [
                [
                    'From' => [
                        'Email' => "fccarsone@gmail.com",
                        'Name' => "La boutique"
                    ],
                    'To' => [
                        [
                            'Email' => $to_email,
                            'Name' => $to_name
                        ]
                    ],
                    'TemplateID' => 6417928,
                    'TemplateLanguage' => true,
                    'Subject' => $subject,
                    'Variables' => [
                        "content"=>$content
                    ]
                ]
            ]
        ];
        $response = $mj->post(Resources::$Email, ['body' => $body]);
        if ($response->success()) {
            return $response->getData();
        } else {
            throw new \Exception('Erreur lors de l\'envoi de l\'email');
        }
    }
}