<?php

declare(strict_types=1);

namespace App\Rules;


class ZeroOne  extends BaseRules
{



    /**
     * 判断是否通过验证规则
     *
     * @param  string  $attribute 检测的字段
     * @param  mixed   $value
     * @return bool
     */
    public function passes($attribute, $value)
    {


        return preg_match('/(1|0){1}/',(string)$value);
    }

    /**
     * 获取校验错误信息
     *
     * @return string
     */
    public function message()
    {
        return ':attribute只能为1跟0';
    }
}
