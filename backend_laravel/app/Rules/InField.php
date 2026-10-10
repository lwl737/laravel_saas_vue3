<?php

declare(strict_types=1);

namespace App\Rules;


class InField  extends BaseRules
{
    public function __construct(
        public readonly array  $in_arr
    )
    {  }

    public string|int $index = "";


    /**
     * 判断是否通过验证规则
     *
     * @param  string  $attribute 检测的字段
     * @param  mixed   $value
     * @return bool
     */
    public function passes($attribute, $value)
    {
        if($value === null || empty($this->in_arr)) return true;

        if(is_array($value) && array_is_list($value) ) foreach($value as $key => $val) if(!in_array($val,$this->in_arr)){
            $this->index = $key;
            return false;
        }

        if(is_string($value) && !in_array($value,$this->in_arr)) return false;

        return true;
    }

    /**
     * 获取校验错误信息
     *
     * @return string
     */
    public function message()
    {
        return ":attribute 下标{$this->index}传入的参数错误";
    }
}
