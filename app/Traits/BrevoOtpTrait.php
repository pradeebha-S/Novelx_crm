<?php

namespace App\Traits;

use App\Models\EmailOtp;
use Carbon\Carbon;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Log;
use SendinBlue\Client\Configuration;
use SendinBlue\Client\Api\TransactionalEmailsApi;
use GuzzleHttp\Client;

trait BrevoOtpTrait
{

public function sendBrevoOtp($email)
{
    try {

        Log::info('================ SEND BREVO OTP START ================');

        /*
        |--------------------------------------------------------------------------
        | CLEAN EMAIL
        |--------------------------------------------------------------------------
        */

        $email = strtolower(trim($email));

        Log::info('Step 1: Incoming Email', [
            'email' => $email
        ]);

        /*
        |--------------------------------------------------------------------------
        | VALIDATE EMAIL
        |--------------------------------------------------------------------------
        */

        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {

            Log::warning('Invalid Email Format', [
                'email' => $email
            ]);

            return [
                'status' => 'error',
                'message' => 'Invalid email format'
            ];
        }

        /*
        |--------------------------------------------------------------------------
        | CHECK EXISTING OTP
        |--------------------------------------------------------------------------
        */

        Log::info('Step 2: Checking Existing OTP');

        $checkOtp = EmailOtp::where('email', $email)->first();

        Log::info('Existing OTP Result', [
            'exists' => $checkOtp ? true : false,
            'data' => $checkOtp
        ]);

        /*
        |--------------------------------------------------------------------------
        | RESEND LIMIT - 60 SECONDS
        |--------------------------------------------------------------------------
        */

        Log::info('Step 3: Checking Resend Limit');

        if (
            $checkOtp &&
            $checkOtp->last_sent_at &&
            $checkOtp->last_sent_at->addSeconds(60)->isFuture()
        ) {

            Log::warning('Resend blocked - wait 60 seconds', [
                'last_sent_at' => $checkOtp->last_sent_at
            ]);

            return [
                'status' => 'error',
                'message' => 'Please wait before resend OTP'
            ];
        }

        /*
        |--------------------------------------------------------------------------
        | GENERATE OTP
        |--------------------------------------------------------------------------
        */

        Log::info('Step 4: Generating OTP');

        $otp = rand(100000, 999999);

        Log::info('Generated OTP', [
            'otp' => $otp
        ]);

        /*
        |--------------------------------------------------------------------------
        | ENCRYPT OTP
        |--------------------------------------------------------------------------
        */

        Log::info('Step 5: Encrypting OTP');

        $encryptedOtp = Crypt::encryptString($otp);

        Log::info('OTP Encrypted Successfully');

        /*
        |--------------------------------------------------------------------------
        | BREVO CONFIG
        |--------------------------------------------------------------------------
        */

        Log::info('Step 6: Loading Brevo Config');

        Log::info('Brevo ENV Check', [

            'BREVO_API_KEY' =>
                env('BREVO_API_KEY') ? 'FOUND' : 'MISSING',

            'BREVO_SENDER_NAME' =>
                env('BREVO_SENDER_NAME'),

            'BREVO_SENDER_EMAIL' =>
                env('BREVO_SENDER_EMAIL')
        ]);

        $config = Configuration::getDefaultConfiguration()
            ->setApiKey(
                'api-key',
                env('BREVO_API_KEY')
            );

        Log::info('Brevo Configuration Created');

        $apiInstance = new TransactionalEmailsApi(
            new Client(),
            $config
        );

        Log::info('Brevo API Instance Created');

        /*
        |--------------------------------------------------------------------------
        | EMAIL TEMPLATE
        |--------------------------------------------------------------------------
        */

        Log::info('Step 7: Preparing Email Template');

        $sendSmtpEmail = new \SendinBlue\Client\Model\SendSmtpEmail([

            'subject' => 'OTP Verification',

            'sender' => [

                'name' => env('BREVO_SENDER_NAME'),

                'email' => env('BREVO_SENDER_EMAIL')
            ],

            'to' => [
                [
                    'email' => $email
                ]
            ],

            'htmlContent' => "

                <div style='padding:20px;font-family:Arial;'>

                    <h2>Email OTP Verification</h2>

                    <p>Your OTP:</p>

                    <h1 style='color:green;'>{$otp}</h1>

                    <p>
                        OTP valid for 60 seconds
                    </p>

                </div>

            "
        ]);

        Log::info('Email Template Ready');

        /*
        |--------------------------------------------------------------------------
        | SEND EMAIL
        |--------------------------------------------------------------------------
        */

        Log::info('Step 8: Sending Email Through Brevo');

        $response = $apiInstance->sendTransacEmail(
            $sendSmtpEmail
        );

        /*
        |--------------------------------------------------------------------------
        | BREVO FULL RESPONSE LOG
        |--------------------------------------------------------------------------
        */

        Log::info('================ BREVO RESPONSE START ================', [

            'email' => $email,

            'response_object' => $response,

            'response_json' => json_encode($response),

            'message_id' => method_exists($response, 'getMessageId')
                ? $response->getMessageId()
                : null
        ]);

        Log::info('================ BREVO RESPONSE END ================');

        /*
        |--------------------------------------------------------------------------
        | STORE OTP AFTER SUCCESSFUL MAIL SEND
        |--------------------------------------------------------------------------
        */

        Log::info('Step 9: Storing OTP In Database');

        $savedOtp = EmailOtp::updateOrCreate(

            ['email' => $email],

            [
                'otp' => $encryptedOtp,

                'expires_at' => Carbon::now()->addMinute(),

                'last_sent_at' => now(),

                'attempts' => 0,

                'is_verified' => 0
            ]
        );

        Log::info('OTP Stored Successfully', [
            'otp_id' => $savedOtp->id
        ]);

        Log::info('================ SEND BREVO OTP END ================');

        return [

            'status' => 'success',

            'message' => 'OTP sent successfully',

            'response' => $response
        ];

    } catch (\Exception $e) {

        Log::error('================ SEND BREVO OTP ERROR ================', [

            'email' => $email,

            'message' => $e->getMessage(),

            'code' => $e->getCode(),

            'file' => $e->getFile(),

            'line' => $e->getLine(),

            'trace' => $e->getTraceAsString(),

            'full_exception' => (string) $e
        ]);

        return [

            'status' => 'error',

            'message' => $e->getMessage()
        ];
    }
}
public function verifyBrevoOtp($email, $otp)
{
    try {

        Log::info('================ VERIFY BREVO OTP START ================');

        Log::info('Step 1: Incoming Verify Request', [
            'email' => $email,
            'otp' => $otp
        ]);

        /*
        |--------------------------------------------------------------------------
        | FIND OTP
        |--------------------------------------------------------------------------
        */

        Log::info('Step 2: Finding OTP Record');

        $otpData = EmailOtp::where('email', $email)->first();

        Log::info('OTP Record Result', [
            'exists' => $otpData ? true : false,
            'data' => $otpData
        ]);

        if (!$otpData) {

            Log::warning('OTP Record Not Found');

            return [

                'status' => 'error',

                'message' => 'OTP not found'
            ];
        }

        /*
        |--------------------------------------------------------------------------
        | OTP EXPIRED
        |--------------------------------------------------------------------------
        */

        Log::info('Step 3: Checking OTP Expiry', [
            'current_time' => Carbon::now(),
            'expires_at' => $otpData->expires_at
        ]);

        if (
            Carbon::now()->gt(
                $otpData->expires_at
            )
        ) {

            Log::warning('OTP Expired');

            return [

                'status' => 'error',

                'message' => 'OTP expired'
            ];
        }

        /*
        |--------------------------------------------------------------------------
        | OTP ALREADY USED
        |--------------------------------------------------------------------------
        */

        Log::info('Step 4: Checking OTP Already Used', [
            'is_verified' => $otpData->is_verified
        ]);

        if ($otpData->is_verified == 1) {

            Log::warning('OTP Already Used');

            return [

                'status' => 'error',

                'message' => 'OTP already used'
            ];
        }

        /*
        |--------------------------------------------------------------------------
        | MAX ATTEMPTS
        |--------------------------------------------------------------------------
        */

        Log::info('Step 5: Checking Attempt Count', [
            'attempts' => $otpData->attempts
        ]);

        if ($otpData->attempts >= 5) {

            Log::warning('Maximum OTP Attempts Reached');

            return [

                'status' => 'error',

                'message' => 'Too many attempts'
            ];
        }

        /*
        |--------------------------------------------------------------------------
        | DECRYPT OTP
        |--------------------------------------------------------------------------
        */

        Log::info('Step 6: Decrypting OTP');

        $decryptOtp = Crypt::decryptString(
            $otpData->otp
        );

        Log::info('OTP Decrypted Successfully', [
            'decrypted_otp' => $decryptOtp
        ]);

        /*
        |--------------------------------------------------------------------------
        | INVALID OTP
        |--------------------------------------------------------------------------
        */

        Log::info('Step 7: Comparing OTP');

        if ($decryptOtp != $otp) {

            Log::warning('Invalid OTP Entered', [
                'entered_otp' => $otp,
                'stored_otp' => $decryptOtp
            ]);

            $otpData->increment('attempts');

            Log::info('Attempt Count Increased', [
                'new_attempts' => $otpData->attempts + 1
            ]);

            return [

                'status' => 'error',

                'message' => 'Invalid OTP'
            ];
        }

        /*
        |--------------------------------------------------------------------------
        | VERIFIED
        |--------------------------------------------------------------------------
        */

        Log::info('Step 8: OTP Verified Successfully');

        $otpData->update([

            'is_verified' => 1
        ]);

        Log::info('OTP Marked As Verified');

        Log::info('================ VERIFY BREVO OTP END ================');

        return [

            'status' => 'success',

            'message' => 'OTP verified successfully'
        ];

    } catch (\Exception $e) {

        Log::error('================ VERIFY BREVO OTP ERROR ================', [

            'email' => $email,

            'message' => $e->getMessage(),

            'file' => $e->getFile(),

            'line' => $e->getLine(),

            'trace' => $e->getTraceAsString()
        ]);

        return [

            'status' => 'error',

            'message' => $e->getMessage()
        ];
    }
}
}