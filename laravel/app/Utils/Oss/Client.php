<?php

declare(strict_types=1);

namespace App\Utils\Oss;


use OSS\OssClient;
// https://help.aliyun.com/zh/oss/developer-reference/installation-13?spm=a2c4g.11186623.0.0.12ae762dHg0SFM
class Client
{
    public readonly OssClient $client;
    public function __construct(
        public readonly string $oss_access_key_id,
        public readonly string $oss_access_key_secret,
        public readonly string $endpoint,
        public readonly string $bucket
    ) {
        // https://oss-cn-hangzhou.aliyuncs.com
        // sts.cn-guangzhou.aliyuncs.com

        // var_dump('https://oss-cn-' . $endpoint);

        // var_dump($oss_access_key_id);
        // var_dump($oss_access_key_secret);
        // var_dump('https://oss-cn-' . $endpoint);

        $this->client =  new OssClient($oss_access_key_id,  $oss_access_key_secret,  'https://oss-cn-' . $endpoint);
    }


    public function doesObjectExist(string $object){
        $res = $this->client->doesObjectExist($this->bucket, $object);
        return $res;
    }


    /**
     * 删除文件
     * @param string $object
     */
    public  function deleteObject(string $object)
    {
        if( $object ){
            if ( substr($object, 0, 1) ===  '/' ) $object = substr($object, 1, mb_strlen($object) - 1);
            if($this->doesObjectExist($object)){
                $res = $this->client->deleteObject($this->bucket, $object);
                return $res;
            }
        }
    }
}
