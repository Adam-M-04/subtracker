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
            <?php $isActive = $sub->getStatus() === Status::ACTIVE; ?>

            <div class="sub-card" style="<?= !$isActive ? 'opacity: 0.6;' : '' ?>">
                <div class="sub-header" style="display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 20px;">
                    <div style="display: flex; align-items: center; gap: 12px;">
                        <div class="sub-logo" style="background: var(--primary-color); color: white; width: 44px; height: 44px; border-radius: 12px; display: flex; align-items: center; justify-content: center; font-weight: bold; font-size: 20px; flex-shrink: 0;">
                            <?= strtoupper(substr($sub->getName(), 0, 1)) ?>
                        </div>
                        <div style="display: flex; flex-direction: column; justify-content: center;">
                            <div style="display: flex; align-items: center; gap: 8px;">
                                <span style="font-size: 16px; font-weight: 600; color: #fff;"><?= htmlspecialchars($sub->getName()) ?></span>
                                <?php if ($isActive): ?>
                                    <span class="status-badge" style="padding: 2px 6px; font-size: 11px;">Active</span>
                                <?php else: ?>
                                    <span class="status-badge" style="background: rgba(239, 68, 68, 0.1); color: #ef4444; padding: 2px 6px; font-size: 11px;">
                                        <?= ucfirst(strtolower($sub->getStatus()->name)) ?>
                                    </span>
                                <?php endif; ?>
                            </div>
                            <span style="color: var(--text-muted); font-size: 13px; margin-top: 2px;"><?= ucfirst(strtolower($sub->getCategory()->name)) ?></span>
                        </div>
                    </div>
                    <button class="delete-btn" data-id="<?= $sub->getId() ?>" title="Delete">
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="3 6 5 6 21 6"></polyline><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path></svg>
                    </button>
                </div>

                <div class="sub-footer" style="border-top: 1px solid var(--border-color); padding-top: 16px;">
                    <div class="sub-date">
                        <label><?= $isActive ? 'Next billing' : 'Ended on' ?></label>
                        <span><?= date('M d, Y', strtotime($sub->getNextPaymentDate())) ?></span>
                    </div>
                    <div class="sub-price">
                        <?= $sub->getCurrency()->symbol() ?> <?= number_format($sub->getPrice(), 2) ?>
                        <span>/<?= $sub->getBillingCycle() === BillingCycle::MONTHLY ? 'mo' : 'yr' ?></span>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    <?php endif; ?>
</div>