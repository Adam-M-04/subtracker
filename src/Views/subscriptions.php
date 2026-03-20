<?php
use Enums\Status;
use Enums\BillingCycle;
?>

<div class="page-header" style="display: flex; flex-wrap: wrap; justify-content: space-between; align-items: flex-end; gap: 16px; margin-bottom: 24px;">
    <div style="flex: 1; min-width: 250px;">
        <h2 style="margin-top: 0; margin-bottom: 8px;">All Subscriptions</h2>
        <p style="margin: 0;">Manage your active and past subscriptions in one place.</p>
    </div>
    <div style="display: flex; gap: 10px; flex-wrap: wrap; flex: 1; min-width: 250px; justify-content: flex-end;">
        <select id="categoryFilter" class="form-control" style="flex: 1; min-width: 140px; max-width: 200px;">
            <option value="all">All Categories</option>
            <option value="entertainment">Entertainment</option>
            <option value="productivity">Productivity</option>
            <option value="utilities">Utilities</option>
            <option value="software">Software</option>
            <option value="general">General</option>
        </select>
        <select id="sortFilter" class="form-control" style="flex: 1; min-width: 140px; max-width: 200px;">
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