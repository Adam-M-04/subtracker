<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($title ?? 'Error 500') ?></title>
    <link rel="stylesheet" href="/css/style.css">
</head>
<body style="display:flex;justify-content:center;align-items:center;min-height:100vh;background:var(--bg-color);margin:0;">
<div class="auth-container" style="max-width:620px;text-align:center;">
    <h1 style="margin-top:0;">500</h1>
    <p><?= htmlspecialchars($message ?? 'Internal server error.') ?></p>
    <?php if (!empty($details)): ?>
        <pre style="text-align:left;white-space:pre-wrap;background:#111827;padding:12px;border-radius:8px;border:1px solid #374151;overflow:auto;"><?= htmlspecialchars($details) ?></pre>
    <?php endif; ?>
    <a class="btn" href="/" style="display:inline-block;width:auto;padding:10px 20px;">Back to dashboard</a>
</div>
</body>
</html>

