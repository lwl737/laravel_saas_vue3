<?php

declare(strict_types=1);

namespace App\Common\Dao\Mysql\Dev\OrganiDao;

use App\Common\Models\Mysql\Orm\Dev\Organi;
use App\Common\Dao\Mysql\Dev\BaseDev;
use Illuminate\Support\Facades\DB;

class Orm extends BaseDev
{
    protected function getModel(): string
    {
        return Organi::class;
    }

    public function tree(
        array|null|int  $organi_ids ,
    ) {

        $query = $this->query->join("organi_relation", "organi_relation.organi_descendant_id", "=", "organi.organi_id");

        if ($organi_ids !== null){
            if(is_array($organi_ids))  $query = $query->whereIn("organi_relation.organi_ancestor_id", $organi_ids);
            else{
                //传数字时
                $query = $query->where("organi_relation.organi_ancestor_id", $organi_ids);
            }
        }

       return $query
            ->orderBy("organi.organi_sort", 'desc')
            ->orderBy("organi.organi_id", "asc")
            ->groupBy("organi_relation.organi_descendant_id")
            ->get([
                DB::raw("MAX(dev_organi.organi_id) as `organi_id`"),
                DB::raw("MAX(dev_organi.organi_pid) as `organi_pid`"),
                DB::raw("MAX(dev_organi.organi_name) as `organi_name`"),
                DB::raw("MAX(dev_organi.organi_sort) as `organi_sort`"),
                DB::raw("MAX(dev_organi_relation.organi_ancestor_id) as `organi_ancestor_id` "),
                DB::raw("MAX(`dev_organi_relation`.`level`) as `level`")
            ]);

    }




    public function whereOrganiId(int $organi_id)
    {
        $this->query = $this->query->where("organi.organi_id", $organi_id);
        return $this;
    }

}
