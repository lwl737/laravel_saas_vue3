<?php

declare(strict_types=1);

namespace App\Rules;

use Illuminate\Support\Facades\Validator;

class FileString  extends BaseRules
{
    public string $errorStr = "";

    public function __construct(
        public int $max = -1,            // 如果为字符串时的长度
        public int $fileSize = -1,       // 如果为文件时多少KB
        public string $fileType = 'image',
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
        else {
            $validator = Validator::make([
                'string' => $value,
                'file' => $value
            ], [
                'string' => ['string', $this->max === -1 ? '' : 'max:' . $this->max],
                'file' => [$this->fileType, $this->fileSize === -1 ? '' : 'max:' . $this->fileSize],
            ]);
            if (is_string($value)) $this->errorStr =  $validator->errors()->first('string');
            else $this->errorStr = $validator->errors()->first('file');

            return  $this->errorStr ?  false : true;
        }
    }

    /**
     * 获取校验错误信息
     *
     * @return string
     */
    public function message()
    {
        return ':attribute ' . $this->errorStr ;
    }
}
