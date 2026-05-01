<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Verify Email | ResearchHub</title>
    <link rel="stylesheet" href="assets/css/style.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;800&display=swap" rel="stylesheet">
    <script src="https://unpkg.com/lucide@latest"></script>
    <style>
        /* Base Reset */
        * { box-sizing: border-box; }

        /* Mobile specific adjustments */
        @media (max-width: 480px) {
            .verify-card {
                padding: 30px 20px !important; /* Smaller padding */
                margin: 20px;
            }
            #otp_input {
                font-size: 24px !important; /* Smaller font so 6 digits fit */
                letter-spacing: 8px !important; /* Tighter spacing */
                padding: 12px !important;
            }
            .verify-title {
                font-size: 1.3rem !important;
            }
        }
    </style>
</head>
<body style="background-color: #f8f9fa; display: flex; align-items: center; justify-content: center; min-height: 100vh; margin: 0; font-family: 'Inter', sans-serif;">

    <div class="verify-card" style="background: white; padding: 40px; border-radius: 20px; box-shadow: 0 10px 25px rgba(0,0,0,0.05); width: 100%; max-width: 450px; border: 1px solid rgba(0,0,0,0.05); text-align: center;">
        
        <div style="background: #f0fdf4; width: 64px; height: 64px; border-radius: 50%; display: flex; align-items: center; justify-content: center; margin: 0 auto 24px;">
            <i data-lucide="mail-check" style="color: #22c55e; width: 32px; height: 32px;"></i>
        </div>

        <h2 class="verify-title" style="margin: 0; font-size: 1.5rem; color: #333; font-weight: 800; letter-spacing: -0.5px;">Check your Email</h2>
        <p style="color: #666; font-size: 0.95rem; margin-top: 10px; line-height: 1.5;">
            We've sent a 6-digit verification code to <br>
            <strong style="color: #333; word-break: break-all;"><?php echo htmlspecialchars($_SESSION['verifying_email'] ?? 'your email'); ?></strong>
        </p>

        <?php if (isset($error)): ?>
            <div style="background: #fef2f2; color: #991b1b; padding: 12px; margin-top: 20px; border-radius: 12px; font-size: 0.85rem; border: 1px solid #fee2e2; font-weight: 500;">
                <strong>⚠️ Error:</strong> <?php echo htmlspecialchars($error); ?>
            </div>
        <?php endif; ?>

        <form action="index.php?action=verify-email" method="POST" style="margin-top: 30px;">
            <label style="display: block; font-size: 0.75rem; font-weight: 700; margin-bottom: 15px; color: #94a3b8; text-transform: uppercase; letter-spacing: 1px;">
                Enter 6-digit Code
            </label>
            
            <!-- inputmode="numeric" triggers the number pad on mobile keyboards -->
            <input type="text" 
                   name="otp_code" 
                   id="otp_input" 
                   placeholder="000000" 
                   maxlength="6" 
                   inputmode="numeric" 
                   pattern="[0-9]*"
                   autofocus 
                   required 
                   style="width: 100%; padding: 15px; font-size: 32px; font-weight: 800; letter-spacing: 12px; text-align: center; border: 2px solid #eee; border-radius: 12px; outline: none; transition: border-color 0.2s; font-family: monospace; appearance: none;">
            
            <button type="submit" style="width: 100%; padding: 14px; background: black; color: white; border: none; border-radius: 12px; font-weight: 600; cursor: pointer; margin-top: 25px; transition: 0.3s; display: flex; align-items: center; justify-content: center; gap: 8px;">
                Verify Account <i data-lucide="shield-check" style="width: 18px; height: 18px;"></i>
            </button>
        </form>

        <p style="font-size: 0.85rem; margin-top: 25px; color: #888;">
            Didn't get a code? <br>
            <a href="index.php?action=resend-otp" style="color: #007bff; text-decoration: none; font-weight: 600;">Click here to resend</a>
        </p>
    </div>

    <script>
        lucide.createIcons();
        
        const otpInput = document.getElementById('otp_input');
        otpInput.addEventListener('focus', () => otpInput.style.borderColor = '#007bff');
        otpInput.addEventListener('blur', () => otpInput.style.borderColor = '#eee');

        // Force numeric and auto-submit when 6 digits are reached
        otpInput.addEventListener('input', function() {
            this.value = this.value.replace(/[^0-9]/g, '');
            if (this.value.length === 6) {
                // Optional: Automatically submit form when 6th digit is typed
                // this.form.submit(); 
            }
        });
    </script>
</body>
</html>