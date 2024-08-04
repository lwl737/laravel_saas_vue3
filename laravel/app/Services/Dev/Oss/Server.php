<?php

declare(strict_types=1);

namespace App\Services\Dev\Oss;

use  App\Utils\Oss\Proxy as OssProxy;
use  App\Services\Dev\Settings\Server as SettingServer;
use  App\Utils\Oss\StsToken;
class Server
{

    private readonly array $settings;

    public function __construct($field = [])
    {
        $this->settings =  SettingServer::dev()->getItem(
            array_unique(array_merge(
                ["upload_file_type", "upload_check_file_type", "upload_max_size", "upload_slice_size", "oss_start", "oss_access_key_id", "oss_access_key_secret", "oss_endpoint", "oss_bucket", "oss_role_arr"],
                $field
            ))
        );
    }

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
}
