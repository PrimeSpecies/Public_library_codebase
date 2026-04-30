<?php
// require_once __DIR__ . '/vendor/autoload.php';
$composerAutoload = __DIR__ . '/vendor/autoload.php';

if (file_exists($composerAutoload)) {
    require_once $composerAutoload;
} else {
    // Keeping your safety check for the PDF Parser library
    die("Error: Composer vendor/autoload.php not found at " . $composerAutoload);
}

spl_autoload_register(function ($className) {
    // 1. Handle Namespaced Classes (Standard PSR-4)
    $prefix = 'App\\';
    $base_dir = __DIR__ . DIRECTORY_SEPARATOR . 'app' . DIRECTORY_SEPARATOR;

    $len = strlen($prefix);
    if (strncmp($prefix, $className, $len) === 0) {
        $relative_class = substr($className, $len);
        // Replace namespace \ with the OS-specific slash
        $file = $base_dir . str_replace('\\', DIRECTORY_SEPARATOR, $relative_class) . '.php';

        if (file_exists($file)) {
            require_once $file;
            return;
        }
    }

    // 2. The "Safety Net" - Updated for Windows & Sprint 2
    $directories = [
        'app/controllers/',
        'app/models/',
        'app/services/',
        'app/middleware/',
        'database/',
        'libs/phpmailer/'
    ];

    foreach ($directories as $directory) {
        $parts = explode('\\', $className);
        $pureClassName = end($parts);
        
        // Use DIRECTORY_SEPARATOR for the final path construction
        $dirPath = str_replace('/', DIRECTORY_SEPARATOR, $directory);
        $file = __DIR__ . DIRECTORY_SEPARATOR . $dirPath . $pureClassName . '.php';
        
        if (file_exists($file)) {
            require_once $file;
            return;
        }
    }
});