<?php

declare(strict_types=1);

namespace App\Utils\Settings\Interfaces;

interface ControlInterface
{
    public function default();
    public function casts();
    public static function all();
}
