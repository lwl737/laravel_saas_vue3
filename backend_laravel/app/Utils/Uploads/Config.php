<?php

declare(strict_types=1);
namespace App\Utils\Uploads;
enum Config
{
  case  FILE_PATH ;              //文件绝对路径
  case  URL_PATH ;              //网站路径

  public function create(string $urlPath)
  {
      //加杠
      if (substr($urlPath, 0, 1) !== '/')  $urlPath  = '/' . $urlPath;
      return match($this)
      {
        Config::FILE_PATH => base_path('public'.$urlPath),
        Config::URL_PATH => $urlPath,
      };
  }

}
