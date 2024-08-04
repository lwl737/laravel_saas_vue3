<?php

declare(strict_types=1);

namespace App\Rules;


class Ymd  extends BaseRules
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
       if($value === "" || $value === null ) return true;

       return preg_match('/\d{4}-\d{2}-\d{2}/',(string)$value);
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
