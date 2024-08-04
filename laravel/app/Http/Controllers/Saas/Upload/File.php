<?php

declare(strict_types=1);
namespace App\Http\Controllers\Saas\Upload;


use App\Dao\Mongo\Upload\SaasAdminsFile;
use App\Helpers\Output\Json\Success;

class File extends BaseController
{

    /* 第一次上传文件 */
    public function first()
    {


        return $this->output(
            SaasAdminsFile::static(  [$this->getAdminsId(),(string)$this->getAdminsLoginInfo()->getTenantId()])->first($this->params),
            Success::UPLOAD_FIRST_SUCCESS
        )  ;

    }

    /* 修改文件上传进度 */
    public function schedule()
    {
        SaasAdminsFile::static([$this->getAdminsId(),(string)$this->getAdminsLoginInfo()->getTenantId()])->schedule($this->params);
        return $this->outputEnum( Success::EDIT_SUCCESS )  ;
    }


    /* 获取配置 */
    public function getConfig()
    {
        return $this->output(
            SaasAdminsFile::static([$this->getAdminsId(),(string)$this->getAdminsLoginInfo()->getTenantId()])->getConfig(),
            Success::SEL_SUCCESS
        );
    }

    /* 文件列表 */
    public function list()
    {
        return $this->output(
            SaasAdminsFile::static([$this->getAdminsId(),(string)$this->getAdminsLoginInfo()->getTenantId()])->list($this->params),
            Success::SEL_SUCCESS
        );
    }

    public function del()
    {
        SaasAdminsFile::static([$this->getAdminsId(),(string)$this->getAdminsLoginInfo()->getTenantId()])->del($this->params);
        return $this->outputEnum(
            Success::DEL_SUCCESS
        );
    }
}
