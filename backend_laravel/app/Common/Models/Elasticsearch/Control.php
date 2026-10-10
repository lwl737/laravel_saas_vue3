<?php

declare(strict_types=1);

namespace App\Common\Models\Elasticsearch;

use Elastic\Elasticsearch\Endpoints\Indices;

class Control
{

    // 操作索引
    public static function indices():Indices
    {

        return Connect::getDriver()->indices();
    }
    public static function index(string $index ,array $body)
    {

        return Connect::getDriver()->index(["index" => $index , "body" => $body]);
    }
    public static function get(string $index ,array|null $body = null , string|int|null $_id = null)
    {

        $parmas = ['index' => $index];
        if(!empty($body)) $parmas["body"] = $body ;
        if($_id !== null) $parmas["id"] = $_id ;

        return Connect::getDriver()->get($parmas);
    }
}
