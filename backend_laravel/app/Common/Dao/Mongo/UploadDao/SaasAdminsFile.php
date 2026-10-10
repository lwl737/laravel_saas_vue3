<?php

declare(strict_types=1);

namespace App\Common\Dao\Mongo\UploadDao;
use App\Common\Models\Mongo\Server\SaasAdminFile as SaasAdminFileModel;

class SaasAdminsFile extends BaseUpload
{

    protected function getModel():string{
        return SaasAdminFileModel::class;
    }

}
