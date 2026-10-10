<?php

declare(strict_types=1);

namespace App\Common\Repository\Saas;

use App\Common\Dao\Mongo\UploadDao\SaasAdminsFile as SaasAdminsFileDao;
use App\Helpers\Queue\Modules\Rabbitmq;
use App\Http\Services\Oss\ServiceProvider as OssServiceProvider;
use App\Services\Settings\ServiceProvider as SettingsServiceProvider;



class SaasAdminsFileRepository  extends BaseRepository
{

    public function __construct(
        protected SaasAdminsFileDao $dao
    ) {}


    public function adminsCreate(int $admins_id , int $after)
    {
        $this->dao = $this->dao->setAdminsId($admins_id)->createRange($admins_id, 1000 , $after);
        return $this;
    }


    public function first(array $params)
    {

        return $this->dao->insertOneFile($params);
    }

    public function schedule(array $params)
    {
        return $this->dao->fileSchedule($params);
    }

    public function getConfig(): array
    {
        /** @var OssServiceProvider **/
        $ossServiceProvider = app()->make(OssServiceProvider::class);
        return array_merge($ossServiceProvider->dev()->getConfig(), [
            'admins_id' => $this->dao->getAdminsId(),
            'dir_prefix' => [
                'admins' =>   $this->dao->getPrefix(),
            ]
        ]);
    }


    public function list(array $params)
    {
        //     // 设置的素材库最大容量
        $settings = SettingsServiceProvider::dev()->getItem(['library_max_capacity', 'prefix_url']);
        return [
            "total" => $this->fileCount($params),
            "datalist" => $this->fileList($params, $settings['prefix_url']),
            'capacity' => $this->dao->getCapacity(), //统计当前积累的容量
            'max_capacity' => $settings['library_max_capacity'] * 1024,
            "pageNum" => (int)$params['pageNum'],
            "pageSize" => (int)$params['pageSize'],
        ];
    }


    public function del(array $params)
    {
        $this->dao->fileDel($params);
        if ($params['real_del'] === 1) {
            //真实删除文件进队列里删除
            Rabbitmq::OSS_DEL_FILE->run([
                'admins_id' => $this->dao->getAdminsId(),
                'oids' => $params['oids'],
                'settings' => SettingsServiceProvider::dev()->getItem(['oss_access_key_id', 'oss_access_key_secret', 'oss_endpoint', 'oss_bucket']),
                'database' => $this->getPrefix(),
                'table' =>   $this->dao->getCollectionName(),
            ]);
        }
    }
}
