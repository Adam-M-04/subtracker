<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Log in - SubTracker</title>
    <link rel="stylesheet" href="/css/style.css">
</head>
<body style="display: flex; justify-content: center; align-items: center; min-height: 100vh; margin: 0; background-color: var(--bg-color);">

<div class="auth-wrapper" style="width: 100%; display: flex; justify-content: center;">
    <div class="auth-container">
        <div class="auth-header">
            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="#2563eb" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="margin-bottom: 15px;">
                <rect x="3" y="11" width="18" height="11" rx="2" ry="2"></rect>
                <path d="M7 11V7a5 5 0 0 1 10 0v4"></path>
            </svg>
            <h1>Welcome Back</h1>
            <p>Access your dashboard and manage your subscriptions efficiently.</p>
        </div>

        <?php if (!empty($error)): ?>
            <div class="alert-error"><?= htmlspecialchars($error) ?></div>
        <?php endif; ?>

        <form action="/login" method="POST">
            <div class="form-group">
                <label for="email">Email Address</label>
                <input type="email" id="email" name="email" class="form-control" placeholder="name@company.com" required>
            </div>

            <div class="form-group">
                <label for="password">Password</label>
                <input type="password" id="password" name="password" class="form-control" placeholder="••••••••" required>
            </div>

            <button type="submit" class="btn">Sign In</button>
        </form>

        <div style="text-align: center; margin-top: 24px; font-size: 14px; color: var(--text-muted);">
            Don't have an account? <a href="/register" style="color: #2563eb; text-decoration: none; font-weight: 500;">Sign up</a>
        </div>
    </div>
</div>

</body>
</html>