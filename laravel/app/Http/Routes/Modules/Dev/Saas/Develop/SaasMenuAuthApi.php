<?php

declare(strict_types=1);

namespace App\Http\Routes\Modules\Dev\Saas\Develop;

use App\Http\Interfaces\Routes;
use App\Rules\{ZeroOne, NotZero};


class SaasMenuAuthApi implements Routes
{
    public function routes(): array
    {
        return [
            'add' => ["method" => "post", "valid" => ["auth_id", "menu_id", "api" , "add_log"]],
            'addLog' => ["method" => "put" ,"valid" => ["api_id", "add_log"]],
            'del' => ["method" => "delete", "valid" => ["api_id"]],
            'edit' => ["method" => "put", "valid" => ["api_id", "api", "add_log" , "menu_id"]],
        ];
    }


    public function rules(): array
    {
        return  [
            'auth_id' =>   ['required','numeric',new NotZero],
            'menu_id' =>   ['required','numeric',new NotZero],
            'api' => ['required','max:255'],
            'add_log' => ['required', new ZeroOne],
            'api_id' =>   ['required','numeric',new NotZero],
        ];
    }
}
