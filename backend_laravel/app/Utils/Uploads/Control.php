<?php

declare(strict_types=1);

namespace App\Utils\Uploads;

use App\Helpers\Output\Json\Error;
use App\Utils\Nanoid\Control as Nanoid;
use App\Helpers\Func;

class Control
{

    public readonly string  $dir_path;
    public readonly string  $url_path;

    public readonly  string  $file_type;      //文件类型
    public readonly  string  $tmp_path;       //文件临时路径
    public readonly  string  $file_name;      //新文件名称


    public function __construct(
        string  $dir_path,
        string  $file_type,
        string  $tmp_path,
        string  $file_name = ''
        )
    {
        $this->dir_path =  Config::FILE_PATH->create($dir_path);    //文件夹路径
        $this->url_path =  Config::URL_PATH->create($dir_path);      //url路径
        $this->file_type =  $file_type ;
        $this->tmp_path =  $tmp_path  ? $tmp_path :   Func::throwHttpCustom(Error::NOT_TMP_PATH);

        $this->mkdir_2($this->dir_path);                            //递归创建文件夹

        $this->file_name = $file_name ?  $file_name : $this->createNewFileName($file_name) ;

    }

    /**
     * @description:   移动文件
     */
    public final function move()
    {
        $path = $this->getPath($this->file_name);
        $ret =   move_uploaded_file($this->tmp_path, $this->getPath($this->file_name));
        $url =  $this->getUrl($this->file_name);
        if ($ret) return  [
            'path' => $path,
            'file_name' =>  $this->file_name,
            'url' =>  $url,
            'full_url' => Func::baseUrl($url),
        ];
        else Func::throwHttpCustom(Error::MOVE_FILE_ERROR);
    }


    private final function createNewFileName():string
    {
        $fileName = Nanoid::create()  . ($this->file_type ? '.' .$this->file_type : '');
        $path =  $this->getPath($fileName);
        if (file_exists($path)) return $this->createNewFileName();   //判断文件是否存在存在就重新生成nanoid
        return $fileName;
    }




    public final function splice():Splice
    {
       return new Splice($this->tmp_path, $this->getPath($this->file_name),$this->getUrl($this->file_name));
    }


    /**
     * @description:递归创建文件夹
     * @param string $path
     */
    private  function mkdir_2(string $path, array $pathError = [])
    {
        if (!is_dir($path)) {
            $prePath = dirname($path) . '/';
            if (is_dir($prePath)) {
                mkdir($path, 0777);
                if (count($pathError) > 0) {
                    for ($i = count($pathError) - 1; $i >= 0; $i--) {
                        mkdir($pathError[$i], 0777);
                    }
                }
            } else {
                $pathError[] = $path;
                $this->mkdir_2($prePath, $pathError);
            }
        }
    }


    private final function getPath(string $fileName): string
    {
        return $this->dir_path . '/' . $fileName;
    }

    private final function getUrl(string $fileName): string
    {
        return $this->url_path . '/' . $fileName;
    }
}
