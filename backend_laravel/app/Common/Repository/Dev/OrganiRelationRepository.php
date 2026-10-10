<?php

declare(strict_types=1);

namespace App\Common\Repository\Dev;

use App\Common\Dao\Mysql\Dev\OrganiRelationDao\Orm as OrganiRelationDao;
use App\Helpers\Func;

class OrganiRelationRepository extends BaseRepository
{

    public function __construct(
        protected OrganiRelationDao $dao
    ) {}


    public function checkIsEnable(int $admins_id, int $organi_id, int $role_id):bool|array{
        if($organi_id === -1 && $role_id <= 0) return true;

        if($role_id > 0){
            $this->dao =  $this->dao->joinOrganiAdminsBind($admins_id);
        }
        return $this->dao->checkIsEnable($organi_id);
    }


    public function getAncestorLink(?int $organi_id){
        return $this->dao->getAncestorLink($organi_id);
    }



}
