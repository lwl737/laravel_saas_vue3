<?php

declare(strict_types=1);

namespace App\Http\Routes\Modules\Dev\Develop;

use App\Http\Interfaces\Routes;
use App\Rules\{HaveZero, NotZero};


class MenuAuth implements Routes
{
    public function routes(): array
    {
        return [
            'add' => ["method" => "post", "valid" => ["menu_id", "auth_name", "auth_sort"]],
            'list' => ["valid" => ["pageSize", "pageNum", "menu_id"]],
            'del' => ["method" => "delete", "valid" => ["auth_id"]],
            'edit' => ["method" => "put", "valid" => ["auth_id", "auth_name", "auth_sort"]],
        ];
    }


    public function rules(): array
    {
        return  [
            'menu_id'  => ['required', 'numeric', new NotZero],
            'auth_name'  => ['required', 'max:255'],
            'auth_sort'  => ['required', 'numeric' , new HaveZero(999999)],
            'auth_id'  => ['required', 'numeric', new NotZero]
        ];
    }
}
