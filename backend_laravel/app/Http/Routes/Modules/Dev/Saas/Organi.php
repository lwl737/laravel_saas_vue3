<?php

declare(strict_types=1);

namespace App\Http\Routes\Modules\Dev\Saas;

use App\Http\Interfaces\Routes;
use App\Rules\{NotZero};


class Organi implements Routes
{
    public function routes(): array
    {
        return [
            'option' =>  ["method" => "get" , "valid" => ["tenant_id"]],
        ];
    }


    public function rules(): array
    {
        return  [
            'tenant_id' => ['required', new NotZero ],
        ];
    }
}
