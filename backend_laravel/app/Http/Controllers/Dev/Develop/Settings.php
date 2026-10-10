<?php

declare(strict_types=1);

namespace App\Http\Controllers\Dev\Develop;

use App\Helpers\Output\Json\Success;
use Illuminate\Container\Container;
use App\Services\Settings\ServiceProvider;

class Settings extends BaseController
{


    public function setItem()
    {
        ServiceProvider::dev()->setItem($this->params);
        return $this->outputEnum(Success::SET_SUCCESS);
    }


    public function getItem()
    {
        return $this->output(ServiceProvider::dev()->getItem($this->params['field']) ,Success::SEL_SUCCESS );
    }

    public function clearCache()
    {

        $config = Container::getInstance()
        ->make("config")
        ->get('laravel-model-caching.store');

        Container::getInstance()
            ->make("cache")
            ->store($config)
            ->flush();
        return $this->outputEnum(Success::CLEAR_SUCCESS);
    }
}
