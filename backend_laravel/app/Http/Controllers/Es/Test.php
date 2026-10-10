<?php

declare(strict_types=1);

namespace App\Http\Controllers\Es;

use App\Common\Models\Elasticsearch\Indices\TestTest;
use App\Models\Elasticsearch\Connect;
use App\Models\Redis\RedisMutexLock;
use App\Jobs\Rabbitmq\DevAdminOperation;
use App\Jobs\DevAdminOperationFailed;

class Test extends BaseController
{
    public function add()
    {

        // $params = [
        //         'settings' => [
        //             'number_of_shards' => 3,
        //             'number_of_replicas' => 2
        //         ],
        //         'mappings' => [
        //             '_source' => [
        //                 'enabled' => true
        //             ],
        //             'properties' => [
        //                 'first_name' => [
        //                     'type' => 'keyword'
        //                 ],
        //                 'age' => [
        //                     'type' => 'integer'
        //                 ]
        //             ]
        //         ]
        // // ];
        // $params = [
        //     "index" => "test",
        //     "body" => [
        //         'settings' => [
        //             'number_of_shards' => 3,
        //             'number_of_replicas' => 2
        //         ],
        //         'mappings' => [
        //             '_source' => [
        //                 'enabled' => true
        //             ],
        //             'properties' => [
        //                 'first_name' => [
        //                     'type' => 'keyword'
        //                 ],
        //                 'age' => [
        //                     'type' => 'integer'
        //                 ]
        //             ]
        //         ]
        //     ]
        // ];
        // var_dump(Connect::getDriver()->indices()->create($params));
        // var_dump(Connect::getDriver()->indices()->do);

        // var_dump( TestTest::static()->create(['test' => 111])->getBody()->getContents() );

        // var_dump(Connect::getDriver());
        //   $redisMutexLock = new RedisMutexLock(['DB' => 1 , 'key' => 'ES_KEY_IDX'] , '3');

        //   $redisMutexLock->tryGetDistributedLock();

        //   $redisMutexLock->releaseDistributedLock();

        // (new DevAdminOperation)

        // dispatch(new DevAdminOperationFailed(['test' => 23] , 'system_error'))->onConnection("rabbitmq")->onQueue("test123456");

    }

    public function get()
    {
        // $redisMutexLock = new RedisMutexLock;
        // $redisMutexLock->tryGetDistributedLock(['DB' => 1 , 'key' => 'ES_KEY'] , '1');
    }
}
