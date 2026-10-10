<?php

declare(strict_types=1);

namespace App\Rules;


class NotHas  extends BaseRules
{

    public function __construct(
        public readonly array $rules = []
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
      if(empty($value))  return true;
      foreach($this->rules as $val){
        //含有其中某字符串就不通过
        if( preg_match("/".$val."/", (string)$value) ) return false;
      }
      return true;
    }

    /**
     * 获取校验错误信息
     *
     * @return string
     */
    public function message()
    {
        return ':attribute 不能含有 '. implode(",",$this->rules);
    }
}
