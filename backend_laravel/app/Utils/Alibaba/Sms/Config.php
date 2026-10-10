<?php

declare(strict_types=1);

namespace App\Utils\Alibaba\Sms;

interface Config
{
    /* @description 传入电话号码*/
    public function __construct( string $phoneNumbers);

    /* @description 获得设置里面的短信配置配置 */
    public function getItem(array $params);

    /* @description 创建验证码 */
    public function createCaptcha(int $num): string;

    /* @description 获得访问ip */
    public function getVisitIp() :string;

    /* @description 发送短信 */
    public  function sendSms() :string|bool ;

    /* @description 冷却ip */
    public  function coolingIp() ;
}
