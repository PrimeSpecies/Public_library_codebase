<div style="max-width: 400px; margin: 80px auto; text-align: center; font-family: 'Inter', sans-serif;">
    <h2 style="font-weight: 800;">Check your email</h2>
    <p style="color: #64748b;">We sent a code to <strong><?= htmlspecialchars($_SESSION['reset_email']) ?></strong></p>
    
    <form action="index.php?action=verify-reset-otp" method="POST" style="margin-top: 30px;">
        <input type="text" name="otp_code" maxlength="6" placeholder="000000" required 
               style="width: 100%; padding: 14px; border: 1px solid #e2e8f0; border-radius: 12px; text-align: center; letter-spacing: 4px; font-weight: 700; font-size: 1.2rem;">
        
        <button type="submit" style="width: 100%; margin-top: 20px; padding: 14px; background: #000; color: #fff; border: none; border-radius: 12px; font-weight: 600; cursor: pointer;">
            Verify Code
        </button>
    </form>
</div>