<?php

declare(strict_types=1);
namespace App\Utils\Permissions;
use App\Helpers\Func;
use App\Models\Mysql\Orm\BaseOrm;

class Menu{

    public function __construct(
        public readonly BaseOrm $menuModel
    )
    { }

    public function treeData()
    {
        $result = $this->menuModel->where('auth_id',null)->with(['auth'])->orderBy('menu_sort','desc')->orderBy('menu_id','desc')->get(
            [
                'menu_id',
                'title',
                'menu_pid',
            ]
        )->toArray();
        return  Func::createTree($result, 'menu_id', 'menu_pid', 'children', function ($item) {
            if ($item['menu_pid']  === null) $item['menu_pid'] =  0;
            return $item;
        });
    }

}
