<?php

declare(strict_types=1);

namespace App\Http\Routes\Modules\Dev\Saas\Develop;

use App\Http\Interfaces\Routes;
use App\Rules\{HaveZero, NotZero, ZeroOne};


class SaasMenu implements Routes
{
    public function routes(): array
    {
        return [
            'add' => ["method" => "post", "valid" => ["menu_pid", "path", "component", "name", "redirect", "menu_sort", "title", "icon", "isLink", "isHide", "isFull", "isAffix", "isKeepAlive"]],
            'all' => [],
            'del' => ["method" => "delete", "valid" => ["menu_id"]],
            'edit' => ["method" => "put", "valid" => ["menu_id", "path", "component", "name", "redirect", "menu_sort", "title", "icon", "isLink", "isHide", "isFull", "isAffix", "isKeepAlive"]],
        ];
    }


    public function rules(): array
    {
        return  [
            'menu_id' =>  ['required', 'numeric', new NotZero],
            'menu_pid' => ['required', 'numeric', new HaveZero],
            'path' => 'required|max:255',
            'component' => 'max:255',
            'name' => 'max:255',
            'redirect' => 'max:255',
            'menu_sort' => ['required', 'numeric', new HaveZero(999999)],
            'title' =>  ['required', 'max:20'],
            'icon' => ['max:20'],
            'isLink' => ['max:512'],
            'isHide' => ['required', 'numeric', new ZeroOne],
            'isFull' => ['required', 'numeric', new ZeroOne],
            'isAffix' => ['required', 'numeric', new ZeroOne],
            'isKeepAlive' => ['required', 'numeric', new ZeroOne],
        ];
    }
}
