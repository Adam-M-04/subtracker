<?php

namespace Controllers;

use Core\Controller;
use Core\Auth;
use Core\JsonResponse;
use Entities\Subscription;
use Enums\Category;
use Enums\BillingCycle;
use Enums\Currency;
use Repositories\SubscriptionRepository;
use Exception;

class SubscriptionController extends Controller
{
    public function index(): void
    {
        Auth::check();

        $search = $_GET['q'] ?? '';
        $repo = new SubscriptionRepository();
        $subscriptions = $repo->findAllByUserId(Auth::id(), $search);

        $this->render('subscriptions', [
            'title' => 'My Subscriptions - SubTracker',
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

        if (empty($input['name']) || empty($input['price']) || empty($input['next_payment_date'])) {
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
                ->setNextPaymentDate(htmlspecialchars($input['next_payment_date']));

            $repo = new SubscriptionRepository();

            if ($repo->save($subscription)) {
                JsonResponse::send('success', 'Subscription added successfully');
            } else {
                JsonResponse::send('error', 'Failed to save subscription', [], 500);
            }
        } catch (Exception $e) {
            JsonResponse::send('error', 'Server error: ' . $e->getMessage(), [], 500);
        }
    }

    public function update(): void
    {
        Auth::check();

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            JsonResponse::send('error', 'Method not allowed', [], 405);
        }

        $input = json_decode(file_get_contents('php://input'), true);

        if (empty($input['id']) || empty($input['name']) || empty($input['price']) || empty($input['next_payment_date'])) {
            JsonResponse::send('error', 'Missing required fields', [], 400);
        }

        try {
            $subscription = new Subscription();
            $subscription->setId((int)$input['id'])
                ->setUserId(Auth::id())
                ->setName(htmlspecialchars($input['name']))
                ->setPrice((float)$input['price'])
                ->setCurrency(Currency::from((int)($input['currency'] ?? 1)))
                ->setBillingCycle(BillingCycle::from((int)($input['billingCycle'] ?? 1)))
                ->setCategory(Category::from((int)($input['category'] ?? 5)))
                ->setNextPaymentDate(htmlspecialchars($input['next_payment_date']));

            $repo = new SubscriptionRepository();

            if ($repo->update($subscription)) {
                JsonResponse::send('success', 'Subscription updated successfully');
            } else {
                JsonResponse::send('error', 'Failed to update subscription', [], 500);
            }
        } catch (Exception $e) {
            JsonResponse::send('error', 'Server error: ' . $e->getMessage(), [], 500);
        }
    }

    public function delete(): void
    {
        Auth::check();

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            JsonResponse::send('error', 'Method not allowed', [], 405);
        }

        $input = json_decode(file_get_contents('php://input'), true);

        if (empty($input['id'])) {
            JsonResponse::send('error', 'Missing subscription ID', [], 400);
        }

        $repo = new SubscriptionRepository();

        if ($repo->delete((int)$input['id'], Auth::id())) {
            JsonResponse::send('success', 'Subscription deleted successfully');
        } else {
            JsonResponse::send('error', 'Failed to delete subscription', [], 500);
        }
    }
}