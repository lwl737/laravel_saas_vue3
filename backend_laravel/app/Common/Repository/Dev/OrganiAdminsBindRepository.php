<?php
declare(strict_types=1);

namespace App\Common\Repository\Dev;
// use App\Common\Dao\Mysql\Dev\RoleDao\Orm as RoleDao;
// use App\Dao\Mysql\Dev\RoleDao\Enums\RoleEnable ;
// use App\Helpers\Output\Json\Dev;

use App\Common\Dao\Mysql\Dev\OrganiAdminsBindDao\Orm as OrganiAdminsBindDao;

class OrganiAdminsBindRepository extends BaseRepository{


    public function __construct(
        protected OrganiAdminsBindDao $dao
    )
    { }


    public function findAdminsOrganiIds(int $admins_id):array{
      return $this->dao->findAdminsOrganiIds($admins_id);
    }




}
