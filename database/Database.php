<?php

class Database {
    private static $instance = null;
    private $connection = null; // Initialize as null

    private function __construct() {
        // Use an absolute path to be 100% sure we find the config
        $configPath = dirname(__DIR__) . '/config/database.php';

        if (!file_exists($configPath)) {
            die("CRITICAL: Configuration file not found at $configPath");
        }

        $config = require $configPath;

        $dsn = "pgsql:host={$config['host']};port={$config['port']};dbname={$config['dbname']}";

        try {
            $this->connection = new PDO($dsn, $config['user'], $config['pass'], [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
            ]);
        } catch (PDOException $e) {
            die("Database Connection Failed: " . $e->getMessage());
        }
    }
    public function query($sql, $params = []) {
        $stmt = $this->connection->prepare($sql);
        $stmt->execute($params);
        return $stmt;
    }
    public static function getInstance() {
        if (self::$instance === null) {
            self::$instance = new Database();
        }
        return self::$instance;
    }

    public function getConnection() {
        return $this->connection; // This was likely returning null!
    }


}