<!DOCTYPE html>
<html lang="pl">
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
                <li><a href="/" class="nav-link active">Dashboard</a></li>
                <li><a href="/subscriptions" class="nav-link">Subscriptions</a></li>
                <li><a href="/users" class="nav-link">Users</a></li>
                <li><a href="/settings" class="nav-link">Settings</a></li>
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
    <script src="/js/app.js"></script>
</body>
</html>