<?php

namespace Controllers;

use Core\Controller;
use Core\Auth;
use Repositories\SubscriptionRepository;

class HomeController extends Controller
{
    public function index(): void
    {
        Auth::check();

        $repo = new SubscriptionRepository();
        $repo->autoRenewSubscriptions(Auth::id());
        $subscriptions = $repo->findAllByUserId(Auth::id());

        $monthlyCost = 0;
        $yearlyCost = 0;
        $activeServices = 0;
        $nextPayment = null;

        $today = new \DateTime();

        foreach ($subscriptions as $sub) {
            if ($sub->getStatus() === 'Active') {
                $activeServices++;

                if ($sub->getBillingCycle() === 'Monthly') {
                    $monthlyCost += $sub->getPrice();
                    $yearlyCost += ($sub->getPrice() * 12);
                } else {
                    $yearlyCost += $sub->getPrice();
                    $monthlyCost += ($sub->getPrice() / 12);
                }

                $paymentDate = new \DateTime($sub->getNextPaymentDate());
                if ($paymentDate >= $today) {
                    if ($nextPayment === null || $paymentDate < new \DateTime($nextPayment->getNextPaymentDate())) {
                        $nextPayment = $sub;
                    }
                }
            }
        }

        $this->render('dashboard', [
            'title' => 'Dashboard - SubTracker',
            'userEmail' => Auth::email(),
            'subscriptions' => $subscriptions,
            'monthlyCost' => $monthlyCost,
            'yearlyCost' => $yearlyCost,
            'activeServices' => $activeServices,
            'nextPayment' => $nextPayment
        ]);
    }
}