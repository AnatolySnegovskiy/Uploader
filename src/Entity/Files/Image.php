<?php

namespace CarrionGrow\Uploader\Entity\Files;

use CarrionGrow\Uploader\Entity\Configs\ImageConfig;
use CarrionGrow\Uploader\Exception\Code;
use CarrionGrow\Uploader\Exception\Exception;
use CarrionGrow\Uploader\Exception\ImageException;

class Image extends File
{
    protected int $width = 0;

    protected int $height = 0;

    protected string $imageType = '';

    protected string $resolution = '';

    #region getter

    /**
     * @psalm-api
     */
    public function getWidth(): int
    {
        return $this->width;
    }

    /**
     * @psalm-api
     */
    public function getHeight(): int
    {
        return $this->height;
    }

    /**
     * @psalm-api
     */
    public function getImageType(): string
    {
        return $this->imageType;
    }

    /**
     * @psalm-api
     */
    public function getResolution(): string
    {
        return $this->resolution;
    }

    #endregion

    public function __construct(ImageConfig $config)
    {
        parent::__construct($config);
    }

    public function behave(array $file): void
    {
        parent::behave($file);

        if (!function_exists('getimagesize')) {
            return;
        }

        $dimension = @getimagesize($this->getTempPath());

        if (!$dimension || $dimension[0] === 0) {
            $this->imageType = $this->getExtension();
            return;
        }
        $config = $this->getConfig();
        if ($config->getMaxWidth() > 0 && $dimension[0] > $config->getMaxWidth()) {
            throw ImageException::widthLarger($config->getMaxWidth());
        }

        if ($config->getMaxHeight() > 0 && $dimension[1] > $config->getMaxHeight()) {
            throw ImageException::heightLarger($config->getMaxHeight());
        }

        if ($config->getMinWidth() > 0 && $dimension[0] < $config->getMinWidth()) {
            throw ImageException::widthLess($config->getMinWidth());
        }

        if ($config->getMinHeight() > 0 && $dimension[1] < $config->getMinHeight()) {
            throw ImageException::heightLess($config->getMinHeight());
        }

        $types = [1 => 'gif', 2 => 'jpeg', 3 => 'png'];

        $this->width = $dimension[0];
        $this->height = $dimension[1];
        $this->imageType = $types[$dimension[2]] ?? 'unknown';
        $this->resolution = sprintf('%dx%d', $this->width, $this->height);
    }

    public function setResolution(string $resolution): Image
    {
        $this->resolution = $resolution;
        return $this;
    }

    /**
     * @throws Exception
     */
    public function getConfig(): ImageConfig
    {
        if (!($this->config instanceof ImageConfig)) {
            throw new Exception(Code::ERROR_CONFIG, 'Config must be instance of ' . ImageConfig::class);
        }

        return $this->config;
    }
}
