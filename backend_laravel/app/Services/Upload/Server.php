<?php

declare(strict_types=1);

namespace App\Services\Upload;
use App\Utils\Uploads\File;
class Server extends \App\Services\BaseService
{

    /**
     * @description:  上传管理员头像
     * @return {*}
     */
    public static function adminPortrait():File
    {
        return new File('upload/admin/portrait/'.date('Ymd'))  ;
    }

    /**
     * @description:  上传素材库
     * $user_id  用户id
     * @return {*}
     */
    public static function material($user_id):File
    {
        return new File('upload/material/'.$user_id)  ;
    }




}
