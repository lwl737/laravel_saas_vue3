<?php

declare(strict_types=1);

namespace App\Http\Controllers\Dev\Upload;

use App\Helpers\Output\Json\Success;
use App\Common\Repository\Dev\DevAdminsFileRepository;
use App\Services\Settings\ServiceProvider;
use App\Helpers\Output\Json\Error;

class File extends BaseController
{

    /* 第一次上传文件 */
    public function first(
        DevAdminsFileRepository $devAdminsFileRepository
    ) {
        $settings = ServiceProvider::dev()->getItem(
            ["upload_max_size", "upload_slice_size", "upload_file_type", "upload_check_file_type", "library_max_capacity"]
        );

        $upload_file_type = array_map(fn($item) => explode(',', $item['type']), $settings["upload_file_type"]);

        if ($settings['upload_check_file_type'] === 1) {

            $upload_file_type_news = [];

            foreach ($upload_file_type as $val) $upload_file_type_news = array_merge($upload_file_type_news, $val);

            // 文件类型错误
            if (!in_array($this->params['file_type'], $upload_file_type_news)) return $this->outputEnum(Error::UPLOAD_FILE_TYPE_ERROR);
        }

        //文件大小错误
        if ($settings['upload_max_size'] * 1024 < $this->params['file_size'])  return $this->outputEnum(Error::UPLOAD_FILE_SIZE_ERROR);

        return $this->output(
            $devAdminsFileRepository->adminsCreate($this->getAdminsId())->first($this->params),
            Success::UPLOAD_FIRST_SUCCESS
        );
    }

    /* 修改文件上传进度 */
    public function schedule(
        DevAdminsFileRepository $devAdminsFileRepository
    ) {
        $devAdminsFileRepository->adminsCreate($this->getAdminsId())->schedule($this->params);
        return $this->outputEnum(Success::EDIT_SUCCESS);
    }


    /* 获取配置 */
    public function getConfig(DevAdminsFileRepository $devAdminsFileRepository)
    {
        return $this->output(
            $devAdminsFileRepository->adminsCreate($this->getAdminsId())->getConfig(),
            Success::SEL_SUCCESS
        );
    }

    /* 文件列表 */
    public function list(
        DevAdminsFileRepository $devAdminsFileRepository
    ) {
        return $this->output(
            $devAdminsFileRepository->adminsCreate($this->getAdminsId())->list($this->params),
            Success::SEL_SUCCESS
        );
    }

    public function del(
        DevAdminsFileRepository $devAdminsFileRepository
    ) {
        $devAdminsFileRepository->adminsCreate($this->getAdminsId())->del($this->params);
        return $this->outputEnum(
            Success::DEL_SUCCESS
        );
    }
}
