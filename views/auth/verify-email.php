<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Verify Email | ResearchHub</title>
    <link rel="stylesheet" href="assets/css/style.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;800&display=swap" rel="stylesheet">
    <script src="https://unpkg.com/lucide@latest"></script>
</head>
<body style="background-color: #f8f9fa; display: flex; align-items: center; justify-content: center; min-height: 100vh; margin: 0; font-family: 'Inter', sans-serif;">

    <div style="background: white; padding: 40px; border-radius: 20px; box-shadow: 0 10px 25px rgba(0,0,0,0.05); width: 100%; max-width: 450px; border: 1px solid rgba(0,0,0,0.05); text-align: center;">
        
        <div style="background: #f0fdf4; width: 64px; height: 64px; border-radius: 50%; display: flex; align-items: center; justify-content: center; margin: 0 auto 24px;">
            <i data-lucide="mail-check" style="color: #22c55e; width: 32px; height: 32px;"></i>
        </div>

        <h2 style="margin: 0; font-size: 1.5rem; color: #333; font-weight: 800; letter-spacing: -0.5px;">Check your Email</h2>
        <p style="color: #666; font-size: 0.95rem; margin-top: 10px; line-height: 1.5;">
            We've sent a 6-digit verification code to <br>
            <strong style="color: #333;"><?php echo htmlspecialchars($_SESSION['verifying_email'] ?? 'your email'); ?></strong>
        </p>

        <?php if (isset($error)): ?>
            <div style="color: white; background-color: #d9534f; padding: 12px; margin-top: 20px; border-radius: 8px; font-size: 0.85rem;">
                <strong>⚠️ Error:</strong> <?php echo htmlspecialchars($error); ?>
            </div>
        <?php endif; ?>

        <form action="index.php?action=verify-email" method="POST" style="margin-top: 30px;">
            <label style="display: block; font-size: 0.85rem; font-weight: 600; margin-bottom: 15px; color: #666; text-transform: uppercase; letter-spacing: 1px;">
                Enter 6-digit Code
            </label>
            
            <input type="text" name="otp_code" id="otp_input" placeholder="000000" maxlength="6" autofocus required 
                   style="width: 100%; padding: 15px; font-size: 32px; font-weight: 800; letter-spacing: 12px; text-align: center; border: 2px solid #eee; border-radius: 12px; outline: none; transition: border-color 0.2s; font-family: monospace;">
            
            <button type="submit" style="width: 100%; padding: 14px; background: black; color: white; border: none; border-radius: 12px; font-weight: 600; cursor: pointer; margin-top: 25px; transition: 0.3s; display: flex; align-items: center; justify-content: center; gap: 8px;">
                Verify Account <i data-lucide="shield-check" style="width: 18px; height: 18px;"></i>
            </button>
        </form>

        <p style="font-size: 0.85rem; margin-top: 25px; color: #888;">
            Didn't get a code? Check your spam folder or 
            <a href="index.php?action=resend-otp" style="color: #007bff; text-decoration: none; font-weight: 600;">resend it</a>.
        </p>
    </div>

    <script>
        lucide.createIcons();
        
        // Visual feedback on focus
        const otpInput = document.getElementById('otp_input');
        otpInput.addEventListener('focus', () => otpInput.style.borderColor = '#007bff');
        otpInput.addEventListener('blur', () => otpInput.style.borderColor = '#eee');

        // Only allow numbers
        otpInput.addEventListener('input', function() {
            this.value = this.value.replace(/[^0-9]/g, '');
        });
    </script>
</body>
</html>