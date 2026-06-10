<?php

namespace Controllers;

use Core\Controller;
use Core\Auth;
use Core\JsonResponse;
use Entities\Subscription;
use Enums\Category;
use Enums\BillingCycle;
use Enums\Currency;
use Enums\Status;
use Repositories\SubscriptionRepository;
use Exception;

class SubscriptionController extends Controller
{
    public function index(): void
    {
        Auth::check();

        $search = $_GET['q'] ?? '';
        $repo = new SubscriptionRepository();

        $repo->autoRenewSubscriptions(Auth::id());

        $subscriptions = $repo->findAllByUserId(Auth::id(), $search, true);

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

        if (!$this->validateCsrf(true)) {
            return;
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
                ->setStatus(Status::from((int)($input['status'] ?? 1)))
                ->setNextPaymentDate(htmlspecialchars($input['next_payment_date']));

            $repo = new SubscriptionRepository();

            if ($repo->save($subscription)) {
                JsonResponse::send('success', 'Subscription added successfully');
            } else {
                JsonResponse::send('error', 'Failed to save subscription', [], 500);
            }
        } catch (Exception $e) {
            error_log('[store_subscription] ' . $e->getMessage());
            JsonResponse::send('error', 'Server error', [], 500);
        }
    }

    public function update(): void
    {
        Auth::check();

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            JsonResponse::send('error', 'Method not allowed', [], 405);
        }

        if (!$this->validateCsrf(true)) {
            return;
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
                ->setStatus(Status::from((int)($input['status'] ?? 1)))
                ->setNextPaymentDate(htmlspecialchars($input['next_payment_date']));

            $repo = new SubscriptionRepository();

            if ($repo->update($subscription)) {
                JsonResponse::send('success', 'Subscription updated successfully');
            } else {
                JsonResponse::send('error', 'Failed to update subscription', [], 500);
            }
        } catch (Exception $e) {
            error_log('[update_subscription] ' . $e->getMessage());
            JsonResponse::send('error', 'Server error', [], 500);
        }
    }

    public function delete(): void
    {
        Auth::check();

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            JsonResponse::send('error', 'Method not allowed', [], 405);
        }

        if (!$this->validateCsrf(true)) {
            return;
        }

        $input = json_decode(file_get_contents('php://input'), true);

        if (empty($input['id'])) {
            JsonResponse::send('error', 'Missing subscription ID', [], 400);
        }

        $repo = new SubscriptionRepository();

        if ($repo->updateStatus((int)$input['id'], Auth::id(), Status::INACTIVE)) {
            JsonResponse::send('success', 'Subscription moved to history');
        } else {
            JsonResponse::send('error', 'Operation failed', [], 500);
        }
    }

    public function updateStatus(): void
    {
        Auth::check();

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            JsonResponse::send('error', 'Method not allowed', [], 405);
        }

        if (!$this->validateCsrf(true)) {
            return;
        }

        $input = json_decode(file_get_contents('php://input'), true);

        if (empty($input['id']) || empty($input['status'])) {
            JsonResponse::send('error', 'Missing data', [], 400);
        }

        $repo = new SubscriptionRepository();
        $status = Status::from((int)$input['status']);

        if ($repo->updateStatus((int)$input['id'], Auth::id(), $status)) {
            JsonResponse::send('success', 'Status updated successfully');
        } else {
            JsonResponse::send('error', 'Failed to update status', [], 500);
        }
    }
}