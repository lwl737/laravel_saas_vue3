<?php

declare(strict_types=1);

namespace App\Http\Controllers\Dev\Upload;

use App\Dao\Mongo\Upload\DevAdminsDir;
use App\Helpers\Output\Json\Success;

class Dir extends BaseController
{

    /**  列表 */
    public function all()
    {

        return $this->output(
            DevAdminsDir::static(
                [$this->getAdminsId()]
            )->all(),
            Success::SEL_SUCCESS
        );
    }

    /**  添加 */
    public function add()
    {
        DevAdminsDir::static(
            [$this->getAdminsId()]
        )->add($this->params);

        return $this->outputEnum(Success::ADD_SUCCESS);
    }

    /**  删除 */
    public function del()
    {

        DevAdminsDir::static(
            [$this->getAdminsId()]
        )->del($this->params);

        return $this->outputEnum(Success::DEL_SUCCESS);
    }

    /**  修改 */
    public function edit()
    {

        DevAdminsDir::static(
            [$this->getAdminsId()]
        )->edit($this->params);

        return $this->outputEnum(Success::EDIT_SUCCESS);
    }
}
