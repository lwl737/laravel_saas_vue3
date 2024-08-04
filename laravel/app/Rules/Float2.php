<?php

declare(strict_types=1);

namespace App\Rules;


class Float2  extends BaseRules
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
        if( $value === null ) return true;

       return preg_match('/^(([0-9]{1})|([1-9]{1}[0-9]{1,}))(\.{1}[0-9]{1,2})?$/',(string)$value);
    }

    /**
     * 获取校验错误信息
     *
     * @return string
     */
    public function message()
    {
        return ':attribute格式错误';
    }
}
