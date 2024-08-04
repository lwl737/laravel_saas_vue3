<?php

declare(strict_types=1);

namespace App\Http\Routes\Modules\Dev\Develop;

use App\Http\Interfaces\Routes;
use App\Rules\{NotZero};


class MenuAuthButtons implements Routes
{
    public function routes(): array
    {
        return [
            'add' => ["method" => "post", "valid" => ["auth_id", "buttons"]],
            'del' => ["method" => "delete", "valid" => ["buttons_id"]],
            'edit' => ["method" => "put", "valid" => ["buttons", "buttons_id"]],
        ];
    }


    public function rules(): array
    {
        return  [
            'auth_id' =>   ['required','numeric',new NotZero],
            'buttons' =>   ['required','max:255'],
            'buttons_id' =>   ['required','numeric',new NotZero],
        ];
    }
}
