<div class="dashboard-welcome">
    <h3>Welcome back! 👋</h3>
    <p>Here's what's happening with your subscriptions this month.</p>
</div>

<div class="stats-grid">
    <div class="stat-card">
        <div class="stat-title">Total Monthly Cost</div>
        <div class="stat-value">$42.50</div>
    </div>
    <div class="stat-card">
        <div class="stat-title">Total Yearly Cost</div>
        <div class="stat-value">$510.00</div>
    </div>
    <div class="stat-card">
        <div class="stat-title">Active Services</div>
        <div class="stat-value">8</div>
    </div>
    <div class="stat-card">
        <div class="stat-title">Next Payment</div>
        <div class="stat-value" style="font-size: 20px;">Oct 24 <br><span style="font-size: 13px; font-weight: normal;">Netflix ($15.99)</span></div>
    </div>
</div>

<div class="subs-header">
    <h3>Active Subscriptions</h3>
</div>

<div class="subs-grid">
    <div class="sub-card">
        <div class="sub-header">
            <div class="sub-logo" style="background: #e50914; color: white;">N</div>
            <span class="status-badge">Active</span>
        </div>
        <div class="sub-name">Netflix</div>
        <div class="sub-plan">Standard Plan</div>
        <div class="sub-footer">
            <div class="sub-date">
                <label>Next billing</label>
                <span>Oct 24, 2023</span>
            </div>
            <div class="sub-price">$15.99<span>/mo</span></div>
        </div>
    </div>

    <div class="sub-card">
        <div class="sub-header">
            <div class="sub-logo" style="background: #1DB954; color: white;">S</div>
            <span class="status-badge">Active</span>
        </div>
        <div class="sub-name">Spotify</div>
        <div class="sub-plan">Premium Duo</div>
        <div class="sub-footer">
            <div class="sub-date">
                <label>Next billing</label>
                <span>Oct 28, 2023</span>
            </div>
            <div class="sub-price">$12.99<span>/mo</span></div>
        </div>
    </div>
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
                    <input type="number" step="0.01" class="form-control" placeholder="$ 0.00" required>
                </div>
                <div class="form-group">
                    <label>Currency</label>
                    <select class="form-control">
                        <option>USD ($)</option>
                        <option>EUR (€)</option>
                        <option>PLN (zł)</option>
                    </select>
                </div>
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label>Billing Cycle</label>
                    <select class="form-control">
                        <option>Monthly</option>
                        <option>Yearly</option>
                    </select>
                </div>
                <div class="form-group">
                    <label>Category</label>
                    <select class="form-control">
                        <option>Select Category</option>
                        <option>Entertainment</option>
                        <option>Productivity</option>
                    </select>
                </div>
            </div>

            <div class="form-group">
                <label>First Payment Date</label>
                <input type="date" class="form-control" required>
            </div>

            <div class="modal-footer">
                <button type="button" class="btn-secondary" id="cancelModalBtn">Cancel</button>
                <button type="submit" class="btn" style="width: auto; padding: 10px 24px;">&#10003; Save Subscription</button>
            </div>
        </form>
    </div>
</div>