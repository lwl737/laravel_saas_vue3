<?php

declare(strict_types=1);

namespace App\Helpers\Queue;

trait Run
{
    public function run(array $data = [])
    {
        $job = $this->job();

        return dispatch(( new $job($data, $this->channel()))
            ->onConnection($this->onConnection())
            ->onQueue($this->onQueue()));
    }
}
