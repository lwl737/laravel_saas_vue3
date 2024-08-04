<?php

declare(strict_types=1);

namespace App\Dao\Mysql\Dev\SaasMenuRelationDao;

use App\Dao\Mysql\Dev\BaseDev;
use App\Models\Mysql\Orm\Dev\SaasMenuRelation;
use Illuminate\Support\Facades\DB;

class Orm extends BaseDev
{

    public function __construct()
    {
        $this->query = new SaasMenuRelation;
    }

    public function getAncestorLinkTitle(int|null $menu_id):null|object
    {
        return   $this->query->join('saas_menu','saas_menu.menu_id','=','saas_menu_relation.menu_ancestor_id')->where("menu_descendant_id", $menu_id)
            ->first([
                DB::raw("IFNULL(GROUP_CONCAT( `dev_saas_menu`.`title` ORDER BY `level` ASC SEPARATOR '-' ),'') as menu_title"),
            ]);
    }


    /**
     * 获得所有祖先link
     *
     * @param integer $menu_id
     * @return array
     */
    public function getAncestorLink(int|null $menu_id): array
    {
        //空字符串就是另起一个分支
        return  !$menu_id ? ["max_level" =>  -1 , "ancestor_link" => "" , "level_link" => ""] : $this->query->where("menu_descendant_id", $menu_id)
            ->first([
                DB::raw("IFNULL(GROUP_CONCAT(DISTINCT `menu_ancestor_id` ORDER BY `level` ASC ),'') as ancestor_link"),
                DB::raw("IFNULL( MAX(`level`) ,  -1 ) as `max_level`"),
                DB::raw("IFNULL(GROUP_CONCAT( `level` ORDER BY `level` ASC ),'') as level_link")
            ])->toArray();
    }

}