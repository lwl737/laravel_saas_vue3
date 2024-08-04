<?php

declare(strict_types=1);

namespace App\Utils\Oss;



use AlibabaCloud\SDK\Sts\V20150401\Sts;
// use AlibabaCloud\Tea\Utils\Utils;
// use AlibabaCloud\Tea\Console\Console;

use Darabonba\OpenApi\Models\Config;
use AlibabaCloud\SDK\Sts\V20150401\Models\AssumeRoleRequest;
use AlibabaCloud\Tea\Utils\Utils\RuntimeOptions;
use AlibabaCloud\Tea\Exception\TeaError;
use App\Helpers\Output\Json\Error;
use App\Helpers\Func;
use App\Models\Redis\Control as Redis;

// $iClientProfile = DefaultProfile::getProfile("cn-hangzhou", $accessKeyID, $accessKeySecret);

// $client = new DefaultAcsClient($iClientProfile);

class StsToken
{
    public readonly array $redisConfig;
    public function __construct(
        public readonly string $oss_access_key_id,
        public readonly string $oss_access_key_secret,
        public readonly string $endpoint,
        public readonly string $roleArn,
    ) {
        $this->redisConfig = ['key' =>  'STS_TOKEN', 'id' => $this->createRedisKey()];
    }

    /**
     * 使用AK&SK初始化账号Client
     * @param string $accessKeyId
     * @param string $accessKeySecret
     * @return Sts Client
     */
    public  function createClient($accessKeyId, $accessKeySecret)
    {
        $config = new Config([
            // 必填，您的 AccessKey ID
            "accessKeyId" => $accessKeyId,
            // 必填，您的 AccessKey Secret
            "accessKeySecret" => $accessKeySecret
        ]);
        // Endpoint 请参考 https://api.aliyun.com/product/Sts         $config->endpoint = "sts.cn-guangzhou.aliyuncs.com";

        $config->endpoint = 'sts.cn-'. $this->endpoint;

        return new Sts($config);
    }


    public  function get()
    {


        $redisRes = Redis::redishGetAll($this->redisConfig);

        if($redisRes && $redisRes['timeout'] >= time()) return $redisRes;

        return $this->create();

    }

    public function create(){
        // 请确保代码运行环境设置了环境变量 ALIBABA_CLOUD_ACCESS_KEY_ID 和 ALIBABA_CLOUD_ACCESS_KEY_SECRET。
        // 工程代码泄露可能会导致 AccessKey 泄露，并威胁账号下所有资源的安全性。以下代码示例使用环境变量获取 AccessKey 的方式进行调用，仅供参考，建议使用更安全的 STS 方式，更多鉴权访问方式请参见：https://help.aliyun.com/document_detail/311677.html
        $client = $this->createClient($this->oss_access_key_id, $this->oss_access_key_secret);

        $assumeRoleRequest = new AssumeRoleRequest([
            "durationSeconds" => 3600,
            "policy" => file_get_contents(dirname(__FILE__) . '/policy/all_policy.txt'),
            "roleSessionName" => "sts",
            "roleArn" =>  $this->roleArn
        ]);

        $runtime = new RuntimeOptions([]);
        try {
            // 复制代码运行请自行打印 API 的返回值
            $result = $client->assumeRoleWithOptions($assumeRoleRequest, $runtime);

            $result = [
                'accessKeyId' => $result->body->credentials->accessKeyId,
                'accessKeySecret' => $result->body->credentials->accessKeySecret,
                'securityToken' => $result->body->credentials->securityToken,
                'timeout' => time() + 1140  // 19分钟
            ];

            Redis::hMset($result, 1140, $this->redisConfig); //提前一分钟过期

            return $result;
        } catch (\Exception $error) {
            if (!($error instanceof TeaError)) {
                $error = new TeaError([], $error->getMessage(), $error->getCode(), $error);
            }
            // 如有需要，请打印 error
            Func::throwHttpCustom(Error::MESSAGE, ['message' => $error->message]);
        }
    }


    private function createRedisKey(){
        return md5( $this->oss_access_key_id . '-' .$this->oss_access_key_secret . '-'. $this->endpoint.'-'. $this->roleArn);
    }
}
