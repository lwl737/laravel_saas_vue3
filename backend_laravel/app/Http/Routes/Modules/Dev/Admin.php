<?php

declare(strict_types=1);

namespace App\Http\Routes\Modules\Dev;

use App\Http\Interfaces\Routes;
use App\Rules\{Phone, HaveZero, NotZero, Account  , ArrValidator, EnumVal};
use App\Common\Models\Mysql\Trait\Enums\Status;


class Admin implements Routes
{

    public function routes(): array
    {
        return [
            'login' => ["method" => "get", "valid" => ["username", "password"]],
            'checkLogin' => ["method" => "get"],
            'editUserInfo' => ["method" => "put", "format" => function ($params) {
                $params['portrait'] = $params['portrait'] ?? "";
                return $params;
            }, "valid" => ["username", "portrait", "nick_name", "phone", "real_name"]],
            'editPass' => ["method" => "put", "valid" => ["ori_password", "password"]],
            "list" => ["method" => "get", "valid" => ["pageNum", "pageSize", "insert_organi_id" ,"insert_nick_name" , "insert_organi_name", 'username' => ["string", 'max:50'], 'nick_name' => ["string", 'max:50'], 'real_name' => ["string", 'max:50'], 'phone' => ["string", 'max:50'], 'role_id' => [new HaveZero]]],
            "add" => ["method" => "post", "format" => function ($item) {
                $item['organi_id'] = array_unique($item['organi_id']);
                return $item;
            }, "valid" => ["username", "password", "status", "role_id", "portrait", "nick_name", "real_name", "phone", "organi_id"]],
            "editStatus" => ["parameters" => ['admins_id'] , "method" => "put", "valid" => ['status']],
            "edit" => ["parameters" => ['admins_id'] ,"method" => "put", "valid" => ["username", "password" => ["string", "size:32"], "status", "role_id", "portrait", "nick_name", "real_name", "phone", "organi_id"]],
            "del" => ["method" => "delete", "valid" => ["ids"]],
        ];
    }


    public function rules(): array
    {
        return  [
            'status' => ['required', new EnumVal(Status::class)],
            'role_id' => ['required', new NotZero],
            'portrait' => ['string', 'max:512'],
            'real_name' => ['required', 'string', 'max:20'],
            'phone' => ['required', new Phone],
            'organi_id' => ['required', new ArrValidator(['required', new NotZero])],
            'password' => ['string' , 'required' ,'size:32' ],
            'ori_password' => 'string|required|size:32',
            'username' =>  ['string' , 'required' , 'string' , 'min:5' ,'max:20' , new Account],
            'nick_name' => ['required', 'string', 'max:20'],
            'admins_id' => ['required', new NotZero]
        ];
    }
}
