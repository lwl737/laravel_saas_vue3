<?php

declare(strict_types=1);

namespace App\Http\Services\Oss;

use App\Services\Oss\ServiceProvider as OssServiceProvider;
use App\Services\Settings\ServiceProvider as SettingsServiceProvider;

class ServiceProvider
{

    public function dev()
    {
        return  new OssServiceProvider(SettingsServiceProvider::dev()->getItem(
            ["upload_file_type", "upload_check_file_type", "upload_max_size", "upload_slice_size", "oss_start", "oss_access_key_id", "oss_access_key_secret", "oss_endpoint", "oss_bucket", "oss_role_arr"]
        ));
    }
}
