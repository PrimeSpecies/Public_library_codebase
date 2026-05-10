<?php

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
use PHPMailer\PHPMailer\SMTP;

require_once __DIR__ . '/../libs/phpmailer/Exception.php';
require_once __DIR__ . '/../libs/phpmailer/PHPMailer.php';
require_once __DIR__ . '/../libs/phpmailer/SMTP.php';

// 1. Session Setup — MUST come before loadLanguage()
$sessionPath = __DIR__ . '/../sessions';
if (!is_dir($sessionPath)) { mkdir($sessionPath, 0777, true); }
session_save_path($sessionPath);
session_start();

// 2. Now load language — session is available
require_once __DIR__ . '/../lang/lang.php';
$translations = loadLanguage();

// 3. Error Reporting
error_reporting(E_ALL);
ini_set('display_errors', 1);

// 4. Autoload
require_once '../autoload.php';

$catalogModel = new Catalog();

$action = $_GET['action'] ?? 'home';
$authController = new AuthController();


// --- ROUTER LOGIC ---

if ($action === 'home') {
    include __DIR__ . '/../views/home/landing.php';

} elseif ( $action === 'set-lang'){
    $allowed = ['en', 'fr'];
    $lang = $_GET['lang'] ?? 'en';
    if (in_array($lang, $allowed)) {
        $_SESSION['lang'] = $lang;
    }
    header('Location: ' . ($_SERVER['HTTP_REFERER'] ?? 'index.php'));
    exit;
} elseif ($action === 'register') {
    $authController->register();

} elseif ($action === 'login') {
    $authController->login();

} elseif ($action === 'dashboard') {

if (isset($_SESSION['user_role']) && $_SESSION['user_role'] === 'admin') {
        $userService = new UserService();
        $users = $userService->getAllUsers();
        include __DIR__ . '/../views/admin/dashboard.php';
        exit();
    }

    if (!isset($_SESSION['user_id'])) {
        header("Location: index.php?action=login");
        exit();
    }


    // Logic moved to DashboardController to maintain clean architecture
    (new \App\Controllers\DashboardController())->index();

}elseif ($action === 'move-file') {
    $catalogController = new \App\Controllers\DocumentController();
    $catalogController->moveFile();
} elseif ($action === 'create-folder') {
    $folderController = new \App\Controllers\FolderController();
    $folderController->createFolder();

}elseif ($action === 'delete-folder') {
    $folderController = new \App\Controllers\FolderController();
    $folderController->deleteFolder();

}elseif ($action === 'check-doc'){
    $fileId = $_GET['id'] ?? null;
    $doc = (new Document())->findById($fileId);
    $path = $doc['file_path'];
    echo "Path: " . $path . "<br>";
    echo "Exists: " . (file_exists($path) ? 'Yes' : 'No') . "<br>";
    echo "Size: " . filesize($path) . " bytes<br>";
    echo "PHP memory limit: " . ini_get('memory_limit') . "<br>";
    echo "Max execution time: " . ini_get('max_execution_time') . "<br>";
    exit;
}elseif ($action === 'verify-reset-otp') {
    // Phase 2: The OTP "Gate"
    $authController->verifyResetOTP();
    exit();

}elseif ($action === 'verify-otp') {
    include __DIR__ . '/../views/auth/verifyOTP.php';
    exit();

}elseif ($action === 'check-otp') {

    $authController->checkOTP();
    exit();

}elseif($action === 'search-content'){
    $docController = new \App\Controllers\DocumentController();
    $docController -> searchContent();

}elseif ($action === 'view-folder') {
    $folderId = $_GET['folder_id'] ?? null;
    $userFiles = $catalogModel->findByFolder($_SESSION['user_id'], $folderId);
    include 'views/dashboard.php';
    exit;
}elseif ($action === 'save-to-catalog') {
   try{
    $docController = new \App\Controllers\DocumentController();
    $docController->bookmark();

   }catch (Exception $e) {
        // THIS WILL CATCH THE REAL ERROR (e.g. Constraint violations, type mismatches)
        die("CRITICAL DATABASE ERROR: " . $e->getMessage());
    }
} elseif ($action === 'remove-from-catalog') {
    $docController = new \App\Controllers\DocumentController();
    $docController->remove();

} elseif ($action === 'upload-doc') {
    $docController = new \App\Controllers\DocumentController();
    $docController->upload();

} elseif ($action === 'logout') {
    $authController->logout();

} elseif ($action === 'forgot-password') {
    // Phase 1: Show "Send Code" confirmation
    $authController->forgotPassword(); 
    exit();

} elseif ($action === 'verify-reset-otp') {
    // Phase 2: The OTP "Gate"
    $authController->verifyResetOTP(); 
    exit();

}elseif ($action === 'set-lang'){
    $allowed = ['en', 'fr'];
    $lang = $_GET['lang'] ?? 'en';
    if (in_array($lang, $allowed)) {
        $_SESSION['lang'] = $lang;
    }
    header('Location: ' . ($_SERVER['HTTP_REFERER'] ?? 'index.php'));
    exit;

}elseif ($action === 'reset-password') {
    // Phase 3: Update password (if OTP was verified)
    $authController->resetPassword(); 
    exit();

}elseif ($action === 'view-doc') {
    $fileId       = $_GET['id'] ?? null;
    $userId       = $_SESSION['user_id'] ?? null;
    $docModel     = new Document();
    $catalogModel = new Catalog();
    $doc          = $docModel->findById($fileId);
    $inCatalog    = $userId ? $catalogModel->exists($userId, $fileId) : false;

    if ($doc && ($doc['is_public'] || $inCatalog)) {
        $path = $doc['file_path'];
        if (str_starts_with($path, 'https://')) {
            header('Location: ' . $path);
            exit;
        }
        if (file_exists($path)) {
            if (ob_get_level()) ob_end_clean();
            header('Content-Type: application/pdf');
            header('Content-Disposition: inline; filename="' . basename($path) . '"');
            header('Content-Length: ' . filesize($path));
            readfile($path);
            exit;
        }
    }
    die("Access Denied");
}

elseif ($action === 'download-doc') {
    $fileId       = $_GET['id'] ?? null;
    $userId       = $_SESSION['user_id'] ?? null;
    $docModel     = new Document();
    $catalogModel = new Catalog();
    $doc          = $docModel->findById($fileId);
    $inCatalog    = $userId ? $catalogModel->exists($userId, $fileId) : false;

    if ($doc && ($doc['is_public'] || $inCatalog)) {
        $url   = $doc['file_path'];
        $title = $doc['title'] ?? 'document';
        if (str_starts_with($url, 'https://')) {
            // Append download query param for Supabase
            header('Location: ' . $url . '?download=' . urlencode($title . '.pdf'));
            exit;
        }
        if (file_exists($url)) {
            if (ob_get_level()) ob_end_clean();
            header('Content-Type: application/pdf');
            header('Content-Disposition: attachment; filename="' . $title . '.pdf"');
            header('Content-Length: ' . filesize($url));
            readfile($url);
            exit;
        }
    }
    die("Unauthorized");
}

elseif ($action === 'get-doc-url') {
    header('Content-Type: application/json');
    $fileId       = $_GET['id'] ?? null;
    $userId       = $_SESSION['user_id'] ?? null;
    $docModel     = new Document();
    $catalogModel = new Catalog();
    $doc          = $docModel->findById($fileId);
    $inCatalog    = $userId ? $catalogModel->exists($userId, $fileId) : false;

    if ($doc && ($doc['is_public'] || $inCatalog)) {
        echo json_encode(['success' => true, 'url' => $doc['file_path']]);
    } else {
        echo json_encode(['success' => false]);
    }
    exit;
} elseif($action === "debug-doc"){
    header('Content-Type: application/json');
    $fileId       = $_GET['id'] ?? null;
    $userId       = $_SESSION['user_id'] ?? null;
    $docModel     = new Document();
    $catalogModel = new Catalog();
    $doc          = $docModel->findById($fileId);
    $inCatalog    = $catalogModel->exists($userId, $fileId);
    echo json_encode([
        'user_id'    => $userId,
        'file_id'    => $fileId,
        'doc_found'  => (bool)$doc,
        'file_path'  => $doc['file_path'] ?? null,
        'is_public'  => $doc['is_public'] ?? null,
        'in_catalog' => $inCatalog,
    ]);
    exit;
}
elseif( $action === 'check-pdf-raw'){
    $fileId = $_GET['id'] ?? null;
    $doc = (new Document())->findById($fileId);
    $path = $doc['file_path'];
    echo "First 10 bytes: " . bin2hex(file_get_contents($path, false, null, 0, 10));
    exit;
    
}elseif ($action === 'debug-supabase') {
    header('Content-Type: application/json');
    $url    = getenv('SUPABASE_URL');
    $key    = getenv('SUPABASE_KEY');
    $bucket = getenv('SUPABASE_BUCKET');

    // List all files in bucket
    $ch = curl_init("{$url}/storage/v1/object/list/{$bucket}");
    curl_setopt_array($ch, [
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_POST           => true,
        CURLOPT_HTTPHEADER     => [
            'Authorization: Bearer ' . $key,
            'Content-Type: application/json',
        ],
        CURLOPT_POSTFIELDS => json_encode([
            'limit'  => 100,
            'offset' => 0,
            'prefix' => '',
        ]),
    ]);
    $raw = curl_exec($ch);
    curl_close($ch);

    echo $raw;
    exit;
}elseif ($action === 'debug-supabase-upload') {
    header('Content-Type: application/json');
    
    $url    = getenv('SUPABASE_URL');
    $key    = getenv('SUPABASE_KEY');
    $bucket = getenv('SUPABASE_BUCKET');
    
    // Create a tiny test PDF
    $tmpFile = tempnam(sys_get_temp_dir(), 'test_') . '.pdf';
    file_put_contents($tmpFile, '%PDF-1.4 test');
    $fileSize = filesize($tmpFile);
    $fh       = fopen($tmpFile, 'rb');

    $endpoint = "{$url}/storage/v1/object/{$bucket}/test/test.pdf";
    
    $ch = curl_init($endpoint);
    curl_setopt_array($ch, [
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_PUT            => true,
        CURLOPT_INFILE         => $fh,
        CURLOPT_INFILESIZE     => $fileSize,
        CURLOPT_HTTPHEADER     => [
            'Authorization: Bearer ' . $key,
            'Content-Type: application/pdf',
            'x-upsert: true',
        ],
    ]);

    $raw      = curl_exec($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    $curlErr  = curl_error($ch);
    curl_close($ch);
    fclose($fh);
    @unlink($tmpFile);

    echo json_encode([
        'endpoint'  => $endpoint,
        'http_code' => $httpCode,
        'response'  => json_decode($raw, true),
        'curl_error'=> $curlErr,
        'url_set'   => !empty($url),
        'key_set'   => !empty($key),
        'bucket_set'=> !empty($bucket),
    ]);
    exit;
}elseif ($action === 'verify-email') {
    $authController->verifyEmail();
    exit();

} elseif ($action === 'verify-otp') {
    include __DIR__ . '/../views/auth/verifyOTP.php';
    exit();

} else {
    http_response_code(404);
    echo "<h1>404 - Page Not Found</h1>";
    echo "<a href='index.php?action=home'>Return Home</a>";
}
exit();