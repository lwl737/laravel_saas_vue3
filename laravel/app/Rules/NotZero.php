<?php

declare(strict_types=1);

namespace App\Rules;


use Illuminate\Contracts\Validation\ImplicitRule;


class NotZero  implements ImplicitRule
{


    public function __construct(
        public  int|null   $maxNum = null
    ) { }

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
        if(!preg_match('/(^[1-9][0-9]+$)|(^[1-9]$)/',(string)$value)) return false;
        return $this->maxNum === null ? true : $this->maxNum > $value ;
    }

    /**
     * 获取校验错误信息
     *
     * @return string
     */
    public function message()
    {
        if($this->maxNum === null) return ':attribute非0开头整数';
        else return ':attribute非0开头整数并且不能大于'.$this->maxNum;
    }
}