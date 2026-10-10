<?php

declare(strict_types=1);

namespace App\Http\Routes\Modules\Dev;

use App\Http\Interfaces\Routes;
use App\Rules\{NotZero, HaveZero, ArrayType, ZeroOne , EnumVal};
use App\Common\Models\Mysql\Trait\Enums\Status;

class Role implements Routes
{
    public function routes(): array
    {
        return [
            'treeData' =>  ["method" => "get"],
            'list' => ["method" => "get", "valid" => ["pageSize", "pageNum", "insert_organi_id" ,"insert_nick_name" , "insert_organi_name" ,"role_name" => ['string', 'max:50']]],
            'add' =>  ["method" => "post", "valid" => ["role_describe", "role_sort", "role_name", "status"]],
            'del' =>  ["method" => "delete", "valid" => ["ids"]],
            'edit' =>  [ "parameters" => ['role_id'] , "method" => "put", "valid" => [ "role_describe", "role_sort", "role_name", "status"]],
            'set' =>  ["parameters" => ['role_id'] ,"method" => "put", "valid" => [ "role_json"]],
            'option' =>  ["method" => "get"],
            'editStatus' =>  ["parameters" => ['role_id'] ,"method" => "put", "valid" => ["status"]],
        ];
    }


    public function rules(): array
    {
        return  [
            'role_describe' =>  ['string', 'max:512'],
            'status' =>  ['required', new EnumVal(Status::class)],
            'role_sort' => ['required', 'numeric', new HaveZero(999999)],
            'role_name' => ['required', 'max:255'],
            'role_id' => ['required', new NotZero],
            'role_json'  => ['present', 'array', new ArrayType('array', 'number')],
        ];
    }
}
