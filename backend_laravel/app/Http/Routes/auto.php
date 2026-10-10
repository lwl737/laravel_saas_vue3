<?php

declare(strict_types=1);

use App\Helpers\Func;
use App\Helpers\Output\Json\Error;
use App\Http\Interfaces\Routes as RoutesInterface;
use App\Http\Systems\ValidMiddleware;
use App\Http\Systems\EndMiddleware;

$ap_dir = dirname(__FILE__) . "/Modules";

$router_namespace = "App\Http\Routes\Modules";

$controller_namespace = "App\Http\Controllers";

$ap_dir_strlen = mb_strlen($ap_dir);

//入口
$entrance_modules = include_once(dirname(__FILE__) . "/../Entrances/auto.php");
//中间件
$middleware_auto = include_once(dirname(__FILE__) . "/../Middlewares/auto.php");

$readFileAndDir = function ($files, $prefix, array $before = [], string $entrance = "", array $middlewares = []) use ($router, &$readFileAndDir, $router_namespace, $controller_namespace, $entrance_modules, $middleware_auto) {
    foreach ($files as $idx => $val) {
        //去除最前面的 . 和 ..
        if ($idx <= 1) continue;

        $tmp_path = $prefix . "/" . $val;

        $class_name = explode('.', $val);

        if (count($class_name) > 1)  array_pop($class_name); //文件

        $implode_prev = implode(".", array_merge($before, $class_name));

        $middleware_arr = $middlewares;

        if (isset($middleware_auto[$implode_prev])) {
            if (is_string($middleware_auto[$implode_prev])) {
                $middleware_arr =  array_merge($middleware_arr, [$middleware_auto[$implode_prev]]);
            } else {
                foreach ($middleware_auto[$implode_prev] as $vals) {
                    $middleware_arr =  array_merge($middleware_arr, [$vals]);
                }
            }
        }

        if (isset($entrance_modules[$implode_prev]))  $entrance = $entrance_modules[$implode_prev];

        if (!is_dir($tmp_path)) {

            $class = implode('.', $class_name);

            $file_arr =  array_merge($before, [$class]);

            $full_class = $router_namespace  . '\\' . implode('\\', $file_arr);

            if (!class_exists($full_class)) {
                Func::throwHttpCustom(Error::ROUTES_CLASS_NOT_DEFIND, ['class_name' => $full_class]);
            }

            $reflectionClass = new $full_class;

            if (!$reflectionClass instanceof RoutesInterface)  Func::throwHttpCustom(Error::ROUTES_CLASS_ERROR, ['interface' => RoutesInterface::class]);

            $router->group(['prefix' => Func::toUnderScore($class)], function ($router) use ($reflectionClass, $entrance, $tmp_path, $controller_namespace, $file_arr, $middleware_arr, $implode_prev, $middleware_auto, $entrance_modules) {

                $middlewares =  $middleware_arr;

                foreach ($reflectionClass->routes() as $key => $item) {

                    $method = 'get';

                    if (isset($item['method'])) {
                        $method = $item['method'];
                    } else {
                        $key_explode = explode('.', $key);
                        if (count($key_explode) >= 2) list($key, $method) = $key_explode;
                    }

                    if (isset($middleware_auto[$implode_prev . "." . $key])) $middlewares = $middleware_auto[$implode_prev . "." . $key];

                    if (isset($entrance_modules[$implode_prev . "." . $key])) $entrance = $entrance[$implode_prev . "." . $key];

                    if (!$entrance) $entrance = "";

                    if (is_array($entrance) && isset($entrance['module']) && isset($entrance['exclude']) && Func::startswithArray($implode_prev . "." . $key, $entrance['exclude']))  $entrance = $entrance['module'];

                    $parameters = "";

                    if (isset($item["parameters"]) && is_array($item["parameters"]) && array_is_list($item["parameters"])) $parameters =  '/{' . implode("}/{", $item["parameters"]) . '}';

                    $middleware = [];

                    if (isset($item['middleware'])) {
                        if (is_string($item['middleware']))  $middleware = [$item['middleware']];
                        else $middleware = $item['middleware'];
                    } else if (isset($reflectionClass->middleware)) {
                        if (is_string($reflectionClass->middleware))  $middleware = [$reflectionClass->middleware];
                        else $middleware = $reflectionClass->middleware;
                    }

                    $toUnderScoreKey = Func::toUnderScore($key);
                    $moduleClass = isset($reflectionClass->controller)   ?   $reflectionClass->controller :  $controller_namespace  . '\\' . implode('\\', $file_arr);

                    if (!is_string($moduleClass))  Func::throwHttpCustom(Error::CONTROLLER_MUST_STRING, ['file' => $tmp_path, 'route_name' => $key]);

                    $router->$method($toUnderScoreKey . $parameters, isset($item['closure']) ? $item['closure'](request()) : [$moduleClass, $key])->name(($item['name'] ??  implode('.', array_map(fn($item) => Func::toUnderScore($item), explode('.', $implode_prev))) . '.' . $toUnderScoreKey) . '/' . $method)->middleware(
                        array_unique(array_merge(
                            [ValidMiddleware::class . ':' . get_class($reflectionClass) . ',' . $key],
                            //中间件传参
                            Func::filterMap(
                                $middlewares,
                                function ($items) use ($implode_prev, $key) {
                                    if (is_string($items)) return $items;
                                    else if (is_array($items) && isset($items["module"])) {
                                        if (isset($items["exclude"]) && is_array($items["exclude"]) && Func::startswithArray($implode_prev . "." . $key, $items["exclude"])) {
                                            return false;
                                        }
                                        return $items["module"];
                                    }
                                }
                            ),
                            $middleware,
                            [EndMiddleware::class. ':' . $entrance . ',' . $key]
                        ))
                    );
                }
            });
        } else {

            if (isset($entrance_modules[$implode_prev]))  $entrance = $entrance_modules[$implode_prev];

            $router->group(['prefix' => Func::toUnderScore($val)], fn() => $readFileAndDir(scandir($tmp_path), $tmp_path, array_merge($before, [$val]), $entrance, $middleware_arr));
        }
    }
};

$readFileAndDir(scandir($ap_dir), $ap_dir);
