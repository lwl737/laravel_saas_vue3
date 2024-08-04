<?php

declare(strict_types=1);

namespace App\Jobs;

use App\Models\Mongo\Connect;

class MongoAsync extends Job
{
    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {

        ["database" => $database , "table" => $table , "method" => $method , "params" => $params] = $this->getData();

        Connect::getDriver()->$database->$table->$method($params);

    }
}
