<?php

declare(strict_types=1);

namespace App\Common\Repository\Dev;

use App\Common\Dao\Mysql\Dev\OrganiDao\Orm as OrganiDao;
use App\Helpers\Func;
use Illuminate\Support\Facades\DB;

class OrganiRepository extends BaseRepository
{

    public function __construct(
        protected OrganiDao $dao
    ) {}

    public function tree(
        array|null|int  $organi_ids,
        bool $has_top = true
    ) {


        $all = $this->dao->tree($organi_ids)->map(function ($item) use ($organi_ids , $has_top) {
            if ($organi_ids === null && $item->organi_pid === null)     $item->organi_pid =  $has_top  ? -1 : 0;
            return $item;
        })->toArray();
        $top_level_organi = [];
        if ($organi_ids === null && $has_top) {
            array_unshift($all, [
                "organi_id" => -1,
                "organi_pid" => 0,
                "is_all" => true,
                "organi_name" => Func::getAllOrganiName(),
            ]);
        }else{
            $top_level_organi = $this->findTopLevel($all);
        }


        $tree = Func::createTree($all, 'organi_id', 'organi_pid', func: function ($item) use ($top_level_organi) {
            if (in_array($item["organi_id"], $top_level_organi))  $item["organi_pid"] = 0;
            return Func::formExtractData($item, ["organi_id", "organi_pid", "organi_name", "organi_sort", "is_all"]);
        });


        return is_numeric($organi_ids) ? array_map(function ($item) {
            $item["organi_pid"] = 0;
            return $item;
        }, $tree[0]['children'] ?? []) : $tree;
    }

    private function findTopLevel(array $data){
        $top_level_organi = [];
        foreach($data as $val){
            if($val["organi_pid"] === null){
                $top_level_organi[] = $val['organi_id'];
                continue;
            }
            $checkHas = false;
            foreach($data as $vals){
                if($val["organi_pid"] == $vals["organi_id"]){
                    //有父级
                    $checkHas = true;
                    break;
                }
            }

            if(!$checkHas) $top_level_organi[] = $val['organi_id'];
        }
        return $top_level_organi;
    }


    public function organiCount(array $organi_id)
    {
        return $this->dao->getByCount($organi_id);
    }


    public function add(array $params, array $ancestor_link, int $maxLevel, array $level_link)
    {

        DB::transaction(function () use ($params, $ancestor_link, $maxLevel, $level_link) {
            $organi_id = $this->dao->add([
                "organi_pid" => $params["organi_pid"],
                "organi_name" => $params["organi_name"],
                "organi_sort" => $params["organi_sort"],
            ]);
            array_push($ancestor_link, $organi_id);
            app()->make(OrganiRelationRepository::class)->insertAll($ancestor_link, [], fn($item, $index) => ["organi_ancestor_id" =>  $item, "organi_descendant_id" => $organi_id, "level" => $index >= count($level_link) ?  $maxLevel + 1 : (int)$level_link[$index]]);
        });
    }
}
