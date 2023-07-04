<?php

namespace CarrionGrow\Uploader\Entity\Configs;

use CarrionGrow\Uploader\Entity\Files\Image;

class ImageConfig extends Config
{
    /** @var int */
    private $maxWidth = 0;
    /** @var int */
    private $maxHeight = 0;
    /** @var int */
    private $minWidth = 0;
    /** @var int */
    private $minHeight = 0;

    public function __construct()
    {
        parent::__construct();
        $this->handler = new Image($this);
    }

    /**
     * @return int
     * @psalm-api
     */
    public function getMaxWidth(): int
    {
        return $this->maxWidth;
    }

    /**
     * @param int $maxWidth
     * @return self
     * @psalm-api
     */
    public function setMaxWidth(int $maxWidth): self
    {
        $this->maxWidth = max($maxWidth, 0);
        return $this;
    }

    /**
     * @return int
     * @psalm-api
     */
    public function getMaxHeight(): int
    {
        return $this->maxHeight;
    }

    /**
     * @param int $maxHeight
     * @return self
     * @psalm-api
     */
    public function setMaxHeight(int $maxHeight): self
    {
        $this->maxHeight = max($maxHeight, 0);
        return $this;
    }

    /**
     * @return int
     * @psalm-api
     */
    public function getMinWidth(): int
    {
        return $this->minWidth;
    }

    /**
     * @param int $minWidth
     * @return self
     * @psalm-api
     */
    public function setMinWidth(int $minWidth): self
    {
        $this->minWidth = max($minWidth, 0);
        return $this;
    }

    /**
     * @return int
     * @psalm-api
     */
    public function getMinHeight(): int
    {
        return $this->minHeight;
    }

    /**
     * @param int $minHeight
     * @return self
     * @psalm-api
     */
    public function setMinHeight(int $minHeight): self
    {
        $this->minHeight = max($minHeight, 0);
        return $this;
    }
}