<?php

declare(strict_types=1);

namespace App\Rules;

use Illuminate\Support\Facades\Validator;


class IsArrayList  extends BaseRules
{

    public string $errorStr = '';


    public function __construct(
        public  array $rule = [],
        public  int|null   $len = null,
        public  int|null   $maxLen = null
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

        if ($value === null) return true;

        if (!is_array($value) || !array_is_list($value)) {

            $this->errorStr = '长度必须为 ' . $this->len;

            return false;
        }

        if ($this->len !== null  && count($value) !== $this->len) {
            $this->errorStr = '长度必须为 ' . $this->len;
            return false;
        }

        if ($this->maxLen !== null  && count($value) > $this->maxLen) {
            $this->errorStr = '长度必须小于 ' . $this->maxLen;
            return false;
        }

        if (!empty($this->rule)) {
            foreach ($value as $val) {

                $validator = Validator::make([
                    'val' => $val
                ], [
                    'val' => $this->rule
                ]);

                if ($validator->fails())  $this->errorStr =   substr($validator->errors()->first(), 3);
            };
        }

        return  $this->errorStr ?  false : true;
    }

    /**
     * 获取校验错误信息
     *
     * @return string
     */
    public function message()
    {
        return ':attribute 必须为list结构的数组' . ($this->errorStr ?  ',并且' . $this->errorStr : '');
    }
}
