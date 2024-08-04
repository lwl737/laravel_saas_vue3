<?php

declare(strict_types=1);

namespace App\Http\Routes\Modules\Dev\Saas\Develop;

use App\Http\Interfaces\Routes;
use App\Rules\{HaveZero, NotZero, ZeroOne};


class SaasMenuAuthPages implements Routes
{
    public function routes(): array
    {
        return [
            'add' => ["method" => "post", "format" => function ($params) {
                $params["isHide"] = 1;  //隐藏栏目
                return $params;
            }, "valid" => ["menu_pid", "path", "component", "name", "redirect", "title", "icon", "isLink", "isFull", "isAffix", "isKeepAlive", "auth_id"]],
            'del' => ["method" => "delete", "valid" => ["menu_id"]],
            'edit' => ["method" => "put", "valid" => ["menu_id","path", "component", "name", "redirect", "title", "icon", "isLink", "isFull", "isAffix", "isKeepAlive"]],
        ];
    }


    public function rules(): array
    {
        return [
            'menu_pid' => ['required', 'numeric', new HaveZero],
            'path' => 'required|max:255',
            'component' => 'required|max:255',
            'name' => 'max:255',
            'redirect' => 'max:255',
            'title' =>  ['required', 'max:20'],
            'icon' => ['max:20'],
            'isLink' => ['max:512'],
            'isFull' => ['required', 'numeric', new ZeroOne],
            'isAffix' => ['required', 'numeric', new ZeroOne],
            'isKeepAlive' => ['required', 'numeric', new ZeroOne],
            'auth_id' => ['required', 'numeric', new NotZero],
            'menu_id' => ['required', 'numeric', new NotZero],
        ];
    }
}
