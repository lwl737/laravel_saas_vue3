<?php

declare(strict_types=1);

namespace App\Rules;



class PayType  extends BaseRules
{
    public  $checkArr = [1,2]  ;


    /**
     * 判断是否通过验证规则
     *
     * @param  string  $attribute 检测的字段
     * @param  mixed   $value
     * @return bool
     */
    public function passes($attribute, $value)
    {
      // var_dump(in_array($value,$this->checkArr));
        return in_array($value,$this->checkArr);
    }

    /**
     * 获取校验错误信息
     *
     * @return string
     */
    public function message()
    {
        return '找不到对应的:attribute';
    }
}
