<?php

namespace CarrionGrow\Uploader\Entity\Files;

use CarrionGrow\Uploader\Entity\Configs\ImageConfig;
use CarrionGrow\Uploader\Exception\ImageException;

class Image extends File
{
    /** @var int  */
    protected $width;
    /** @var int  */
    protected $height;
    /** @var string  */
    protected $imageType;
    /** @var string  */
    protected $resolution;
    /** @var ImageConfig */
    protected $config;
#region getter
    /**
     * @return int
     * @psalm-api
     */
    public function getWidth(): int
    {
        return $this->width;
    }

    /**
     * @return int
     * @psalm-api
     */
    public function getHeight(): int
    {
        return $this->height;
    }

    /**
     * @return string
     * @psalm-api
     */
    public function getImageType(): string
    {
        return $this->imageType;
    }

    /**
     * @return string
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

    public function behave(array $file)
    {
        parent::behave($file);

        if (!function_exists('getimagesize')) {
            return;
        }

        $dimension = @getimagesize($this->getTempPath());

        if ($this->config->getMaxWidth() > 0 && $dimension[0] > $this->config->getMaxWidth())
            throw ImageException::widthLarger($this->config->getMaxWidth());

        if ($this->config->getMaxHeight() > 0 && $dimension[1] > $this->config->getMaxHeight())
            throw ImageException::heightLarger($this->config->getMaxHeight());

        if ($this->config->getMinWidth() > 0 && $dimension[0] < $this->config->getMinWidth())
            throw ImageException::widthLess($this->config->getMinWidth());

        if ($this->config->getMinHeight() > 0 && $dimension[1] < $this->config->getMinHeight())
            throw ImageException::heightLess($this->config->getMinHeight());

        $types = [1 => 'gif', 2 => 'jpeg', 3 => 'png'];

        $this->width = $dimension[0];
        $this->height = $dimension[1];
        $this->imageType = $types[$dimension[2]] ?? 'unknown';
        $this->resolution = sprintf('%dx%d', $this->width, $this->height);
    }
}