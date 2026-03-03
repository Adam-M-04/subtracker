<div class="page-header">
    <h2>Account Settings</h2>
    <p>Update your personal information and preferences.</p>
</div>

<div style="display: grid; grid-template-columns: 1fr 1fr; gap: 30px;">

    <div style="background: var(--card-bg); border: 1px solid var(--border-color); padding: 30px; border-radius: 12px;">
        <h3 style="margin-bottom: 20px; font-size: 18px;">Profile Information</h3>
        <form>
            <div class="form-row">
                <div class="form-group">
                    <label>First Name</label>
                    <input type="text" class="form-control" value="Alex">
                </div>
                <div class="form-group">
                    <label>Last Name</label>
                    <input type="text" class="form-control" value="Morgan">
                </div>
            </div>

            <div class="form-group">
                <label>Email Address</label>
                <input type="email" class="form-control" value="<?= htmlspecialchars($userEmail) ?>">
            </div>

            <div class="form-group">
                <label>Default Currency</label>
                <select class="form-control">
                    <option selected>USD ($)</option>
                    <option>EUR (€)</option>
                    <option>PLN (zł)</option>
                </select>
            </div>

            <button type="submit" class="btn" style="width: auto; padding: 10px 24px;">Save Changes</button>
        </form>
    </div>

    <div style="background: var(--card-bg); border: 1px solid var(--border-color); padding: 30px; border-radius: 12px; height: fit-content;">
        <h3 style="margin-bottom: 20px; font-size: 18px;">Change Password</h3>
        <form>
            <div class="form-group">
                <label>Current Password</label>
                <input type="password" class="form-control" placeholder="••••••••">
            </div>

            <div class="form-group">
                <label>New Password</label>
                <input type="password" class="form-control" placeholder="••••••••">
            </div>

            <div class="form-group">
                <label>Confirm New Password</label>
                <input type="password" class="form-control" placeholder="••••••••">
            </div>

            <button type="submit" class="btn" style="width: auto; padding: 10px 24px;">Update Password</button>
        </form>
    </div>

</div>