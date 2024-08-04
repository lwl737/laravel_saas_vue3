<?php

declare(strict_types=1);

namespace App\Models\Mongo;

use App\Helpers\Queue\QueueInterface;


/**
 *  @see  \MongoDB\Collection
 *  @method  object updateOne($params)
 *  @method  object list($params)
 *  @method  object insertOne($params)
 *  @method  object find($params)
 *  @method  object findOne($params)
 *  @method  object deleteMany($params)
 *  @method  object getCollectionName()
 *  @method  object getDatabaseName()
 *  @method  object countDocuments()
 */
class Handle
{
    public function __construct(

        protected string $database,
        protected string|null $tableName,
        protected QueueInterface|null $queue = null

    ) {
    }

    public function getDriver()
    {
        $tableName = $this->tableName;
        $database = $this->database;
        return !$tableName ? Connect::getDriver()->$database :  Connect::getDriver()->$database->$tableName;
    }

    public  function __call($method, $params)
    {
        return $this->getDriver()->$method(...$params);
    }


    /**
     *  异步添加一条数据
     *
     * @param  array $params
     * @return void
     */
    public  function insertOneAsync(array $params)
    {
        $this->queue->run([
            "params" => $params,
            "database" => $this->database,
            "table" => $this->tableName,
            "method" => "insertOne"
        ]);
    }

}
