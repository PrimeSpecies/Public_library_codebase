<?php
// Security check: If someone tries to load this page directly without a session, kick them out
if (!isset($_SESSION['otp_verified']) || $_SESSION['otp_verified'] !== true) {
    header("Location: index.php?action=forgot-password");
    exit();
}
?>

<style>
    /* Mobile-specific adjustments */
    .reset-container {
        max-width: 420px;
        margin: 80px auto;
        padding: 0 20px; /* Prevents box from touching screen edges */
        font-family: 'Inter', -apple-system, sans-serif;
    }

    @media (max-width: 480px) {
        .reset-container {
            margin: 40px auto; /* Less top space on mobile */
        }
        .reset-card {
            padding: 30px 20px !important; /* Tighter padding */
        }
        .reset-card h2 {
            font-size: 1.4rem !important;
        }
        /* Prevents iOS auto-zoom on focus by ensuring 16px font */
        input[type="password"] {
            font-size: 16px !important; 
        }
    }
</style>

<div class="reset-container">
    <div class="reset-card" style="background: white; padding: 40px; border-radius: 24px; box-shadow: 0 20px 40px rgba(0,0,0,0.05); border: 1px solid #f0f0f0;">
        
        <div style="text-align: center; margin-bottom: 30px;">
            <div style="background: #f0fdf4; width: 64px; height: 64px; border-radius: 50%; display: flex; align-items: center; justify-content: center; margin: 0 auto 20px;">
                <i data-lucide="lock" style="color: #22c55e; width: 30px; height: 30px;"></i>
            </div>
            <h2 style="font-weight: 800; font-size: 1.6rem; margin: 0; color: #1e293b; letter-spacing: -1px;">Secure Your Account</h2>
            <p style="color: #64748b; font-size: 0.95rem; margin-top: 8px;">Create a new password for <br><strong style="color: #1e293b;"><?= htmlspecialchars($_SESSION['reset_email']) ?></strong></p>
        </div>

        <?php if (isset($error)): ?>
            <div style="background: #fef2f2; color: #dc2626; padding: 12px; border-radius: 12px; font-size: 0.85rem; margin-bottom: 20px; border: 1px solid #fee2e2; text-align: center;">
                <?= $error ?>
            </div>
        <?php endif; ?>

        <form action="index.php?action=reset-password" method="POST" id="resetForm">
            <div style="margin-bottom: 20px;">
                <label style="font-size: 0.75rem; font-weight: 800; color: #94a3b8; display: block; margin-bottom: 8px; text-transform: uppercase;">New Password</label>
                <input type="password" name="new_password" id="new_password" required placeholder="••••••••"
                       style="width: 100%; padding: 14px; border: 2px solid #e2e8f0; border-radius: 12px; transition: all 0.2s; outline: none; box-sizing: border-box;">
                
                <div style="height: 4px; width: 100%; background: #e2e8f0; border-radius: 2px; margin-top: 10px; overflow: hidden;">
                    <div id="strength_bar" style="height: 100%; width: 0%; transition: width 0.3s, background 0.3s;"></div>
                </div>
            </div>

            <div style="margin-bottom: 30px;">
                <label style="font-size: 0.75rem; font-weight: 800; color: #94a3b8; display: block; margin-bottom: 8px; text-transform: uppercase;">Confirm New Password</label>
                <input type="password" name="confirm_password" id="confirm_password" required placeholder="••••••••"
                       style="width: 100%; padding: 14px; border: 2px solid #e2e8f0; border-radius: 12px; transition: all 0.2s; outline: none; box-sizing: border-box;">
                <p id="match_message" style="font-size: 0.8rem; margin-top: 8px; font-weight: 500; min-height: 1.2em; text-align: center;"></p>
            </div>

            <button type="submit" id="submit_btn" disabled
                    style="width: 100%; padding: 16px; background: #1e293b; color: #fff; border: none; border-radius: 12px; font-weight: 600; cursor: not-allowed; opacity: 0.5; transition: all 0.2s;">
                Update Password
            </button>
        </form>
    </div>
</div>

<script>
const passInput = document.getElementById('new_password');
const confirmInput = document.getElementById('confirm_password');
const strengthBar = document.getElementById('strength_bar');
const matchMessage = document.getElementById('match_message');
const submitBtn = document.getElementById('submit_btn');

function validate() {
    const val = passInput.value;
    const confirmVal = confirmInput.value;

    // 1. Check Strength
    let strength = 0;
    if (val.length >= 8) strength += 40;
    if (/[A-Z]/.test(val)) strength += 20;
    if (/[0-9]/.test(val)) strength += 20;
    if (/[^A-Za-z0-9]/.test(val)) strength += 20;

    strengthBar.style.width = strength + '%';
    if (strength < 50) strengthBar.style.background = '#ef4444';
    else if (strength < 80) strengthBar.style.background = '#f59e0b';
    else strengthBar.style.background = '#22c55e';

    // 2. Check Matching
    if (confirmVal === "") {
        matchMessage.textContent = "";
        confirmInput.style.borderColor = "#e2e8f0";
    } else if (val === confirmVal && val.length >= 8) {
        matchMessage.textContent = "✓ Passwords match";
        matchMessage.style.color = "#22c55e";
        confirmInput.style.borderColor = "#22c55e";
        submitBtn.disabled = false;
        submitBtn.style.opacity = "1";
        submitBtn.style.background = "#1e293b";
        submitBtn.style.cursor = "pointer";
    } else {
        matchMessage.textContent = val.length < 8 ? "Password must be at least 8 characters" : "✕ Passwords do not match";
        matchMessage.style.color = "#ef4444";
        confirmInput.style.borderColor = "#ef4444";
        submitBtn.disabled = true;
        submitBtn.style.opacity = "0.5";
        submitBtn.style.cursor = "not-allowed";
    }
}

passInput.addEventListener('input', validate);
confirmInput.addEventListener('input', validate);

// Initialize Lucide icons
if (typeof lucide !== 'undefined') {
    lucide.createIcons();
}
</script>