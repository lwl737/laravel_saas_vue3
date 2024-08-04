<?php

declare(strict_types=1);
namespace App\Dao\Mongo;
use App\Dao\BaseDao;
abstract class BaseMongo extends BaseDao{

    public function page(array $data){
        return [
            "skip" => (int)(($data['pageNum'] - 1) * $data['pageSize']),
            "limit" => (int)$data['pageSize']
        ];
    }


}
