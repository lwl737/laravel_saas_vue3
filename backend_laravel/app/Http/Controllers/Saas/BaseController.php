<?php

declare(strict_types=1);

namespace App\Http\Controllers\Saas;

use App\Http\Controllers\BaseController as Controllers;
use App\Http\Services\Admin\LoginInfo\Saas\ServiceProvider as AdminsLoginInfo;
use App\Helpers\Func;
use App\Common\Repository\Saas\OrganiRepository;

abstract class BaseController extends Controllers
{
    public function getAdminsId():int
    {
        return  app(AdminsLoginInfo::class)->getPrimaryKey();
    }

    public function getOrganiId():int
    {
        return  app(AdminsLoginInfo::class)->getLoginOrganiId();
    }

    public function getOrgani(string|null $key = null)
    {
        return  $key ?  app(AdminsLoginInfo::class)->getLoginOrgani()[$key] : app(AdminsLoginInfo::class)->getLoginOrgani();
    }

    public function getAdminsLoginInfo(): AdminsLoginInfo
    {
        return  app(AdminsLoginInfo::class);
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

        if ($checkSql && app()->make(OrganiRepository::class)->whereIn("organi_id", $organi_id)->count() !== count($organi_id)) return false;

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
