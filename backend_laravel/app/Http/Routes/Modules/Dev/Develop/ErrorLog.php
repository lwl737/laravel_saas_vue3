<?php

declare(strict_types=1);

namespace App\Http\Routes\Modules\Dev\Develop;

use App\Http\Interfaces\Routes;
use App\Rules\{Ymd};


class ErrorLog implements Routes
{

    public function routes(): array
    {
        return [
            'list' => [
                "format" => function ($params) {
                    $params['ymd']      = isset($params['ymd']) ?  str_ireplace('-', "", $params['ymd'])  : date('Ymd');
                    $params['pageSize'] = (int)$params['pageSize'];
                    $params['pageNum']  = (int)$params['pageNum'];
                    return $params;
                },
                "valid" => ['pageSize', 'pageNum', 'ymd']
            ],
            'date' => [],
        ];
    }


    public function rules(): array
    {
        return  [
            'ymd' => [new Ymd],
        ];
    }
}
