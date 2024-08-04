<?php

declare(strict_types=1);

namespace App\Http\Controllers\Dev;

use App\Http\Controllers\BaseController as Controllers;
use App\Services\Dev\{AdminsLoginInfo};
use App\Helpers\Inject;
use App\Dao\Mysql\Dev\OrganiDao\Orm as OrganiDao;
use App\Helpers\Func;

abstract class BaseController extends Controllers
{

    public function getAdminsId()
    {
        return  Inject::get(AdminsLoginInfo::class)->getPrimaryKey();
    }

    public function getOrganiId()
    {
        return  Inject::get(AdminsLoginInfo::class)->getLoginOrganiId();
    }

    public function getOrgani(string|null $key = null)
    {
        return  $key ?  Inject::get(AdminsLoginInfo::class)->getLoginOrgani()[$key] : Inject::get(AdminsLoginInfo::class)->getLoginOrgani();
    }



    public function getAdminsLoginInfo(): AdminsLoginInfo
    {
        return  Inject::get(AdminsLoginInfo::class);
    }

    public function checkOrgani(array $organi_id, bool $checkSql = true): bool
    {
        if (empty($organi_id)) return true;

        $organi_link = $this->getAdminsLoginInfo()->getLoginOrganiLink();

        if ($organi_link === false)  return false;
        else if (is_array($organi_link)) {
            foreach ($organi_id as $val) {
                if (!in_array($val, $organi_link) || $val == $this->getAdminsLoginInfo()->getLoginOrganiId()) return false;
            }
        }

        if ($checkSql && OrganiDao::static()->query()->whereIn("organi_id", $organi_id)->count() !== count($organi_id)) return false;

        return true;
    }


    //排除自己的部门
    public function getOrganiLinkExcludeSelf(): array|bool
    {
        $organi_link = $this->getAdminsLoginInfo()->getLoginOrganiLink();
        if (is_array($organi_link)) $organi_link  = Func::filter($organi_link, fn ($item) => $item != $this->getAdminsLoginInfo()->getLoginOrganiId());
        return  $organi_link;
    }
}
