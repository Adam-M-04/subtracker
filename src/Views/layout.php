<?php
use Core\Auth;
use Enums\Role;

$currentUri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$role = Auth::role() ?? Role::USER;
$defaultCurrency = Auth::currencyId();
$userName = Auth::name();
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
            <li><a href="/subscriptions" class="nav-link <?= strpos($currentUri, '/subscriptions') === 0 ? 'active' : '' ?>">Subscriptions</a></li>

            <?php if ($role === Role::ADMIN): ?>
                <li><a href="/users" class="nav-link <?= $currentUri === '/users' ? 'active' : '' ?>">Users</a></li>
            <?php endif; ?>

            <li><a href="/settings" class="nav-link <?= $currentUri === '/settings' ? 'active' : '' ?>">Settings</a></li>
        </ul>

        <div class="user-profile">
            <div class="user-avatar"><?= strtoupper(substr($userName, 0, 1)) ?></div>
            <div class="user-info">
                <span class="user-name"><?= htmlspecialchars($userName) ?></span>
                <a href="/logout" class="logout-link">Logout</a>
            </div>
        </div>
    </aside>

    <main class="main-content">
        <header class="top-header">
            <h2>Overview</h2>
            <div class="header-actions">
                <form action="/subscriptions" method="GET" style="margin: 0; display: flex;">
                    <input type="text" name="q" class="search-bar" placeholder="Search subscriptions..." value="<?= htmlspecialchars($_GET['q'] ?? '') ?>">
                </form>
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
                <h3 id="modalTitle">Add Subscription</h3>
                <p>Track your recurring expenses effortlessly.</p>
            </div>
            <button class="close-modal" id="closeModalBtn">&times;</button>
        </div>

        <form id="subscriptionForm">
            <input type="hidden" id="subId" name="id">

            <div class="form-group">
                <label>Service Name</label>
                <input type="text" name="name" class="form-control" placeholder="e.g. Netflix, Spotify, Adobe" required>
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label>Price</label>
                    <input type="number" name="price" step="0.01" class="form-control" placeholder="0.00" required>
                </div>
                <div class="form-group">
                    <label>Currency</label>
                    <select name="currency" class="form-control">
                        <option value="1" <?= $defaultCurrency === 1 ? 'selected' : '' ?>>USD ($)</option>
                        <option value="2" <?= $defaultCurrency === 2 ? 'selected' : '' ?>>EUR (€)</option>
                        <option value="3" <?= $defaultCurrency === 3 ? 'selected' : '' ?>>PLN (zł)</option>
                    </select>
                </div>
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label>Billing Cycle</label>
                    <select name="billingCycle" class="form-control">
                        <option value="1">Monthly</option>
                        <option value="2">Yearly</option>
                    </select>
                </div>
                <div class="form-group">
                    <label>Category</label>
                    <select name="category" class="form-control">
                        <option value="5">General</option>
                        <option value="1">Entertainment</option>
                        <option value="2">Productivity</option>
                        <option value="3">Utilities</option>
                        <option value="4">Software</option>
                    </select>
                </div>
            </div>

            <div class="form-group">
                <label>Next Payment Date</label>
                <input type="date" name="next_payment_date" class="form-control" required>
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