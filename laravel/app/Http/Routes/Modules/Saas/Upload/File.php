<?php

declare(strict_types=1);

namespace App\Http\Routes\Modules\Saas\Upload;

use App\Http\Interfaces\Routes;
use App\Rules\{HaveZero, ZeroOne, ArrValidator, OrVaild, InArr, CommaVaild, NotZero};


class File implements Routes
{

    public function routes(): array
    {
        return [
            'first' => ["method" => "post", "valid" => ['dir_id', 'file_name', 'file_size', 'file_type', 'dir_link']],
            'schedule' => ["method" => "put", "valid" => ['oid', 'schedule']],
            'getConfig' => ["method" => "get"],
            'list' => ["method" => "get", "valid" => ['dir_link', 'pageSize', 'pageNum', 'keyWord', 'file_type']],
            'del' => ["method" => "delete", "format" => function ($params) {
                $params['real_del'] = (int)$params['real_del'];
                return $params;
            }, "valid" => ['oids', 'real_del']],
        ];
    }


    public function rules(): array
    {
        return  [
            'file_name' => ['required', 'string', 'max:255'],
            'dir_id' => ['required', 'string', new OrVaild([
                ['min:20', 'max:50'],
                [new InArr(['0'], true)],
            ])],
            'dir_link' => [
                'required',
                'string',
                new OrVaild([
                    new CommaVaild(['min:20', 'max:50']),
                    [new InArr(['0', '-1'], true)],
                ])
            ],
            'sizes' => ['required', new HaveZero],
            'file_size' => ['required', new HaveZero],
            'is_common' => ['required', new ZeroOne],
            'oid' => ['required', 'string', 'min:20', 'max:50'],
            'schedule' => ['required', 'numeric', 'min:0', 'max:1'],
            'file_type' => ['string', 'max:255'],
            'keyWord' => ['string', 'max:255'],
            'real_del' => ['required', new ZeroOne],
            'file_list' => [
                'required', 'array', new ArrValidator([
                    'file_name' => ['required', 'string', 'max:255'],
                    'file_type' => ['required', 'string'],
                    'size' => ['required', 'numeric', new NotZero, 'min:0'],
                ])
            ],
            'oids' => ['required', 'array', new ArrValidator(['required', 'string', 'min:20', 'max:50'], [0, 36])]
        ];
    }
}
