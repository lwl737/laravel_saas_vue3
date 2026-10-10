<?php

declare(strict_types=1);

namespace App\Rules;


class Account extends BaseRules
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
        if($value === null ) return true;
        if(!preg_match('/^[\w]{5,20}$/',(string)$value)) return false;
        return true ;
    }

    /**
     * 获取校验错误信息
     *
     * @return string
     */
    public function message()
    {

        return ':attribute只能由字母数字下划线组成,长度5-20位';
    }
}
