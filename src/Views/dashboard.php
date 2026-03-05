<?php
use Enums\Status;
use Enums\BillingCycle;

$monthlyCost = 0;
$yearlyCost = 0;
$activeServices = 0;
$nextPayment = null;
$today = new \DateTime();

foreach ($subscriptions as $sub) {
    if ($sub->getStatus() === Status::ACTIVE) {
        $activeServices++;

        if ($sub->getBillingCycle() === BillingCycle::MONTHLY) {
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
?>

<div class="dashboard-welcome">
    <h3>Welcome back! 👋</h3>
    <p>Here's what's happening with your subscriptions this month.</p>
</div>

<div class="stats-grid">
    <div class="stat-card">
        <div class="stat-title">Total Monthly Cost</div>
        <div class="stat-value">$<?= number_format($monthlyCost, 2) ?></div>
    </div>
    <div class="stat-card">
        <div class="stat-title">Total Yearly Cost</div>
        <div class="stat-value">$<?= number_format($yearlyCost, 2) ?></div>
    </div>
    <div class="stat-card">
        <div class="stat-title">Active Services</div>
        <div class="stat-value"><?= $activeServices ?></div>
    </div>
    <div class="stat-card">
        <div class="stat-title">Next Payment</div>
        <?php if ($nextPayment): ?>
            <div class="stat-value" style="font-size: 20px;">
                <?= date('M d', strtotime($nextPayment->getNextPaymentDate())) ?> <br>
                <span style="font-size: 13px; font-weight: normal;"><?= htmlspecialchars($nextPayment->getName()) ?> (<?= $nextPayment->getCurrency()->symbol() ?><?= number_format($nextPayment->getPrice(), 2) ?>)</span>
            </div>
        <?php else: ?>
            <div class="stat-value" style="font-size: 20px;">None</div>
        <?php endif; ?>
    </div>
</div>

<div class="subs-header">
    <h3>Active Subscriptions</h3>
</div>

<div class="subs-grid">
    <?php if (empty($subscriptions)): ?>
        <p style="color: var(--text-muted); grid-column: 1 / -1;">No subscriptions found. Click "+ Add Subscription" to get started.</p>
    <?php else: ?>
        <?php foreach ($subscriptions as $sub): ?>
            <?php require __DIR__ . '/partials/subscription_card.php'; ?>
        <?php endforeach; ?>
    <?php endif; ?>
</div>