<?php

namespace App\Traits;

use Illuminate\Support\Facades\Log;
use SendinBlue\Client\Configuration;
use SendinBlue\Client\Api\TransactionalEmailsApi;
use SendinBlue\Client\Model\SendSmtpEmail;
use GuzzleHttp\Client;

trait BrevoMailTrait
{
    public function sendBrevoMail(
        $toEmail,
        $toName,
        $subject,
        $htmlContent,
        $attachments = []
    ) {

        try {

            Log::info('================ BREVO MAIL START ================');

            /*
            |--------------------------------------------------------------------------
            | CHECK ENV VALUES
            |--------------------------------------------------------------------------
            */

            Log::info('Brevo ENV Check', [

                'BREVO_API_KEY' =>
                    env('BREVO_API_KEY') ? 'FOUND' : 'MISSING',

                'BREVO_SENDER_NAME' =>
                    env('BREVO_SENDER_NAME'),

                'BREVO_SENDER_EMAIL' =>
                    env('BREVO_SENDER_EMAIL')

            ]);

            /*
            |--------------------------------------------------------------------------
            | BREVO CONFIG
            |--------------------------------------------------------------------------
            */

            Log::info('Creating Brevo Configuration');

            $config = Configuration::getDefaultConfiguration()
                ->setApiKey(
                    'api-key',
                    env('BREVO_API_KEY')
                );

            Log::info('Brevo Configuration Created Successfully');

            /*
            |--------------------------------------------------------------------------
            | API INSTANCE
            |--------------------------------------------------------------------------
            */

            $apiInstance = new TransactionalEmailsApi(
                new Client(),
                $config
            );

            Log::info('TransactionalEmailsApi Instance Created');

            /*
            |--------------------------------------------------------------------------
            | MAIL DETAILS
            |--------------------------------------------------------------------------
            */

            Log::info('Preparing Mail Payload', [

                'to_email' => $toEmail,

                'to_name' => $toName,

                'subject' => $subject,

                'attachments_count' => count($attachments)

            ]);

            /*
            |--------------------------------------------------------------------------
            | MAIL DATA
            |--------------------------------------------------------------------------
            */

            $mailData = [

                'sender' => [

                    'name'  => env('BREVO_SENDER_NAME'),

                    'email' => env('BREVO_SENDER_EMAIL'),

                ],

                'to' => [[

                    'email' => $toEmail,

                    'name'  => $toName,

                ]],

                'subject' => $subject,

                'htmlContent' => $htmlContent,

            ];

            /*
            |--------------------------------------------------------------------------
            | ADD ATTACHMENTS ONLY IF EXISTS
            |--------------------------------------------------------------------------
            */

            if (!empty($attachments)) {

                $mailData['attachment'] = $attachments;

                Log::info('Attachments Added To Mail Payload', [
                    'total_attachments' => count($attachments)
                ]);
            } else {

                Log::info('No Attachments Found, Sending Normal Mail');
            }

            /*
            |--------------------------------------------------------------------------
            | SMTP EMAIL OBJECT
            |--------------------------------------------------------------------------
            */

            $sendSmtpEmail = new SendSmtpEmail($mailData);

            Log::info('SMTP Payload Prepared Successfully');

            /*
            |--------------------------------------------------------------------------
            | SEND EMAIL
            |--------------------------------------------------------------------------
            */

            Log::info('Sending Email To Brevo');

            $response = $apiInstance->sendTransacEmail(
                $sendSmtpEmail
            );

            /*
            |--------------------------------------------------------------------------
            | RESPONSE LOG
            |--------------------------------------------------------------------------
            */

            Log::info('================ BREVO RESPONSE START ================', [

                'response_object' => $response,

                'response_json' => json_encode($response),

                'message_id' => method_exists($response, 'getMessageId')
                    ? $response->getMessageId()
                    : null

            ]);

            Log::info('================ BREVO RESPONSE END ================');

            Log::info('Mail Sent Successfully');

            Log::info('================ BREVO MAIL END ================');

            return $response;

        } catch (\Exception $e) {

            Log::error('================ BREVO MAIL ERROR ================', [

                'message' => $e->getMessage(),

                'code' => $e->getCode(),

                'file' => $e->getFile(),

                'line' => $e->getLine(),

                'trace' => $e->getTraceAsString(),

                'full_exception' => (string) $e

            ]);

            return false;
        }
    }
}