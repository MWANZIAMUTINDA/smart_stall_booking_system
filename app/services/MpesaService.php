<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class MpesaService
{
    protected string $consumerKey;
    protected string $consumerSecret;
    protected string $shortcode;
    protected string $passkey;
    protected string $callbackUrl;
    protected string $baseUrl;

    public function __construct()
    {
        $this->consumerKey    = config('services.mpesa.consumer_key');
        $this->consumerSecret = config('services.mpesa.consumer_secret');
        $this->shortcode      = config('services.mpesa.shortcode');
        $this->passkey        = config('services.mpesa.passkey');
        $this->callbackUrl    = config('services.mpesa.callback_url');

        $env = config('services.mpesa.env', 'sandbox');
        $this->baseUrl = $env === 'production'
            ? 'https://api.safaricom.co.ke'
            : 'https://sandbox.safaricom.co.ke';
    }

    /**
     * Get OAuth access token from Daraja API
     */
    public function getAccessToken(): ?string
    {
        try {
            $response = Http::withBasicAuth($this->consumerKey, $this->consumerSecret)
                ->get("{$this->baseUrl}/oauth/v1/generate?grant_type=client_credentials");

            if ($response->successful()) {
                return $response->json('access_token');
            }

            Log::error('M-Pesa Auth Failed', ['response' => $response->body()]);
            return null;
        } catch (\Exception $e) {
            Log::error('M-Pesa Auth Exception', ['error' => $e->getMessage()]);
            return null;
        }
    }

    /**
     * Generate the Daraja API timestamp
     */
    protected function generateTimestamp(): string
    {
        return now()->format('YmdHis');
    }

    /**
     * Generate the Daraja API password
     */
    protected function generatePassword(string $timestamp): string
    {
        return base64_encode($this->shortcode . $this->passkey . $timestamp);
    }

    /**
     * Initiate STK Push (Lipa Na M-Pesa Online)
     *
     * @param string $phone   Phone number (254XXXXXXXXX format)
     * @param float  $amount  Amount to charge
     * @param string $reference Account reference (e.g. receipt number)
     * @param string $description Transaction description
     * @return array ['success' => bool, 'data' => array|null, 'message' => string]
     */
    public function stkPush(string $phone, float $amount, string $reference, string $description = 'Stall Booking Payment'): array
    {
        $accessToken = $this->getAccessToken();

        if (!$accessToken) {
            return [
                'success' => false,
                'data'    => null,
                'message' => 'Failed to authenticate with M-Pesa. Please try again.',
            ];
        }

        // Normalize phone number to 254 format
        $phone = $this->normalizePhone($phone);

        $timestamp = $this->generateTimestamp();
        $password  = $this->generatePassword($timestamp);

        $payload = [
            'BusinessShortCode' => $this->shortcode,
            'Password'          => $password,
            'Timestamp'         => $timestamp,
            'TransactionType'   => 'CustomerPayBillOnline',
            'Amount'            => (int) ceil($amount),
            'PartyA'            => $phone,
            'PartyB'            => $this->shortcode,
            'PhoneNumber'       => $phone,
            'CallBackURL'       => $this->callbackUrl,
            'AccountReference'  => $reference,
            'TransactionDesc'   => $description,
        ];

        try {
            $response = Http::withToken($accessToken)
                ->post("{$this->baseUrl}/mpesa/stkpush/v1/processrequest", $payload);

            $data = $response->json();

            Log::info('M-Pesa STK Push Response', ['data' => $data]);

            if (isset($data['ResponseCode']) && $data['ResponseCode'] === '0') {
                return [
                    'success' => true,
                    'data'    => $data,
                    'message' => 'STK Push sent to your phone. Please enter your M-Pesa PIN.',
                ];
            }

            return [
                'success' => false,
                'data'    => $data,
                'message' => $data['errorMessage'] ?? $data['ResponseDescription'] ?? 'M-Pesa request failed. Please try again.',
            ];
        } catch (\Exception $e) {
            Log::error('M-Pesa STK Push Exception', ['error' => $e->getMessage()]);

            return [
                'success' => false,
                'data'    => null,
                'message' => 'Could not connect to M-Pesa. Please check your internet and try again.',
            ];
        }
    }

    /**
     * Normalize phone number to 254XXXXXXXXX format
     */
    protected function normalizePhone(string $phone): string
    {
        // Remove spaces, dashes, plus signs
        $phone = preg_replace('/[\s\-\+]/', '', $phone);

        // Convert 07XX to 2547XX
        if (preg_match('/^0/', $phone)) {
            $phone = '254' . substr($phone, 1);
        }

        // Convert +254 to 254
        if (preg_match('/^\+254/', $phone)) {
            $phone = substr($phone, 1);
        }

        return $phone;
    }
}
