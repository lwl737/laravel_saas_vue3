<?php

declare(strict_types=1);

use App\Helpers\Func;
use App\Helpers\Output\Json\Error;
use App\Helpers\Output\Json;
use App\Http\Interfaces\Routes as RoutesInterface;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;
use App\Http\Interfaces\Entrances;

$ap_dir = dirname(__FILE__) . "/Modules";

$router_namespace = "App\Http\Routes\Modules";

$controller_namespace = "App\Http\Controllers";

$ap_dir_strlen = mb_strlen($ap_dir);

//公共自定义规则
$common_rules = include_once(dirname(__FILE__) . "/../Requests/Common.php");
//入口
$entrance_modules = include_once(dirname(__FILE__) . "/../Entrances/auto.php");
//中间件
$middleware_auto = include_once(dirname(__FILE__) . "/../Middlewares/auto.php");

function checkRules(array $params, RoutesInterface $routerClass, $item ,array $common_rules) {

    $newRule = [];

    $common = array_merge($common_rules, $routerClass->rules());

    foreach ($item['valid'] as $key =>  $val) {

        if (is_string($val) && isset($common[$val])) $newRule[$val] = $common[$val];

        else $newRule[$key] = $val;
    }

    $validator = Validator::make($params, $newRule);

    if ($validator->fails()) {
        $result = new Json($validator->getMessageBag()->toArray(), Error::FORM_VALID_ERROR);
        throw new HttpResponseException(Func::toHttpJson($result));
    }

    return  $validator->validated();
};


$readFileAndDir = function ($files, $prefix, array $before = [], string $inject = "", array $middlewares = []) use ($router, &$readFileAndDir, $router_namespace, $controller_namespace, $entrance_modules, $middleware_auto , $common_rules) {
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

        if (isset($entrance_modules[$implode_prev]))  $inject = $entrance_modules[$implode_prev];

        if (!is_dir($tmp_path)) {

            $class = implode('.', $class_name);

            $file_arr =  array_merge($before, [$class]);

            $full_class = $router_namespace  . '\\' . implode('\\', $file_arr);


            if (!class_exists($full_class)){

            var_dump($full_class);
                Func::throwHttpCustom(Error::ROUTES_CLASS_NOT_DEFIND, ['class_name' => $full_class]);
            }

            $reflectionClass = new $full_class;

            if (!$reflectionClass instanceof RoutesInterface)  Func::throwHttpCustom(Error::ROUTES_CLASS_ERROR, ['interface' => RoutesInterface::class]);

            $router->group(['prefix' => Func::toUnderScore($class)], function ($router) use ($reflectionClass, $inject, $tmp_path, $controller_namespace, $file_arr, $middleware_arr, $implode_prev, $middleware_auto, $entrance_modules , $common_rules) {

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

                    if (isset($entrance_modules[$implode_prev . "." . $key])) $inject = $inject[$implode_prev . "." . $key];

                    if (!$inject) $inject = App\Http\Entrances\Modules\Base::class;

                    if (is_array($inject) && isset($inject['module']) && isset($inject['exclude']) && Func::startswith_array($implode_prev . "." . $key, $inject['exclude']))  $inject = $inject['module'];

                    $closure = function (...$parameters) use ($item, $reflectionClass, $inject, $tmp_path, $key, $controller_namespace, $file_arr , $common_rules) {

                        $request = request();

                        if (isset($item['closure']))  return $item['closure']($request);
                        else {

                            $inject = $reflectionClass->inject ?? $inject;

                            $entrance_module =  new $inject(
                                request: $request,
                                route_auto: ['key' => $key, 'item' => $item, 'class' => $reflectionClass::class],
                                parameters: array_combine($item['parameters'] ?? [], $parameters),
                                output: $item['output'] ?? "json"
                            );

                            if (!$entrance_module instanceof Entrances)  Func::throwHttpCustom(Error::INJECT_CLASS_ERROR, ['class_name' => $inject::class, 'interface' => Entrances::class]);

                            $moduleClass = isset($reflectionClass->controller)   ?   $reflectionClass->controller :  $controller_namespace  . '\\' . implode('\\', $file_arr);;

                            if (!is_string($moduleClass))  Func::throwHttpCustom(Error::CONTROLLER_MUST_STRING, ['file' => $tmp_path, 'route_name' => $key]);

                            if (isset($item['controller'])) {
                                if (is_string($item['controller'])) $moduleClass = [$moduleClass, $item['controller']];
                                else if (is_array($item['controller']) && array_is_list($item['controller']) && count($item['controller']) !== 2) $moduleClass = $item['controller'];
                                else Func::throwHttpCustom(Error::MODULES_PARAMS_ERROR, ['file' => $tmp_path, 'route_name' => $key]);
                            }

                            return $entrance_module->loadController(
                                $moduleClass,
                                function ($params) use ($item, $reflectionClass , $common_rules) {

                                    $params = isset($item["valid"]) && !empty($item["valid"]) && !empty($params) ?  checkRules($params, $reflectionClass, $item , $common_rules) : $params;

                                    return ($item['format'] ?? fn ($params) => $params)($params);
                                },
                                $item['funcParams'] ?? []
                            );
                        }
                    };
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

                    $router->$method($toUnderScoreKey . $parameters, $closure)->name(($item['name'] ??  implode('.', array_map(fn ($item) => Func::toUnderScore($item), explode('.', $implode_prev))) . '.' . $toUnderScoreKey) . '/' . $method)->middleware(
                        array_unique(array_merge(
                            Func::filterMap(
                                $middlewares,
                                function ($items) use ($implode_prev, $key) {
                                    if (is_string($items)) return $items;
                                    else if (is_array($items) && isset($items["module"])) {
                                        if (isset($items["exclude"]) && is_array($items["exclude"]) && Func::startswith_array($implode_prev . "." . $key, $items["exclude"])) {
                                            return false;
                                        }
                                        return $items["module"];
                                    }
                                }
                            ),
                            $middleware
                        ))
                    );
                }
            });
        } else {

            if (isset($entrance_modules[$implode_prev]))  $inject = $entrance_modules[$implode_prev];

            $router->group(['prefix' => Func::toUnderScore($val)], fn () => $readFileAndDir(scandir($tmp_path), $tmp_path, array_merge($before, [$val]), $inject, $middleware_arr));
        }
    }
};

$readFileAndDir(scandir($ap_dir), $ap_dir);
