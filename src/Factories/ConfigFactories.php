<?php

namespace CarrionGrow\Uploader\Factories;

use CarrionGrow\Uploader\Collections\ConfigCollection;
use CarrionGrow\Uploader\Entity\Configs\BuilderImageConfig;
use CarrionGrow\Uploader\Entity\Configs\BuilderConfig;
use CarrionGrow\Uploader\Entity\Configs\BuilderVideoConfig;

class ConfigFactories
{
    private $collection;
    private $key;

    public function __construct(ConfigCollection $collection, string $key)
    {
        $this->key = $key;
        $this->collection = $collection;
    }

    public function other(): BuilderConfig
    {
        $item = new BuilderConfig($this->collection);
        $this->collection->set($this->key, $item);

        return $item;
    }

    public function image(): BuilderImageConfig
    {
        $item = new BuilderImageConfig($this->collection);
        $this->collection->set($this->key, $item);

        return $item;
    }

    public function video(): BuilderVideoConfig
    {
        $item = new BuilderVideoConfig($this->collection);
        $this->collection->set($this->key, $item);

        return $item;
    }
}