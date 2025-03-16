<?php

namespace App\Controller;

use App\Service\PaymentService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class PaymentController extends AbstractController
{
    private PaymentService $paymentService;

    public function __construct(PaymentService $paymentService)
    {
        $this->paymentService = $paymentService;
    }

    #[Route('/', name: 'app_index')]
    public function index(): Response
    {
        return $this->render('payment/index.html.twig');
    }
    #[Route('/payment', name: 'app_payment', methods: ['POST'])]
    public function processPayment(Request $request): JsonResponse
    {
        $data = json_decode($request->getContent(), true);

        $amount = $data['amount'] ?? null;
        $currency = $data['currency'] ?? 'usd';
        $token = $data['token'] ?? null;

        if (!$amount || !$token) {
            return $this->json([
                'status' => 'error',
                'message' => 'Invalid input data',
            ], 400);
        }

        $response = $this->paymentService->processPayment($amount, $currency, $token);

        return $this->json($response);
    }

}
