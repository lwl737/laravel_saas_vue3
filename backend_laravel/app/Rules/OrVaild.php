<?php

declare(strict_types=1);

namespace App\Rules;

use Illuminate\Support\Facades\Validator;

class OrVaild  extends BaseRules
{

    protected string $errorStr = '';

    public function __construct(
        protected array $arr_rule = [],   // 数组规则 其中有一条生效就生效

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


        $errorStrArr = [];

        foreach ($this->arr_rule as $val) {

            $validator = Validator::make([
                'val' => $value
            ], [
                'val' => $val
            ]);

            if ($validator->fails()) $errorStrArr[] = substr($validator->errors()->first(), 3);
            else{
                $errorStrArr = [];
                break;
            }
        };


        $this->errorStr = empty($errorStrArr) ?  '' : implode(' 或者 ',$errorStrArr)  ;

        return  $this->errorStr ?  false : true;
    }

    /**
     * 获取校验错误信息
     *
     * @return string
     */
    public function message()
    {
        return ':attribute 必须为 ' .  $this->errorStr ;
    }
}
