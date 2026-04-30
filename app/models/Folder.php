<?php

class Folder {
    private $db;

    public function __construct() {
        $instance = Database::getInstance();
        $this->db = $instance->getConnection();

        // Lead's Safety Check:
        if ($this->db === null) {
            die("Fatal Error: User Model failed to retrieve Database connection.");
        }
    }
    // In your Folder model
public function getAllFoldersByUserId($userId) {
    $stmt = $this->db->prepare("SELECT * FROM folders WHERE user_id = ? ORDER BY name ASC");
    $stmt->execute([$userId]);
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}
public function renameFolder($folderId, $newName) {
    $userId = $_SESSION['user_id'] ?? null;
    if (!$userId) return false;

    // Ensure we only rename folders owned by the current user
    $sql = "UPDATE folders SET name = :name
            WHERE id = :id AND user_id = :user_id";

    $stmt = $this->db->prepare($sql);
    $stmt->execute([
        ':name'    => $newName,
        ':id'      => $folderId,
        ':user_id' => $userId
    ]);

    return $stmt->rowCount() > 0;
}
// Inside Folder.php

public function deleteFolder($folderId) {
    $userId = $_SESSION['user_id'] ?? null;
    if (!$userId) return "Unauthorized";

    // Check for Files
    $stmt = $this->db->prepare("SELECT 1 FROM catalog WHERE folder_id = :id");
    $stmt->execute([':id' => $folderId]);
    if ($stmt->fetch()) return "Folder contains files.";

    // Check for Subfolders
    $stmt = $this->db->prepare("SELECT 1 FROM folders WHERE parent_id = :id");
    $stmt->execute([':id' => $folderId]);
    if ($stmt->fetch()) return "Folder contains subfolders.";

    // Perform Delete
    $stmt = $this->db->prepare("DELETE FROM folders WHERE id = :id AND user_id = :user_id");
    $stmt->execute([':id' => $folderId, ':user_id' => $userId]);

    return ($stmt->rowCount() > 0) ? true : "Folder not found or you don't own it.";
}

    public function create($userId, $name, $parentId = null) {
        $sql = "INSERT INTO folders (user_id, name, parent_id) VALUES (:user_id, :name, :parent_id)";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([
            ':user_id'   => $userId,
            ':name'      => $name,
            ':parent_id' => $parentId
        ]);
    }

    // Add this helper inside Folder.php to convert flat database results into a tree
private function buildTree(array $elements, $parentId = null) {
    $branch = [];
    foreach ($elements as $element) {
        if ($element['parent_id'] == $parentId) {
            $children = $this->buildTree($elements, $element['id']);
            if ($children) {
                $element['children'] = $children;
            }
            $element['level'] = ($parentId === null) ? 0 : 1; // Simplified level
            $branch[] = $element;
        }
    }
    return $branch;
}

    // Get all folders for a user (needed to build the tree in UI)
        // In Folder.php
    public function getTree($userId, $parentId = null, $level = 0) {
        $sql = "SELECT * FROM folders WHERE user_id = :user_id AND " .
            ($parentId === null ? "parent_id IS NULL" : "parent_id = :parent_id");

        $stmt = $this->db->prepare($sql);
        $params = [':user_id' => $userId];
        if ($parentId !== null) $params[':parent_id'] = $parentId;

        $stmt->execute($params);
        $folders = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $tree = [];
        foreach ($folders as $folder) {
            $folder['level'] = $level;
            $folder['children'] = $this->getTree($userId, $folder['id'], $level + 1);
            $tree[] = $folder;
        }
        return $tree;
    }
}