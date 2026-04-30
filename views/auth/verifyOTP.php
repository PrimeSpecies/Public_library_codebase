<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Verification | ResearchHub</title>
    <link rel="stylesheet" href="assets/css/style.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;800&display=swap" rel="stylesheet">
    <script src="https://unpkg.com/lucide@latest"></script>
</head>
<body style="background-color: #f8f9fa; display: flex; align-items: center; justify-content: center; min-height: 100vh; margin: 0; font-family: 'Inter', sans-serif;">

    <div style="background: white; padding: 40px; border-radius: 20px; box-shadow: 0 10px 25px rgba(0,0,0,0.05); width: 100%; max-width: 400px; border: 1px solid rgba(0,0,0,0.05); text-align: center;">
        
        <div style="background: #f0f7ff; width: 64px; height: 64px; border-radius: 50%; display: flex; align-items: center; justify-content: center; margin: 0 auto 24px;">
            <i data-lucide="shield-check" style="color: #007bff; width: 32px; height: 32px;"></i>
        </div>

        <h2 style="margin: 0; font-size: 1.5rem; color: #333; font-weight: 800; letter-spacing: -0.5px;">Admin Security Check</h2>
        <p style="color: #666; font-size: 0.95rem; margin-top: 10px; line-height: 1.5;">
            Enter the 6-digit code from your <br>
            <strong style="color: #333;">Authenticator App</strong> to continue.
        </p>

        <?php if (isset($error)): ?>
            <div style="background: #fff5f5; color: #c53030; padding: 12px; margin-top: 20px; border-radius: 8px; font-size: 0.85rem; border: 1px solid #feb2b2;">
                <strong>🛑 Access Denied:</strong> <?php echo htmlspecialchars($error); ?>
            </div>
        <?php endif; ?>

        <form action="index.php?action=check-otp" method="POST" style="margin-top: 30px;">
            <div style="margin-bottom: 20px;">
                <input type="text" name="otp_code" id="admin_otp" placeholder="000000" maxlength="6" pattern="\d{6}" autofocus required 
                       style="width: 100%; padding: 15px; font-size: 32px; font-weight: 800; letter-spacing: 12px; text-align: center; border: 2px solid #eee; border-radius: 12px; outline: none; transition: all 0.2s; font-family: monospace; box-sizing: border-box;">
            </div>
            
            <button type="submit" style="width: 100%; padding: 14px; background: black; color: white; border: none; border-radius: 12px; font-weight: 600; cursor: pointer; transition: 0.3s; display: flex; align-items: center; justify-content: center; gap: 8px; font-size: 1rem;">
                Verify Identity <i data-lucide="lock" style="width: 18px; height: 18px;"></i>
            </button>
        </form>

        <div style="margin-top: 25px; padding-top: 20px; border-top: 1px solid #f1f1f1;">
             <a href="index.php?action=logout" style="color: #666; text-decoration: none; font-size: 0.85rem; font-weight: 600;">
                Cancel & Sign Out
            </a>
        </div>
    </div>

    <script>
        lucide.createIcons();
        
        const otpInput = document.getElementById('admin_otp');
        
        // Focus effect matching the main theme
        otpInput.addEventListener('focus', () => {
            otpInput.style.borderColor = '#007bff';
        });
        
        otpInput.addEventListener('blur', () => {
            otpInput.style.borderColor = '#eee';
        });

        // Numeric only restriction
        otpInput.addEventListener('input', function() {
            this.value = this.value.replace(/[^0-9]/g, '');
        });
    </script>
</body>
</html>