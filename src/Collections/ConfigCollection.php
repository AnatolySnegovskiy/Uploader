<?php

namespace CarrionGrow\Uploader\Collections;

use CarrionGrow\Uploader\Entity\Configs\BuilderConfig;
use CarrionGrow\Uploader\Entity\Configs\Config;
use CarrionGrow\Uploader\Entity\Entity;
use CarrionGrow\Uploader\Factories\ConfigFactories;

class ConfigCollection extends ArrayCollection
{
    /**
     * @param string $key
     * @param Config $value
     */
    public function set($key, $value)
    {
        parent::set($key, $value);
    }

    /**
     * @return Config
     */
    public function first(): Config
    {
        $this->addIsEmpty();
        return parent::first();
    }

    /**
     * @return Config
     */
    public function current(): Config
    {
        $this->addIsEmpty();
        return parent::current();
    }

    /**
     * @return Config
     */
    public function last(): Config
    {
        $this->addIsEmpty();
        return parent::last();
    }

    /**
     * @param $key
     * @return Config|null
     */
    public function get($key): ?Config
    {
        return parent::get($key);
    }

    /**
     * @return Config[]
     */
    public function toArray(): array
    {
        return parent::toArray();
    }

    public function new(string $inputName): ConfigFactories
    {
        return new ConfigFactories($this, $inputName);
    }

    private function addIsEmpty()
    {
        if ($this->isEmpty()) {
            $this->add(new Config());
        }
    }
}