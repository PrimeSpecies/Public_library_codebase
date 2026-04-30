<?php

class Document {
    private $db;

    public function __construct() {
        $instance = Database::getInstance();
        $this->db = $instance->getConnection();

        if ($this->db === null) {
            die("Fatal Error: Document Model failed to retrieve Database connection.");
        }
    }

    /**
     * Create the master record for a file and return its ID
     */
    public function create($data) {
    $sql = "INSERT INTO documents (user_id, title, description, tags, is_public, file_path, folder_id, content_text)
            VALUES (:user_id, :title, :description, :tags, :is_public, :file_path, :folder_id, :content_text)
            RETURNING id";

    try {
        $stmt = $this->db->prepare($sql);
        $stmt->execute([
            ':user_id'      => $data['user_id'],
            ':title'        => $data['title'],
            ':description'  => $data['description'],
            ':tags'         => $data['tags'],
            ':is_public'    => $data['is_public'] ? 'true' : 'false',
            ':file_path'    => $data['file_path'],
            ':folder_id'    => $data['folder_id'],
            ':content_text' => $data['content_text'] ?? ''
        ]);

        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result['id'] ?? false;
    } catch (PDOException $e) {
        error_log("Document Create Error: " . $e->getMessage());
        return false;
    }
}

    // In Document.php
public function move($docId, $targetFolderId, $userId) {
    // If targetFolderId is 0 or null, you might want to set it to NULL
    // to move it back to the "root" level
    $folderId = ($targetFolderId > 0) ? $targetFolderId : null;

    $sql = "UPDATE documents SET folder_id = :folder_id WHERE id = :id AND user_id = :user_id";
    $stmt = $this->db->prepare($sql);
    return $stmt->execute([
        ':folder_id' => $folderId,
        ':id'        => $docId,
        ':user_id'   => $userId
    ]);
}

public function searchContent($query, $scope = 'all', $userId = null, $tags = '') {
    // Convert query to tsquery format: "machine learning" → "machine & learning"
    $tsQuery = implode(' & ', array_filter(array_map('trim', explode(' ', $query))));
    $params  = [':query' => $tsQuery];

    $where = "to_tsquery('english', :query) @@ d.search_vector";

    if ($scope === 'private' && $userId) {
        $where .= " AND EXISTS (SELECT 1 FROM catalog c WHERE c.document_id = d.id AND c.user_id = :user_id)";
        $params[':user_id'] = $userId;
    } elseif ($scope === 'public') {
        $where .= " AND d.is_public = TRUE";
    } else {
        $where .= " AND (d.is_public = TRUE OR EXISTS (SELECT 1 FROM catalog c WHERE c.document_id = d.id AND c.user_id = :user_id))";
        $params[':user_id'] = $userId;
    }

    if ($tags) {
        $where .= " AND d.tags ILIKE :tags";
        $params[':tags'] = '%' . $tags . '%';
    }

    // ts_rank ranks results by relevance, ts_headline extracts snippet
    $sql = "SELECT d.id, d.title, d.tags,
                ts_rank(d.search_vector, to_tsquery('english', :query2)) AS rank,
                ts_headline('english', d.content_text, to_tsquery('english', :query3),
                    'MaxWords=50, MinWords=20, StartSel=[[, StopSel=]]') AS snippet
            FROM documents d
            WHERE $where
            ORDER BY rank DESC
            LIMIT 20";

    $params[':query2'] = $tsQuery;
    $params[':query3'] = $tsQuery;

    $stmt = $this->db->prepare($sql);
    $stmt->execute($params);
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

    /**
     * Get recent public files for Global Discovery
     */
    public function getLatestPublic() {
        // Updated table name to 'documents' and column 'user_id'
        $sql = "SELECT d.*, u.username
                FROM documents d
                JOIN users u ON d.user_id = u.id
                WHERE d.is_public = TRUE
                ORDER BY d.uploaded_at DESC
                LIMIT 10";

        $stmt = $this->db->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function findById($id) {
        $stmt = $this->db->prepare("SELECT * FROM documents WHERE id = :id");
        $stmt->execute([':id' => $id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
}