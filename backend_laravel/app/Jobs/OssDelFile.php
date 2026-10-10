<?php

declare(strict_types=1);

namespace App\Jobs;

use  App\Utils\Oss\Proxy as OssProxy;

use App\Common\Models\Mongo\Connect;

use MongoDB\BSON\ObjectId;

class OssDelFile  extends Job
{


    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {

        [
            "admins_id" => $admins_id,
            "oids" => $oids,
            "settings" => $settings,
            "database" => $database,
            "table" => $table
        ] = $this->getData();
;

        $dels = [];

        // 远程删除
        foreach (Connect::getDriver()->$database->$table->find(['$and' => [
            ['admins_id' =>  $admins_id],
            ['$or' => array_map(function ($item) {
                return ['_id' => new ObjectId($item)];
            }, $oids)]
        ]], [
            'projection' => [
                "file_url" =>   1,
                "_id" => [
                    '$toString' =>  '$_id'
                ]
            ]
        ])  as $file) {
            $file = (array)$file;
             (new OssProxy(
                $settings["oss_access_key_id"],
                $settings["oss_access_key_secret"],
                $settings["oss_endpoint"],
                $settings["oss_bucket"],
            ))->deleteObject($file['file_url']); //OSS远程删除
            $dels[] = $file['_id'];
        };


        //删除本地mongodb
        if(!empty( $dels ) ){
            Connect::getDriver()->$database->$table->deleteMany([
                '$and' => [
                    ['admins_id' =>  $admins_id],
                    ['$or' => array_map(function ($item) {
                        return ['_id' => new ObjectId($item)];
                    }, $dels)]
                ]
            ]);
        }
    }
}
