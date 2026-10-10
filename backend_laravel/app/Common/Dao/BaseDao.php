<?php

declare(strict_types=1);

namespace App\Common\Dao;


abstract class BaseDao
{

    abstract protected function getModel():string;
}
