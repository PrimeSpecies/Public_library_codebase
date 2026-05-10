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
    $fileKey  = $userId . '/' . $fileName;
    $fileSize = filesize($localPath);
    $fh       = fopen($localPath, 'rb');

    error_log("Supabase: starting upload of {$fileName}, size={$fileSize}");
    error_log("Supabase: URL={$this->url}, bucket={$this->bucket}, key set=" . (!empty($this->key) ? 'yes' : 'no'));

    $ch = curl_init("{$this->url}/storage/v1/object/{$this->bucket}/{$fileKey}");
    curl_setopt_array($ch, [
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_PUT            => true,
        CURLOPT_INFILE         => $fh,
        CURLOPT_INFILESIZE     => $fileSize,
        CURLOPT_HTTPHEADER     => [
            'Authorization: Bearer ' . $this->key,
            'Content-Type: application/pdf',
            'x-upsert: true',
        ],
    ]);

    $raw      = curl_exec($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    $curlError = curl_error($ch);
    curl_close($ch);
    fclose($fh);

    error_log("Supabase: httpCode={$httpCode}, curlError={$curlError}, response={$raw}");

    if ($httpCode === 200 || $httpCode === 201) {
        $url = "{$this->url}/storage/v1/object/public/{$this->bucket}/{$fileKey}";
        error_log("Supabase: success, url={$url}");
        return $url;
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