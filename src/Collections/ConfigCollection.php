<?php

namespace CarrionGrow\Uploader\Collections;

use CarrionGrow\Uploader\Entity\Configs\Config;
use CarrionGrow\Uploader\Factories\ConfigFactories;

/**
 * @template TKey of string|int
 * @template TValue of Config
 * @extends ArrayCollection<TKey, TValue>
 */
class ConfigCollection extends ArrayCollection
{
    /**
     * @psalm-api
     */
    public function setConfig(string $key, Config $value): void
    {
        parent::set($key, $value);
    }

    /**
     * @psalm-api
     */
    public function first(): Config
    {
        $this->addIsEmpty();
        return parent::first();
    }

    /**
     * @psalm-api
     */
    public function current(): Config
    {
        $this->addIsEmpty();
        return parent::current();
    }

    /**
     * @psalm-api
     */
    public function last(): Config
    {
        $this->addIsEmpty();
        return parent::last();
    }

    /**
     * @psalm-api
     */
    public function get(mixed $key): ?Config
    {
        return parent::get($key);
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
    public function new(string $inputName): ConfigFactories
    {
        return new ConfigFactories($this, $inputName);
    }

    private function addIsEmpty(): void
    {
        if ($this->isEmpty()) {
            $this->add(new Config());
        }
    }
}
