<?php

declare(strict_types=1);

namespace App\Models\Mysql\Trait;

use  App\Helpers\Inject;
use  App\Services\Dev\AdminsLoginInfo;

trait Organi
{

    //添加人字段
    const INSERT_ADMINS_ID_KEY = "insert_admins_id";

    //添加部门字段
    const INSERT_ORGANI_ID_KEY = "insert_organi_id";


    public function scopeOrgani($builder)
    {
        $adminsLoginInfo = Inject::bindGet(AdminsLoginInfo::class);

        if ($adminsLoginInfo->getLoginOrganiLink() !== false) {

            $admins_id = $adminsLoginInfo->getPrimaryKey();

            $organi_id = $adminsLoginInfo->getLoginOrganiId() <= 0 ? 0 : $adminsLoginInfo->getLoginOrganiId();

            $organi_link = $adminsLoginInfo->getLoginOrganiLink();

            if (is_array($organi_link) && !empty($organi_link)) {
                $builder->where(function ($query) use ($organi_link) {
                    $query->where(function ($query)  use ($organi_link) {
                        foreach ($organi_link as $val) $query->orWhere($this->tableName . "." . $this::INSERT_ORGANI_ID_KEY, $val);
                    });
                })->where(function ($query) use ($organi_id, $admins_id) {
                    $query->orWhere($this->tableName . "." . $this::INSERT_ORGANI_ID_KEY, "<>", $organi_id)
                        ->orWhere($this->tableName . "." . $this::INSERT_ADMINS_ID_KEY, $admins_id);
                });
            }
        }
        return $builder;
    }

    public function saveOrgani()
    {
        $adminsLoginInfo = Inject::bindGet(AdminsLoginInfo::class);
        if ($adminsLoginInfo->getLoginOrganiLink() !== false) {
            $insert_admins_id_key = self::INSERT_ADMINS_ID_KEY;
            $insert_organi_id_key = self::INSERT_ORGANI_ID_KEY;
            $this->$insert_admins_id_key = $adminsLoginInfo->getPrimaryKey();
            $this->$insert_organi_id_key =  $adminsLoginInfo->getLoginOrganiId() <= 0 ? 0 : $adminsLoginInfo->getLoginOrganiId();
        }
        $this->save();
    }
}
