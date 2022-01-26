<?php

namespace CarrionGrow\Uploader\Factories;

use CarrionGrow\Uploader\Entity\Configs\Config;
use CarrionGrow\Uploader\Entity\Configs\ImageConfig;
use CarrionGrow\Uploader\Entity\Configs\VideoConfig;
use CarrionGrow\Uploader\Entity\Files\File;
use CarrionGrow\Uploader\Entity\Files\Image;
use CarrionGrow\Uploader\Entity\Files\Video;
use CarrionGrow\Uploader\Entity\Mimes;
use CarrionGrow\Uploader\Exception\Exception;

class FileFactories
{
    private $config;

    public function __construct(Config $config)
    {
        $this->config = $config;
    }

    /**
     * @param array $tempData
     * @return File
     */
    public function init(array $tempData): File
    {
        $type = Mimes::getFileType($tempData);
        $tempData['mimes'] = $type;
        $file = clone $this->config->getHandler();
        $file->behave($tempData);

        return $file;
    }
}