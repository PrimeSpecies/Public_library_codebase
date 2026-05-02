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

}elseif ($action === 'view-doc') {
    $docController = new \App\Controllers\DocumentController();
    $docController->viewDoc();

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

}
 elseif ($action === 'reset-password') {
    // Phase 3: Update password (if OTP was verified)
    $authController->resetPassword(); 
    exit();

}elseif ( $action === 'get-doc-url'){
    header('Content-Type: application/json');
    $fileId       = $_GET['id'] ?? null;
    $userId       = $_SESSION['user_id'] ?? null;

    if (!$fileId || !$userId) {
        echo json_encode(['success' => false]);
        exit;
    }

    $docModel     = new Document();
    $catalogModel = new Catalog();
    $doc          = $docModel->findById($fileId);
    $inCatalog    = $catalogModel->exists($userId, $fileId);

    if ($doc && ($doc['is_public'] || $inCatalog)) {
        echo json_encode(['success' => true, 'url' => $doc['file_path']]);
    } else {
        echo json_encode(['success' => false]);
    }
    exit;
}elseif( $action === 'check-pdf-raw'){
    $fileId = $_GET['id'] ?? null;
    $doc = (new Document())->findById($fileId);
    $path = $doc['file_path'];
    echo "First 10 bytes: " . bin2hex(file_get_contents($path, false, null, 0, 10));
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