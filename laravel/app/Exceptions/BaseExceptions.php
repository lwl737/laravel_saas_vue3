<?php

declare(strict_types=1);
namespace App\Exceptions;
use Exception;
use App\Helpers\Output\Json;

abstract class BaseExceptions extends Exception
{

    public function __construct(
        public readonly Json $result,
        private Exception|null $exception = null,
        private bool  $addLog = false
    )
    {
        parent::__construct($this->result->getMessage() );
    }


    public function getResult():Json
    {
         return $this->result;
    }

    public function getAddLog():bool
    {
         return $this->addLog;
    }

    public function getException():Exception|null{
        return $this->exception;
    }
}
