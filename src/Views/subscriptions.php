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
            <?php $isActive = $sub->getStatus() === 'Active'; ?>

            <div class="sub-card" style="<?= !$isActive ? 'opacity: 0.6;' : '' ?>">
                <div class="sub-header">
                    <div class="sub-logo" style="background: var(--primary-color); color: white;">
                        <?= strtoupper(substr($sub->getName(), 0, 1)) ?>
                    </div>
                    <?php if ($isActive): ?>
                        <span class="status-badge">Active</span>
                    <?php else: ?>
                        <span class="status-badge" style="background: rgba(239, 68, 68, 0.1); color: #ef4444;">
                            <?= htmlspecialchars($sub->getStatus()) ?>
                        </span>
                    <?php endif; ?>
                </div>
                <div class="sub-name"><?= htmlspecialchars($sub->getName()) ?></div>
                <div class="sub-plan"><?= htmlspecialchars($sub->getCategory() ?? 'General') ?></div>
                <div class="sub-footer">
                    <div class="sub-date">
                        <label><?= $isActive ? 'Next billing' : 'Ended on' ?></label>
                        <span><?= date('M d, Y', strtotime($sub->getNextPaymentDate())) ?></span>
                    </div>
                    <div class="sub-price">
                        <?= htmlspecialchars($sub->getCurrency()) ?> <?= number_format($sub->getPrice(), 2) ?>
                        <span>/<?= $sub->getBillingCycle() === 'Monthly' ? 'mo' : 'yr' ?></span>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    <?php endif; ?>
</div>