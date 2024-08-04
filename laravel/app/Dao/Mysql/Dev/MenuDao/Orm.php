<?php

declare(strict_types=1);

namespace App\Dao\Mysql\Dev\MenuDao;

use App\Dao\Mysql\Dev\BaseDev;
use App\Models\Mysql\Orm\Dev\Menu;
use App\Models\Mysql\Orm\Dev\MenuRelation;
use Illuminate\Support\Facades\DB;

class Orm extends BaseDev
{

    public function __construct()
    {
        $this->query = new Menu;
    }


    public function checkPath($params)
    {
        if (isset($params['menu_id'])) $this->query = $this->query->where('menu_id', '<>', $params["menu_id"]);
        $this->query = $this->query->where("path", $params["path"]);
        return $this;
    }


    public function add(array $params, int $maxLevel, array $ancestor_link , array $level_link)
    {
        $this->query->menu_pid = $params['menu_pid'] == 0 ? null : $params['menu_pid'];
        $this->query->path = $params['path'];
        $this->query->name = $params['name'];
        $this->query->redirect = $params['redirect'];
        $this->query->component = $params['component'];
        $this->query->icon = $params['icon'];
        $this->query->title = $params['title'];
        $this->query->isLink = $params['isLink'];
        $this->query->isFull = $params['isFull'];
        $this->query->isAffix = $params['isAffix'];
        $this->query->isKeepAlive = $params['isKeepAlive'];
        $this->query->isHide = $params['isHide'];
        if (isset($params['menu_sort'])) $this->query->menu_sort = $params['menu_sort'];
        if (isset($params['auth_id'])) $this->query->auth_id = $params['auth_id'];

        DB::transaction(function () use ($ancestor_link, $maxLevel , $level_link) {
            $this->query->save();
            $menu_id = $this->query->getPrimaryKeyVal();
            array_push($ancestor_link, $menu_id);
            (new MenuRelation)->insertAll($ancestor_link, [], fn ($item , $index ) => ["menu_ancestor_id" =>  $item, "menu_descendant_id" => $menu_id, "level" =>  $index >= count($level_link) ? $maxLevel + 1 : $level_link[$index] ]);
        });
    }

    public function allConfig()
    {
        $this->query =  $this->query->where('auth_id', null)->orderBy('menu_sort', 'desc')->orderBy('menu_id', 'desc');
        return $this;
    }


    public function del(array $params)
    {
        return  $this->query->where('menu_id', $params['menu_id'])->delete();
    }

    public function edit(array $params)
    {
        return $this->query->where('menu_id', $params['menu_id'])->update($params);
    }
}
