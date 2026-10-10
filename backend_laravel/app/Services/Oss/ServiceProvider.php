<?php

declare(strict_types=1);

namespace App\Services\Oss;

use  App\Helpers\Func;
use  App\Utils\Oss\StsToken;
use  App\Utils\Oss\Proxy as OssProxy;

class ServiceProvider
{



    public function __construct(
        private readonly array $settings

    ) {}



    public function getSettings()
    {
        return  $this->settings;
    }


    public function stsToken()
    {
        return (new StsToken(
            $this->settings["oss_access_key_id"],
            $this->settings["oss_access_key_secret"],
            $this->settings["oss_endpoint"],
            $this->settings["oss_role_arr"],
        ));
    }


    public function control()
    {
        return (new OssProxy(
            $this->settings["oss_access_key_id"],
            $this->settings["oss_access_key_secret"],
            $this->settings["oss_endpoint"],
            $this->settings["oss_bucket"],
        ));
    }

    public function getConfig()
    {
        // $server =  new OssServer([
        //     "upload_file_type",
        //     "upload_check_file_type",
        //     "upload_max_size",
        //     "upload_slice_size",
        //     "oss_start"
        // ]);

        $settings = $this->getSettings();

        $oss_config = [];
        if ($settings['oss_start'] === 1) {   //oss Token请求

            $oss_config = $this->stsToken()->get();
            $oss_config['timeout'] = (int)$oss_config['timeout'];
            $oss_config['oss_bucket'] = $settings['oss_bucket'];
            $oss_config['oss_endpoint'] = $settings['oss_endpoint'];
        }

        return [
            'upload' =>  Func::formExtractData($settings, ["upload_file_type", "upload_check_file_type", "upload_max_size", "upload_slice_size"]),
            'oss' =>  $oss_config,
            // 'admins_id' => $this->admins_id,
            // 'dir_prefix' => [
            //     'admins' =>   $this->getPrefix(),
            // ]
        ];
    }
}
