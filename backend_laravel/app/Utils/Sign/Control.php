<?php

declare(strict_types=1);

namespace App\Utils\Sign;

use Closure;

class Control
{

    /**
     * @description:
     * @param  params       参数
     * @param  sign         签名字符串
     * @param  encryption   签名方式回调函数
     * @return {*}
     */
    public   function  __construct(
        public readonly array $params,
        public readonly string $sign,
        public readonly Closure $encryption
     )
    {  }

    /**
     * @description:  检查签名
     * @return {*}
     */
    public function checkSign():bool
    {
       return $this->sign  === $this->createSign();
    }

    /**
     * @description:  生成签名
     * @return {*}
     */
    public function createSign(): string
    {
        $form = $this->params;
        ksort($form);
        $encryption = $this->encryption;
        return $encryption(http_build_query($form, '', '&', PHP_QUERY_RFC3986));
    }

}
