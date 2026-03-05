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
                <span style="font-size: 13px; font-weight: normal;"><?= htmlspecialchars($nextPayment->getName()) ?> ($<?= number_format($nextPayment->getPrice(), 2) ?>)</span>
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
            <div class="sub-card">
                <div class="sub-header">
                    <div class="sub-logo" style="background: var(--primary-color); color: white;">
                        <?= strtoupper(substr($sub->getName(), 0, 1)) ?>
                    </div>
                    <?php if ($sub->getStatus() === 'Active'): ?>
                        <span class="status-badge">Active</span>
                    <?php else: ?>
                        <span class="status-badge" style="background: rgba(239, 68, 68, 0.1); color: #ef4444;"><?= htmlspecialchars($sub->getStatus()) ?></span>
                    <?php endif; ?>
                </div>
                <div class="sub-name"><?= htmlspecialchars($sub->getName()) ?></div>
                <div class="sub-plan"><?= htmlspecialchars($sub->getCategory() ?? 'General') ?></div>
                <div class="sub-footer">
                    <div class="sub-date">
                        <label>Next billing</label>
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

<div class="modal-overlay" id="addSubscriptionModal">
    <div class="modal-content">
        <div class="modal-header">
            <div>
                <h3>Add Subscription</h3>
                <p>Track your recurring expenses effortlessly.</p>
            </div>
            <button class="close-modal" id="closeModalBtn">&times;</button>
        </div>

        <form id="subscriptionForm">
            <div class="form-group">
                <label>Service Name</label>
                <input type="text" class="form-control" placeholder="e.g. Netflix, Spotify, Adobe" required>
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label>Price</label>
                    <input type="number" step="0.01" class="form-control" placeholder="0.00" required>
                </div>
                <div class="form-group">
                    <label>Currency</label>
                    <select class="form-control">
                        <option value="$">USD ($)</option>
                        <option value="€">EUR (€)</option>
                        <option value="zł">PLN (zł)</option>
                    </select>
                </div>
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label>Billing Cycle</label>
                    <select class="form-control">
                        <option value="Monthly">Monthly</option>
                        <option value="Yearly">Yearly</option>
                    </select>
                </div>
                <div class="form-group">
                    <label>Category</label>
                    <select class="form-control">
                        <option value="Entertainment">Entertainment</option>
                        <option value="Productivity">Productivity</option>
                        <option value="Utilities">Utilities</option>
                        <option value="Software">Software</option>
                    </select>
                </div>
            </div>

            <div class="form-group">
                <label>Next Payment Date</label>
                <input type="date" class="form-control" required>
            </div>

            <div class="modal-footer">
                <button type="button" class="btn-secondary" id="cancelModalBtn">Cancel</button>
                <button type="submit" class="btn" style="width: auto; padding: 10px 24px;">&#10003; Save Subscription</button>
            </div>
        </form>
    </div>
</div>