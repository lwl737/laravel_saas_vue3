<?php

declare(strict_types=1);

namespace App\Http\Controllers\Saas\Upload;

use App\Dao\Mongo\Upload\SaasAdminsDir;
use App\Helpers\Output\Json\Success;

class Dir extends BaseController
{

    /**  列表 */
    public function all()
    {

        return $this->output(
            SaasAdminsDir::static(
                [$this->getAdminsId(),(string)$this->getAdminsLoginInfo()->getTenantId()],
            )->all(),
            Success::SEL_SUCCESS
        );
    }

    /**  添加 */
    public function add()
    {
        SaasAdminsDir::static(
            [$this->getAdminsId(),(string)$this->getAdminsLoginInfo()->getTenantId()],
        )->add($this->params);

        return $this->outputEnum(Success::ADD_SUCCESS);
    }

    /**  删除 */
    public function del()
    {

        SaasAdminsDir::static(
            [$this->getAdminsId(),(string)$this->getAdminsLoginInfo()->getTenantId()],
        )->del($this->params);

        return $this->outputEnum(Success::DEL_SUCCESS);
    }

    /**  修改 */
    public function edit()
    {

        SaasAdminsDir::static(
            [$this->getAdminsId(),(string)$this->getAdminsLoginInfo()->getTenantId()],
        )->edit($this->params);

        return $this->outputEnum(Success::EDIT_SUCCESS);
    }
}
