<?php

declare(strict_types=1);

namespace App\Common\Dao\Mysql\Dev\MenuDao;

use App\Common\Dao\Mysql\Dev\BaseDev;
use App\Common\Models\Mysql\Orm\Dev\Menu;

class Orm extends BaseDev
{

    protected function getModel(): string
    {
        return Menu::class;
    }


    public function checkPath(array $params, array|null $field ,?int $menu_id = null)
    {
        if ($menu_id) $this->query = $this->query->where('menu_id', '<>', $menu_id);
        $this->query = $this->query->where("path", $params["path"]);
        return $field ? $this->query->first($field) : $this;
    }

    public function allConfig()
    {
        $this->query =  $this->query->where('auth_id', null)->orderBy('menu_sort', 'desc')->orderBy('menu_id', 'desc');
        return $this;
    }

    public function all()
    {
        return $this->allConfig()->query()->get(
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
        );
    }


    public function rolePages(array|null $menu_id){
        $this->query  = $this->query->where('auth_id',null);
        if($menu_id !== null)   $this->query  =   $this->query->whereIn('menu_id',$menu_id);
        return $this;
    }
}
