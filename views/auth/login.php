<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= __('login.page_title') ?> | ResearchHub</title>
    <link rel="stylesheet" href="assets/css/style.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;800&display=swap" rel="stylesheet">
    <script src="https://unpkg.com/lucide@latest"></script>
    <style>
        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

        body {
            background: #f8f9fa;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            min-height: 100vh;
            font-family: 'Inter', sans-serif;
            padding: 16px;
        }

        .login-wrapper {
            width: 100%;
            max-width: 420px;
        }

        /* Language switcher */
        .lang-bar {
            display: flex;
            justify-content: flex-end;
            gap: 6px;
            margin-bottom: 16px;
        }
        .lang-btn {
            font-size: 0.72rem;
            font-weight: 700;
            padding: 4px 10px;
            border-radius: 5px;
            text-decoration: none;
            color: #64748b;
            border: 1px solid #e2e8f0;
            background: white;
            transition: all 0.15s;
        }
        .lang-btn.active { background: #0f172a; color: white; border-color: #0f172a; }

        .login-card {
            background: white;
            padding: 40px;
            border-radius: 20px;
            box-shadow: 0 10px 25px rgba(0,0,0,0.06);
            border: 1px solid rgba(0,0,0,0.05);
            width: 100%;
        }

        .brand { text-align: center; margin-bottom: 28px; }
        .brand-title {
            font-weight: 800;
            font-size: 1.5rem;
            letter-spacing: -1px;
            margin-bottom: 8px;
            color: #0f172a;
        }
        .brand-subtitle { font-size: 1.1rem; font-weight: 700; color: #333; margin-bottom: 4px; }
        .brand-text { color: #666; font-size: 0.875rem; }

        /* Alerts */
        .alert {
            padding: 12px 14px;
            border-radius: 10px;
            font-size: 0.85rem;
            margin-bottom: 18px;
            display: flex;
            align-items: center;
            gap: 8px;
            font-weight: 500;
        }
        .alert-success { background: #f0fdf4; color: #166534; border: 1px solid #bbf7d0; }
        .alert-error   { background: #fef2f2; color: #991b1b; border: 1px solid #fee2e2; }

        /* Form */
        .form-group { margin-bottom: 18px; }
        .form-label {
            display: block;
            font-size: 0.83rem;
            font-weight: 600;
            margin-bottom: 6px;
            color: #374151;
        }
        .form-input {
            width: 100%;
            padding: 12px 14px;
            border: 1px solid #d1d5db;
            border-radius: 8px;
            font-family: 'Inter', sans-serif;
            font-size: 0.9rem;
            outline: none;
            transition: border-color 0.2s, box-shadow 0.2s;
            color: #0f172a;
        }
        .form-input:focus {
            border-color: #0f172a;
            box-shadow: 0 0 0 3px rgba(15,23,26,0.06);
        }

        .forgot-link {
            display: block;
            text-align: right;
            font-size: 0.82rem;
            color: #2563eb;
            text-decoration: none;
            font-weight: 600;
            margin-top: 6px;
        }
        .forgot-link:hover { text-decoration: underline; }

        .submit-btn {
            width: 100%;
            padding: 13px;
            background: #0f172a;
            color: white;
            border: none;
            border-radius: 8px;
            font-weight: 700;
            font-size: 0.95rem;
            cursor: pointer;
            transition: background 0.2s;
            margin-top: 8px;
            font-family: 'Inter', sans-serif;
        }
        .submit-btn:hover { background: #1e293b; }

        .register-link {
            text-align: center;
            font-size: 0.875rem;
            margin-top: 22px;
            color: #666;
        }
        .register-link a {
            color: #2563eb;
            text-decoration: none;
            font-weight: 600;
        }
        .register-link a:hover { text-decoration: underline; }

        /* Mobile */
        @media (max-width: 480px) {
            body { padding: 12px; justify-content: flex-start; padding-top: 32px; }
            .login-card { padding: 24px 20px; border-radius: 16px; }
            .brand-title { font-size: 1.3rem; }
            .brand-subtitle { font-size: 1rem; }
            .form-input { padding: 11px 12px; font-size: 16px; /* prevents iOS zoom */ }
            .submit-btn { padding: 13px; font-size: 0.9rem; }
        }

        @media (max-width: 360px) {
            .login-card { padding: 20px 16px; }
            .brand-title { font-size: 1.2rem; }
        }
    </style>
</head>
<body>

<div class="login-wrapper">

    <!-- Language switcher -->
    <div class="lang-bar">
        <a href="index.php?action=set-lang&lang=en" class="lang-btn <?= ($_SESSION['lang'] ?? 'en') === 'en' ? 'active' : '' ?>">EN</a>
        <a href="index.php?action=set-lang&lang=fr" class="lang-btn <?= ($_SESSION['lang'] ?? 'en') === 'fr' ? 'active' : '' ?>">FR</a>
    </div>

    <div class="login-card">

        <!-- Brand -->
        <div class="brand">
            <div class="brand-title"><?= __('nav.brand') ?></div>
            <div class="brand-subtitle"><?= __('login.welcome') ?></div>
            <p class="brand-text"><?= __('login.subtitle') ?></p>
        </div>

        <!-- Alerts -->
        <?php if (isset($_GET['reset']) && $_GET['reset'] === 'success'): ?>
            <div class="alert alert-success">
                <i data-lucide="check-circle" style="width:16px;height:16px;flex-shrink:0;"></i>
                <?= __('login.password_updated') ?>
            </div>
        <?php endif; ?>

        <?php if (isset($success)): ?>
            <div class="alert alert-success">
                <i data-lucide="check-circle" style="width:16px;height:16px;flex-shrink:0;"></i>
                <?= htmlspecialchars($success) ?>
            </div>
        <?php endif; ?>

        <?php if (isset($error)): ?>
            <div class="alert alert-error">
                <i data-lucide="alert-circle" style="width:16px;height:16px;flex-shrink:0;"></i>
                <?= htmlspecialchars($error) ?>
            </div>
        <?php endif; ?>

        <?php
        $flashMessage = $_SESSION['flash_success'] ?? null;
        if ($flashMessage): unset($_SESSION['flash_success']); ?>
            <div class="alert alert-success">
                <i data-lucide="check-circle" style="width:16px;height:16px;flex-shrink:0;"></i>
                <?= htmlspecialchars($flashMessage) ?>
            </div>
        <?php endif; ?>

        <!-- Form -->
        <form method="POST" action="index.php?action=login">
            <div class="form-group">
                <label class="form-label"><?= __('login.email_label') ?></label>
                <input type="email" name="email" placeholder="<?= __('login.email_ph') ?>"
                       required class="form-input" autocomplete="email">
            </div>

            <div class="form-group" style="margin-bottom:6px;">
                <label class="form-label"><?= __('login.password_label') ?></label>
                <input type="password" name="password" placeholder="<?= __('login.password_ph') ?>"
                       required class="form-input" autocomplete="current-password">
            </div>

            <a href="index.php?action=forgot-password" class="forgot-link" style="margin-bottom:20px;display:block;">
                <?= __('login.forgot_password') ?>
            </a>

            <button type="submit" class="submit-btn"><?= __('login.submit') ?></button>
        </form>

        <p class="register-link">
            <?= __('login.no_account') ?> <a href="index.php?action=register"><?= __('login.register_link') ?></a>
        </p>

    </div>
</div>

<script>
    if (typeof lucide !== 'undefined') lucide.createIcons();
</script>
</body>
</html>