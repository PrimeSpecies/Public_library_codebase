<?php
namespace App\Controllers;

use App\Services\FileService;
use Catalog;
use Document;

class DocumentController {
    private $fileService;
    private $documentModel;
    private $catalogModel;

    public function __construct() {
        $this->fileService = new FileService();
        $this->documentModel = new Document();
        $this->catalogModel = new Catalog();
    }

    public function bookmark() {
        if (isset($_GET['id'])) {
            $userId = $_SESSION['user_id'] ?? null;
            $fileId = $_GET['id'];

            if (!$userId) {
                header("Location: index.php?action=login");
                exit;
            }

            try {
                $success = $this->fileService->bookmarkToCatalog($userId, $fileId);
                header("Location: index.php?action=dashboard&msg=" . ($success ? "success" : "error"));
                exit;
            } catch (\Exception $e) {
                header("Location: index.php?action=dashboard&msg=error");
                exit;
            }
        }
    }

    // Consolidated remove method
    public function remove() {
        header('Content-Type: application/json');
        $fileId = $_GET['id'] ?? null;
        $userId = $_SESSION['user_id'] ?? null;

        if ($fileId && $userId) {
            // Use the initialized catalogModel property
            $success = $this->catalogModel->removeFromCatalog($userId, $fileId);
            echo json_encode(['success' => $success, 'message' => $success ? 'Removed.' : 'Failed.']);
        } else {
            http_response_code(400);
            echo json_encode(['success' => false, 'message' => 'Invalid ID.']);
        }
        exit;
    }
    public function moveDoc() {
    $docId = (int)$_POST['doc_id'];
    $targetFolderId = (int)$_POST['folder_id'];
    $userId = $_SESSION['user_id'];

    // Instantiate your document model
    $docModel = new \Document();

    // Call the model method
    $success = $docModel->move($docId, $targetFolderId, $userId);

    header('Content-Type: application/json');
    echo json_encode(['success' => $success]);
    exit;
}


    public function viewDoc() {
        $fileId = $_GET['id'] ?? null;
        $userId = $_SESSION['user_id'] ?? null;

        if (!$fileId || !$userId) die("Unauthorized");

        $doc = $this->documentModel->findById($fileId);
        $inCatalog = $this->catalogModel->exists($userId, $fileId);

// DEBUG: Force output to screen
    echo "DEBUG: File Found: " . ($doc ? "Yes" : "No") . "<br>";
    echo "DEBUG: In Catalog: " . ($inCatalog ? "Yes" : "No") . "<br>";
    echo "DEBUG: Is Public: " . ($doc['is_public'] ?? 'N/A') . "<br>";

    // Check DB manually one last time
    if (!$inCatalog) {
        die("STOPPED: System claims you don't own this in the catalog. Check DB table 'catalog'.");
    }
        if ($doc && ($doc['is_public'] || $inCatalog)) {
            $path = $doc['file_path'];
            if (file_exists($path)) {
                header('Content-Type: application/pdf');
                header('Content-Disposition: inline; filename="' . basename($path) . '"');
                readfile($path);
                exit;
            }
        }
        die("Access Denied");
    }



// In DocumentController.php

public function moveFile() {
        header('Content-Type: application/json');

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            http_response_code(405);
            echo json_encode(['success' => false, 'message' => 'Method not allowed.']);
            exit;
        }

        $userId = $_SESSION['user_id'] ?? null;
        $fileId = $_POST['file_id'] ?? null;
        $newFolderId = $_POST['folder_id'] ?? null;

        if (!$fileId || !$userId) {
            http_response_code(400);
            echo json_encode(['success' => false, 'message' => 'Missing identifiers.']);
            exit;
        }

        try {
            // Normalize ID: If 0 or empty, treat as NULL (Root)
            $targetFolder = (empty($newFolderId) || $newFolderId == 0) ? null : (int)$newFolderId;

            // Critical Safety: Prevent collision loops
            if ($fileId == $targetFolder) {
                throw new \Exception('Logic error: Cannot move a file into itself.');
            }

            // Centralized service call
            $success = $this->fileService->moveDocument($fileId, $targetFolder, $userId);

            if ($success) {
                echo json_encode(['success' => true]);
            } else {
                throw new \Exception('Database update failed.');
            }
        } catch (\Exception $e) {
            http_response_code(500);
            echo json_encode(['success' => false, 'message' => $e->getMessage()]);
        }
        exit;
}    public function upload() {
        header('Content-Type: application/json');

        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['document'])) {
            $userId = $_SESSION['user_id'] ?? 1;

            $metadata = [
                'title'       => !empty($_POST['title']) ? $_POST['title'] : $_FILES['document']['name'],
                'description' => $_POST['description'] ?? '',
                'tags'        => $_POST['tags'] ?? '',
                'is_public'   => ($_POST['is_public'] === '1' || $_POST['is_public'] === 'true'),
                'folder_id'   => !empty($_POST['folder_id']) ? (int)$_POST['folder_id'] : null
            ];

            try {
                $fileId = $this->fileService->store($_FILES['document'], $userId, $metadata);
                if ($fileId) {
                    echo json_encode(['success' => true, 'message' => 'Research indexed successfully', 'document_id' => $fileId]);
                } else {
                    throw new \Exception('Service layer failed to store document.');
                }
            } catch (\Exception $e) {
                http_response_code(500);
                echo json_encode(['success' => false, 'message' => $e->getMessage()]);
            }
            exit;
        }

        http_response_code(400);
        echo json_encode(['success' => false, 'message' => 'Invalid request or no file received.']);
        exit;
    }

function renderFolderTree($folders, $currentFolderId = null) {
    global $catalogModel;

    echo "<ul style='list-style: none; padding-left: 15px;'>";
    foreach ($folders as $folder) {
        $isActive = ($folder['id'] == $currentFolderId);
        $hasChildren = !empty($folder['children']);

        echo "<li style='margin-top: 8px;'>";
        echo "<div class='folder-row' style='display: flex; align-items: center; justify-content: space-between; padding: 2px 0;'>";
$files = $catalogModel->findByFolder($_SESSION['user_id'], $folder['id']);

        echo "<div style='display: flex; align-items: center;'>";

        // Chevron Toggle
        if ($hasChildren) {
            echo "<button onclick='toggleFolder(this)' style='border:none; background:none; cursor:pointer; color:#94a3b8;'>
                    <i data-lucide='chevron-down' style='width: 14px;'></i>
                  </button>";
        } else {
            echo "<span style='width: 14px; margin-right: 5px;'></span>";
        }

        // Folder Link
        $style = $isActive ? "background: #eff6ff; color: #2563eb; font-weight: 600;" : "";
        echo "<a href='index.php?action=dashboard&folder_id=" . $folder['id'] . "'
         ondragover='allowDrop(event)'
         ondrop='drop(event, " . $folder['id'] . ")'
         style='text-decoration:none; color:inherit; padding: 2px 8px; ...'>";
        echo "<i data-lucide='folder' style='width: 14px; margin-right: 5px;'></i> " . htmlspecialchars($folder['name']);
        echo "</a>";
        echo "</div>";

        // Action Buttons
        echo "<div class='folder-actions' style='display: flex; gap: 8px;'>
                <button onclick='renameFolder(" . $folder['id'] . ", \"" . addslashes($folder['name']) . "\")'
                        title='Rename' style='border:none; background:none; cursor:pointer;'>
                    <i data-lucide='edit-2' style='width: 14px;'></i>
                </button>
                <button onclick='createSubfolder(" . $folder['id'] . ")'
                        title='New Subfolder' style='border:none; background:none; cursor:pointer; color:#64748b;'>
                    <i data-lucide='plus' style='width: 14px;'></i>
                </button>
                <button onclick='deleteFolder(" . $folder['id'] . ")'
                        title='Delete' style='border:none; background:none; cursor:pointer; color:#ef4444;'>
                    <i data-lucide='trash-2' style='width: 14px;'></i>
                </button>
              </div>";

        echo "</div>";

        // Recursive Children
        if ($hasChildren) {
            echo "<div class='folder-children' style='padding-left: 20px;'>";
            $this->renderFolderTree($folder['children'], $currentFolderId);
            echo "</div>";
        }
        echo "</li>";
    }

    // Use the initialized catalog model property
    $files = $this->catalogModel->findByFolder($_SESSION['user_id'], $folder['id']);

    if (!empty($files)) {
        echo "<ul class='file-list'>";
        foreach ($files as $file) {
            echo "<li data-file-id='" . $file['id'] . "' draggable='true' ondragstart='drag(event)'>";
            echo "<i data-lucide='file' style='width: 12px; margin-right: 5px;'></i> " . htmlspecialchars($file['title']);
            echo "</li>";
        }
        echo "</ul>";
    }
}

public function searchContent() {
    header('Content-Type: application/json');
    $userId = $_SESSION['user_id'] ?? null;

    $body  = json_decode(file_get_contents('php://input'), true);
    $query = trim($body['query'] ?? '');
    $scope = $body['scope'] ?? 'all';
    $tags  = trim($body['tags'] ?? '');

    if (!$query) {
        echo json_encode(['results' => []]);
        exit;
    }

    $results = $this->documentModel->searchContent($query, $scope, $userId, $tags);

$output = array_map(function($row) {
    // Don't htmlspecialchars the whole snippet — it kills the <mark> tags
    // Instead sanitize the raw snippet then swap markers
    $raw     = $row['snippet'] ?? 'No preview available.';
    // Temporarily protect markers, escape the rest, restore as <mark>
    $snippet = htmlspecialchars($raw, ENT_QUOTES, 'UTF-8');
    $snippet = str_replace(['[[', ']]'], ['<mark>', '</mark>'], $snippet);

    return [
        'id'      => $row['id'],
        'title'   => $row['title'],
        'snippet' => $snippet
    ];
}, $results);

    echo json_encode(['results' => $output]);
    exit;
}

}