<?php

namespace CarrionGrow\Uploader\Exception;

use CarrionGrow\Uploader\Entity\ToArrayInterface;

class Exception extends \Exception implements ToArrayInterface
{
    public function __construct(Code $code, string $message = '')
    {
        parent::__construct($message ?: Code::getMessage($code), $code->value);
    }

    public function toArray(): array
    {
        return ['code' => $this->getCode(), 'message' => $this->getMessage()];
    }
}
