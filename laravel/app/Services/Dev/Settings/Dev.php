<?php

declare(strict_types=1);

namespace App\Services\Dev\Settings;
use App\Utils\Settings\Interfaces\ControlInterface;
use App\Utils\Settings\StaticFunc;
enum Dev implements ControlInterface
{
    use StaticFunc;
    /* 配置参数 数据库dev_settings表有字段注释 */
    /**
     * @description: 文件切片大小
     */
    case upload_slice_size;

    /**
     * @description: 素材库最大容量
     */
    case library_max_capacity;
    /**
     * @description: 地址前缀
     */
    case prefix_url;
    /**
     * @description: 文件上传最大大小
     */
    case upload_max_size;
    /**
     * @description: 文件上传类型
     */
    case upload_file_type;
    /**
     * @description: 是否检查文件上传类型
     */
    case upload_check_file_type;

    /**
     * @description: 是否开启oss校验
     */
    case oss_start;
    /**
     * @description: oss_access_key_id
     */
    case oss_access_key_id;
    /**
     * @description: oss_access_key_secret
     */
    case oss_access_key_secret;
    /**
     * @description: oss_endpoint
     */
    case oss_endpoint;
    /**
     * @description: oss_bucket
     */
    case oss_bucket;

    /**
     * @description: oss_role_arr
     */
    case oss_role_arr;

    /* 配置默认值 */
    public function default()
    {
        return match ($this) {
            Dev::upload_slice_size => 100,
            Dev::upload_max_size => 204800,
            Dev::prefix_url => env("OSS_PREFIX_URL",""),
            Dev::upload_file_type => array(['type' => 'image/png,image/jpg,image/jpeg,image/gif' , 'name' => '图片' , 'only' => "image"]),
            Dev::upload_check_file_type => 1,
            Dev::oss_start => 1,
            Dev::oss_access_key_id => env("OSS_ACCESS_KEY_ID",""),
            Dev::oss_access_key_secret => env("OSS_ACCESS_KEY_SECRET",""),
            Dev::oss_endpoint => env("OSS_ENDPOINT",""),
            Dev::oss_bucket => env("OSS_BUCKET",""),
            Dev::oss_role_arr => env("OSS_ROLE_ARR",""),
            Dev::library_max_capacity => 1048576,
        };
    }

    /*配置属性转换 在 app/Common/Helper.php 文件里casts 方法 */
    public function casts():string
    {
        return match ($this) {
            Dev::upload_slice_size => 'integer',
            Dev::upload_max_size => 'integer',
            Dev::upload_file_type => 'serialize',
            Dev::upload_check_file_type => 'integer',
            Dev::oss_start => 'integer',
            Dev::oss_access_key_id => "string",
            Dev::prefix_url => "string",
            Dev::oss_access_key_secret => "string",
            Dev::oss_endpoint => "string",
            Dev::oss_bucket => "string",
            Dev::oss_role_arr => "string",
            Dev::library_max_capacity => "integer",
            default => 'self'
        };
    }





}
