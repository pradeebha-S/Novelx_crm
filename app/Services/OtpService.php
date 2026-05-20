<?php

namespace App\Services;

use App\Models\EmailOtp;
use Carbon\Carbon;
use Exception;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Log;
use Brevo\Client\Api\TransactionalEmailsApi;
use Brevo\Client\Configuration;
use GuzzleHttp\Client;

class OtpService
{
    public function sendOtp($email)
    {
        try {

            $existingOtp = EmailOtp::where('email', $email)->first();

            // Rate Limiting
            if (
                $existingOtp &&
                $existingOtp->last_sent_at &&
                now()->diffInSeconds($existingOtp->last_sent_at) < env('OTP_RESEND_SECONDS', 60)
            ) {

                return [
                    'status' => false,
                    'message' => 'Please wait before requesting another OTP.'
                ];
            }

            $otp = rand(100000, 999999);

            $encryptedOtp = Crypt::encryptString($otp);

            EmailOtp::updateOrCreate(
                ['email' => $email],
                [
                    'otp' => $encryptedOtp,
                    'expires_at' => Carbon::now()->addMinutes(env('OTP_EXPIRE_MINUTES', 5)),
                    'last_sent_at' => now(),
                    'attempts' => 0,
                    'is_verified' => false,
                ]
            );

            // Brevo Configuration
            $config = Configuration::getDefaultConfiguration()
                ->setApiKey('api-key', env('BREVO_API_KEY'));

            $apiInstance = new TransactionalEmailsApi(
                new Client(),
                $config
            );

            $sendSmtpEmail = new \Brevo\Client\Model\SendSmtpEmail([
                'subject' => 'Your OTP Verification Code',
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
                    <h3>Your OTP Code</h3>
                    <p>Your OTP is:</p>
                    <h1>{$otp}</h1>
                    <p>This OTP will expire in 5 minutes.</p>
                "
            ]);

            $apiInstance->sendTransacEmail($sendSmtpEmail);

            return [
                'status' => true,
                'message' => 'OTP sent successfully.'
            ];

        } catch (Exception $e) {

            Log::error('OTP Send Failed', [
                'email' => $email,
                'error' => $e->getMessage()
            ]);

            return [
                'status' => false,
                'message' => 'Failed to send OTP.'
            ];
        }
    }

    public function verifyOtp($email, $otp)
    {
        try {

            $otpData = EmailOtp::where('email', $email)->first();

            if (!$otpData) {
                return [
                    'status' => false,
                    'message' => 'OTP not found.'
                ];
            }

            if ($otpData->is_verified) {
                return [
                    'status' => false,
                    'message' => 'OTP already used.'
                ];
            }

            if (Carbon::now()->gt($otpData->expires_at)) {

                return [
                    'status' => false,
                    'message' => 'OTP expired.'
                ];
            }

            $decryptedOtp = Crypt::decryptString($otpData->otp);

            if ($decryptedOtp != $otp) {

                $otpData->increment('attempts');

                return [
                    'status' => false,
                    'message' => 'Invalid OTP.'
                ];
            }

            $otpData->update([
                'is_verified' => true
            ]);

            return [
                'status' => true,
                'message' => 'OTP verified successfully.'
            ];

        } catch (Exception $e) {

            Log::error('OTP Verification Failed', [
                'email' => $email,
                'error' => $e->getMessage()
            ]);

            return [
                'status' => false,
                'message' => 'OTP verification failed.'
            ];
        }
    }
}