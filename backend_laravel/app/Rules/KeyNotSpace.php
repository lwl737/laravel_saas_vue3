<?php

declare(strict_types=1);

namespace App\Rules;


class KeyNotSpace extends BaseRules
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
      if(empty($value))  return true;
      foreach(array_keys($value) as $val){
        if( preg_match("/\s/", (string)$val) ) return false;
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
        return ':attribute key值不能有空格';
    }
}
