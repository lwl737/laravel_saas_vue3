<?php

declare(strict_types=1);

namespace App\Http\Controllers\Dev\Upload;

use App\Common\Repository\Dev\DevAdminsDirRepository;
use App\Helpers\Output\Json\Success;

class Dir extends BaseController
{

    /**  列表 */
    public function all(DevAdminsDirRepository $repository)
    {

        return $this->output(
            $repository->adminsCreate($this->getAdminsId())->all() ,
            Success::SEL_SUCCESS
        );
    }

    /**  添加 */
    public function add(DevAdminsDirRepository $repository)
    {
        $repository->adminsCreate($this->getAdminsId())->add($this->params);
        return $this->outputEnum(Success::ADD_SUCCESS);
    }

    /**  删除 */
    public function del(DevAdminsDirRepository $repository)
    {

        $repository->adminsCreate($this->getAdminsId())->del($this->params);

        return $this->outputEnum(Success::DEL_SUCCESS);
    }

    /**  修改 */
    public function edit(string $dir_id , DevAdminsDirRepository $repository)
    {
        $repository->adminsCreate($this->getAdminsId())->edit($dir_id , $this->params);
        return $this->outputEnum(Success::EDIT_SUCCESS);
    }
}
