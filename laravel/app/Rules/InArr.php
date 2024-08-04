<?php

declare(strict_types=1);

namespace App\Rules;


class InArr  extends BaseRules
{


    public function __construct(
        public readonly array  $in_arr,
        public readonly bool  $strict = false
    )
    {  }

    /**
     * 判断是否通过验证规则
     *
     * @param  string  $attribute 检测的字段
     * @param  mixed   $value
     * @return bool
     */
    public function passes($attribute, $value)
    {

        return $value === null ? true : in_array($value,$this->in_arr , $this->strict );
    }

    /**
     * 获取校验错误信息
     *
     * @return string
     */
    public function message()
    {
        return ':attribute参数错误';
    }
}
