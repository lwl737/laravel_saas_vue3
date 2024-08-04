<?php

declare(strict_types=1);

namespace App\Http\Controllers\Dev\Saas\Develop;

use App\Dao\Mysql\Dev\SaasMenuDao\Orm as SaasMenuDao;
use App\Dao\Mysql\Dev\SaasMenuRelationDao\Orm as SaasMenuRelationDao;
use App\Helpers\Output\Json\Success;
use App\Helpers\Output\Json\Dev;
use App\Helpers\Func;

class SaasMenu extends BaseController
{
    /* 添加 */
    public function add()
    {
        $res =  SaasMenuDao::static()->checkPath($this->params)->query()->first(['title']);

        if ($res)  return $this->outputEnum(Dev::PAGES_IS_EXIST, ['menu_title' => $res->title]);

        ["max_level" => $max_level, "ancestor_link" => $ancestor_link , "level_link" => $level_link] = SaasMenuRelationDao::static()->getAncestorLink(
            (int)$this->params['menu_pid']
        );

        $ancestor_link = $ancestor_link != "" ?  explode(",", $ancestor_link) : [];
        $level_link = $level_link != "" ?  explode(",", $level_link) : [];

        SaasMenuDao::static()->add($this->params,  (int) $max_level,  $ancestor_link , $level_link);

        return $this->outputEnum(Success::ADD_SUCCESS);
    }
    /* 列表 */
    public function all()
    {
        return $this->output(Func::createTree(
            SaasMenuDao::static()->allConfig()->query()->get(
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

        SaasMenuDao::static()->del($this->params);
        return $this->outputEnum(Success::DEL_SUCCESS);
    }
    /* 编辑 */
    public function edit()
    {
        $res =  SaasMenuDao::static()->checkPath($this->params)->query()->first(['title']);

        if ($res)  return $this->outputEnum(Dev::PAGES_IS_EXIST, ['menu_title' => $res->title]);

        SaasMenuDao::static()->edit($this->params);

        return $this->outputEnum(Success::EDIT_SUCCESS);
    }
}
