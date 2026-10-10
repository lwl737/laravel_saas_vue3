<?php

declare(strict_types=1);

namespace App\Common\Models\Elasticsearch;

use Elastic\Elasticsearch\ClientBuilder;
use Elastic\Elasticsearch\Client;

class Connect
{
    private static Client|null $driver = null;


    private function __construct()
    {
    } //禁止实例化
    private function __clone()
    {
    }     //禁止克隆

    /**
     * @description: 连接
     */
    public static  function getDriver(): Client
    {

        if (!self::$driver instanceof Client) self::$driver = ClientBuilder::create()
            ->setHosts([self::getHost()])
            ->setBasicAuthentication(config('database.connections.elasticsearch.username'), config('database.connections.elasticsearch.password'))
            ->build();

        return self::$driver;
    }

    public static function getHost()
    {
        return config('database.connections.elasticsearch.host') . ':' .  config('database.connections.elasticsearch.port');
    }



}
