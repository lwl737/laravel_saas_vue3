<?php

declare(strict_types=1);
namespace App\Dao\Mongo\Upload;
use App\Models\Mongo\Server\DevAdminsDir as DevAdminsDirModel;

class DevAdminsDir extends BaseUpload{

    public function __construct(
        int $admins_id
    )
    {
        $this->admins_id = $admins_id;
        $this->mongo = DevAdminsDirModel::createRange($this->admins_id,1000);
    }

    public function add( array $params)
    {

        return $this->insertOneDir($params);
    }


    public function del(array $params)
    {
        return $this->deleteDir($params);
    }

    public function edit(array $params)
    {
        return $this->editDir($params);
    }

    public function all()
    {

        return $this->allDir();
    }

}
