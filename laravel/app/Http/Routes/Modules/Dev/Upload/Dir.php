<?php

declare(strict_types=1);

namespace App\Http\Routes\Modules\Dev\Upload;

use App\Http\Interfaces\Routes;
use App\Rules\{HaveZero , ZeroOne , ArrValidator};


class Dir implements Routes
{

    public function routes(): array
    {
        return [
            'all' => [],
            'add' => ["method" => "post" , "valid" => ['dir_name', 'dir_pid','dir_sort'] ],
            'del' => ["method" => "delete" , "valid" => ['ids']],
            'edit' => ["method" => "put" , "valid" => ['dir_id','dir_name','is_common','dir_sort'] ],
        ];
    }


    public function rules(): array
    {
        return  [
            'dir_name'=>['required','max:30'],
            'dir_id'=>['required', 'string'],
            'dir_pid'=> [ 'required', new HaveZero],
            // 'is_common' => ['required', new ZeroOne],
            'dir_sort' => ['required', new HaveZero(999999)],
            'ids' => ['required', 'array', new ArrValidator(['required','string'] , [0,36])]
        ];
    }
}




