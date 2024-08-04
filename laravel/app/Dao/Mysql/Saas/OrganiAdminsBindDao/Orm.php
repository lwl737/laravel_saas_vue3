<?php

declare(strict_types=1);

namespace App\Dao\Mysql\Saas\OrganiAdminsBindDao;

use App\Dao\Mysql\Saas\BaseSaas;
use App\Models\Mysql\Orm\Saas\OrganiAdminsBind;

class Orm extends BaseSaas
{

    public function __construct()
    {
        $this->query = new OrganiAdminsBind;
    }


    public function findAdminsOrganiIds(int $admins_id):array
    {
        return $this->query->where("admins_id", $admins_id)->get(["organi_id"])->map(fn ($item) => $item->organi_id)->toArray();
    }

    public function adminsOrganiIdFirst(int $admins_id)
    {


        return $this->query->join("organi", "organi.organi_id", "organi_admins_bind.organi_id")
            ->where("organi_admins_bind.admins_id", $admins_id)
            ->orderBy('organi.organi_sort', 'desc')
            ->orderBy('organi.organi_id', 'asc')
            ->first(["organi.organi_id"])?->organi_id;
    }


    public function changeBind(array $organi_id, $admins_id)
    {
        foreach ($organi_id as $item) $this->query->insertUpdatedStr(["organi_id" => $item, "admins_id" => $admins_id]);

        $this->query->where("admins_id", $admins_id)->whereNotIn("organi_id", $organi_id)->delete();
    }
}
