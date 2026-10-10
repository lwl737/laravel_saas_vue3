<?php

declare(strict_types=1);

namespace App\Helpers;



class Inject
{
    /**
     * @description: 禁止实例化
     * @return {*}
     */
    private function __construct()
    {
    }
    /**
     * @description: 禁止克隆
     * @return {*}
     */
    private function __clone()
    {
    }


    public static function get(string $class_name)
    {

        return app($class_name);
    }


    public static function bindGet(string $class_name, array|object $params = [])
    {
        if (!app()->bound($class_name)) self::bind($class_name, $params);
        return self::get($class_name);
    }


    public static function bind(string $class_name, array|object $params)
    {
        if (is_array($params)) {
            app()->bind($class_name, function () use ($class_name, $params) {
                return new $class_name(...$params);
            });
        } else {
            app()->bind($class_name, fn () => $params);
        }
    }
}
