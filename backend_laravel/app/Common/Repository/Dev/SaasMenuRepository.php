<?php
declare(strict_types=1);

namespace App\Common\Repository\Dev;
use App\Common\Dao\Mysql\Dev\SaasMenuDao\Orm as SaasMenuDao;
use App\Helpers\Func;
class SaasMenuRepository extends BaseRepository{

    public function __construct(
        protected SaasMenuDao $dao
    ) {}




    public function add(array $params, int $maxLevel, array $ancestor_link , array $level_link)
    {
        $params['menu_pid'] = $params['menu_pid'] == 0 ? null : $params['menu_pid'];

        $this->dao->transaction(function () use ($params , $ancestor_link, $maxLevel , $level_link) {
            $menu_id = $this->addPages($params);
            array_push($ancestor_link, $menu_id);
            app()->make(SaasMenuRelationRepository::class)->insertAll($ancestor_link, [], fn ($item , $index ) => ["menu_ancestor_id" =>  $item, "menu_descendant_id" => $menu_id, "level" =>  $index >= count($level_link) ? $maxLevel + 1 : $level_link[$index] ]);
        });
    }

    public function addPages(array $params){
       return $this->dao->add($params);
    }

    public function tree(){
        return Func::createTree(
            $this->dao->all()->toArray(),
            'menu_id',
            'menu_pid',
            'children',
            function ($item) {
                if ($item["menu_pid"] === null)  $item["menu_pid"] = 0;
                return $item;
            }
        );
    }

    public function pages(array|null $menu_id , array $auth):array{
        return $this->dao->rolePages($menu_id)->query()->orderBy('menu_sort','desc')->orderBy('menu_id','desc')->get(
            [
                'menu_id',
                'title',
                'menu_pid',
            ]
        )->map(function($item) use ($auth){
            $dataAuth = [];
            foreach($auth as $val){
                if($val['menu_id'] === $item->menu_id)  $dataAuth[] = $val;
            }
            $item->auth = $dataAuth;
            return $item;
        })->toArray();
    }

}
