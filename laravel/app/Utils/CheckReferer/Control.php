<?php

declare(strict_types=1);

namespace App\Utils\CheckReferer;

use App\Models\Redis\Control as RedisModel;
use App\Helpers\Enums\Referer;
use App\Helpers\Enums\HeaderReq;
use App\Helpers\Func;
use Closure;

class Control
{

    /**
     * @description: 验证码的生成和校验
     * @param  \Illuminate\Http\Request $request    redis 的id值
     * @param  Referer|Closure  $referer   redis 的键值
     * @param  Header           $header    redis 过期时间
     * @param  string|null      $character     切割字符串
     * @param  string           $explode   redis 数据库
     */
    public function __construct(
        public readonly  \Illuminate\Http\Request $request,
        public readonly  Referer|Closure $referer,
        public readonly  HeaderReq $header = HeaderReq::REFERER,

        public readonly  string|null $character =  '*',

        public readonly  string $explode =  '|'

    ) {
    }

    public function getStatus(): Status
    {

        $res = $this->request->header($this->header->value, null);

        if (!$res) return Status::NOT_DEFIENED;

        $referer = $this->referer;

        $referer  = $referer instanceof Referer ? $referer->value : $referer();

        $refererArr = explode($this->explode, $referer);

        $status = Status::SUCCESS;

        foreach ($refererArr as $val) {
            $status = $this->checkItem($val, $res);
            if ($status === Status::SUCCESS) break;
        }

        return $status;
    }

    private function checkItem(string $referer, string $res): Status
    {
        if ($this->character !== null && strpos($referer, $this->character)) {  //含有*号
            if (!$this->checkCharacter($res, $referer, $this->character)) return  Status::ERROR;
        } else if (!Func::startsWith($res, $referer))  return Status::ERROR;
        return Status::SUCCESS;
    }



    private function checkCharacter(string $needle, string $rule, string $character = '*')
    {

        $checkstr = $needle;
        //含有星号通配符
        $check_arr =  explode($character, $rule);

        $check = true;

        $start_check_index = 0;

        for ($i = 0; $i < count($check_arr); $i++) {


            if ($check_arr[$i] === "") {   //多余星号空字符串跳过
                continue;
            } else {

                $this_check = false;

                if ($i > 0) {

                    //前面是通配符
                    for ($j = $start_check_index; $j < mb_strlen($checkstr); $j++) {

                        //等于最后一个的时候是绝等于
                        // $res =  $i === count($check_arr) - 1  ? mb_substr($checkstr, $j) ===   $check_arr[$i]   :  $this->startWith(mb_substr($checkstr, $j), $check_arr[$i]) === 0;
                        $res = $this->startWith(mb_substr($checkstr, $j), $check_arr[$i]) === 0;
                        if ($res) {
                            $this_check = true;
                            $start_check_index = $j + mb_strlen($check_arr[$i]);
                            break;
                        }
                    }
                } else {
                    $res = $this->startWith(mb_substr($checkstr, $start_check_index), $check_arr[$i]);

                    if ($res >= 0) {
                        $this_check = true;
                        $start_check_index = $start_check_index + mb_strlen($check_arr[$i]);
                    }
                }


                if (!$this_check) {
                    $check = false;
                    break;
                }
            }
        }
        return $check;
    }






    private function startWith($longText, $shortText)
    {
        return strncmp($longText, $shortText, mb_strlen($shortText));
    }
}
