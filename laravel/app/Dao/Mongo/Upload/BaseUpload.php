<?php

declare(strict_types=1);

namespace App\Dao\Mongo\Upload;

use App\Dao\Mongo\BaseMongo;
use App\Utils\Uuid\Control as Uuid;
use App\Models\Mongo\Handle;
use App\Helpers\Output\Json\Error;
use App\Helpers\Func;
use MongoDB\BSON\ObjectId;
use MongoDB\BSON\Regex;
use App\Helpers\Queue\QueueInterface;


abstract class BaseUpload extends BaseMongo
{

    protected  Handle|null $mongo = null;
    protected  int|string $admins_id = "";
    protected  string $db_after = "";
    protected function insertOneFile($params)
    {
        $uuid = Uuid::createVersion4();

        ['file_url' =>  $file_url] = $this->createOssUrl($uuid, $params['dir_id'], $params['file_name']);

        $oid = $this->mongo->insertOne(
            [
                'dir_id'    =>    $params['dir_id'],       //文件夹id
                'file_size' =>    $params['file_size'],    //文件大小
                'admins_id' =>    $this->admins_id,
                'file_url'  =>    $file_url,
                'schedule'  =>    0,                       //文件上传进度
                'file_type' =>    $params['file_type'],
                'file_name' =>    $params['file_name'],
                'dir_link' =>    $params['dir_link'],
                'create_time' =>  time(),
                'is_del' => 0,  //是否在回收站
                'wait_del' => 0, // 是否在队列等待删除
                'update_time' => time()
            ]
        )->getInsertedId()->__toString();

        return ['file_url' => $file_url, 'oid' => $oid];
    }


    private  function  createOssUrl(string $uuid, string $dir_id, string $file_type)
    {

        $file = '/' . $dir_id . '/' . $uuid . ($file_type ? '.' . Func::getLastText($file_type, '.') : '');

        return ['file_url' => '/' . $this->getPrefix()   .  '/' . $this->admins_id . $file, 'file_type' =>   $file_type];
    }

    protected final function getPrefix()
    {
        return $this->mongo->getDatabaseName();
    }

    protected final function getCapacity()
    {
        $cursor =  $this->mongo->aggregate([
            ['$match' => ["admins_id" => $this->admins_id, 'wait_del' => 0]],
            ['$group' =>  ["_id" => null, 'capacity' => ['$sum' => '$file_size']]]
        ]);

        $cursor = iterator_to_array($cursor);

        return count($cursor) > 0 ?  $cursor[0]->capacity : 0;  //统计当前积累的容量
    }


    protected function fileList(array $params, string $prefix_url)
    {
        $arr = [];

        $filter  = $this->configList(
            $params['dir_link'],
            $params['keyWord'],
            $params['file_type'] ? explode("," , $params['file_type']) : []
        );

        // https://lwl-study.oss-cn-guangzhou.aliyuncs.com/

        foreach ($this->mongo->find($filter, [
            "sort" => [
                "create_time" => -1
            ],
            'projection' => [
                "file_size" => 1,
                "file_name" => 1,
                "create_time" => 1,
                "schedule" => 1,
                'file_type' => 1,
                "full_url" =>   ['$concat' => [$prefix_url, '$file_url']],
                "_id" => [
                    '$toString' =>  '$_id'
                ]
            ],
            "skip" => (int)(($params["pageNum"] - 1) * $params["pageSize"]),
            "limit" => (int)$params["pageSize"]
        ]) as $val) {
            $arr[] = (array) $val;
        }
        return $arr;
    }

    protected function fileCount(array $params)
    {

        // var_dump( $params['dir_link'],
        // $params['keyWord'],
        // $params['file_type']);
        return $this->mongo->countDocuments(
            $this->configList(
                $params['dir_link'],
                $params['keyWord'],
                $params['file_type'] ? explode("," , $params['file_type']) : []
            )
        );
    }

    protected function fileOidFindOne(string $oid)
    {
        return $this->mongo->findOne(['$and' => [
            ['admins_id' =>  $this->admins_id],
            ['_id' => new ObjectId($oid)]
        ]]);
    }


    protected  function fileOidFind(array $oids)
    {

        $arr = [];

        foreach ($this->mongo->find(['$and' => [
            ['admins_id' =>  $this->admins_id],
            ['$or' => array_map(function ($item) {
                return ['_id' => new ObjectId($item)];
            }, $oids)]
        ]], [

            "sort" => [
                "create_time" => -1
            ],
            'projection' => [
                "file_size" => 1,
                "file_name" => 1,
                "create_time" => 1,
                "schedule" => 1,
                'file_type' => 1,
                "file_url" =>   1,
                "_id" => [
                    '$toString' =>  '$_id'
                ]
            ],
        ]) as $val) {

            $arr[] = (array) $val;
        }

        return $arr;
    }


    protected function fileRealDel(array $oids)
    {
        return  $this->mongo->deleteMany([
            '$and' => [
                ['admins_id' =>  $this->admins_id],
                ['$or' => array_map(function ($item) {
                    return ['_id' => new ObjectId($item)];
                }, $oids)]
            ]
        ]);
    }

    protected function fileSchedule(array $params)
    {
        return $this->mongo->updateOne(['admins_id' => $this->admins_id, '_id' => new ObjectId($params['oid'])], ['$set' => ['update_time' => time(), 'schedule' => $params['schedule']]]);
    }


    protected function fileDel(array $params, QueueInterface $queue , array $settings)
    {

        if ($params['real_del'] === 1) {
            //修改成等待删除状态
            $this->mongo->updateMany([
                '$and' => [
                    ['admins_id' =>  $this->admins_id],
                    ['$or' => array_map(function ($item) {
                        return ['_id' => new ObjectId($item)];
                    }, $params["oids"])]
                ]
            ], ['$set' => ['wait_del' => 1]]);

            // $settings["oss_access_key_id"],
            // $settings["oss_access_key_secret"],
            // $settings["oss_endpoint"],
            // $settings["oss_bucket"],


            $queue->run([
                'admins_id' => $this->admins_id,
                'oids' => $params['oids'],
                'settings' => $settings,
                'database' => $this->getPrefix(),
                'table' =>   $this->mongo->getCollectionName(),
                // "admins_id" => $admins_id,
                // "oids" => $oids,
                // "settings" => $settings,
                // "database" => $database,
                // "table" => $table
            ]);

            // 异步进队列删除
            // dispatch((new OssDelFile($this->admins_id,$this->dbAfter,$params['oids']))->onConnection('rabbitmq')->onQueue('oss_del_file'));
            // Rabbitmq::OSS_DEL_FILE->run($this->admins_id,$this->dbAfter,$params['oids']);


        } else {
            //软删除进回收站
            $this->mongo->updateMany([
                '$and' => [
                    ['admins_id' =>  $this->admins_id],
                    ['$or' => array_map(function ($item) {
                        return ['_id' => new ObjectId($item)];
                    }, $params["oids"])]
                ]
            ], ['$set' => ['is_del' => 1]]);
        }
    }
    private  function configList(string $dir_link, string $keyWord, array $file_type)
    {
        $filter  = ['$and' => [
            ['admins_id' =>  $this->admins_id],
            ['$or' => [['wait_del'  => ['$exists' => false]], ['wait_del' => 0]]]
        ]];

        if ($dir_link === '-1') {
            //软删除
            $filter['$and'][] = ['is_del' => 1];
        } else {
            //没有被删除
            $filter['$and'][] = ['$or' => [['is_del'  => ['$exists' => false]], ['is_del' => 0]]];

            //文件夹
            if ($dir_link != '0')  $filter['$and'][] = ['dir_link' => new Regex('^' . $dir_link)];
        }


        if ($keyWord) $filter['$and'][] =  ['file_name' => new Regex('^' . $keyWord)];

        if (!empty($file_type)) {
            $filter['$and'][] = ['$or' => array_map(function ($item) {
                return ['file_type' => $item];
            }, $file_type)];
        }

        return $filter;
    }

    protected function insertOneDir(array $params)
    {
        $total =  $this->mongo->countDocuments(['$and' => [
            ['admins_id' =>  $this->admins_id],
            ['dir_pid' =>  "0"],
        ]]);

        if($total >= 10)  return Func::throwHttpCustom(Error::DIR_MORE_NUM,["num" => 10]);

        if ($params['dir_pid'] == 0) $params['dir_link']  = "";
        else {
            $res = $this->mongo->findOne(
                ['admins_id' => $this->admins_id, '_id' => new ObjectId($params['dir_pid'])],
                [
                    'projection' => [
                        "_id" => [
                            '$toString' =>  '$_id'
                        ],
                        "dir_link" => 1
                    ]
                ]
            );

            if (!$res) return Func::throwHttpCustom(Error::P_DIR_NOT_DEFINED);

            if ( count(explode( "," , $res->dir_link )) >= 3) return Func::throwHttpCustom(Error::DIR_MORE_LEVE,["level" => "三"]);

            $params['dir_link'] = ($res->dir_link === "" ? $res->_id :  $res->dir_link . "," . $res->_id).",";
        }

        return $this->mongo->insertOne(
            array_merge($params, ['admins_id' => $this->admins_id])
        )->getInsertedId();
    }


    protected function deleteDir(array $params)
    {
        return $this->mongo->deleteMany([
            'admins_id' => $this->admins_id,
            '$or' =>
            array_map(function ($item) {
                return ['_id' => new ObjectId($item)];
            }, $params['ids'])
        ]);
    }


    protected function editDir(array $params)
    {
        return  $this->mongo->updateOne(['admins_id' => $this->admins_id, '_id' => new ObjectId($params['dir_id'])], ['$set' =>  $params]);
    }

    protected function allDir()
    {
        $arr = [];
        foreach ($this->mongo->find([
            'admins_id' => $this->admins_id
        ], [
            'sort' => ['dir_sort' => -1],
            'projection' => [
                "dir_name" => 1,
                "admins_id" => 1,
                "dir_link" => 1,
                "dir_sort" => 1,
                "dir_pid" => 1,
                "_id" => 0,
                "dir_id" => [
                    '$toString' =>  '$_id'
                ],
            ]
        ]) as $val) {
            $val->dir_pid = (string) $val->dir_pid;
            $arr[] =   (array)$val;
        }
        return Func::createTree($arr, 'dir_id', 'dir_pid', 'children', function ($item) {
            $item['dir_link'] = ($item['dir_link'] ?  $item['dir_link']  . $item['dir_id']  : $item['dir_id']  ) . ",";
            return $item;
        }, false, '0');
    }
}
