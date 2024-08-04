<?php

declare(strict_types=1);

namespace App\Http\Controllers\Dev\Develop;

use App\Dao\Mysql\Dev\MenuDao\Orm as MenuDao;
use App\Dao\Mysql\Dev\MenuRelationDao\Orm as MenuRelationDao;
use App\Helpers\Output\Json\Success;
use App\Helpers\Output\Json\Dev;
use App\Helpers\Func;

class Menu extends BaseController
{
    /* 添加 */
    public function add()
    {
        $res =  MenuDao::static()->checkPath($this->params)->query()->first(['name', 'menu_id']);

        if ($res)  return $this->outputEnum(Dev::MENU_EXIST, ['title' => $res->name, "menu_id" => $res->menu_id]);

        //祖先link
        ["max_level" => $max_level, "ancestor_link" => $ancestor_link, "level_link" => $level_link] = MenuRelationDao::static()->getAncestorLink(
            (int)$this->params['menu_pid']
        );

        $ancestor_link = $ancestor_link != "" ?  explode(",", $ancestor_link) : [];
        $level_link = $level_link != "" ?  explode(",", $level_link) : [];



        MenuDao::static()->add($this->params, (int)$max_level, (array)$ancestor_link , $level_link);

        return $this->outputEnum(Success::ADD_SUCCESS);
    }
    /* 列表 */
    public function all()
    {
        return $this->output(Func::createTree(
            MenuDao::static()->allConfig()->query()->get(
                [
                    'menu_id',
                    'menu_pid',
                    'path',
                    'name',
                    'redirect',
                    'component',
                    'menu_sort',
                    'icon',
                    'title',
                    'isLink',
                    'isHide',
                    'isFull',
                    'isAffix',
                    'isKeepAlive'
                ]
            )->toArray(),
            'menu_id',
            'menu_pid',
            'children',
            function ($item) {
                if ($item["menu_pid"] === null)  $item["menu_pid"] = 0;
                return $item;
            }
        ), Success::SEL_SUCCESS);
    }
    /* 删除 */
    public function del()
    {

        MenuDao::static()->del($this->params);
        return $this->outputEnum(Success::DEL_SUCCESS);
    }
    /* 编辑 */
    public function edit()
    {
        $res =   MenuDao::static()->checkPath($this->params)->query()->first(['name', 'menu_id']);

        if ($res)  return $this->outputEnum(Dev::MENU_EXIST, ['title' => $res->name, "menu_id" => $res->menu_id]);

        MenuDao::static()->edit($this->params);

        return $this->outputEnum(Success::EDIT_SUCCESS);
    }
}
