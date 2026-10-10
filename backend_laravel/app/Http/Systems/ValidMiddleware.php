<?php
declare(strict_types=1);

namespace App\Http\Systems;

use Illuminate\Http\Request;
use Closure;
use App\Http\Interfaces\Routes;
use Illuminate\Support\Facades\Validator;
use App\Helpers\Output\Json;
use App\Helpers\Output\Json\Error;
use App\Helpers\Func;

class ValidMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next, string $reflectionClass, string $key)
    {
         app()->instance(CustomRouter::class,  new CustomRouter($request));
        /** @var Routes **/
        $reflectionClass = new $reflectionClass;

        $item = $reflectionClass->routes()[$key];
        $common_rules = include(dirname(__FILE__) . "/CommonValidate.php");

        $params = $request->input();

        if (isset($item["valid"]) && !empty($item["valid"]) && !empty($params)) {

            $params = $this->checkRules( $params , $reflectionClass->rules(), $item['valid'], $common_rules);
            //表单验证失败
            if($params instanceof Json)  return Func::toHttpJson($params);
        }


        $parameters = Func::extractKey( $item['parameters'] ?? [] )  ;

        $parameters_vals =  array_reduce($parameters , function($pre , $item) use ($request ){
            $pre[$item] = $request->route($item , null);
            return $pre;
        } , []);

        if(!empty($parameters_vals)){
            $res =  $this->checkRules($parameters_vals, $reflectionClass->rules() , $item['parameters'], $common_rules , $parameters , Error::PARAMETERS_VALID_ERROR);
            if($res instanceof Json) return Func::toHttpJson($res);
        }

        $parameters = array_map(fn() => ':params', $parameters );

        $custom_path = explode( '/' , $request->path());

        array_splice( $custom_path , -count($parameters) , count($parameters) , $parameters  );

        app(CustomRouter::class)->init(implode("/",$custom_path)  ,  $item , $params , $parameters_vals );

        return $next($request);
    }

    private function checkRules(array $params, array $rules, array $valid, array $common_rules , array $mapping = [] , Error $error = Error::FORM_VALID_ERROR):Json|array
    {

        $newRule = [];

        $common = array_merge($common_rules, $rules);

        foreach ($valid as $key =>  $val) {

            if (is_string($val) && isset($common[$val])) $newRule[$val] = $common[$val];

            else $newRule[$key] = $val;
        }


        $validator = Validator::make($params, $newRule);
        if ($validator->fails()){
            $errorBag  = $validator->getMessageBag()->toArray();
            foreach( $mapping as $key => $val) $mapping[$key] = $errorBag[$val] ?? [];
            return new Json( !empty($mapping) ? $mapping : $errorBag , $error);
        }

        return   Func::formExtractData($params , array_keys($newRule));
    }
}
