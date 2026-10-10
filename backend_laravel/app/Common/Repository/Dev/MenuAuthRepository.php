<?php

declare(strict_types=1);

namespace App\Common\Repository\Dev;

use App\Common\Dao\Mysql\Dev\MenuAuthDao\Orm as MenuAuthDao;

class MenuAuthRepository extends BaseRepository
{
    public function __construct(
        protected MenuAuthDao $dao
    ) {}

    public function list(array $params)
    {
        $query = $this->dao->whereConfig($params);

        return [
            "total" => $query->query()->count(),
            "list" => $query->withAll()->listOrderBy($params)->query()->get()
        ];
    }

    public function roleAuth(array $params):array{

        $query = $this->dao->whereConfig($params);

        return $query->query()->orderBy('auth_sort','desc')
        ->orderBy('auth_id','desc')
        ->get(['auth_id','auth_name','menu_id'])
        ->toArray();
        // $this->dao->query()->orderBy('auth_sort','desc')
        // ->orderBy('auth_id','desc')
        // ->get(['auth_id','auth_name','menu_id'])
        // ->toArray();


    }
}
