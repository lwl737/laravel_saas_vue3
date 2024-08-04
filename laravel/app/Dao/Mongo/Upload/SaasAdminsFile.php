<?php

declare(strict_types=1);

namespace App\Dao\Mongo\Upload;

use App\Services\Dev\Settings\Server as SettingServer;
use App\Models\Mongo\Server\DevAdminFile as FileMongo;
use App\Helpers\Func;
use App\Helpers\Output\Json\Error;
use App\Services\Dev\Oss\Server as OssServer;
use App\Helpers\Queue\Modules\Rabbitmq;

class SaasAdminsFile extends BaseUpload
{


    public function __construct(
        int $admins_id,
        string $db_after = ""
    ) {

        $this->admins_id = $admins_id;
        $this->db_after = $db_after;
        $this->mongo = FileMongo::createRange($admins_id,1000 , $db_after);
    }

    public function first($params)
    {


        $settings = SettingServer::dev()->getItem(
            ["upload_max_size", "upload_slice_size", "upload_file_type", "upload_check_file_type", "library_max_capacity"]
        );

        $upload_file_type = array_map(fn ($item) => explode(',', $item['type']), $settings["upload_file_type"]);


        if ($settings['upload_check_file_type'] === 1) {

            $upload_file_type_news = [];

            foreach ($upload_file_type as $val) $upload_file_type_news = array_merge($upload_file_type_news, $val);

            // 文件类型错误
            if (!in_array($params['file_type'], $upload_file_type_news)) return Func::throwHttpCustom(Error::UPLOAD_FILE_TYPE_ERROR);
        }

        //文件大小错误
        if ($settings['upload_max_size'] * 1024 < $params['file_size'])  return Func::throwHttpCustom(Error::UPLOAD_FILE_SIZE_ERROR);

        return  $this->insertOneFile($params);
    }


    public function schedule(array $params)
    {
        return  $this->fileSchedule($params);
    }





    public function list(array $params)
    {
        // 素材库容量大小
        $capacity = $this->getCapacity();  //统计当前积累的容量
        // 设置的素材库最大容量
        $settings = SettingServer::dev()->getItem(['library_max_capacity','prefix_url']);

        return [
            "total" => $this->fileCount($params),
            "datalist" => $this->fileList($params , $settings['prefix_url']),
            'capacity' => $capacity,
            'max_capacity' => $settings['library_max_capacity'] * 1024,
            "pageNum" => (int)$params['pageNum'],
            "pageSize" => (int)$params['pageSize'],
        ];
    }

    public function getConfig()
    {
        // $settings =  $server->getSettings();

        $server =  new OssServer([
            "upload_file_type",
            "upload_check_file_type",
            "upload_max_size",
            "upload_slice_size",
            "oss_start"
        ]);

        $settings = $server->getSettings();

        $oss_config = [];
        if ($settings['oss_start'] === 1) {   //oss Token请求

            // var_dump($server->stsToken());
            $oss_config = $server->stsToken()->get();
            $oss_config['timeout'] = (int)$oss_config['timeout'];
            $oss_config['oss_bucket'] = $settings['oss_bucket'];
            $oss_config['oss_endpoint'] = $settings['oss_endpoint'];
        }

        return [
            'upload' =>  Func::formExtractData($settings, ["upload_file_type", "upload_check_file_type", "upload_max_size", "upload_slice_size"]),
            'oss' =>  $oss_config,
            'admins_id' => $this->admins_id,
            'dir_prefix' => [
                'admins' =>   $this->getPrefix(),
            ]
        ];
    }

    public  function oidFindOne( string $oid ){
        return $this->fileOidFindOne($oid) ;
    }

    public  function oidFind( array $oids ){

        return $this->fileOidFind($oids);

    }

    public function realDel(array $oids){
        return  $this->fileRealDel($oids) ;
    }




    public function del(array $params)
    {
        return $this->fileDel($params , Rabbitmq::OSS_DEL_FILE ,SettingServer::dev()->getItem(['oss_access_key_id','oss_access_key_secret','oss_endpoint','oss_bucket']) );
    }
}
