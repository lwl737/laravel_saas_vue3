<?php

declare(strict_types=1);
namespace App\Exceptions;
use App\Helpers\Output\Interface\Json as JsonInterface;
use App\Helpers\Output\Json;
class HttpCustomException extends BaseExceptions
{
    public function __construct(
        public readonly JsonInterface $http,
        public readonly array $replace = [],
        public readonly \Exception|null  $exception = null,
        private bool  $addLog = false
    )
    {
        parent::__construct(new Json([],$this->http,[],$this->replace) , $this->exception , $this->addLog );
    }
}
