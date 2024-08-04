<?php

declare(strict_types=1);

namespace App\Http\Routes\Modules\Dev\Saas\Develop;

use App\Http\Interfaces\Routes;
use App\Rules\{ZeroOne, NotZero};


class SaasMenuApi implements Routes
{
    public function routes(): array
    {
        return [
            'add' => ["method" => "post", "valid" => [ "menu_id", "api" ,"api_name" ,"add_log"]],
            'addLog' => ["method" => "put" ,"valid" => ["api_id", "add_log"]],
            'list' => ["valid" => ["pageNum", "pageSize" , "menu_id"]],
            'del' => ["method" => "delete", "valid" => ["api_id"]],
            'edit' => ["method" => "put", "valid" => ["api_id", "api", "add_log" ,"api_name" , "menu_id" ]],
        ];
    }


    public function rules(): array
    {
        return  [
            'auth_id' =>   ['required','numeric',new NotZero],
            'menu_id' =>   ['required','numeric',new NotZero],
            'api' => ['required','max:255'],
            'api_name' => ['required','max:255'],
            'add_log' => ['required', new ZeroOne],
            'api_id' =>   ['required','numeric',new NotZero],
        ];
    }
}
