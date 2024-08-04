<?php

declare(strict_types=1);

namespace App\Http\Routes\Modules\Dev\Saas;

use App\Http\Interfaces\Routes;
use App\Rules\{NotZero, EnumVal, Phone};
use App\Models\Mysql\Trait\Enums\Status;

class SuperAdmin implements Routes
{

    public function routes(): array
    {
        return [
            "list" => ["method" => "get", "valid" => ["tenant_id" => [new NotZero], "pageNum", "pageSize", 'nick_name' => ["string", 'max:50'], 'real_name' => ["string", 'max:50'], 'phone' => ["string", 'max:50']]],
            "add" => ["method" => "post", "valid" => ["tenant_id", "username", "password", "status", "portrait", "nick_name", "real_name", "phone"]],
            "editStatus" => ["method" => "put", "valid" => ["tenant_id", 'status', "admins_id"]],
            "edit" => ["method" => "put", "valid" => ["tenant_id", "admins_id", "username", "password" => ["string", "size:32"], "status", "portrait", "nick_name", "real_name", "phone"]],
            "del" => ["method" => "delete", "valid" => ["tenant_id", "ids"]],
        ];
    }


    public function rules(): array
    {
        return  [
            'status' => ['required', new EnumVal(Status::class)],
            'portrait' => ['string', 'max:512'],
            'real_name' => ['required', 'string', 'max:20'],
            'phone' => ['required', new Phone],
            'password' => 'string|required|size:32',
            'tenant_id' => ['required', new NotZero],
            'username' => 'string|required|string|min:5|max:20',
            'nick_name' => ['required', 'string', 'max:20'],
            'admins_id' => ['required', new NotZero]
        ];
    }
}
