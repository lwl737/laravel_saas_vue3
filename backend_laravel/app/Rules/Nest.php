<?php

namespace App\Rules;

use Illuminate\Support\Facades\Validator;


class Nest  extends BaseRules
{

    public string $errorStr = '';

    public function __construct(
        public readonly array $rule
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

        $validator = Validator::make($value, $this->rule);

        if ($validator->fails())  $this->errorStr =   $validator->errors()->first();
        else $this->errorStr = '';
        return  $this->errorStr ?  false : true;
    }

    /**
     * 获取校验错误信息
     *
     * @return string
     */
    public function message()
    {
        return ':attribute 表单验证错误 '.$this->errorStr;
    }
}
