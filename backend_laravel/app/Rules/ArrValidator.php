<?php

declare(strict_types=1);

namespace App\Rules;

use Illuminate\Support\Facades\Validator;
use App\Helpers\Func;
use App\Helpers\Output\Json\Error;
class ArrValidator  extends BaseRules
{
    private int $index = 0;  //坐标
    private string $validatorEditor = "";  //错误
    private string $error = "";  //错误收集器

    public function __construct(
        public readonly  array $validator,
        public readonly  array|int|null $range = null,
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
        if( $value === null) return true;


        if(!is_array($value) || !array_is_list($value)){
            $this->error  = "必须是list结构的数组";
            return false;
        }

        if( $this->range !== null ){

            if(is_numeric($this->range) ) {

                if($this->range !== count($value)){
                    $this->error = "数组必须为{$this->range}数量";
                    return false;
                }

            }else{
                $range_count = count($this->range);

                if(!array_is_list($this->range) && !($range_count > 0 && $range_count <= 2) ) Func::throwHttpCustom(Error::MESSAGE,[ 'message' => 'count_arr 必须为list结构数组 并且大于0 小于等于2']);

                if($range_count === 1){
                    if(  $this->range[0]  > count($value) ){
                        $this->error = "数组必须大于{$this->range[0]}数量";
                        return false;
                    };
                }else{
                    //数组为2
                    if(  $this->range[0]  > count($value) || $this->range[1] <  count($value)){
                        $this->error = "数组必须大于{$this->range[0]}并且小于{$this->range[1]}数量";
                        return false;
                    };
                }
            }

        }

        foreach($value as $key => $val){
            $validator =  Validator::make(
                [
                'val' => $val
            ], [
                'val' => $this->validator
            ] );
            if ($validator->fails()) {
                $this->validatorEditor =   substr( $validator->errors()->first(),3 );
                $this->index = $key ;
                return false;  //数据验证错误
            }
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
        return  $this->error  ? ":attribute  ". $this->error : ":attribute  下标{$this->index}  {$this->validatorEditor}";
    }
}
