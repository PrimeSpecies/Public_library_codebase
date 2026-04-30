<?php
namespace App\Controllers;

use \Catalog;
use \Folder;
use \Document;

class DashboardController {


    public function index() {
        $userId = $_SESSION['user_id'] ?? null;
        if (!$userId) {
            header('Location: index.php?action=login');
            exit;
        }

        $folderId = filter_input(INPUT_GET, 'folder_id', FILTER_VALIDATE_INT) ?: null;

        $documentModel = new \Document();
        $catalogModel  = new \Catalog();
        $folderModel   = new \Folder();

        $folders     = $folderModel->getTree($userId) ?: [];
        $publicFiles = $documentModel->getLatestPublic() ?: [];

        // Updated logic: Always fetch all files if folderId is not provided
        $userFiles = $folderId
            ? $catalogModel->findByFolder($userId, $folderId)
            : $catalogModel->findAllFilesByUserId($userId);

        $userFiles = $userFiles ?: [];

        // Debugging
        error_log("DEBUG: User ID: $userId | Folder ID: " . ($folderId ?? 'NULL') . " | Count: " . count($userFiles));

        include __DIR__ . '/../../views/user/dashboard.php';
    }
}