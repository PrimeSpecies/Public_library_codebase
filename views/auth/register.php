<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create Account | ResearchHub</title>
    <link rel="stylesheet" href="assets/css/style.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;800&display=swap" rel="stylesheet">
    <script src="https://unpkg.com/lucide@latest"></script>
</head>
<body style="background-color: #f8f9fa; display: flex; align-items: center; justify-content: center; min-height: 100vh; margin: 0;">

    <div style="background: white; padding: 40px; border-radius: 20px; box-shadow: 0 10px 25px rgba(0,0,0,0.05); width: 100%; max-width: 400px; border: 1px solid rgba(0,0,0,0.05);">

        <div style="text-align: center; margin-bottom: 30px;">
            <div style="font-weight: 800; font-size: 1.5rem; letter-spacing: -1px; margin-bottom: 10px;">ResearchHub</div>
            <h2 style="margin: 0; font-size: 1.25rem; color: #333;">Create an Account</h2>
            <p style="color: #666; font-size: 0.9rem; margin-top: 5px;">Join the future of academic research</p>
        </div>

        <?php if (isset($error)): ?>
            <div style="background: #fff5f5; color: #c53030; padding: 12px; border-radius: 8px; margin-bottom: 20px; font-size: 0.85rem; border: 1px solid #feb2b2;">
                <strong>⚠️ Error:</strong> <?php echo htmlspecialchars($error); ?>
            </div>
        <?php endif; ?>

        <form method="POST" action="index.php?action=register">
            <div style="margin-bottom: 15px;">
        <label style="display: block; font-size: 0.85rem; font-weight: 600; margin-bottom: 5px;">Full Name</label>
        <input type="text" name="name" placeholder="e.g. Mballa Julien" required
               style="width: 100%; padding: 12px; border: 1px solid #ddd; border-radius: 8px; font-family: inherit; box-sizing: border-box;">
    </div>

    <div style="margin-bottom: 15px;">
        <label style="display: block; font-size: 0.85rem; font-weight: 600; margin-bottom: 5px;">Phone Number</label>
        <input type="tel" name="phone_number" placeholder="e.g. +237 650607080" required
               style="width: 100%; padding: 12px; border: 1px solid #ddd; border-radius: 8px; font-family: inherit; box-sizing: border-box;">
    </div>
            <div style="margin-bottom: 15px;">
                <label style="display: block; font-size: 0.85rem; font-weight: 600; margin-bottom: 5px;">Email Address</label>
                <input type="email" name="email" placeholder="e.g. mballasteve@gmail.com" required
                       style="width: 100%; padding: 12px; border: 1px solid #ddd; border-radius: 8px; font-family: inherit; box-sizing: border-box;">
            </div>

            <div style="margin-bottom: 5px;">
                <label style="display: block; font-size: 0.85rem; font-weight: 600; margin-bottom: 5px;">Password</label>
                <input type="password" id="reg_password" name="password" placeholder="••••••••" required
                       onkeyup="checkStrength(this.value)"
                       style="width: 100%; padding: 12px; border: 1px solid #ddd; border-radius: 8px; font-family: inherit; box-sizing: border-box;">
            </div>

            <div style="height: 4px; width: 100%; background: #eee; border-radius: 2px; margin-bottom: 5px; overflow: hidden;">
                <div id="strength-bar" style="height: 100%; width: 0%; transition: 0.3s ease;"></div>
            </div>
            <p id="strength-text" style="font-size: 0.75rem; color: #888; margin-bottom: 20px;">Use 8+ characters with numbers & caps.</p>

            <button type="submit" id="reg_submit" disabled
                    style="width: 100%; padding: 12px; background: #ccc; color: white; border: none; border-radius: 8px; font-weight: 600; cursor: not-allowed; transition: 0.3s;">
                Create Account
            </button>
        </form>

        <p style="text-align: center; font-size: 0.9rem; margin-top: 25px; color: #666;">
            Already have an account? <a href="index.php?action=login" style="color: #007bff; text-decoration: none; font-weight: 600;">Login here</a>
        </p>
    </div>

    <div id="loading-overlay" style="display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(255, 255, 255, 0.9); z-index: 9999; flex-direction: column; align-items: center; justify-content: center; font-family: 'Inter', sans-serif;">
    <div class="spinner"></div>
    <h2 style="margin-top: 20px; color: #1e293b; font-weight: 700;">Sending Verification Code...</h2>
    <p style="color: #64748b; font-size: 0.9rem;">Please wait while we secure your account.</p>
</div>

<style>
/* Modern CSS Spinner */
.spinner {
    width: 50px;
    height: 50px;
    border: 5px solid #f3f3f3;
    border-top: 5px solid #3b82f6; /* ResearchHub Blue */
    border-radius: 50%;
    animation: spin 1s linear infinite;
}

@keyframes spin {
    0% { transform: rotate(0deg); }
    100% { transform: rotate(360deg); }
}
</style>

    <script>
    function checkStrength(password) {
        let strength = 0;
        const bar = document.getElementById('strength-bar');
        const text = document.getElementById('strength-text');
        const btn = document.getElementById('reg_submit');

        if (password.length >= 8) strength++;
        if (/[A-Z]/.test(password)) strength++;
        if (/[0-9]/.test(password)) strength++;
        if (/[^A-Za-z0-9]/.test(password)) strength++;

        const colors = ['#ff4d4d', '#ff4d4d', '#ffd11a', '#2ecc71', '#2ecc71'];
        const messages = ['Too weak', 'Weak', 'Fair', 'Good', 'Strong!'];

        bar.style.width = (strength * 25) + '%';
        bar.style.backgroundColor = colors[strength];
        text.innerText = "Strength: " + messages[strength];

        if (strength >= 2) {
            btn.disabled = false;
            btn.style.background = "black";
            btn.style.cursor = "pointer";
        } else {
            btn.disabled = true;
            btn.style.background = "#ccc";
            btn.style.cursor = "not-allowed";
        }
    }


    document.querySelector('form').addEventListener('submit', function(e) {
    // Show the loading overlay
    const overlay = document.getElementById('loading-overlay');
    overlay.style.display = 'flex';

    // Optional: Disable the register button to prevent double-clicks
    const btn = this.querySelector('button[type="submit"]');
    if(btn) {
        btn.disabled = true;
        btn.innerText = "Processing...";
        btn.style.opacity = "0.7";
    }
});
    </script>
</body>
</html>