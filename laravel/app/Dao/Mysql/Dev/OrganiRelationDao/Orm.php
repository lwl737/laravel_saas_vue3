<?php

declare(strict_types=1);

namespace App\Dao\Mysql\Dev\OrganiRelationDao;

use App\Dao\Mysql\Dev\BaseDev;
use App\Models\Mysql\Orm\Dev\OrganiRelation;
use Illuminate\Support\Facades\DB;

class Orm extends BaseDev
{

    public function __construct()
    {
        $this->query = new OrganiRelation;
    }


    /**
     * 获得所有祖先link
     *
     * @param integer $organi_id
     * @return array
     */
    public function getAncestorLink(int|null $organi_id): array
    {
        //空字符串就是另起一个分支
        return $organi_id === -1 || !$organi_id ? ["max_level" =>  -1 , "ancestor_link" => "" , "level_link" => ""] : $this->query->where("organi_descendant_id", $organi_id)
            ->first([
                DB::raw("IFNULL(GROUP_CONCAT( `organi_ancestor_id` ORDER BY `level` ASC ),'') as ancestor_link"),
                DB::raw("IFNULL(GROUP_CONCAT( `level` ORDER BY `level` ASC ),'') as level_link"),
                DB::raw("IFNULL( MAX(`level`) ,  -1 ) as `max_level`")
            ])->toArray();
    }


     /**
     * 检测部门是否合法
     * @param integer $admins_id
     * @param integer $organi_id
     * @return void
     */
    public function checkIsEnable(int $admins_id , int $organi_id , int $role_id ):bool|array{

        if($organi_id === -1 && $role_id <= 0) return true;

        if($role_id > 0){
            $this->query =  $this->query
            ->join("organi_admins_bind","organi_relation.organi_ancestor_id","=","organi_admins_bind.organi_id")
            ->where("organi_admins_bind.admins_id" , $admins_id );
        }

        $organi_ids =  $this->query
        ->where("organi_relation.organi_ancestor_id" , $organi_id)
        ->get(["organi_relation.organi_descendant_id"])
        ->map(fn($item)=>$item->organi_descendant_id);


        return !empty($organi_ids) ? $organi_ids->toArray() : false;
    }


}
