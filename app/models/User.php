<?php

class User {
    private $db;

   public function create($email, $passwordHash, $mfaSecret) {
    $sql = "INSERT INTO users (email, password_hash, google_2fa_secret) 
            VALUES (:email, :password, :secret)";
    
    $stmt = $this->db->prepare($sql);
    
    return $stmt->execute([
        ':email'    => $email,
        ':password' => $passwordHash,
        ':secret'   => $mfaSecret
    ]);
}

    public function __construct() {
        $instance = Database::getInstance();
        $this->db = $instance->getConnection();
        
        // Lead's Safety Check:
        if ($this->db === null) {
            die("Fatal Error: User Model failed to retrieve Database connection.");
        }
    }

    public function findByEmail($email) {
        // This is Line 8 where it crashed
        $stmt = $this->db->prepare("SELECT * FROM users WHERE email = :email");
        $stmt->execute([':email' => $email]);
        return $stmt->fetch();
    }
}