<?php

declare(strict_types=1);
namespace App\Models\Mongo\Server;
use App\Helpers\Queue\Modules\Rabbitmq;
use App\Helpers\Queue\QueueInterface;

class ErrorLog extends BaseServer
{
   protected static string $database = 'error_log';

   protected static QueueInterface|null $queue = Rabbitmq::SYSTEM_ERROR;


}
