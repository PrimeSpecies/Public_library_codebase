<?php
namespace App\Controllers;

// Assuming your model is in the global namespace as \Folder
class FolderController {
    private $folderModel;

    public function __construct() {
        $this->folderModel = new \Folder();
    }

    /**
     * Logic to create a new folder
     */
    public function createFolder() {
        header('Content-Type: application/json');

        $userId = $_SESSION['user_id'] ?? null;
        $name = $_POST['name'] ?? null;
        $parentId = !empty($_POST['parent_id']) ? (int)$_POST['parent_id'] : null;

        if (!$userId) {
            http_response_code(401);
            echo json_encode(['success' => false, 'message' => 'Unauthorized.']);
            exit;
        }

        if (!$name) {
            http_response_code(400);
            echo json_encode(['success' => false, 'message' => 'Folder name is required.']);
        } else {
            try {
                $success = $this->folderModel->create($userId, $name, $parentId);
                echo json_encode(['success' => $success, 'message' => $success ? 'Created.' : 'Database error.']);
            } catch (\Exception $e) {
                http_response_code(500);
                echo json_encode(['success' => false, 'message' => $e->getMessage()]);
            }
        }
        exit;
    }
public function renameFolder() {
    $id = $_POST['id'] ?? null;
    $newName = $_POST['name'] ?? null;

    if (!$id || !$newName) {
        echo json_encode(['success' => false, 'message' => 'Invalid data']);
        return;
    }

    $folderModel = new \Folder();
    $success = $folderModel->renameFolder($id, $newName);

    echo json_encode(['success' => $success]);
}

public function deleteFolder() {
    // 1. Get the ID from the GET request
    $id = $_GET['id'] ?? null;

    // 2. Instantiate the model
    $folderModel = new \Folder();

    // 3. Call the model method
    $success = $folderModel->deleteFolder($id);

    // 4. Return consistent JSON response
    echo json_encode(['success' => $success]);
}

    /**
     * Recursive function to build the tree for your UI
     * Note: We removed the & reference in the parameter if your IDE complains,
     * but passing by reference is standard for performance in recursion.
     */
    public function buildTree(array $elements, $parentId = null) {
        $branch = [];
        foreach ($elements as $element) {
            if ($element['parent_id'] == $parentId) {
                $children = $this->buildTree($elements, $element['id']);
                if ($children) {
                    $element['children'] = $children;
                }
                $branch[] = $element;
            }
        }
        return $branch;
    }
}