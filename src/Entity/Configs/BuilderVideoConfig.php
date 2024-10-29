<?php

namespace CarrionGrow\Uploader\Entity\Configs;

use CarrionGrow\Uploader\Collections\ConfigCollection;

class BuilderVideoConfig extends VideoConfig
{
    private ConfigCollection $collection;

    public function __construct(ConfigCollection $collection)
    {
        $this->collection = $collection;
        parent::__construct();
    }

    /**
     * @psalm-api
     */
    public function save(): ConfigCollection
    {
        return $this->collection;
    }
}
