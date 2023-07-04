<?php

namespace CarrionGrow\Uploader\Entity\Configs;

use CarrionGrow\Uploader\Collections\ConfigCollection;

class BuilderConfig extends Config
{
    /**
     * @var ConfigCollection
     */
    private $collection;

    public function __construct(ConfigCollection $collection)
    {
        $this->collection = $collection;
        parent::__construct();
    }

    /**
     * @return ConfigCollection
     * @psalm-api
     */
    public function save(): ConfigCollection
    {
        return $this->collection;
    }
}