<?php
namespace App\Services;

class SupabaseService {
    private $url;
    private $key;
    private $bucket;

    public function __construct() {
        $this->url    = getenv('SUPABASE_URL');
        $this->key    = getenv('SUPABASE_KEY');
        $this->bucket = getenv('SUPABASE_BUCKET');
    }
public function upload($localPath, $userId, $fileName) {

    error_log("Supabase upload called: path={$localPath}, exists=" . (file_exists($localPath) ? 'yes' : 'no'));
    
    if (!file_exists($localPath)) {
        error_log("Supabase: file does not exist at {$localPath}");
        return false;
    }
    $fileKey = $userId . '/' . $fileName;

    $ch = curl_init("{$this->url}/storage/v1/object/{$this->bucket}/{$fileKey}");
    curl_setopt_array($ch, [
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_POST           => true,
        CURLOPT_TIMEOUT        => 30,        // ← fail after 30s
        CURLOPT_CONNECTTIMEOUT => 10,        // ← fail to connect after 10s
        CURLOPT_HTTPHEADER     => [
            'Authorization: Bearer ' . $this->key,
            'x-upsert: true',
        ],
        CURLOPT_POSTFIELDS => [
            'file' => new \CURLFile($localPath, 'application/pdf', $fileName),
        ],
    ]);

    $raw      = curl_exec($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    $curlErr  = curl_error($ch);
    curl_close($ch);

    error_log("Supabase [{$httpCode}] err=[{$curlErr}] response=[{$raw}]");

    if ($httpCode === 200 || $httpCode === 201) {
        return "{$this->url}/storage/v1/object/public/{$this->bucket}/{$fileKey}";
    }

    return false;
}
    public function delete($fileUrl) {
        // Extract file key from URL
        $fileKey = str_replace(
            "{$this->url}/storage/v1/object/public/{$this->bucket}/",
            '',
            $fileUrl
        );

        $ch = curl_init("{$this->url}/storage/v1/object/{$this->bucket}/{$fileKey}");
        curl_setopt_array($ch, [
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_CUSTOMREQUEST  => 'DELETE',
            CURLOPT_HTTPHEADER     => [
                'Authorization: Bearer ' . $this->key,
            ],
        ]);

        curl_exec($ch);
        curl_close($ch);
    }
}