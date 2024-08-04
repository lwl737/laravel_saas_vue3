<?php

declare(strict_types=1);

namespace App\Rules;


class ArrayType  extends BaseRules
{

    public function __construct(
        public readonly  string $type,
        public string|false $keyType = false,
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
        foreach ($value as $key => $val) {
            switch ($this->type) {
                case 'number':
                    if (!is_numeric($val)) return false;
                    break;
                case 'string':
                    if (!is_string($val)) return false;
                    break;
                case 'array':
                    if (!is_array($val))  return false;
                    break;
            }
            if ($this->keyType === false) continue;
            switch ($this->keyType) {
                case 'number':
                    if (!is_numeric($key)) return false;
                    break;
                case 'string':
                    if (!is_string($key)) return false;
                    break;
            }
        };
        return true;
    }

    /**
     * 获取校验错误信息
     *
     * @return string
     */
    public function message()
    {
        return ':attribute格式错误';
    }
}
