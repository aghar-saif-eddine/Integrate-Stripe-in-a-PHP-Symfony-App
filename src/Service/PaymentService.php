<?php


namespace App\Service;

use Stripe\Stripe;
use Stripe\Charge;
use Stripe\Exception\ApiErrorException;

class PaymentService
{
    private string $secretKey;

    public function __construct(string $secretKey)
    {
        $this->secretKey = $secretKey;
        Stripe::setApiKey($this->secretKey);
    }

    public function processPayment(int $amount, string $currency, string $token): array
    {
        try {
            $charge = Charge::create([
                'amount' => $amount,
                'currency' => $currency,
                'source' => $token,
                'description' => 'App symfony Payment',
            ]);

            return [
                'status' => 'success',
                'message' => 'Payment successful',
                'data' => $charge,
            ];
        } catch (ApiErrorException $e) {
            return [
                'status' => 'error',
                'message' => $e->getMessage(),
            ];
        }
    }
}