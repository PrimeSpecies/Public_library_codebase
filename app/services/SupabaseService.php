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
        $fileKey = $userId . '/' . $fileName;
        $content = file_get_contents($localPath);

        $ch = curl_init("{$this->url}/storage/v1/object/{$this->bucket}/{$fileKey}");
        curl_setopt_array($ch, [
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_POST           => true,
            CURLOPT_HTTPHEADER     => [
                'Authorization: Bearer ' . $this->key,
                'Content-Type: application/pdf',
                'x-upsert: true',
            ],
            CURLOPT_POSTFIELDS => $content,
        ]);

        $raw      = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        if ($httpCode === 200 || $httpCode === 201) {
            // Return public URL
            return "{$this->url}/storage/v1/object/public/{$this->bucket}/{$fileKey}";
        }

        error_log("Supabase Upload Error [{$httpCode}]: " . $raw);
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