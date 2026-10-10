<?php

declare(strict_types=1);
namespace App\Common\Dao\Mongo\UploadDao;
use App\Common\Models\Mongo\Server\SaasAdminsDir as SaasAdminsDirModel;

class SaasAdminsDir extends BaseUpload{
    protected function getModel():string{
        return SaasAdminsDirModel::class;
    }


}
