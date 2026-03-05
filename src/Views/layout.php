<?php
    use Core\Auth;
    $currentUri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
    $role = Auth::role() ?? 'user';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($title ?? 'SubTracker') ?></title>
    <link rel="stylesheet" href="/css/style.css">
</head>
<body>
<div class="app-container">
    <aside class="sidebar">
        <div class="sidebar-logo">
            <svg width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="#2563eb" stroke-width="2"><rect x="3" y="11" width="18" height="11" rx="2" ry="2"></rect><path d="M7 11V7a5 5 0 0 1 10 0v4"></path></svg>
            SubTracker
        </div>

        <ul class="nav-menu">
            <li><a href="/" class="nav-link <?= $currentUri === '/' ? 'active' : '' ?>">Dashboard</a></li>
            <li><a href="/subscriptions" class="nav-link <?= $currentUri === '/subscriptions' ? 'active' : '' ?>">Subscriptions</a></li>

            <?php if ($role === 'admin'): ?>
                <li><a href="/users" class="nav-link <?= $currentUri === '/users' ? 'active' : '' ?>">Users</a></li>
            <?php endif; ?>

            <li><a href="/settings" class="nav-link <?= $currentUri === '/settings' ? 'active' : '' ?>">Settings</a></li>
        </ul>

        <div class="user-profile">
            <div class="user-avatar"><?= strtoupper(substr($userEmail ?? 'U', 0, 1)) ?></div>
            <div class="user-info">
                <span class="user-name"><?= htmlspecialchars($userEmail ?? 'User') ?></span>
                <a href="/logout" class="logout-link">Logout</a>
            </div>
        </div>
    </aside>

    <main class="main-content">
        <header class="top-header">
            <h2>Overview</h2>
            <div class="header-actions">
                <input type="text" class="search-bar" placeholder="Search subscriptions...">
                <button class="btn-primary" id="addSubscriptionBtn">+ Add Subscription</button>
            </div>
        </header>

        <?= $content ?>
    </main>
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

<script type="module" src="/js/app.js"></script>
</body>
</html>