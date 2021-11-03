<?php

namespace CarrionGrow\Uploader\Exception;

use CarrionGrow\Uploader\Entity\ToArrayInterface;
use Throwable;

class Exception extends \Exception implements ToArrayInterface
{

    public function __construct($code, $message = '')
    {
        parent::__construct($message ?: Code::MESSAGE_LIST[$code], $code);
    }

    public function toArray(): array
    {
        return ['code' => $this->getCode(), 'message' => $this->message];
    }
}