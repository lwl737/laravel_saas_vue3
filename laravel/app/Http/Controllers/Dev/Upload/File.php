<?php

declare(strict_types=1);
namespace App\Http\Controllers\Dev\Upload;


use App\Dao\Mongo\Upload\DevAdminsFile;
use App\Helpers\Output\Json\Success;

class File extends BaseController
{

    /* 第一次上传文件 */
    public function first()
    {


        return $this->output(
            DevAdminsFile::static([$this->getAdminsId()])->first($this->params),
            Success::UPLOAD_FIRST_SUCCESS
        )  ;

    }

    /* 修改文件上传进度 */
    public function schedule()
    {
        DevAdminsFile::static([$this->getAdminsId()])->schedule($this->params);
        return $this->outputEnum( Success::EDIT_SUCCESS )  ;
    }


    /* 获取配置 */
    public function getConfig()
    {
        return $this->output(
            DevAdminsFile::static([$this->getAdminsId()])->getConfig(),
            Success::SEL_SUCCESS
        );
    }

    /* 文件列表 */
    public function list()
    {
        return $this->output(
            DevAdminsFile::static([$this->getAdminsId()])->list($this->params),
            Success::SEL_SUCCESS
        );
    }

    public function del()
    {
        DevAdminsFile::static([$this->getAdminsId()])->del($this->params);
        return $this->outputEnum(
            Success::DEL_SUCCESS
        );
    }
}
