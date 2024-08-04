<?php

declare(strict_types=1);
namespace App\Jobs;


class MongoPodcast extends Job
{




    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(
        public readonly string $server_class,      // App\Models\Mongo\Server class 类名
        public readonly array $config,   // ['database' => 数据库名 'table' => 表名 'func' => 操作名 'params': 传入参数]

    ) {
        //
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $func = $this->config['func'];
        if ($func !== null) {
            $this->server_class::create(
                $this->config['table'],
                $this->config['database'] ?? '',
            )->$func($this->config['params']);
        }
    }
}
