<?php

namespace CarrionGrow\Uploader\Factories;

use CarrionGrow\Uploader\Entity\Configs\Config;
use CarrionGrow\Uploader\Entity\Files\UploadHandlerInterface;
use CarrionGrow\Uploader\Entity\Mimes;

class FileFactories
{
    private Config $config;

    public function __construct(Config $config)
    {
        $this->config = $config;
    }

    /**
     * @param array<string, mixed> $tempData
     * @return UploadHandlerInterface
     */
    public function init(array $tempData): UploadHandlerInterface
    {
        $type = Mimes::getFileType($tempData);
        $tempData['mimes'] = $type;
        $file = clone $this->config->getHandler();
        $file->behave($tempData);

        return $file;
    }
}
