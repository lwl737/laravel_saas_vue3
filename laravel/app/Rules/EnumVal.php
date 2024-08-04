<?php

declare(strict_types=1);

namespace App\Rules;


class EnumVal  extends BaseRules
{


    public function __construct(
        public  string   $enum
    ) {
    }

    /**
     * 判断是否通过验证规则
     *
     * @param  string  $attribute 检测的字段
     * @param  mixed   $value
     * @return bool
     */
    public function passes($attribute, $value)
    {
        if($value === null) return true;
        foreach($this->enum::cases() as $val) if( $val->value === $value ) return true ;
        return false;
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
