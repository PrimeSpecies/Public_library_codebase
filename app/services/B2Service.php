<?php
namespace App\Services;

class B2Service {
    private $keyId;
    private $appKey;
    private $bucketId;
    private $bucketName;
    private $endpoint;

    public function __construct() {
        $this->keyId      = getenv('B2_KEY_ID');
        $this->appKey     = getenv('B2_APP_KEY');
        $this->bucketId   = getenv('B2_BUCKET_ID');
        $this->bucketName = getenv('B2_BUCKET_NAME');
        $this->endpoint   = getenv('B2_ENDPOINT');
    }

    /**
     * Upload a file to B2 and return the file key
     */
    public function upload($localPath, $userId, $hashedName) {
        $fileKey     = "documents/{$userId}/{$hashedName}";
        $fileContent = file_get_contents($localPath);
        $fileSize    = strlen($fileContent);
        $sha1        = sha1($fileContent);

        $url = $this->endpoint . '/' . $this->bucketName . '/' . $fileKey;

        $ch = curl_init($url);
        curl_setopt_array($ch, [
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_PUT            => true,
            CURLOPT_HTTPHEADER     => [
                'Authorization: Basic ' . base64_encode($this->keyId . ':' . $this->appKey),
                'Content-Type: application/pdf',
                'Content-Length: ' . $fileSize,
                'X-Bz-Content-Sha1: ' . $sha1,
            ],
            CURLOPT_POSTFIELDS => $fileContent,
        ]);

        $response = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        if ($httpCode === 200) {
            return $fileKey;
        }

        error_log("B2 Upload Error: " . $response);
        return false;
    }

    /**
     * Generate a presigned URL valid for 1 hour
     */
    public function getSignedUrl($fileKey) {
        $expires  = time() + 3600; // 1 hour
        $endpoint = $this->endpoint;
        $bucket   = $this->bucketName;

        // Build the string to sign
        $method      = 'GET';
        $contentMD5  = '';
        $contentType = '';
        $date        = gmdate('D, d M Y H:i:s T');
        $resource    = "/{$bucket}/{$fileKey}";

        // Use AWS Signature V4 compatible presigned URL for B2
        $url = "{$endpoint}/{$bucket}/{$fileKey}";

        // Generate presigned URL using query params
        $params = http_build_query([
            'X-Amz-Algorithm'  => 'AWS4-HMAC-SHA256',
            'X-Amz-Credential' => $this->keyId . '/' . gmdate('Ymd') . '/auto/s3/aws4_request',
            'X-Amz-Date'       => gmdate('Ymd\THis\Z'),
            'X-Amz-Expires'    => 3600,
            'X-Amz-SignedHeaders' => 'host',
        ]);

        // Simpler approach — use B2's native auth token for presigned URLs
        return $this->getNativeSignedUrl($fileKey, $expires);
    }

    private function getNativeSignedUrl($fileKey, $expires) {
        // Get download authorization from B2
        $ch = curl_init('https://api.backblazeb2.com/b2api/v2/b2_authorize_account');
        curl_setopt_array($ch, [
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_HTTPHEADER     => [
                'Authorization: Basic ' . base64_encode($this->keyId . ':' . $this->appKey),
            ],
        ]);
        $response = json_decode(curl_exec($ch), true);
        curl_close($ch);

        if (!isset($response['apiUrl'])) {
            error_log("B2 Auth Error: " . json_encode($response));
            return false;
        }

        $apiUrl       = $response['apiUrl'];
        $authToken    = $response['authorizationToken'];
        $downloadUrl  = $response['downloadUrl'];

        // Get download authorization token
        $ch = curl_init($apiUrl . '/b2api/v2/b2_get_download_authorization');
        curl_setopt_array($ch, [
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_POST           => true,
            CURLOPT_HTTPHEADER     => [
                'Authorization: ' . $authToken,
                'Content-Type: application/json',
            ],
            CURLOPT_POSTFIELDS => json_encode([
                'bucketId'               => $this->bucketId,
                'fileNamePrefix'         => $fileKey,
                'validDurationInSeconds' => 3600,
            ]),
        ]);
        $authResponse = json_decode(curl_exec($ch), true);
        curl_close($ch);

        if (!isset($authResponse['authorizationToken'])) {
            error_log("B2 Download Auth Error: " . json_encode($authResponse));
            return false;
        }

        // Build signed download URL
        return $downloadUrl . '/file/' . $this->bucketName . '/' . $fileKey
             . '?Authorization=' . urlencode($authResponse['authorizationToken']);
    }
}