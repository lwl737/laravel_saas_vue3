<?php

declare(strict_types=1);

namespace App\Utils\Uploads;


use Illuminate\Http\UploadedFile;

class File
{
    public function __construct(
        public readonly string  $dir_path
    )
    {}

    public  function laravel(UploadedFile $file):Control
    {
        return new Control($this->dir_path,$file->getClientOriginalExtension(),$file->getRealPath());
    }





}
