<?php

namespace Controllers;

use Core\Controller;
use Core\Auth;
use Core\JsonResponse;
use Entities\Subscription;
use Repositories\SubscriptionRepository;
use Enums\Currency;
use Enums\BillingCycle;
use Enums\Category;
use Enums\Status;
use Exception;

class SubscriptionController extends Controller
{
    public function index(): void
    {
        Auth::check();

        $repo = new SubscriptionRepository();
        $subscriptions = $repo->findAllByUserId(Auth::id());

        $this->render('subscriptions', [
            'title' => 'Subscriptions - SubTracker',
            'userEmail' => Auth::email(),
            'subscriptions' => $subscriptions
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
            $subscription->setUserId(Auth::id())
                ->setName(htmlspecialchars($input['name']))
                ->setPrice((float)$input['price'])
                ->setCurrency(Currency::from((int)($input['currency'] ?? 1)))
                ->setBillingCycle(BillingCycle::from((int)($input['billingCycle'] ?? 1)))
                ->setCategory(Category::from((int)($input['category'] ?? 5)))
                ->setNextPaymentDate(htmlspecialchars($input['next_payment_date']))
                ->setStatus(Status::ACTIVE);

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