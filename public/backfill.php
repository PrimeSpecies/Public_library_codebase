<?php
// backfill.php — run once from CLI: php backfill.php
// Place in your project root, run it, then delete it.

require_once '../autoload.php';
// adjust path to wherever you initialize DB/session

$db = Database::getInstance()->getConnection();

$rows = $db->query("SELECT id, file_path FROM documents WHERE content_text IS NULL OR content_text = ''")->fetchAll(PDO::FETCH_ASSOC);

echo "Found " . count($rows) . " documents to index.\n";

foreach ($rows as $row) {
    if (!file_exists($row['file_path'])) {
        echo "SKIP (file missing): " . $row['id'] . " → " . $row['file_path'] . "\n";
        continue;
    }

    $textPath = tempnam(sys_get_temp_dir(), 'pdf_');
    exec('pdftotext ' . escapeshellarg($row['file_path']) . ' ' . escapeshellarg($textPath) . ' 2>NUL');

    $text = '';
    if (file_exists($textPath)) {
        $text = file_get_contents($textPath);
        @unlink($textPath);
    }

    if (empty(trim($text))) {
        echo "WARN (no text extracted): " . $row['id'] . "\n";
        continue;
    }

    $stmt = $db->prepare("UPDATE documents SET content_text = :text WHERE id = :id");
    $stmt->execute([':text' => $text, ':id' => $row['id']]);

    echo "OK: " . $row['id'] . " — " . mb_strlen($text) . " chars\n";
}

echo "\nDone. Delete this file.\n";