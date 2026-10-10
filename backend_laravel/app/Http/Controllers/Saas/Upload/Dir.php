<?php

declare(strict_types=1);

namespace App\Http\Controllers\Saas\Upload;

use App\Helpers\Output\Json\Success;
use App\Common\Repository\Saas\SaasAdminsDirRepository;

class Dir extends BaseController
{

    /**  列表 */
    public function all(SaasAdminsDirRepository $repository)
    {

        return $this->output(
            $repository->adminsCreate($this->getAdminsId() , $this->getAdminsLoginInfo()->getTenantId() )->all() ,
            Success::SEL_SUCCESS
        );
    }

    /**  添加 */
    public function add(SaasAdminsDirRepository $repository)
    {
        $repository->adminsCreate($this->getAdminsId(), $this->getAdminsLoginInfo()->getTenantId())->add($this->params);
        return $this->outputEnum(Success::ADD_SUCCESS);
    }

    /**  删除 */
    public function del(SaasAdminsDirRepository $repository)
    {

        $repository->adminsCreate($this->getAdminsId(), $this->getAdminsLoginInfo()->getTenantId())->del($this->params);

        return $this->outputEnum(Success::DEL_SUCCESS);
    }

    /**  修改 */
    public function edit(string $dir_id , SaasAdminsDirRepository $repository)
    {
        $repository->adminsCreate($this->getAdminsId(), $this->getAdminsLoginInfo()->getTenantId())->edit($dir_id , $this->params);
        return $this->outputEnum(Success::EDIT_SUCCESS);
    }
}
