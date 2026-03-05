<?php

namespace Controllers;

use Core\Controller;
use Core\Auth;
use Core\JsonResponse;
use Entities\Subscription;
use Repositories\SubscriptionRepository;
use Exception;

class SubscriptionController extends Controller
{
    public function index(): void
    {
        Auth::check();

        $this->render('subscriptions', [
            'title' => 'Subscriptions - SubTracker',
            'userEmail' => $_SESSION['user_email']
        ]);
    }

    public function store(): void
    {
        Auth::check();

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            JsonResponse::send('error', 'Method not allowed', [], 405);
        }

        $input = json_decode(file_get_contents('php://input'), true);

        if (!$input || empty($input['name']) || empty($input['price']) || empty($input['next_payment_date'])) {
            JsonResponse::send('error', 'Missing required fields', [], 400);
        }

        try {
            $subscription = new Subscription();
            $subscription->setUserId($_SESSION['user_id'])
                ->setName(htmlspecialchars($input['name']))
                ->setPrice((float)$input['price'])
                ->setCurrency(htmlspecialchars($input['currency'] ?? 'USD'))
                ->setBillingCycle(htmlspecialchars($input['billingCycle'] ?? 'Monthly'))
                ->setCategory(htmlspecialchars($input['category'] ?? 'General'))
                ->setNextPaymentDate(htmlspecialchars($input['next_payment_date']))
                ->setStatus('Active');

            $repo = new SubscriptionRepository();

            if ($repo->save($subscription)) {
                JsonResponse::send('success', 'Subscription added successfully', ['id' => $subscription->getId()], 201);
            } else {
                JsonResponse::send('error', 'Failed to save subscription', [], 500);
            }
        } catch (Exception $e) {
            JsonResponse::send('error', 'Server error: ' . $e->getMessage(), [], 500);
        }
    }
}