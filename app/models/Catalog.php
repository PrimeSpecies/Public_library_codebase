<?php


class Catalog {
    private $db;

    public function __construct() {
        $instance = Database::getInstance();
        $this->db = $instance->getConnection();

        if ($this->db === null) {
            die("Fatal Error: Catalog Model failed to retrieve Database connection.");
        }
    }

    /**
     * Link a file to a user's personal catalog.
     */
public function addToFileCatalog($userId, $fileId, $folderId = null, $displayName = null) {
    // We are now explicitly handling the columns
    $sql = "INSERT INTO catalog (user_id, document_id, folder_id, custom_display_name, created_at)
            VALUES (:user_id, :document_id, :folder_id, :display_name, CURRENT_TIMESTAMP)";

    $stmt = $this->db->prepare($sql);

    // We pass null for folder_id and display_name if they aren't provided
    return $stmt->execute([
        ':user_id'      => $userId,
        ':document_id'  => $fileId,
        ':folder_id'    => $folderId,
        ':display_name' => $displayName
    ]);
}

    /**
     * "All Files" View: Fetches every file saved by the user.
     * Uses JOIN to pull metadata from the files table.
     */
    // Update these two methods in Catalog.php:

public function findAllFilesByUserId($userId) {
    // We are pulling 'f.original_name' as 'title'
    $sql = "SELECT c.*, f.original_name AS title
            FROM catalog c
            LEFT JOIN files f ON c.document_id = f.id
            WHERE c.user_id = :user_id
            ORDER BY c.created_at DESC";

    $stmt = $this->db->prepare($sql);
    $stmt->execute([':user_id' => $userId]);
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

// In Catalog.php
public function findByFolder($userId, $folderId) {
    // Check if moving to Root (folderId is null, 0, or empty string)
    if (empty($folderId) || $folderId == 0) {
        $sql = "SELECT c.*, f.original_name AS title
                FROM catalog c
                JOIN files f ON c.document_id = f.id
                WHERE c.user_id = :user_id AND c.folder_id IS NULL";
        $params = [':user_id' => $userId];
    } else {
        $sql = "SELECT c.*, f.original_name AS title
                FROM catalog c
                JOIN files f ON c.document_id = f.id
                WHERE c.user_id = :user_id AND c.folder_id = :folder_id";
        $params = [':user_id' => $userId, ':folder_id' => $folderId];
    }

    $stmt = $this->db->prepare($sql);
    $stmt->execute($params);
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}
// In Catalog.php (The Model)
public function updateFolderId($fileId, $folderId, $userId) {
    // Ensure the table name is correct.
    // If your table uses 'item_type', ensure that is included here.
    $sql = "UPDATE catalog
            SET folder_id = :folder_id
            WHERE document_id = :id AND user_id = :user_id";

    $stmt = $this->db->prepare($sql);

    // Explicitly pass variables to execute
    return $stmt->execute([
        ':folder_id' => $folderId,
        ':id'        => $fileId,
        ':user_id'   => $userId
    ]);
}

// Inside your Catalog model or Controller
public function getFilesByFolder($userId, $folderId = null) {
    $sql = "SELECT * FROM catalog WHERE user_id = :user_id AND ";
    $sql .= ($folderId === null) ? "folder_id IS NULL" : "folder_id = :folder_id";

    $stmt = $this->db->prepare($sql);
    $params = [':user_id' => $userId];
    if ($folderId !== null) $params[':folder_id'] = $folderId;

    $stmt->execute($params);
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

    /**
     * Removes a link from the catalog.
     */
    public function removeFromCatalog($userId, $fileId) {
    // Ensure this also uses document_id
    $sql = "DELETE FROM catalog WHERE user_id = :user_id AND document_id = :document_id";
    $stmt = $this->db->prepare($sql);
    return $stmt->execute([':user_id' => $userId, ':document_id' => $fileId]);
}

    /**
     * Checks if a link exists.
     */
public function exists($userId, $fileId) {
    // Force the types to match
    $sql = "SELECT 1 FROM catalog WHERE user_id = :user_id AND document_id = :document_id LIMIT 1";
    $stmt = $this->db->prepare($sql);

    // Explicitly cast to integer to be safe
    $stmt->execute([
        ':user_id' => (int)$userId,
        ':document_id' => (int)$fileId
    ]);

    return (bool) $stmt->fetch();
}
}