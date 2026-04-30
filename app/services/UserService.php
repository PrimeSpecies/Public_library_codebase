<?php

// 1. These must come FIRST
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
use PHPMailer\PHPMailer\SMTP;

// 2. Then the physical links to the files
require_once __DIR__ . '/../../libs/phpmailer/Exception.php';
require_once __DIR__ . '/../../libs/phpmailer/PHPMailer.php';
require_once __DIR__ . '/../../libs/phpmailer/SMTP.php';
require_once __DIR__ . '/../../database/Database.php';
class UserService {


    private $userModel;
        public function getUserByEmail($email) {
        // Assuming your Database class has a query() method that returns a PDO statement
        $stmt = $this->db->query(
            "SELECT * FROM users WHERE email = :email LIMIT 1",
            ['email' => $email]
        );

        // fetch() will return the row as an associative array or FALSE if not found
        return $stmt ? $stmt->fetch(PDO::FETCH_ASSOC) : false;
    }
    // public function __construct() {
    //     // The Service creates the Model to talk to the DB
    //     $this->userModel = new User();
    // }
    private function findByEmail($email) {
        // We use SELECT * so we get password_hash, is_suspended, etc.
        return $this->db->query(
            "SELECT * FROM users WHERE email = :email", 
            ['email' => $email]
        )->fetch();
    }
    /**
     * The logic for registering a new user.
     * Returns true on success, or an error message string on failure.
     */
    public function registerUser($email, $password) {
        // 1. Basic Validation
        if (empty($email) || empty($password)) {
            return ['success' => false, 'message' => "Email and password are required."];
        }

        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            return ['success' => false, 'message' => "Please enter a valid email address."];
        }

    //2. Check for Duplicate Email
        if ($this->findByEmail($email)) {
           return ['success' => false, 'message' => "This email is already registered."];
    }

        // 3. Generate a Base32 Secret (for Google Authenticator)
        $chars = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ234567';
        $secret = '';
        for ($i = 0; $i < 16; $i++) {
            $secret .= $chars[rand(0, 31)];
        }

        $hashedPassword = password_hash($password, PASSWORD_ARGON2ID);

            // The logic that finally worked:
$success = $this->db->query(
    "INSERT INTO users (email, password_hash, google_2fa_secret, role, is_suspended) 
     VALUES (:email, :pass, :secret, :role, :suspended)",
    [
        'email'     => $email, 
        'pass'      => $hashedPassword, 
        'secret'    => $secret,
        'role'      => 'user',
        'suspended' => 0 // Use 0/1 for Postgres Booleans
    ]
);

        if ($success) {
            // Return the secret so the Controller can show it to the user!
            return [
                'success' => true, 
                'message' => "Registration successful!",
                'mfa_secret' => $secret 
            ];
        }
        
        return ['success' => false, 'message' => "An unexpected error occurred."];
    }
    
public function verifyCredentials($email, $password) {
    $user = $this->findByEmail($email);

    // 🚩 IMPORTANT: Change $user['password'] to $user['password_hash']
    if ($user && password_verify($password, $user['password_hash'])) {
        
        if ($user['is_suspended']) {
            return ['success' => false, 'message' => "Account deactivated."];
        }

        return [
            'success' => true,
            'user' => [
                'id' => $user['id'],
                'email' => $user['email'],
                'role' => $user['role'],
                'is_suspended' => $user['is_suspended']
            ]
        ];
    }
    return ['success' => false, 'message' => "Invalid email or password."];
}

  public function verify2FA($userEmail, $inputCode) {
    $user = $this->findByEmail($userEmail);
    if (!$user || empty($user['google_2fa_secret'])) {
        return false;
    }

    $secret = $user['google_2fa_secret'];

    // 1. Clean the input and secret
    $inputCode = str_replace(' ', '', $inputCode);
    
    // 2. Calculate the current 30-second time window
    $timeWindow = floor(time() / 30);

    // 3. Manual TOTP Verification logic (Standard RFC 6238)
    // For Sprint 1, we can use a simpler version or a small helper file.
    // If you prefer the library approach, see Option 2 below.
    
    return $this->checkTOTP($secret, $inputCode); 
}
    /**
 * Verifies credentials and returns user data (without the password hash)
 */
    public function loginUser($email, $password) {
        // 1. Ask the Model to find the user in PostgreSQL
        $user = $this->findByEmail($email);

        // 2. If user exists, check the password using PHP's built-in verify tool
        if ($user && password_verify($password, $user['password_hash'])) {
            // Remove the sensitive hash before passing data back
            unset($user['password_hash']); 
            return $user;
        }

        // 3. If anything fails, return false
        return false;
    }

/**
 * Native TOTP Verification (Lead's Security Implementation)
 * Validates a 6-digit code against a Base32 secret.
 */
    private function checkTOTP($secret, $code) {
        $chars = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ234567';
        $secret = strtoupper(str_replace('=', '', trim($secret))); 
        $binarySecret = '';
        
        foreach (str_split($secret) as $char) {
            $pos = strpos($chars, $char);
            if ($pos === false) continue;
            $binarySecret .= str_pad(decbin($pos), 5, '0', STR_PAD_LEFT);
        }

        $binarySecretBytes = '';
        foreach (str_split($binarySecret, 8) as $bin) {
            if (strlen($bin) < 8) break;
            $binarySecretBytes .= chr(bindec($bin));
        }

        // 🛡️ THE TIME WINDOW (Check -1, 0, and +1 time steps)
        // This gives you a 90-second grace period.
        $currentTimeStep = floor(time() / 30);

        for ($i = -1; $i <= 1; $i++) {
            $checkTime = $currentTimeStep + $i;
            $timeBinary = pack('N*', 0) . pack('N*', $checkTime); 
            $hash = hash_hmac('sha1', $timeBinary, $binarySecretBytes, true);
            
            $offset = ord($hash[19]) & 0xf;
            $otp = (
                ((ord($hash[$offset+0]) & 0x7f) << 24) |
                ((ord($hash[$offset+1]) & 0xff) << 16) |
                ((ord($hash[$offset+2]) & 0xff) << 8) |
                (ord($hash[$offset+3]) & 0xff)
            ) % 1000000;

            $calculated = str_pad($otp, 6, '0', STR_PAD_LEFT);
            
            if ($calculated === $code) {
                return true; // Match found in the window!
            }
        }

        return false; // No match found in any window
    }
    private $db;

        public function __construct() {
        // Use the Singleton instance instead of 'new'
    $this->db = \Database::getInstance(); 
       }
    public function getAllUsers() {
    // Fetch everyone except the current admin (optional)
        return $this->db->query("SELECT id, email, role, is_suspended FROM users ORDER BY id ASC")->fetchAll();
    }

    public function toggleUserStatus($userId) {
        // This flips the boolean: if 0, make 1. If 1, make 0.
        return $this->db->query(
            "UPDATE users SET is_suspended = NOT is_suspended WHERE id = :id", 
            ['id' => $userId]
        );
    }

    public function sendEmailOTP($email, $type = 'registration') {
        // 1. Generate 6-digit code
        $otp = str_pad(random_int(0, 999999), 6, '0', STR_PAD_LEFT);
        $expires = date('Y-m-d H:i:s', strtotime('+15 minutes'));

        // 2. Save to DB (Postgres style)
        $this->db->query(
            "INSERT INTO user_otps (email, otp_code, type, expires_at) VALUES (:email, :code, :type, :expires)",
            [
                'email' => $email,
                'code'  => $otp,
                'type'  => $type,
                'expires' => $expires
            ]
        );

        // 3. Send the Email
        return $this->mailUser($email, "Your Verification Code: $otp", "Your code is $otp. It expires in 15 minutes.");
    }

    // Inside your UserService class
private function mailUser($to, $subject, $otpCode) {
    $mail = new PHPMailer(true);

    try {
        // 1. Gmail SMTP Settings
        $mail->isSMTP();
        $mail->Host       = 'smtp.gmail.com';
        $mail->SMTPAuth   = true;
        $mail->Username   = 'kenvosamuel@gmail.com'; // Your real Gmail address
        $mail->Password   = 'bith djif xhag usun';   // THE 16-CHAR APP PASSWORD YOU JUST COPIED
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port       = 587;

        // 2. Localhost Fix (Ensures Windows/XAMPP doesn't block the connection)
        $mail->SMTPOptions = array(
            'ssl' => array(
                'verify_peer' => false,
                'verify_peer_name' => false,
                'allow_self_signed' => true
            )
        );

        // 3. Sender & Recipient
        $mail->setFrom('kenvosamuel@gmail.com', 'Digital Library project');
        $mail->addAddress($to);

        // 4. The Email Design
        $mail->isHTML(true);
        $mail->Subject = $subject;
        $mail->Body    = "
            <div style='font-family: Arial, sans-serif; padding: 20px; border: 1px solid #eee;'>
                <h2 style='color: #2c3e50;'>Verification Code</h2>
                <p>Use the code below to verify your library account:</p>
                <div style='font-size: 32px; font-weight: bold; color: #e74c3c; letter-spacing: 5px; text-align: center; background: #f9f9f9; padding: 10px;'>
                    $otpCode
                </div>
                <p>This code expires in 15 minutes.</p>
            </div>";

        $mail->send();
        return true;
    } catch (Exception $e) {
        // Check C:\xampp\php\logs\php_error_log if this returns false
        error_log("Mail Error: {$mail->ErrorInfo}");
        return false;
    }
}

    public function verifyEmailOTP($email, $inputCode, $type) {
        $record = $this->db->query(
            "SELECT * FROM user_otps 
            WHERE email = :email AND otp_code = :code AND type = :type 
            AND expires_at > CURRENT_TIMESTAMP 
            ORDER BY created_at DESC LIMIT 1",
            ['email' => $email, 'code' => $inputCode, 'type' => $type]
        )->fetch();

        if ($record) {
            // Code is valid! Clean up the used OTP
            $this->db->query("DELETE FROM user_otps WHERE email = :email", ['email' => $email]);
            return true;
        }
        return false;
    }

    public function resetPassword($email, $newPassword) {
        $hashedPassword = password_hash($newPassword, PASSWORD_BCRYPT);
        $sql = "UPDATE users SET password_hash = ? WHERE email = ?";
        return $this->db->query($sql, [$hashedPassword, $email]);
    }

        public function toggleUserSuspension($userId) {
        // 1. Get current status
        $sql = "SELECT is_suspended FROM users WHERE id = ?";
        $user = $this->db->query($sql, [$userId])->fetch();

        if ($user) {
            // 2. Flip the status
            // If it's 't' (true), make it 'f' (false), and vice versa
            $newStatus = ($user['is_suspended'] === 't' || $user['is_suspended'] === true) ? 'f' : 't';
            
            $updateSql = "UPDATE users SET is_suspended = ? WHERE id = ?";
            return $this->db->query($updateSql, [$newStatus, $userId]);
        }
        return false;
    }
}