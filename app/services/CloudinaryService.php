<?php
namespace App\Services;

class CloudinaryService {
    private $cloud;
    private $key;
    private $secret;

    public function __construct() {
        $this->cloud  = getenv('CLOUDINARY_CLOUD');
        $this->key    = getenv('CLOUDINARY_KEY');
        $this->secret = getenv('CLOUDINARY_SECRET');
    }

public function upload($localPath, $fileName) {
    $timestamp = time();
    $publicId  = 'documents/' . pathinfo($fileName, PATHINFO_FILENAME);

    $params = [
        'access_mode' => 'public',
        'public_id'   => $publicId,
        'timestamp'   => $timestamp,
    ];
    ksort($params);
    $sigString = '';
    foreach ($params as $k => $v) {
        $sigString .= $k . '=' . $v . '&';
    }
    $sigString = rtrim($sigString, '&') . $this->secret;
    $signature = sha1($sigString);

    $ch = curl_init("https://api.cloudinary.com/v1_1/{$this->cloud}/raw/upload");
    curl_setopt_array($ch, [
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_POST           => true,
        CURLOPT_POSTFIELDS     => [
            'file'        => new \CURLFile($localPath, 'application/pdf', $fileName),
            'public_id'   => $publicId,
            'access_mode' => 'public',
            'timestamp'   => $timestamp,
            'api_key'     => $this->key,
            'signature'   => $signature,
        ],
    ]);

    $raw      = curl_exec($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);

    $response = json_decode($raw, true);

    if ($httpCode === 200 && isset($response['secure_url'])) {
        return $response['secure_url'];
    }

    error_log("Cloudinary Upload Error [{$httpCode}]: " . $raw);
    return false;
}
}