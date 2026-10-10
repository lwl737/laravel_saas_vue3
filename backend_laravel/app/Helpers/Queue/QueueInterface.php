<?php

declare(strict_types=1);

namespace App\Helpers\Queue;

interface QueueInterface
{
    public function run(array $data);
    public function job(): string|null;
    public function onConnection(): string;


    public function onQueue(): string;

    public function channel(): string;
}
