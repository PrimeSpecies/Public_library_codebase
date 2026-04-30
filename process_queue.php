<?php
// process_queue.php — run from CLI: php process_queue.php
require_once 'autoload.php';

$queueDir = __DIR__ . 'storage/queue';
$jobs = glob($queueDir . '/job_*.json');

if (empty($jobs)) {
    echo "No jobs in queue.\n";
    exit;
}

$db = Database::getInstance()->getConnection();
echo "Processing " . count($jobs) . " jobs...\n";

foreach ($jobs as $jobFile) {
    $job = json_decode(file_get_contents($jobFile), true);
    $fileId   = $job['file_id'];
    $filePath = $job['file_path'];

    if (!file_exists($filePath)) {
        echo "SKIP (missing): $fileId\n";
        unlink($jobFile);
        continue;
    }

    $textPath = tempnam(sys_get_temp_dir(), 'pdf_');
    exec('pdftotext ' . escapeshellarg($filePath) . ' ' . escapeshellarg($textPath) . ' 2>NUL');

    $text = '';
    if (file_exists($textPath)) {
        $text = file_get_contents($textPath);
        @unlink($textPath);
    }

    if (empty(trim($text))) {
        echo "WARN (no text): $fileId\n";
        unlink($jobFile);
        continue;
    }

    // Update both content_text and search_vector
    $stmt = $db->prepare("
        UPDATE documents
        SET content_text  = :text,
            search_vector = to_tsvector('english',
                coalesce(:text2, '') || ' ' ||
                coalesce(title, '')  || ' ' ||
                coalesce(tags, ''))
        WHERE id = :id
    ");
    $stmt->execute([':text' => $text, ':text2' => $text, ':id' => $fileId]);

    unlink($jobFile); // Remove job after success
    echo "OK: $fileId\n";
}

echo "Done.\n";