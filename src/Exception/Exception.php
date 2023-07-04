<?php

namespace CarrionGrow\Uploader\Exception;

use CarrionGrow\Uploader\Entity\ToArrayInterface;

class Exception extends \Exception implements ToArrayInterface
{

    public function __construct(int $code, string $message = '')
    {
        parent::__construct($message ?: Code::MESSAGE_LIST[$code], $code);
    }

    public function toArray(): array
    {
        return ['code' => $this->getCode(), 'message' => $this->getMessage()];
    }
}