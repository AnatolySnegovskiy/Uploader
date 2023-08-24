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
     * @param string $key
     * @param File|Exception $value
     * @psalm-api
     */
    public function setFile(string $key, $value)
    {
        parent::set($key, $value);
    }

    /**
     * @return Exception[]
     * @psalm-api
     */
    public function getErrors(): array
    {
        return $this->filter(function ($element) : bool {
            return $element instanceof Exception;
        })->toArray();
    }

    /**
     * @return Image[]
     * @psalm-api
     */
    public function getImages(): array
    {
        return $this->filter(function ($element) : bool {
            return $element instanceof Image;
        })->toArray();
    }

    /**
     * @return Video[]
     * @psalm-api
     */
    public function getVideos(): array
    {
        return $this->filter(function ($element): bool {
            return $element instanceof Video;
        })->toArray();
    }

    /**
     * @return File[]
     * @psalm-api
     */
    public function getFiles(): array
    {
        return $this->filter(function ($element): bool {
            return $element instanceof File;
        })->toArray();
    }

    /**
     * @return ToArrayInterface[]
     * @psalm-api
     */
    public function toArray(): array
    {
        return parent::toArray();
    }

    /**
     * @param $key
     * @return File|Exception|null
     * @psalm-api
     */
    public function get($key): ?File
    {
        return parent::get($key);
    }
}