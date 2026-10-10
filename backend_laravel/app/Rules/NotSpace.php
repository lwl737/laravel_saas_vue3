<?php

declare(strict_types=1);

namespace App\Rules;


class NotSpace  extends BaseRules
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
      return !preg_match("/\s/", (string)$value);
    }

    /**
     * 获取校验错误信息
     *
     * @return string
     */
    public function message()
    {
        return ':attribute不能有空格';
    }
}
