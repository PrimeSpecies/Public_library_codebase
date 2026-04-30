<?php

class AuthController {
    private $userService;

    public function __construct() {
        $this->userService = new UserService();
    }

    public function register() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $email = $_POST['email'] ?? '';
            $password = $_POST['password'] ?? '';

            $result = $this->userService->registerUser($email, $password);

            if ($result['success']) {
                $this->userService->sendEmailOTP($email, 'registration');
                $_SESSION['verifying_email'] = $email;
                header("Location: index.php?action=verify-email");
                exit();
            } else {
                $error = $result['message'];
                include __DIR__ . '/../../views/auth/register.php';
            }
        } else {
            include __DIR__ . '/../../views/auth/register.php';
        }
    }

    public function login() {
        

        $viewPath = dirname(__DIR__, 2) . '/views/auth/login.php';

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $email = $_POST['email'] ?? '';
            $password = $_POST['password'] ?? '';

            // 🛡️ Save email for potential Forgot Password flow
            if (session_status() === PHP_SESSION_NONE) session_start();
            $_SESSION['attempted_email'] = $email;

            $authResult = $this->userService->verifyCredentials($email, $password);

            if ($authResult['success']) {
                $user = $authResult['user'];

                if (isset($user['is_suspended']) && ($user['is_suspended'] === true || $user['is_suspended'] === 't')) {
                    $error = "Access Denied: This account has been deactivated.";
                    include $viewPath;
                    exit(); 
                }

                session_regenerate_id(true);

                if (isset($user['role']) && $user['role'] === 'admin') {
                    $_SESSION['pending_user_id'] = $user['id'];
                    $_SESSION['pending_user_email'] = $user['email'];
                    $_SESSION['pending_user_role'] = $user['role'];
                    $_SESSION['mfa_pending'] = true; 
                    header("Location: index.php?action=verify-otp");
                    exit();
                }

                $_SESSION['user_id'] = $user['id'];
                $_SESSION['user_email'] = $user['email'];
                $_SESSION['user_role'] = $user['role'];
                unset($_SESSION['attempted_email']);
                
                header("Location: index.php?action=dashboard");
                exit();

            } else {
                $error = $authResult['message'];
                include $viewPath;
            }
        } else {
            include $viewPath;
        }
    }

    public function forgotPassword() {
        if (!isset($_SESSION['attempted_email'])) {
            header("Location: index.php?action=login");
            exit();
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $email = $_SESSION['attempted_email'];
            $this->userService->sendEmailOTP($email, 'password_reset');
            $_SESSION['reset_email'] = $email;
            header("Location: index.php?action=verify-reset-otp");
            exit();
        } else {
            include __DIR__ . '/../../views/auth/forgot-password.php';
        }
    }

    public function verifyResetOTP() {
        if (!isset($_SESSION['reset_email'])) {
            header("Location: index.php?action=forgot-password");
            exit();
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $otp = $_POST['otp_code'] ?? '';
            $email = $_SESSION['reset_email'];

            if ($this->userService->verifyEmailOTP($email, $otp, 'password_reset')) {
                $_SESSION['otp_verified'] = true; // 🔓 Gate Opened
                header("Location: index.php?action=reset-password");
                exit();
            } else {
                $error = "Invalid or expired code.";
                include __DIR__ . '/../../views/auth/verify-reset-otp.php';
            }
        } else {
            include __DIR__ . '/../../views/auth/verify-reset-otp.php';
        }
    }
    public function toggleUserStatus() {
    $userId = $_GET['id'] ?? null;

    if ($userId) {
        // We call the service to handle the DB update
        $this->userService->toggleUserSuspension($userId);
    }

    // Redirect back to the dashboard to see the change
    header("Location: index.php?action=dashboard");
    exit();
}
    public function resetPassword() {
        // 🛡️ Security Check: Did they pass the OTP gate?
        if (!isset($_SESSION['otp_verified']) || $_SESSION['otp_verified'] !== true) {
            header("Location: index.php?action=verify-reset-otp");
            exit();
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $newPass = $_POST['new_password'] ?? '';
            $confirmPass = $_POST['confirm_password'] ?? '';
            $email = $_SESSION['reset_email'];

            if ($newPass === $confirmPass) {
                $this->userService->resetPassword($email, $newPass);
                unset($_SESSION['reset_email'], $_SESSION['otp_verified'], $_SESSION['attempted_email']);
                
                $success = "Success! Log in with your new password.";
                include __DIR__ . '/../../views/auth/login.php';
            } else {
                $error = "Passwords do not match.";
                include __DIR__ . '/../../views/auth/reset-password.php';
            }
        } else {
            include __DIR__ . '/../../views/auth/reset-password.php';
        }
    }

    public function checkOTP() {
        $email = $_SESSION['pending_user_email'] ?? '';
        $code = $_POST['otp_code'] ?? '';

        if ($this->userService->verify2FA($email, $code)) {
            $_SESSION['user_id'] = $_SESSION['pending_user_id']; 
            $_SESSION['user_email'] = $email;
            $_SESSION['user_role'] = $_SESSION['pending_user_role'];
            unset($_SESSION['pending_user_id'], $_SESSION['pending_user_email'], $_SESSION['mfa_pending']);
            header("Location: index.php?action=dashboard");
            exit();
        } else {
            $error = "Invalid 2FA Code.";
            include __DIR__ . '/../../views/auth/verifyOTP.php';
        }
    }

   public function verifyEmail() {
    if (!isset($_SESSION['verifying_email'])) {
        header("Location: index.php?action=register");
        exit();
    }

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $code = $_POST['otp_code'] ?? '';
        $email = $_SESSION['verifying_email'];

        if ($this->userService->verifyEmailOTP($email, $code, 'registration')) {
            // 1. Clear the verification session
            unset($_SESSION['verifying_email']);
            
            $_SESSION['flash_success'] = "Account verified! Please log in.";
            header("Location: index.php?action=login");
            exit();
            // 2. Set a success message
            $success = "Account verified successfully! You can now log in.";
            
            // 3. Include the login view (it will now have access to $success)
            include __DIR__ . '/../../views/auth/login.php';
            exit(); 
        } else {
            $error = "Invalid or expired verification code.";
            include __DIR__ . '/../../views/auth/verify-email.php';
        }
    } else {
        include __DIR__ . '/../../views/auth/verify-email.php';
    }
}
    public function logout() {
        $_SESSION = [];
        session_destroy();
        header("Location: index.php?action=home");
        exit();
    }
}