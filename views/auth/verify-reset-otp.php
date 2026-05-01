<style>
    /* Mobile-specific adjustments */
    .reset-otp-container {
        max-width: 400px;
        margin: 80px auto;
        padding: 0 20px; /* Prevents touching screen edges */
        text-align: center;
        font-family: 'Inter', sans-serif;
    }

    @media (max-width: 480px) {
        .reset-otp-container {
            margin: 40px auto; /* Tighter vertical spacing */
        }
        .reset-otp-card {
            padding: 30px 20px !important;
        }
        #reset_otp_input {
            font-size: 1.5rem !important; /* Slightly smaller for mobile fit */
            letter-spacing: 8px !important; /* Clearer separation */
        }
    }
</style>

<div class="reset-otp-container">
    <div class="reset-otp-card" style="background: white; padding: 40px; border-radius: 24px; box-shadow: 0 10px 25px rgba(0,0,0,0.05); border: 1px solid rgba(0,0,0,0.05);">
        
        <div style="background: #eff6ff; width: 64px; height: 64px; border-radius: 50%; display: flex; align-items: center; justify-content: center; margin: 0 auto 20px;">
            <i data-lucide="key-round" style="color: #3b82f6; width: 30px; height: 30px;"></i>
        </div>

        <h2 style="font-weight: 800; font-size: 1.5rem; margin-bottom: 10px; letter-spacing: -0.5px;">Check your email</h2>
        <p style="color: #64748b; font-size: 0.95rem; line-height: 1.5;">
            We sent a reset code to <br>
            <strong style="color: #1e293b; word-break: break-all;"><?= htmlspecialchars($_SESSION['reset_email']) ?></strong>
        </p>
        
        <form action="index.php?action=verify-reset-otp" method="POST" style="margin-top: 30px;">
            <label style="display: block; font-size: 0.75rem; font-weight: 700; color: #94a3b8; text-transform: uppercase; letter-spacing: 1px; margin-bottom: 15px;">
                Enter 6-Digit Code
            </label>
            
            <input type="text" 
                   name="otp_code" 
                   id="reset_otp_input"
                   maxlength="6" 
                   placeholder="000000" 
                   inputmode="numeric" 
                   pattern="[0-9]*"
                   required 
                   style="width: 100%; padding: 14px; border: 2px solid #e2e8f0; border-radius: 12px; text-align: center; letter-spacing: 10px; font-weight: 800; font-size: 1.8rem; outline: none; transition: border-color 0.2s; font-family: monospace; box-sizing: border-box;">
            
            <button type="submit" style="width: 100%; margin-top: 25px; padding: 16px; background: #000; color: #fff; border: none; border-radius: 12px; font-weight: 600; cursor: pointer; transition: opacity 0.3s;">
                Verify Code
            </button>
        </form>

        <p style="margin-top: 25px; font-size: 0.85rem; color: #64748b;">
            Wait a few minutes? <a href="index.php?action=forgot-password" style="color: #3b82f6; text-decoration: none; font-weight: 600;">Resend Code</a>
        </p>
    </div>
</div>

<script>
    // Force numeric only
    const otpInput = document.getElementById('reset_otp_input');
    otpInput.addEventListener('input', function() {
        this.value = this.value.replace(/[^0-9]/g, '');
    });

    // Handle focus state
    otpInput.addEventListener('focus', () => otpInput.style.borderColor = '#3b82f6');
    otpInput.addEventListener('blur', () => otpInput.style.borderColor = '#e2e8f0');

    // Initialize icons if lucide is loaded
    if (typeof lucide !== 'undefined') {
        lucide.createIcons();
    }
</script>