<?php

declare(strict_types=1);

namespace App\Common\Dao\Mongo\UploadDao;

use App\Common\Models\Mongo\Server\DevAdminFile as DevAdminFileModel;

class DevAdminsFile extends BaseUpload
{

    protected function getModel():string{
        return DevAdminFileModel::class;
    }

}
