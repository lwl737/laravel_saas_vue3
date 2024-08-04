<?php

declare(strict_types=1);

namespace App\Http\Routes\Modules\Dev\Develop;

use App\Http\Interfaces\Routes;
use App\Rules\{HaveZero, ArrValidator, NotZero, ZeroOne, InField};


class Settings implements Routes
{

    public function routes(): array
    {
        return [
            'setItem' => ["method" => "put" ,"valid" => array_keys($this->rules())],
            'getItem' => ["valid" => ['field' => ['required', new InField(array_keys($this->rules()))]]],
            'clearCache' => [],
        ];
    }


    public function rules(): array
    {
        return  [
            'upload_slice_size' => ['required', "numeric", "min:100", "max:1024", new NotZero],
            'upload_max_size' => ['required', "numeric", "min:0", "max:204800", new HaveZero],
            'library_max_capacity' => ['required', "numeric", "min:1"],
            'upload_file_type' => ["present", "array", new ArrValidator(["type" => ['required', 'string'], "name" => ['required', 'string']])],
            'upload_check_file_type' => ["required", new ZeroOne],
            'oss_start' => ["required", new ZeroOne],
            'oss_access_key_id' => ["string", "max:255"],
            'oss_access_key_secret' => ["string", "max:255"],
            'oss_endpoint' => ["string", "max:255"],
            'oss_bucket' => ["string", "max:255"],
            'oss_role_arr' => ["string", "max:255"],
            'prefix_url' => ["string", 'max:255']
        ];
    }
}
