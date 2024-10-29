<?php

namespace CarrionGrow\Uploader\Factories;

use CarrionGrow\Uploader\Collections\ConfigCollection;
use CarrionGrow\Uploader\Entity\Configs\BuilderImageConfig;
use CarrionGrow\Uploader\Entity\Configs\BuilderConfig;
use CarrionGrow\Uploader\Entity\Configs\BuilderVideoConfig;

class ConfigFactories
{
    private ConfigCollection $collection;
    private string $key;

    public function __construct(ConfigCollection $collection, string $key)
    {
        $this->key = $key;
        $this->collection = $collection;
    }

    /**
     * @psalm-api
     */
    public function other(): BuilderConfig
    {
        $item = new BuilderConfig($this->collection);
        $this->collection->setConfig($this->key, $item);

        return $item;
    }

    /**
     * @psalm-api
     */
    public function image(): BuilderImageConfig
    {
        $item = new BuilderImageConfig($this->collection);
        $this->collection->setConfig($this->key, $item);

        return $item;
    }

    /**
     * @psalm-api
     */
    public function video(): BuilderVideoConfig
    {
        $item = new BuilderVideoConfig($this->collection);
        $this->collection->setConfig($this->key, $item);

        return $item;
    }
}
