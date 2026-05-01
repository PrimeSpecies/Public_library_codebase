<?php

function detectLanguage(): string {
    // 1. Check session override (user manually switched)
    if (isset($_SESSION['lang']) && in_array($_SESSION['lang'], ['en', 'fr'])) {
        return $_SESSION['lang'];
    }

    // 2. Auto-detect from browser
    $accepted = $_SERVER['HTTP_ACCEPT_LANGUAGE'] ?? 'en';
    if (stripos($accepted, 'fr') !== false) {
        return 'fr';
    }

    return 'en';
}

function loadLanguage(): array {
    $lang = detectLanguage();
    $file = __DIR__ . '/' . $lang . '.php';
    return file_exists($file) ? require $file : require __DIR__ . '/en.php';
}

// Global translation function
function __($key): string {
    global $translations;
    return $translations[$key] ?? $key; // fallback to key if missing
}