<?php

declare(strict_types=1);

namespace App\Utils\Captcha;

class Control
{
 /**
   * @description:  生成验证码几位
   * @param int $num
   */
  public static function create(int $num = 5)
  {

    // 合并数组的值
    $arr = array_merge(range(0, 9),  range('A', 'Z'));
    // 打乱数组值的顺序
    shuffle($arr);
    // 获取数组前5个值
    $arr2 = array_slice($arr, 0, $num);
    // 合并数组的值
    $arr3 = join('', $arr2);

    return  $arr3;
  }
}
