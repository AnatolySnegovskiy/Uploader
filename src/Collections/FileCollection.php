<?php

namespace CarrionGrow\Uploader\Collections;

use CarrionGrow\Uploader\Entity\Files\File;
use CarrionGrow\Uploader\Entity\Files\Image;
use CarrionGrow\Uploader\Entity\Files\Video;
use CarrionGrow\Uploader\Entity\ToArrayInterface;
use CarrionGrow\Uploader\Exception\Exception;

class FileCollection extends ArrayCollection
{
    /**
     * @return Exception[]
     */
    public function getErrors(): array
    {
        return $this->filter(function ($element) {
            return $element instanceof Exception;
        })->toArray();
    }

    /**
     * @return Image[]
     */
    public function getImages(): array
    {
        return $this->filter(function ($element) {
            return $element instanceof Image;
        })->toArray();
    }

    /**
     * @return Video[]
     */
    public function getVideos(): array
    {
        return $this->filter(function ($element) {
            return $element instanceof Video;
        })->toArray();
    }

    /**
     * @return File[]
     */
    public function getFiles(): array
    {
        return $this->filter(function ($element) {
            return $element instanceof File;
        })->toArray();
    }

    /**
     * @return ToArrayInterface[]
     */
    public function toArray(): array
    {
        return parent::toArray();
    }

    /**
     * @param $key
     * @return File|null
     */
    public function get($key): ?File
    {
        return parent::get($key);
    }
}