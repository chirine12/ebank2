<?php
// SMSService.php

namespace App\Service;

use Twilio\Exceptions\TwilioException;
use Twilio\Rest\Client;

// Dans votre classe SMSService

class SMSService
{
    private $twilioClient;
    private $twilioPhoneNumber;
    private $accountSid;
    private $authToken;

    public function __construct(string $accountSid, string $authToken, string $twilioPhoneNumber)
    {
        $this->accountSid = $accountSid;
        $this->authToken = $authToken;
        $this->twilioPhoneNumber = $twilioPhoneNumber;
        $this->twilioClient = new Client($accountSid, $authToken);
    }

    public function sendAccountDeactivationSMS( $toPhoneNumber)
    {
        // Utilisation des informations de configuration stockées dans la classe
        // Remplacez cela par le numéro de téléphone réel que vous souhaitez utiliser
        $message = 'Votre compte a été désactivé par l\'administrateur. Contactez le support pour plus d\'informations.';

        $this->twilioClient->messages->create(
            $toPhoneNumber,
            [
                'from' => $this->twilioPhoneNumber,
                'body' => $message,
            ]
        );
    }
}

