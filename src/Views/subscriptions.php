<?php
use Enums\Status;
use Enums\BillingCycle;
?>

<div class="page-header" style="display: flex; justify-content: space-between; align-items: flex-end;">
    <div>
        <h2>All Subscriptions</h2>
        <p>Manage your active and past subscriptions in one place.</p>
    </div>
    <div style="display: flex; gap: 10px;">
        <select id="categoryFilter" class="form-control" style="width: auto;">
            <option value="all">All Categories</option>
            <option value="entertainment">Entertainment</option>
            <option value="productivity">Productivity</option>
            <option value="utilities">Utilities</option>
            <option value="software">Software</option>
            <option value="general">General</option>
        </select>
        <select id="sortFilter" class="form-control" style="width: auto;">
            <option value="date_asc">Date: Nearest first</option>
            <option value="date_desc">Date: Furthest first</option>
            <option value="price_desc">Price: Highest first</option>
            <option value="price_asc">Price: Lowest first</option>
        </select>
    </div>
</div>

<div class="subs-grid" id="subsGrid">
    <?php if (empty($subscriptions)): ?>
        <p style="color: var(--text-muted); grid-column: 1 / -1;">No subscriptions found. Click "+ Add Subscription" to add your first one.</p>
    <?php else: ?>
        <?php foreach ($subscriptions as $sub): ?>
            <?php require __DIR__ . '/partials/subscription_card.php'; ?>
        <?php endforeach; ?>
    <?php endif; ?>
</div>