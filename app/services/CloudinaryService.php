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

        // Generate signature
        $params = [
            'public_id'     => $publicId,
            'resource_type' => 'raw',
            'timestamp'     => $timestamp,
        ];
        ksort($params);
        $sigString = http_build_query($params) . $this->secret;
        $signature = sha1($sigString);

        $ch = curl_init("https://api.cloudinary.com/v1_1/{$this->cloud}/raw/upload");
        curl_setopt_array($ch, [
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_POST           => true,
            CURLOPT_POSTFIELDS     => [
                'file'          => new \CURLFile($localPath, 'application/pdf', $fileName),
                'public_id'     => $publicId,
                'resource_type' => 'raw',
                'timestamp'     => $timestamp,
                'api_key'       => $this->key,
                'signature'     => $signature,
            ],
        ]);

        $response = json_decode(curl_exec($ch), true);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        if ($httpCode === 200 && isset($response['secure_url'])) {
            return $response['secure_url'];
        }

        error_log("Cloudinary Upload Error: " . json_encode($response));
        return false;
    }
}