<?php

declare(strict_types=1);

namespace App\Common\Repository\Saas;

use App\Common\Dao\Mongo\UploadDao\SaasAdminsDir as SaasAdminsDirDao;

class SaasAdminsDirRepository extends BaseRepository
{

    public function __construct(
        protected SaasAdminsDirDao $dao
    ) {}

    public function adminsCreate(int $admins_id , int $after)
    {
        $this->dao = $this->dao->setAdminsId($admins_id)->createRange($admins_id, 1000 , $after);
        return $this;
    }


    public function add(array $params)
    {

        return $this->dao->insertOneDir($params);
    }


    public function del(array $params)
    {
        return $this->dao->deleteDir($params);
    }

    public function edit(string $dir_id, array $params)
    {
        return $this->dao->editDir($dir_id,  $params);
    }

    public function all()
    {
        return $this->dao->allDir();
    }
}
