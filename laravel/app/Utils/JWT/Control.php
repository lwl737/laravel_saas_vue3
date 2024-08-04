<?php

declare(strict_types=1);

namespace App\Utils\JWT;

use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use Closure;
use Illuminate\Http\Request;
use App\Helpers\Enums\HeaderReq;
use App\Helpers\Func;
use App\Helpers\Output\Json\Error;

class Control
{
    public readonly string  $public_path;            //公钥路径
    public readonly string  $private_path;           //私钥路径

    /**
     * 生成jwt
     *
     * @param string   $iss        签发人
     * @param integer  $nbf        生效时间 -1 为立即生效
     * @param integer  $exp        有效期时间 秒
     * @param int|null $over_time  超时时间 用于客户端刷新token 第二个参数会返回true和false
     */
    public function __construct(
        public readonly string  $iss,                    //签发人
        public readonly int     $nbf,                    //生效时间 -1 为立即生效
        public readonly int     $exp,                     //有效期时间 秒
        public readonly int|null   $over_time = null     //超时时间 用于客户端刷新token
    ) {
        $this->public_path  =  Func::readTextRedis(base_path('/pem/JWT/rsa_public_key.pem'), 'PUBLIC_PEM');
        $this->private_path =  Func::readTextRedis(base_path('/pem/JWT/rsa_private_key.pem'), 'PRIVATE_PEM');
    }

    public   function create(array $data ): string
    {
        $privateKey =  $this->private_path;
        $payload = [
            "iss" => $this->iss,           //签发人  可空
            "iat" => time(),               //签发时间
            "nbf" => time() + $this->nbf,  //生效时间  （立即生效）
            "exp" => time() + $this->exp,  //有效期
            "over_time" => $this->over_time ? time() + $this->over_time : null ,    //超时时间 用于客户端刷新token
            "data" => $data
        ];
        return JWT::encode($payload, $privateKey, 'RS256');
    }

    /**
     * @description: 检验 token 操作
     * @param {string} $key \App\Enums\Header的key
     * @param Request $request      请求
     * @param App\Utils\JWT\Config                 JWT配置里的方法
     * @param function|null $successFunc           闭包 成功回调
     * @param function|null $timeOutFunc           闭包 超时回调
     * @param function|null $default               闭包 其他回调
     * @return {*}
     */
    public  function check(Request $request, HeaderReq $headerKey, Closure|null $successFunc = null, Closure|null $timeOutFunc = null, Closure|null $defaultFunc = null, Closure|null $notHaveFunc = null)
    {

        $headerKey = $headerKey->value;
        $access_token = $request->header($headerKey, null);
        if (!$access_token)  return $notHaveFunc !== null ? $notHaveFunc() : Func::toHttpEnum(Error::TOKEN_NOT_HAVE);
        return self::checkJWTName($access_token, $successFunc, $timeOutFunc,  $defaultFunc);
    }

    public  function checkJWT($access_token)
    {
        try {
            $data = self::getJWT($access_token);

            return ['data' => (array)$data->data , 'is_over' => isset( $data->over_time ) && !empty($data->over_time) ?  $data->over_time <= time() : null    , 'code' => Status::success->value];
        } catch (\Firebase\JWT\SignatureInvalidException $e) { //签名不正确
            return ['code' => Status::sign_error->value];
        } catch (\Firebase\JWT\BeforeValidException $e) { // 签名在某个时间点之后才能用
            return ['code' => Status::before_error->value];
        } catch (\Firebase\JWT\ExpiredException $e) { // token过期
            return ['code' => Status::timeout->value];
        } catch (\Exception $e) { //其他错误
            return ['code' => Status::other_error->value];
        }
    }


    public  function checkJWTName(string $access_token, Closure|null $successFunc = null, Closure|null $timeOutFunc = null, Closure|null $defaultFunc = null)
    {
        $status =  Control::checkJWT((string)$access_token);
        return  match (Status::from($status['code'])->name) {
            'success' => $successFunc !== null ? $successFunc( $status['data'] ,  $status['is_over'] ) : $status['data'],
            'timeout' => $timeOutFunc !== null ? $timeOutFunc() :  Func::toHttpEnum(Error::LOGIN_OUT),   //token过期
            default =>   $defaultFunc !== null ? $defaultFunc() :  Func::toHttpEnum(Error::TOKEN_ERROR)
        };
    }


    public  function getJWT($access_token)
    {
        $decode =  JWT::decode($access_token, new Key($this->public_path, 'RS256'));
        return $decode;
    }
}
