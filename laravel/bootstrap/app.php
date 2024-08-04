<?php

declare(strict_types=1);

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Http\Request;

use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpKernel\Exception\MethodNotAllowedHttpException;
use App\Helpers\Func;
use App\Helpers\Output\Json\Error;
use App\Helpers\Output\Interface\Pages as PagesInterface;
use App\Helpers\Output\Interface\Json as JsonInterface;
use App\Helpers\Output\Json;
use App\Helpers\Output\Pages;
use App\Exceptions\HttpCustomException;
use App\Helpers\Enums\TrueCode;
use App\Dao\Mongo\ErrorLogDao;
use App\Helpers\Inject;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        api: __DIR__ . '/../app/Http/Routes/auto.php',
        apiPrefix:""
        // commands: __DIR__.'/../routes/console.php',
        // health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        $middleware->remove([
            \Illuminate\Foundation\Http\Middleware\ConvertEmptyStringsToNull::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions)  {
        //停止原来的错误日志
        $exceptions->report(fn (Throwable $e) => false);

        $exceptions->render(function (Throwable $e, Request $request)  {

            //判断是否已经设置了响应头
            $header = Inject::bindGet(Json::class)->getHeader();

            //如果404内部错误先返回
            if ($e instanceof NotFoundHttpException) return Func::toHttpJson(new Json( [] ,Error::API_NOT_FIND , $header));
            //请求方式错误
            else if ($e instanceof MethodNotAllowedHttpException) return Func::toHttpJson(new Json( [] ,Error::API_METHOD_ERROR ,  $header , ['method' => $request->getMethod()]));
            //100错误
            else if ($e instanceof HttpCustomException && $e->getResult()->getTrueCodeName() === TrueCode::SUCCESS->name){
                $e->getResult()->addHeader( $header, true );
                return Func::toHttpJson($e->getResult());
            }
            //自定义错误和系统错误
            else {

                $error = $e instanceof HttpCustomException && $e->getException() ?
                    [
                        'line' => $e->getException()->getLine(),
                        'file' => $e->getException()->getFile(),
                        'error' => $e->getMessage() . ' error:message ' . $e->getException()->getMessage(),
                    ]:[
                        'line' => $e->getLine(),
                        'file' => $e->getFile(),
                        'error' => $e->getMessage(),
                    ];

                if( $e instanceof HttpCustomException ){

                    $merge = ['enum' => [
                        'class' =>  $e->getResult()->getHttpEnum()::class,
                        'name' => $e->getResult()->getHttpName(),
                        'trueCodeName' => $e->getResult()->getTrueCodeName(),
                        'code' => $e->getResult()->getHttpVal(),
                    ] ];
                    $addLog = $e->getAddLog();  //是否加入错误日志
                }else{
                    $merge = ['enum' =>  null];
                    $addLog = true;  //必定加入错误日志
                }

                //加入mongodb日志
                $logPostion =  $addLog ? ErrorLogDao::static()->insertOneAsync(array_merge($error, $merge), $request) : [];

                // //请求方式错误
                if (config('app.debug') === true) {     //测试环境输出错误信息

                    $error = array_merge($error, $logPostion);

                    $result = $e instanceof HttpCustomException ? new Json($error,  $e->getResult()->getHttpEnum(), $header,  $e->getResult()->getReplace()) : new Json($error, Error::SERVER_ERROR , $header);

                } else {

                    $result = new Json($logPostion, Error::SERVER_ERROR , $header);

                }

                return Func::toHttpJson( $result ) ;

            }

        });
    })->create();


