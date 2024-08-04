<?php

declare(strict_types=1);

namespace App\Dao\Mysql\Saas\OrganiDao;

use App\Models\Mysql\Orm\Saas\Organi;
use App\Models\Mysql\Orm\Saas\OrganiRelation;
use App\Dao\Mysql\Saas\BaseSaas;
use Illuminate\Support\Facades\DB;
use App\Helpers\Func;

class Orm extends BaseSaas
{
    public function __construct()
    {
        $this->query = new Organi;
    }

    public function add(array $params , array $ancestor_link , int $maxLevel , array $level_link)
    {

        $this->query->organi_pid = $params["organi_pid"];

        $this->query->organi_name = $params["organi_name"];

        $this->query->organi_sort = $params["organi_sort"];

        $organiRelationModel = new OrganiRelation;

        DB::transaction(function () use ($organiRelationModel, $ancestor_link , $maxLevel , $level_link) {
            $this->query->save();
            $organi_id = $this->query->getPrimaryKeyVal();
            array_push( $ancestor_link , $organi_id );
            $organiRelationModel->insertAll($ancestor_link , [] , fn($item , $index)=> [ "organi_ancestor_id" =>  $item , "organi_descendant_id" => $organi_id , "level" => $index >= count($level_link) ?  $maxLevel + 1 : (int)$level_link[$index]  ]);
        });
    }

    public function edit(array $params){
        return $this->query->where("organi_id",$params["organi_id"])->update([
               "organi_name" => $params["organi_name"],
               "organi_sort" => $params["organi_sort"]
        ]);
    }




    public function tree(
        array|null|int  $organi_ids ,
        bool $has_top = true
    ) {

        $this->query = $this->query->join("organi_relation", "organi_relation.organi_descendant_id", "=", "organi.organi_id");

        if ($organi_ids !== null){
            if(is_array($organi_ids))  $this->query = $this->query->whereIn("organi_relation.organi_ancestor_id", $organi_ids);
            else{
                //传数字时
                $this->query = $this->query->where("organi_relation.organi_ancestor_id", $organi_ids);
            }
        }

        $top_level = [];

        $all = $this->query
            ->orderBy("organi.organi_sort", 'desc')
            ->orderBy("organi.organi_id", "asc")
            ->get([
                "organi.organi_id",
                "organi.organi_pid",
                "organi.organi_name",
                "organi.organi_sort",
                "organi_relation.organi_ancestor_id",
                "organi_relation.level",
            ])->map(function ($item) use ($organi_ids, &$top_level , $has_top) {
                if ($organi_ids === null) {
                    if($item->organi_pid === null )   $item->organi_pid =  $has_top  ? -1 : 0;
                } else if (!isset($top_level[$item->organi_ancestor_id]) ||  $item->level < $top_level[$item->organi_ancestor_id]["level"]) {
                    $top_level[$item->organi_ancestor_id] = ["organi_id" => $item->organi_id, "level" =>  $item->level];
                }
                return $item;
            })->toArray();

        if ($organi_ids === null && $has_top) {
            array_unshift($all, [
                "organi_id" => -1,
                "organi_pid" => 0,
                "is_all" => true ,
                "organi_name" => Func::getAllOrganiName(),
            ]);
        }

        $top_level_organi = [];

        foreach($top_level as $val) $top_level_organi[] = $val["organi_id"];

        $tree = Func::createTree($all, 'organi_id', 'organi_pid', func: function ($item) use ($top_level_organi) {
            if(in_array($item["organi_id"],$top_level_organi))  $item["organi_pid"] = 0;
            return Func::formExtractData($item , ["organi_id","organi_pid","organi_name","organi_sort" , "is_all"]) ;
        });

        return is_numeric($organi_ids) ? array_map( function($item){
            $item["organi_pid"] = 0;
            return $item;
        } , $tree[0]['children'] ?? [] ) : $tree;


    }


    public function del(int $organi_id){
        return $this->query->where('organi_id',$organi_id)->delete();
    }


    public function whereOrganiId(int $organi_id)
    {
        $this->query = $this->query->where("organi.organi_id", $organi_id);
        return $this;
    }

}
