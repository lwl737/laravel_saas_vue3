<?php

declare(strict_types=1);
namespace App\Models\Mysql\Orm\Dev;
use GeneaLabs\LaravelModelCaching\Traits\Cachable;

class Settings extends BaseDev{
     use Cachable;
     public $timestamps = false;
}
