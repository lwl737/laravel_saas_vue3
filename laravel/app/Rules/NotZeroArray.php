<?php

declare(strict_types=1);

namespace App\Rules;


class NotZeroArray  extends BaseRules
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
    // var_dump(in_array($value,$this->checkArr));
    // return in_array($value,$this->checkArr);
    foreach ($value as $val) {
      if (!preg_match('/(^[1-9][0-9]+$)|(^[1-9]$)/', (string)$val)) return false;
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
    return ':attribute非0开头整数';
  }
}
