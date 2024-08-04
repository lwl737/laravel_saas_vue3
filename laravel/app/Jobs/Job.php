<?php

declare(strict_types=1);

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use App\Helpers\Func;
use Illuminate\Support\Facades\Log;

abstract class Job implements ShouldQueue
{

    /*
    |--------------------------------------------------------------------------
    | Queueable Jobs
    |--------------------------------------------------------------------------
    |
    | This job base class provides a central location to place any logic that
    | is shared across all of your jobs. The trait included with the class
    | provides access to the "queueOn" and "delay" queue helper methods.
    |
    */

    use InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(
        public  array  $data,     // 数据
        public  string  $channel,   // 错误日志管道
    ) {
    }


    public function getData(string|array $key = [])
    {
        if (empty($key)) return $this->data;

        return is_string($key) ?  $this->data[$key] :  Func::formExtractData($this->data, $key);
    }


    public function failed(\Throwable $exception)
    {
        Log::channel($this->channel)->info(json_encode([
            'message' => $exception->getMessage(),
            'line' => $exception->getLine(),
            'file' => $exception->getFile(),
            'data' => $this->getData(),
        ], JSON_UNESCAPED_UNICODE));
    }
}
