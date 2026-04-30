<div style="max-width: 400px; margin: 80px auto; font-family: 'Inter', sans-serif; text-align: center;">
    <div style="background: white; padding: 40px; border-radius: 24px; box-shadow: 0 20px 40px rgba(0,0,0,0.05); border: 1px solid #f0f0f0;">
        
        <div style="background: #f0f7ff; width: 64px; height: 64px; border-radius: 50%; display: flex; align-items: center; justify-content: center; margin: 0 auto 20px;">
            <i data-lucide="shield-check" style="color: #3b82f6; width: 32px; height: 32px;"></i>
        </div>

        <h2 style="font-weight: 800; font-size: 1.5rem; margin: 0; letter-spacing: -1px;">Verify Identity</h2>
        <p style="color: #64748b; font-size: 0.95rem; margin-top: 10px; line-height: 1.5;">
            We will send a 6-digit reset code to:
        </p>
        
        <div style="background: #f8fafc; padding: 12px; border-radius: 12px; margin: 15px 0; font-weight: 600; color: #1e293b; border: 1px solid #e2e8f0;">
            <?= htmlspecialchars($_SESSION['attempted_email'] ?? 'your registered email') ?>
        </div>

        <form action="index.php?action=forgot-password" method="POST">
            <button type="submit" style="width: 100%; padding: 14px; background: #000; color: #fff; border: none; border-radius: 12px; font-weight: 600; cursor: pointer; display: flex; align-items: center; justify-content: center; gap: 10px;">
                <i data-lucide="send" style="width: 18px;"></i> Send Reset Code
            </button>
        </form>

        <a href="index.php?action=login" style="display: block; margin-top: 24px; color: #64748b; text-decoration: none; font-size: 0.85rem;">
            Not your email? Back to login
        </a>
    </div>
</div>