<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login | ResearchHub</title>
    <link rel="stylesheet" href="assets/css/style.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;800&display=swap" rel="stylesheet">
    <script src="https://unpkg.com/lucide@latest"></script>
    
    <style>
        /* Base Reset */
        * { box-sizing: border-box; }
        
        /* Mobile Adjustments */
        @media (max-width: 480px) {
            .login-card {
                padding: 25px !important; /* Reduces internal spacing on small screens */
            }
            .brand-title {
                font-size: 1.3rem !important;
            }
        }
    </style>
</head>
<body style="background-color: #f8f9fa; display: flex; align-items: center; justify-content: center; min-height: 100vh; margin: 0; padding: 20px; font-family: 'Inter', sans-serif;">

    <!-- Added 'login-card' class for mobile media query targeting -->
    <div class="login-card" style="background: white; padding: 40px; border-radius: 20px; box-shadow: 0 10px 25px rgba(0,0,0,0.05); width: 100%; max-width: 400px; border: 1px solid rgba(0,0,0,0.05);">
        
        <div style="text-align: center; margin-bottom: 30px;">
            <div class="brand-title" style="font-weight: 800; font-size: 1.5rem; letter-spacing: -1px; margin-bottom: 10px;">ResearchHub</div>
            <h2 style="margin: 0; font-size: 1.25rem; color: #333;">Welcome Back</h2>
            <p style="color: #666; font-size: 0.9rem; margin-top: 5px;">Login to access your research library</p>
        </div>

        <!-- System Messages (Cleaned up duplicates) -->
        <?php if (isset($_GET['reset']) && $_GET['reset'] === 'success'): ?>
            <div style="background: #f0fdf4; color: #166534; padding: 12px; border-radius: 12px; font-size: 0.85rem; margin-bottom: 20px; border: 1px solid #bbf7d0; text-align: center; font-weight: 500;">
                <i data-lucide="check-circle" style="width: 16px; height: 16px; vertical-align: middle; margin-right: 4px;"></i>
                Password updated. Please log in.
            </div>
        <?php endif; ?>

        <?php if (isset($success)): ?>
            <div style="background: #f0fdf4; color: #166534; padding: 12px; border-radius: 12px; font-size: 0.85rem; margin-bottom: 20px; border: 1px solid #bbf7d0; text-align: center; font-weight: 500;">
                <i data-lucide="check-circle" style="width: 16px; height: 16px; vertical-align: middle; margin-right: 4px;"></i>
                <?= htmlspecialchars($success) ?>
            </div>
        <?php endif; ?>

        <?php if (isset($error)): ?>
            <div style="background: #fef2f2; color: #991b1b; padding: 12px; border-radius: 12px; font-size: 0.85rem; margin-bottom: 20px; border: 1px solid #fee2e2; text-align: center; font-weight: 500;">
                <i data-lucide="alert-circle" style="width: 16px; height: 16px; vertical-align: middle; margin-right: 4px;"></i>
                <?= htmlspecialchars($error) ?>
            </div>
        <?php endif; ?>

        <?php 
        $flashMessage = $_SESSION['flash_success'] ?? null;
        if ($flashMessage): 
            unset($_SESSION['flash_success']); 
        ?>
            <div style="background: #f0fdf4; color: #166534; padding: 14px; border-radius: 12px; font-size: 0.9rem; margin-bottom: 20px; border: 1px solid #bbf7d0; display: flex; align-items: center; justify-content: center; gap: 8px; font-weight: 600;">
                <i data-lucide="check-circle" style="width: 18px; height: 18px;"></i>
                <?= htmlspecialchars($flashMessage) ?>
            </div>
        <?php endif; ?>

        <!-- Login Form -->
        <form method="POST" action="index.php?action=login">
            <div style="margin-bottom: 20px;">
                <label style="display: block; font-size: 0.85rem; font-weight: 600; margin-bottom: 5px;">Email Address</label>
                <input type="email" name="email" placeholder="example@email.com" required 
                       style="width: 100%; padding: 12px; border: 1px solid #ddd; border-radius: 8px; font-family: inherit; outline: none; transition: border-color 0.3s;">
            </div>

            <div style="margin-bottom: 5px;">
                <label style="display: block; font-size: 0.85rem; font-weight: 600; margin-bottom: 5px;">Password</label>
                <input type="password" name="password" placeholder="Enter your password" required 
                       style="width: 100%; padding: 12px; border: 1px solid #ddd; border-radius: 8px; font-family: inherit; outline: none; transition: border-color 0.3s;">
            </div>
            
            <div style="text-align: right; margin-bottom: 25px;">
                <a href="index.php?action=forgot-password" style="font-size: 0.85em; color: #007bff; text-decoration: none; font-weight: 600;">
                    Forgot Password?
                </a>
            </div>
            
            <button type="submit" style="width: 100%; padding: 12px; background: black; color: white; border: none; border-radius: 8px; font-weight: 600; cursor: pointer; transition: 0.3s;">
                Login
            </button>
        </form>

        <p style="text-align: center; font-size: 0.9rem; margin-top: 25px; color: #666;">
            New user? <a href="index.php?action=register" style="color: #007bff; text-decoration: none; font-weight: 600;">Create an account here</a>
        </p>
    </div>

    <script>
        // Ensure the icons render correctly if a flash message triggers them
        if (typeof lucide !== 'undefined') {
            lucide.createIcons();
        }
    </script>
</body>
</html>