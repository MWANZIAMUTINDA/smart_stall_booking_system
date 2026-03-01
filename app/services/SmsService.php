<?php

namespace App\Services;

use AfricasTalking\SDK\AfricasTalking;

class SmsService
{
    protected $username;
    protected $apiKey;

    public function __construct()
    {
        $this->username = config('services.africastalking.username');
        $this->apiKey   = config('services.africastalking.key');
    }

    public function send($phone, $message)
{
    $phone = preg_replace('/^0/', '+254', $phone);

    $AT = new \AfricasTalking\SDK\AfricasTalking(
        $this->username,
        $this->apiKey
    );

    $sms = $AT->sms();

    return $sms->send([
        'to' => $phone,
        'message' => $message,
    ]);
}
}