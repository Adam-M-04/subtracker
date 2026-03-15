<div class="page-header" style="margin-bottom: 24px;">
    <h2>Account Settings</h2>
    <p>Update your personal information and application preferences.</p>
</div>

<?php if (!empty($success)): ?>
    <div style="background: rgba(16, 185, 129, 0.1); border: 1px solid #10b981; color: #10b981; padding: 12px 16px; border-radius: 8px; margin-bottom: 24px; font-size: 14px;">
        <?= htmlspecialchars($success) ?>
    </div>
<?php endif; ?>

<?php if (!empty($error)): ?>
    <div class="alert-error" style="margin-bottom: 24px;">
        <?= htmlspecialchars($error) ?>
    </div>
<?php endif; ?>

<div class="card" style="background: var(--card-bg); border-radius: 12px; border: 1px solid var(--border-color); padding: 24px; max-width: 600px;">
    <h3 style="margin-top: 0; margin-bottom: 20px; font-size: 18px; color: #fff;">Profile Information</h3>

    <form action="/settings" method="POST">
        <div class="form-row">
            <div class="form-group">
                <label for="first_name">First Name</label>
                <input type="text" id="first_name" name="first_name" class="form-control" value="<?= htmlspecialchars($profile['first_name'] ?? '') ?>" required>
            </div>
            <div class="form-group">
                <label for="last_name">Last Name</label>
                <input type="text" id="last_name" name="last_name" class="form-control" value="<?= htmlspecialchars($profile['last_name'] ?? '') ?>">
            </div>
        </div>

        <div class="form-group">
            <label for="email">Email Address (Read-only)</label>
            <input type="email" id="email" class="form-control" value="<?= htmlspecialchars($userEmail) ?>" disabled style="opacity: 0.6; cursor: not-allowed;">
        </div>

        <h3 style="margin-top: 32px; margin-bottom: 20px; font-size: 18px; color: #fff;">Preferences</h3>

        <div class="form-group">
            <label for="currency_id">Default Currency</label>
            <select id="currency_id" name="currency_id" class="form-control">
                <option value="1" <?= ($profile['currency_id'] == 1) ? 'selected' : '' ?>>USD ($)</option>
                <option value="2" <?= ($profile['currency_id'] == 2) ? 'selected' : '' ?>>EUR (€)</option>
                <option value="3" <?= ($profile['currency_id'] == 3) ? 'selected' : '' ?>>PLN (zł)</option>
            </select>
        </div>

        <div style="margin-top: 32px; padding-top: 24px; border-top: 1px solid var(--border-color); display: flex; justify-content: flex-end;">
            <button type="submit" class="btn" style="width: auto; padding: 10px 24px;">Save Changes</button>
        </div>
    </form>
</div>