<?php

declare(strict_types=1);

namespace App\Dao\Mongo;

use App\Models\Mongo\Server\ErrorLog;
use Illuminate\Http\Request;
use App\Utils\Nanoid\Control as Nanoid;
use Illuminate\Support\Facades\Log;
use App\Helpers\Func;
use App\Models\Mongo\Handle;

class ErrorLogDao extends BaseMongo
{

    public readonly Handle $mongo;

    public function __construct(
        public readonly int|null $time = null
    ) {
        $this->mongo = ErrorLog::createDate($time ? $time : time(),'Ymd');
    }


    /**
     * @description:      异步 添加日志错误日志
     * @param {array} $data
     * @return {*}
     */
    public  function insertOneAsync(array $data, Request $request): array
    {
        $time = time();

        $resultData = self::createData($data, $request, $time);

        $dateYmd =  date('Ymd', $time);

        try {
            $this->mongo->insertOneAsync($resultData);
        } catch (\Exception $e) {
            Log::channel('system_error')->error(json_encode(['data' => $resultData, 'rabbitmq_error' => $e->getMessage()]));
        }

        return ['date' => $dateYmd, 'oid' => $resultData["oid"]];
    }

    public  function insertOne(array $data, Request $request): array
    {
        $time = time();

        $resultData = self::createData($data, $request, $time);

        try {
            //加入mongodb
            $obj = $this->mongo->insertOne($resultData);

            return ['oid' => $obj->getInsertedId()->__toString(), 'date' => date('Ymd', $time), 'type' => 'mongo' , 'addMongo' => true];
        } catch (\Exception $e) {
            //mongodb 错误时加入记事本

            $nanoid = Nanoid::create();

            $result = ['oid' =>  $nanoid, 'date' => date('Ymd', $time), 'type' => 'text' , 'addMongo' => false];

            Log::channel('system_error')->error(json_encode(array_merge($result, ['data' => $resultData, 'mongo_error' => $e->getMessage()]) , JSON_UNESCAPED_UNICODE ));

            return $result;
        }

    }


    public  function createData(array $data, Request $request, int $time)
    {

        return  [
            "oid" => Nanoid::create(),  //日志标识
            "line" => $data["line"],
            "file" => $data["file"],
            "error" => $data["error"],
            "enum" => $data["enum"],
            "params" => $request->input(),
            "full_url" =>  $request->url(),
            "ip" => Func::getRealIp($request),
            "method" =>  $request->method(),
            "create_time" => date('Y-m-d H:i:s', $time)
        ];
    }


    /**
     * @description:      查找所有集合
     * @param {array} $data
     * @return {*}
     */
    public static  function getTableName()
    {
        $data = [];
        foreach (ErrorLog::create(null)->listCollectionNames() as $val) $data[] = $val;
        return $data;
    }

    /**
     * @description:      分页
     * @param {array} $data
     * @return {*}
     */
    public  function list(array $data)
    {
        $arr = [];

        foreach ($this->mongo->find([], [
            'projection' => [
                "_id" => [
                    '$toString' =>  '$_id'
                ],
                "line" => 1,
                "file" => 1,
                "error" => 1,
                "params" => 1,
                "full_url" => 1,
                "enum" => 1,
                "ip" => 1,
                "method" => 1,
                "create_time" => 1,
            ],
            "sort" => [
                "create_time" => -1
            ],
           ...$this->page($data)
        ]) as $val) {
            $val = (array)$val;
            $arr[] = $val;
        };
        return $arr;
    }


    public function total()
    {
        return $this->mongo->countDocuments();
    }
}
