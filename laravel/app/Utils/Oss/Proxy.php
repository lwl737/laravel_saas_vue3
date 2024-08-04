<?php

declare(strict_types=1);

namespace App\Utils\Oss;

use OSS\Core\OssException;
use Illuminate\Support\Facades\Log;




/**
 *  https://help.aliyun.com/zh/oss/developer-reference/installation-13?spm=a2c4g.11186623.0.0.12ae762dHg0SFM
 *  @see \App\Utils\Oss\Client
 *  @method function deleteObject(string $object)
 */
class Proxy
{
    public readonly Client $client;
    public function __construct(
        public readonly string $oss_access_key_id,
        public readonly string $oss_access_key_secret,
        public readonly string $endpoint,
        public readonly string $bucket
    ) {
        $this->client =  new Client($this->oss_access_key_id, $this->oss_access_key_secret, $this->endpoint, $this->bucket);
        // $this->client =  new Client(
        //     "LTAI5tCHQATNkYMuCB9XgfBD",
        //     "rkDP1SShsfgszl1fOUHYOLvVwJtpzu",
        //     'https://oss-cn-guangzhou.aliyuncs.com' ,
        // "lwl-study");
    }


    public function __call(string $method, array $params = [])
    {
        try {
            $result =  $this->client->$method(...$params);
            return true;
        } catch (OssException $e) {
            Log::channel('sts')->error(json_encode([
                'date' => date('Y-m-d H:i:s'),
                'message' => $e->getMessage(),
                'params' => $params,
                'method' => $method,
                'body' => $e->getDetails(),
                'requestId' => $e->getRequestId(),
                'oss_access_key_id' => $this->oss_access_key_id,
                'oss_access_key_secret' => $this->oss_access_key_secret,
                'endpoint' => $this->endpoint,
                'bucket' => $this->bucket,
            ], JSON_UNESCAPED_UNICODE));
            return false;
        }
    }
}
