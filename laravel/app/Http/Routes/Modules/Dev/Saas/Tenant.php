<?php

declare(strict_types=1);

namespace App\Http\Routes\Modules\Dev\Saas;

use App\Http\Interfaces\Routes;
use App\Rules\HaveZero;
use App\Rules\NotZero;
use App\Rules\EnumVal;
use App\Models\Mysql\Trait\Enums\Status;


class Tenant implements Routes
{

    public function routes(): array
    {
        return [
            'list' => ["method" => "get" , "valid" => ['pageSize','pageNum' , 'tenant_name' => ['string','max:50']]],
            'all' => ["method" => "get" ],
            'add' => ["method" => "post" , "valid" => ['tenant_name','status' , 'tenant_sort']],
            'del' => ['method' => 'delete' , "valid" => ['ids']],
            'edit' => ["method" => "put" , "valid" => [ 'tenant_id' ,'tenant_name','status' , 'tenant_sort'] ],
            'editStatus' => ["method" => "put" , "valid" => [ 'tenant_id' ,'status']],
        ];
    }


    public function rules(): array
    {
        return  [
            'tenant_name' => ['required','string','max:50'],
            'status' => ['required', new EnumVal(Status::class)],
            'tenant_id' => ['required',new NotZero],
            'tenant_sort' => ['required',new HaveZero(999999)],
        ];
    }
}
