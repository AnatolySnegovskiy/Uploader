<?php

namespace CarrionGrow\Uploader\Collections;

use CarrionGrow\Uploader\Entity\Configs\Config;
use CarrionGrow\Uploader\Factories\ConfigFactories;
use Exception;

class ConfigCollection extends ArrayCollection
{
    /**
     * @param string $key
     * @param Config $value
     * @psalm-api
     */
    public function setConfig(string $key, Config $value)
    {
        parent::set($key, $value);
    }

    /**
     * @return Config
     * @psalm-api
     * @throws Exception
     */
    public function first(): Config
    {
        $this->addIsEmpty();

        $config = parent::first();

        if (!($config instanceof Config)) {
            throw new Exception("Collection Error");
        }

        return $config;
    }

    /**
     * @return Config
     * @psalm-api
     * @throws Exception
     */
    public function current(): Config
    {
        $this->addIsEmpty();
        $config = parent::current();

        if (!($config instanceof Config)) {
            throw new Exception("Collection Error");
        }

        return $config;
    }

    /**
     * @return Config
     * @psalm-api
     * @throws Exception
     */
    public function last(): Config
    {
        $this->addIsEmpty();
        $config = parent::last();

        if (!($config instanceof Config)) {
            throw new Exception("Collection Error");
        }

        return $config;
    }

    /**
     * @param mixed $key
     * @return Config|null
     * @psalm-api
     */
    public function get($key): ?Config
    {
        return parent::get($key);
    }

    /**
     * @return Config[]
     * @psalm-api
     */
    public function toArray(): array
    {
        return parent::toArray();
    }

    /**
     * @param string $inputName
     * @return ConfigFactories
     * @psalm-api
     */
    public function new(string $inputName): ConfigFactories
    {
        return new ConfigFactories($this, $inputName);
    }

    /**
     * @return void
     */
    private function addIsEmpty()
    {
        if ($this->isEmpty()) {
            $this->add(new Config());
        }
    }
}