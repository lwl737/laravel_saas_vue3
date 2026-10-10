<?php

declare(strict_types=1);
namespace App\Common\Dao\Mongo\UploadDao;
use App\Common\Models\Mongo\Server\DevAdminsDir as DevAdminsDirModel;

class DevAdminsDir extends BaseUpload{

    protected function getModel():string{
        return DevAdminsDirModel::class;
    }




}
