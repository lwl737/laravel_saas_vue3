<?php

declare(strict_types=1);

namespace App\Rules;

use Illuminate\Support\Facades\Validator;

class CommaVaild  extends BaseRules
{

    protected string $errorStr = '';

    public function __construct(
        protected array $rule = [],   // 如果为字符串时的长度
        protected string $symbol = ',',   // 需要切割的字符串
        protected int|null  $len = null,   // 如果为字符串时的长度

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

        if($value === null ) return true;



        $arr = explode($this->symbol, $value);

        if( $this->len !== null && $this->len !== count($arr)){
            return false;
        }

        foreach($arr as $val){

            $validator = Validator::make([
                'val' => $val
            ], [
                'val' => $this->rule
            ]);

            if ($validator->fails())  $this->errorStr =   substr( $validator->errors()->first(),3 ) ;

        } ;


        return  $this->errorStr ?  false : true;


    }

    /**
     * 获取校验错误信息
     *
     * @return string
     */
    public function message()
    {
        return ':attribute 为 '.$this->symbol.' 隔开'. ($this->len !== null ? '长度为'.$this->len.'  ' :  ' ' ) .$this->errorStr ;
    }
}
