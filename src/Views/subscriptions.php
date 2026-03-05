<div class="page-header" style="display: flex; justify-content: space-between; align-items: flex-end;">
    <div>
        <h2>All Subscriptions</h2>
        <p>Manage your active and past subscriptions in one place.</p>
    </div>
    <div style="display: flex; gap: 10px;">
        <select class="form-control" style="width: auto;">
            <option>All Categories</option>
            <option>Entertainment</option>
            <option>Productivity</option>
            <option>Utilities</option>
            <option>Software</option>
        </select>
        <select class="form-control" style="width: auto;">
            <option>Sort by Date</option>
            <option>Sort by Price</option>
        </select>
    </div>
</div>

<div class="subs-grid">
    <?php if (empty($subscriptions)): ?>
        <p style="color: var(--text-muted); grid-column: 1 / -1;">No subscriptions found. Click "+ Add Subscription" to add your first one.</p>
    <?php else: ?>
        <?php foreach ($subscriptions as $sub): ?>
            <?php require __DIR__ . '/partials/subscription_card.php'; ?>
        <?php endforeach; ?>
    <?php endif; ?>
</div>