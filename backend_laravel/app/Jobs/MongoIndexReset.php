<?php

declare(strict_types=1);

namespace App\Jobs;

class MongoIndexReset extends Job
{

    public $delay = 20; // 延迟执行时间，单位为秒

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        ["mongoServerName" => $mongoServerName , "tableAfter" => $tableAfter , "dbAfter" => $dbAfter ] = $this->getData();

        $mongoServerName::resetIndex($tableAfter, $dbAfter);  //重置索引
    }


}
