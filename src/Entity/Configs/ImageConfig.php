<?php

namespace CarrionGrow\Uploader\Entity\Configs;

use CarrionGrow\Uploader\Entity\Files\Image;

class ImageConfig extends Config
{
    private int $maxWidth = 0;

    private int $maxHeight = 0;

    private int $minWidth = 0;

    private int $minHeight = 0;

    public function __construct()
    {
        parent::__construct();
        $this->handler = new Image($this);
    }

    /**
     * @psalm-api
     */
    public function getMaxWidth(): int
    {
        return $this->maxWidth;
    }

    /**
     * @psalm-api
     */
    public function setMaxWidth(int $maxWidth): self
    {
        $this->maxWidth = max($maxWidth, 0);
        return $this;
    }

    /**
     * @psalm-api
     */
    public function getMaxHeight(): int
    {
        return $this->maxHeight;
    }

    /**
     * @psalm-api
     */
    public function setMaxHeight(int $maxHeight): self
    {
        $this->maxHeight = max($maxHeight, 0);
        return $this;
    }

    /**
     * @psalm-api
     */
    public function getMinWidth(): int
    {
        return $this->minWidth;
    }

    /**
     * @psalm-api
     */
    public function setMinWidth(int $minWidth): self
    {
        $this->minWidth = max($minWidth, 0);
        return $this;
    }

    /**
     * @psalm-api
     */
    public function getMinHeight(): int
    {
        return $this->minHeight;
    }

    /**
     * @psalm-api
     */
    public function setMinHeight(int $minHeight): self
    {
        $this->minHeight = max($minHeight, 0);
        return $this;
    }
}
