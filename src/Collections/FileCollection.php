<?php

namespace CarrionGrow\Uploader\Collections;

use CarrionGrow\Uploader\Entity\Files\File;
use CarrionGrow\Uploader\Entity\Files\Image;
use CarrionGrow\Uploader\Entity\Files\UploadHandlerInterface;
use CarrionGrow\Uploader\Entity\Files\Video;
use CarrionGrow\Uploader\Exception\Exception;

/**
 * @template TKey extends string|int
 * @template TValue extends UploadHandlerInterface|Exception
 * @extends ArrayCollection<TKey, TValue>
 */
class FileCollection extends ArrayCollection
{
    /**
     * @psalm-api
     */
    public function setFile(string $key, UploadHandlerInterface|Exception $value): void
    {
        parent::set($key, $value);
    }

    /**
     * @return Exception[]
     * @psalm-api
     */
    public function getErrors(): array
    {
        return $this->filter(static fn (mixed $element): bool => $element instanceof Exception)->toArray();
    }

    /**
     * @return Image[]
     * @psalm-api
     */
    public function getImages(): array
    {
        return $this->filter(static fn (mixed $element): bool => $element instanceof Image)->toArray();
    }

    /**
     * @return Video[]
     * @psalm-api
     */
    public function getVideos(): array
    {
        return $this->filter(static fn (mixed $element): bool => $element instanceof Video)->toArray();
    }

    /**
     * @return File[]
     * @psalm-api
     */
    public function getFiles(): array
    {
        return $this->filter(static fn (mixed $element): bool => $element instanceof File)->toArray();
    }

    /**
     * @psalm-api
     */
    public function toArray(): array
    {
        return parent::toArray();
    }

    /**
     * @psalm-api
     */
    public function get(mixed $key): File|Exception|null
    {
        return parent::get($key);
    }
}
