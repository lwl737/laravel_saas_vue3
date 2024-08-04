<?php

declare(strict_types=1);

namespace App\Helpers;

use Closure;
// 公共函数
class Func
{
    /**
     * @description: 禁止实例化
     * @return {*}
     */
    private function __construct()
    {
    }
    /**
     * @description: 禁止克隆
     * @return {*}
     */
    private function __clone()
    {
    }


    /**
     * @description:   返回参数
     * @param Result $result
     * @return {*}
     */
    public static function toHttpJson(\App\Helpers\Output\Json $result)
    {
        $enum =  $result->getHttpEnum();

        return  response()->json(
            [
                'errCode' =>  $result->getHttpVal(),
                'msg' =>   $result->getMessage(),
                'data' =>  $result->getData(),
                'enum' =>  [
                    'class' =>  $enum::class,
                    'name' => $enum->name,
                    'trueCodeName' => $enum->trueCode()->name
                ]
            ],
            $result->getTrueCodeVal()
        )->withHeaders(\App\Helpers\Func::setHeader($result->getHeader()));
    }

    public static function toHttpXml(\App\Helpers\Output\Json $result)
    {
        $enum =  $result->getHttpEnum();
        $xml_data = new \SimpleXMLElement('<root/>');

        self::array_to_xml([
            'errCode' =>  $result->getHttpVal(),
            'msg' =>   $result->getMessage(),
            'data' =>  $result->getData(),
            'enum' =>  [
                'class' =>  $enum::class,
                'name' => $enum->name,
                'trueCodeName' => $enum->trueCode()->name
            ]
        ], $xml_data);

        return  response(
            $xml_data->asXML(),
            $result->getTrueCodeVal()
        )
            ->withHeaders(self::setHeader($result->getHeader()));
    }



    public static  function array_to_xml($data, &$xml_data)
    {
        foreach ($data as $key => $value) {
            if (is_array($value)) {
                if (is_numeric($key)) {
                    $key = 'item' . $key; // 为数字键名添加前缀以符合XML的命名规则
                }
                $subnode = $xml_data->addChild($key);
                self::array_to_xml($value, $subnode);
            } else {
                $xml_data->addChild("$key", htmlspecialchars("$value"));
            }
        }
    }


    /**
     * @description:  检查前缀
     * @param {string} $string
     * @param {string} $prefix
     * @return {*}
     */
    public static function startsWith(string $string, string $prefix)
    {
        return strncmp($string, $prefix, strlen($prefix)) === 0;
    }


    public static  function replacePrefix(string $string, string $oldPrefix, string $newPrefix)
    {
        if (self::startsWith($string, $oldPrefix)) {
            return $newPrefix . substr($string, strlen($oldPrefix));
        }
        return $string;
    }


    /**
     * @description:
     * @param \App\Helpers\Output\Interface\Json   $http  code状态码
     * @return {*}
     */
    public static function toHttpEnum(\App\Helpers\Output\Interface\Json $http, array $data = [], array $replace = [])
    {

        return  self::toHttpJson(new \App\Helpers\Output\Json($data, $http, [], $replace));
    }



    /**
     * @description:  设置响应头信息
     * @param {array} $header
     * @return {*}
     */
    public static  function setHeader(array $header = [])
    {
        if (empty($header)) return  [];
        $data = [];
        foreach ($header as $key => $val) $data[constant("\App\Helpers\Enums\HeaderRes::{$key}")->value] = $val;
        return $data;
    }



    //驼峰命名转下划线命名
    public static   function toUnderScore($str)
    {
        $dstr = preg_replace_callback('/([A-Z]+)/', function ($matchs) {
            return '_' . strtolower($matchs[0]);
        }, $str);
        return trim(preg_replace('/_{2,}/', '_', $dstr), '_');
    }
    /**
     * @description:  打码
     * @param  string $string  替换的字符
     * @param  int    $start   从 0 开始
     * @param  int    $length  到第几个字符 可负数
     * @param  string $replace 要替换的字符串
     * @return string
     */
    public static function mosaic(string $string, int $start, int $length = 0, string $replace = "*"): string
    {
        $end = strlen($string);
        if ($length < 0) $end = strlen($string) + $length;
        else if ($length > 0) $end = $start   + $length;
        $new_string = "";
        for ($i = 0; $i < strlen($string); $i++) {
            $new_string .=  $i >=  $start ? ($i < $end ? $replace :  $string[$i]) : $string[$i];
        }
        return  $new_string;
    }
    /**
     * @description:  打码
     * @param  string $string  替换的字符
     * @param  int    $start   打码开始
     * @param  int    $length  打码结束
     * @param  string $replace 要替换的字符串
     * @return string
     */
    public static function mosaicSE(string $string, int $start, int $end = 0, string $replace = "*"): string
    {

        for ($i = 0; $i < strlen($string); $i++)   $string[$i] =  $i >=  $start && $i <= $end ?  $replace : $string[$i];

        return  $string;
    }

    /**
     * @description:递归创建文件夹
     * @param string $path
     * @param array $pathError
     */
    public static function mkdir_2(string $path, array $pathError = [])
    {
        if (!is_dir($path)) {
            $prePath = dirname($path) . '/';
            if (is_dir($prePath)) {
                mkdir($path, 0777);
                if (count($pathError) > 0) {
                    for ($i = count($pathError) - 1; $i >= 0; $i--) {
                        mkdir($pathError[$i], 0777);
                    }
                }
            } else {
                $pathError[] = $path;
                self::mkdir_2($prePath, $pathError);
            }
        }
    }

    /**
     * 输出错误信息
     * @param \App\Helpers\Output\Interface\Json $message 报错信息
     * @param array $data 附加数据
     * @throws Exception
     */
    public static  function throwHttpCustom(\App\Helpers\Output\Interface\Json $enum, array $replace = [], bool $addLog = false, \Exception|null $e = null)
    {
        throw new \App\Exceptions\HttpCustomException($enum, $replace, $e, $addLog);
    }



    /**
     * @description:  读取的文件 存入redis
     * @param {string} $path  文件路径
     * @param {string} $key   redis key值
     * @return {*}
     */
    public static function readTextRedis(string $path, string $key): string
    {
        if (!$text =   \App\Models\Redis\Control::get(['key' => $key])) {

            $text = file_get_contents($path);

            \App\Models\Redis\Control::set($text, ['key' => $key]);
        }

        return $text;
    }




    /**
     * @description:  生成树数据
     * @param {array}  $data
     * @param {string} $idKey
     * @param {string} $pidKey
     * @param {string} $chilKey
     * @param {null}   $func      需要追加的数据
     * @return {*}
     */
    public static function createTree(array $data, string $idKey = 'id', string $pidKey = 'pid', string $chilKey = 'children', $func = null, bool $hasTmp = false, string|int $topLevel = 0): array
    {
        $treeData = [];
        $tmp = [];
        foreach ($data as $val)  $tmp[$val[$idKey]] = $func === null ? $val : $func($val);
        foreach ($tmp as  &$val) {
            if ($val[$pidKey] === $topLevel) $treeData[] = &$val;
            else if (isset($tmp[$val[$pidKey]])) {
                $tmp[$val[$pidKey]][$chilKey] ?? $tmp[$val[$pidKey]][$chilKey] = [];
                $tmp[$val[$pidKey]][$chilKey][] = &$val;
            }
        }
        return $hasTmp ? [$treeData,  $tmp] : $treeData;
    }



    /**
     * @description:  根据key 转换数据
     * @param {string} $key
     * @param {mixed} $data
     * @param {bool}  $reverse
     * @return {*}
     */
    public static function casts(string $key, mixed $data, bool $reverse = false): mixed
    {
        return match ($key) {
            'serialize' => $reverse ? unserialize($data) : serialize($data),
            'json' => $reverse ? json_encode($data, JSON_UNESCAPED_UNICODE) : json_decode($data),
            'integer' => (int)$data,
            'string' => (string)$data,
            'self' => $data,
            default => $data
        };
    }


    /**
     * @description: 过滤
     */
    public static function  filtrfunction($arr)
    {
        if ($arr === '' || $arr === null) {
            return false;
        }
        return true;
    }



    /**
     * @description: 拼接静态资源
     */
    public static function  baseUrl(string $url)
    {
        $base_url = config('app.url', "/");
        if (substr($base_url, -1) === '/') $base_url = substr($base_url, 0, -1); //最后一个是/删除/
        if (substr($url, 0, 1) !== '/')  $url  = '/' . $url;
        return $base_url  . $url;
    }


    /**
     * @description: 获得当前ip
     */
    public static function  visitIp(): string
    {
        return \Illuminate\Support\Facades\Request::ip();
    }



    /**
     * @description:  生成日期随机数
     * @return {*}
     */
    public static function createDateRound(): string
    {

        return  date('Ymd') . substr(implode("", array_map('ord', str_split(substr(uniqid(), 7, 13), 1))), 0, 8);
    }



    /**
     * @description:  转成key数组
     * @param   数组或者orm数据
     * @param   key唯一值
     */
    public static  function keyArray(array $array, string $key, Closure|null $func = null): array
    {
        $newArray = [];
        if ($func  === null) $func = fn ($item) => $item;
        foreach ($array as $val) $newArray[$val[$key]] = $func($val);
        return $newArray;
    }

    /**
     * @description:        筛选数组字段组成新数组
     * @param array $array
     * @param array $key
     * @param \Closure|null $func
     * @return {*}
     */
    public static function keyVal(array $array, array $keyArr, $default = null)
    {
        $newArray = [];
        if (empty($keyArr)) return $array;
        foreach ($keyArr as $val)   $newArray[$val] = $array[$val] ?? $default;
        return $newArray;
    }

    /**
     * @description:  \App\Helpers\Enums 方便字典输出
     * @param {string} $str
     * @param {bool} $getVal
     * @return {*}
     */
    public static function getEnum(string $str, bool $getVal = false)
    {
        list($fileName, $key) = explode('.', $str);
        return  $getVal ? constant("\App\Helpers\Enums\\{$fileName}::{$key}")->value :  constant("\App\Helpers\Enums\\{$fileName}::{$key}");
    }

    /**
     * @description:   后面天数时间戳
     * @param  int $aftDay
     * @return int
     */
    public static function afterDaysTimestamp(int $aftDay): int
    {
        $aftDaysTimestamp = strtotime('+' . $aftDay . ' day');

        $aftDaysZeroTimestamp = strtotime(date('Y-m-d', $aftDaysTimestamp));

        return $aftDaysZeroTimestamp;
    }

    /**
     * @description:  月头的时间戳
     * @param int|null  $aftDay  null这个月 大于0往后推 小于0往前推
     * @return {*}
     */
    public static function monthFirstTimestamp(int|null $aftDay = null): int
    {

        $day_str = null;
        if ($aftDay === null)  $day_str = null;
        else if ($aftDay < 0)  $day_str = '+' . $aftDay;
        else   $day_str = (string)$aftDay;

        return strtotime(date('Y-m', $day_str === null ?  null : strtotime($day_str . ' months')) . '-1');
    }




    public static function formExtractData(array $form, array $filed)
    {

        $new_data = [];

        foreach (array_keys($form) as $val) if (in_array($val, $filed)) $new_data[$val]  = $form[$val];


        return $new_data;
    }


    public static function strOmit(string $str, int $end_len, string $omit = "...")
    {

        $mb_strlen_str = mb_strlen($str);

        $mb_strlen_omit = mb_strlen($omit);

        if ($mb_strlen_str <= $end_len) return $str;
        else if ($mb_strlen_str >=   $mb_strlen_omit) {

            $short_str = mb_substr($str, 0, $end_len);

            return mb_substr($short_str, 0, -mb_strlen($omit)) . $omit;
        } else return mb_substr($omit, 0, $end_len);
    }

    public static function getLastText(string $str, string $separator)
    {

        $str_arr =  explode($separator, $str);

        return end($str_arr);
    }

    public static  function fileUnit(int $size)
    {
        if ($size >= 1024 * 1024 * 1024) return ceil($size / 1024 / 1024 / 1024) . 'GB';
        else if ($size >= 1024 * 1024) {
            return ceil($size / 1024 / 1024) . 'MB';
        } else return ceil($size / 1024) . 'KB';
    }

    public static function getRealIp(\Illuminate\Http\Request $request)
    {
        $real_ip = $request->header(\App\Helpers\Enums\HeaderReq::REAL_IP->value, null);
        return $real_ip === null ?  $request->ip() : $real_ip;
    }


    public static function isBrowser(\Illuminate\Http\Request $request)
    {
        return (!$request->expectsJson() && $request->isMethod('get') && strpos($request->header('User-Agent', ""), 'Mozilla') !== false);
    }


    public static function sortKeysLen(array $data, callable $func)
    {

        $keys_arr = array_keys($data);

        for ($i = 0; $i < count($keys_arr); $i++) {

            for ($j = 0; $j < count($keys_arr) - $i - 1; $j++) {

                if ($func(mb_strlen($keys_arr[$j]),  mb_strlen($keys_arr[$j + 1]))) {
                    $before =  $keys_arr[$j + 1];
                    $keys_arr[$j + 1] = $keys_arr[$j];
                    $keys_arr[$j] = $before;
                }
            }
        }

        $newData = [];
        foreach ($keys_arr as $val) {
            $newData[$val] = $data[$val];
        }

        return $newData;
    }

    public static function filterMap(array $array, callable $callback, mixed $falseConfig = false)
    {
        $newData = [];
        foreach ($array as $val) {
            $res = $callback($val);
            if ($res === $falseConfig) continue;
            else  $newData[] = $res;
        }
        return $newData;
    }

    public static function  startswith_array(mixed $needle, array $haystack, bool $emptyBool = true)
    {
        if (empty($haystack)) return $emptyBool;
        foreach ($haystack as $val) if (self::startsWith($needle, $val)) return true;
        return false;
    }

    public static function extractKeyAndValue(array $array)
    {
        $newArray = [];
        foreach ($array as $key => $val) {
            if (!is_array($val)) {
                $newArray[] = $val;
            } else {
                $newArray[] = $key;
            }
        }
        return $newArray;
    }

    public static function getAllOrganiName()
    {
        return env("ALL_ORGANI_NAME", "所有部门");
    }


    public static function filter(array $array, callable $callback): array
    {
        $newArray = [];
        foreach ($array as $val) {
            if ($callback($val))  $newArray[] = $val;
        }
        return $newArray;
    }

    public static function tenancyInitialize(string|int $tenancy)
    {
        //切库操作
        app(\Stancl\Tenancy\Tenancy::class)->initialize($tenancy);
    }

    /**
     * @description: 拼接静态资源
     */
    public static function  tenantUrl(string $url)
    {
        $tenant_url =  env("TENANT_URL" , "");
        if (substr($tenant_url, -1) === '/') $tenant_url = substr($tenant_url, 0, -1); //最后一个是/删除/
        if (substr($url, 0, 1) !== '/')  $url  = '/' . $url;
        return $tenant_url  . $url;
    }
}
