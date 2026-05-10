<?php
namespace App\Services;

use Document;
use Catalog;

class FileService {
    private $documentModel;
    private $catalogModel;

    public function __construct() {
        $this->documentModel = new Document();
        $this->catalogModel  = new Catalog();
    }

public function store($file, $userId, $metadata = []) {
    $extension  = pathinfo($file['name'], PATHINFO_EXTENSION);
    $hashedName = bin2hex(random_bytes(16)) . '.' . $extension;
    $tmpPath    = $file['tmp_name']; // use tmp file directly

    $isPublic = ($metadata['is_public'] === true
              || $metadata['is_public'] === 1
              || $metadata['is_public'] === '1');

    // Upload directly from tmp file to Supabase
    $supabase = new \App\Services\SupabaseService();
    $fileUrl  = $supabase->upload($tmpPath, $userId, $hashedName);

    if (!$fileUrl) {
        error_log("Supabase upload failed for user {$userId}");
        return false;
    }

    // Create DB record
    $fileId = $this->documentModel->create([
        'user_id'      => $userId,
        'file_path'    => $fileUrl,
        'title'        => $metadata['title'],
        'is_public'    => $isPublic,
        'description'  => $metadata['description'] ?? '',
        'tags'         => $metadata['tags'] ?? '',
        'folder_id'    => $metadata['folder_id'] ?? null,
        'content_text' => ''
    ]);

    if (!$fileId) {
        error_log("Document::create() failed for user {$userId}");
        return false;
    }

    // Link to catalog
    $folderId = !empty($metadata['folder_id']) ? (int)$metadata['folder_id'] : null;
    $this->catalogModel->addToFileCatalog($userId, $fileId, $folderId, $metadata['title']);

    // Queue text extraction
    if (strtolower($extension) === 'pdf') {
        $this->queueExtraction($fileId, $tmpPath);
    }

    return $fileId;
}
private function queueExtraction($fileId, $filePath) {
    $queueDir = dirname(__DIR__, 2) . DIRECTORY_SEPARATOR . 'storage' . DIRECTORY_SEPARATOR . 'queue';
    if (!is_dir($queueDir)) mkdir($queueDir, 0755, true);

    // Write a tiny job file
    file_put_contents(
        $queueDir . DIRECTORY_SEPARATOR . 'job_' . $fileId . '.json',
        json_encode(['file_id' => $fileId, 'file_path' => $filePath, 'created_at' => time()])
    );
}

   private function extractTextAsync($fileId, $filePath) {
    $textPath  = str_replace('\\', '/', tempnam(sys_get_temp_dir(), 'pdf_'));
    $configPath = str_replace('\\', '/', dirname(__DIR__, 2) . '/config/database.php');
    $filePath  = str_replace('\\', '/', $filePath);

    $phpScript = tempnam(sys_get_temp_dir(), 'ext_') . '.php';

    file_put_contents($phpScript, '<?php
        $textPath = "' . $textPath . '";
        exec("pdftotext " . escapeshellarg("' . $filePath . '") . " " . escapeshellarg($textPath) . " 2>NUL");
        $text = file_exists($textPath) ? file_get_contents($textPath) : "";
        @unlink($textPath);
        if (!trim($text)) exit;

        $config = require "' . $configPath . '";
        $dsn = "pgsql:host={$config[\'host\']};port={$config[\'port\']};dbname={$config[\'dbname\']}";
        $db  = new PDO($dsn, $config[\'user\'], $config[\'pass\']);
        $stmt = $db->prepare("UPDATE documents SET content_text = :text WHERE id = :id");
        $stmt->execute([":text" => $text, ":id" => ' . (int)$fileId . ']);
        @unlink(__FILE__);
    ');

    pclose(popen('start /B php ' . escapeshellarg($phpScript), 'r'));
}

    public function moveDocument($fileId, $folderId, $userId) {
        return $this->catalogModel->updateFolderId($fileId, $folderId, $userId);
    }

    public function moveDocumentToFolder($fileId, $folderId, $userId) {
        return $this->catalogModel->updateFolderId($fileId, $folderId, $userId);
    }

    public function bookmarkToCatalog($userId, $fileId) {
        $file = $this->documentModel->findById($fileId);

        if (!$file) {
            throw new \Exception('Document does not exist in the library.');
        }

        return $this->catalogModel->addToFileCatalog($userId, $fileId, null, $file['title']);
    }
}