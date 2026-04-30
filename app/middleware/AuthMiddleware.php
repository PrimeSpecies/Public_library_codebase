<?php
// File: app/middleware/AuthMiddleware.php

class AuthMiddleware {
    public static function isAuthenticated() {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        if (!isset($_SESSION['user_id'])) {
            header("Location: index.php?action=login");
            exit();
        }
    }

    public static function isAdmin() {
        self::isAuthenticated();
        if ($_SESSION['user_role'] !== 'admin') {
            die("Access Denied: Admins Only.");
        }
    }
}