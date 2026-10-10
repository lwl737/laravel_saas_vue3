<?php

declare(strict_types=1);

namespace App\Utils\Uploads;
use App\Helpers\Func;
use App\Helpers\Output\Json\Error ;

class Splice
{


    public   function  __construct(
        public readonly string $tmp_path,
        public readonly string $target_file,
        public readonly string $url
     )
    {
    }


    public function read():string
    {
        return  file_get_contents($this->tmp_path) or Func::throwHttpCustom(Error::FILE_READ_ERROR);
    }





    /**
     *  文件追加
     * */
    public function readdition(){
        $file_content = $this->read();
        $openfile = fopen ($this->target_file,"a+") or Func::throwHttpCustom(Error::FILE_READ_ERROR);
        fwrite ($openfile,$file_content);
        fclose ($openfile);
        return [
            'path' => $this->target_file,
            'url' =>  $this->url,
            'fileName' => end(explode('/', $this->target_file))
        ];
    }

}
