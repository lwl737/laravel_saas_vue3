<?php

declare(strict_types=1);
namespace App\Utils\Alibaba\Sms;

use AlibabaCloud\SDK\Dysmsapi\V20170525\Dysmsapi;
use \Exception;
use AlibabaCloud\Tea\Exception\TeaError;
// use AlibabaCloud\Tea\Utils\Utils;

use Darabonba\OpenApi\Models\Config as AliConfig;
use AlibabaCloud\SDK\Dysmsapi\V20170525\Models\SendSmsRequest;
use AlibabaCloud\Tea\Utils\Utils\RuntimeOptions;


class Control
{

     /**
     * 使用AK&SK初始化账号Client
     * @param string $accessKeyId
     * @param string $accessKeySecret
     * @return Dysmsapi Client
     */
    public static function createClient(string $accessKeyId,string $accessKeySecret){
        $config = new AliConfig([
            // 必填，您的 AccessKey ID
            "accessKeyId" => $accessKeyId,
            // 必填，您的 AccessKey Secret
            "accessKeySecret" => $accessKeySecret
        ]);
        // 访问的域名
        $config->endpoint = "dysmsapi.aliyuncs.com";
        return new Dysmsapi($config);
    }

    /**
     * @description:  发送短信
     * @param Config $config  //配置类
     */
    public static function main(Config $config)  :bool|string
    {
        // 工程代码泄露可能会导致AccessKey泄露，并威胁账号下所有资源的安全性。以下代码示例仅供参考，建议使用更安全的 STS 方式，更多鉴权访问方式请参见：https://help.aliyun.com/document_detail/311677.html
        $client = self::createClient($config->access_key_id, $config->access_key_secret);
        $sendSmsRequest = new SendSmsRequest([
            "phoneNumbers" => $config->phoneNumbers,
            "signName" =>$config->sign_name,
            "templateCode" => $config->mod_code,
            "templateParam" =>  json_encode($config->params)
        ]);
        $runtime = new RuntimeOptions([]);
        try {
            // 复制代码运行请自行打印 API 的返回值
            $client->sendSmsWithOptions($sendSmsRequest, $runtime);
            return true;
        }
        catch (Exception $error) {
            if (!($error instanceof TeaError)) {
                $error = new TeaError([], $error->getMessage(), $error->getCode(), $error);
            }
            // 如有需要，请打印 error
            return $error->message;
        }
    }
}
