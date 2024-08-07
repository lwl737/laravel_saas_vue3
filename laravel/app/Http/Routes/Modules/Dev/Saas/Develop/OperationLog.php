<?php

declare(strict_types=1);

namespace App\Http\Routes\Modules\Dev\Saas\Develop;

use App\Http\Interfaces\Routes;
use App\Rules\{Ymd, NotZero};


class OperationLog implements Routes
{

    public function routes(): array
    {
        return [
            'list' => ["format" => function ($params) {
                if (isset($params['api'])) $params['api']   =  substr($params['api'], 0, 1) === '/' ? substr($params['api'], 1) : $params['api'];
                return $params;
            }, "valid" => ['tenant_id', 'insert_organi_name','pageSize', 'pageNum', 'ip', 'log_id', 'router_path', 'menu_title', 'auth_name', 'nickName', 'realName', 'operation_time']],
        ];
    }


    public function rules(): array
    {
        return  [
            'log_id' => ['max:50'],
            'menu_title' => ['max:50'],
            'auth_name' => ['max:50'],
            'nickName' => ['max:50'],
            'realName' => ['max:50'],
            'router_path' => ['max:50'],
            'insert_organi_name' => ['max:50'],
            'api' => ['max:50'],
            'ip' => ['max:50'],
            'tenant_id' => ['required', new NotZero],
            'operation_time' => [new Ymd],
        ];
    }
}
