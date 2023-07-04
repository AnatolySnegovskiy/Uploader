<?php

namespace CarrionGrow\Uploader\Entity;

use JsonSerializable;
use ReflectionClass;

class Entity implements JsonSerializable, ToArrayInterface
{
    /**
     * @return array
     */
    public function jsonSerialize(): array
    {
        return $this->toArray();
    }

    /**
     * @return array
     */
    public function toArray(): array
    {
        $properties = $this->getChildFields();
        $result = [];

        foreach ($properties as $property) {
            $propertyName = $property->getName();
            $method = $this->makeMethodName($propertyName, 'get');

            if (method_exists($this, $method)) {
                $result[$propertyName] = $this->$method();
            }
        }

        return $result;
    }

    /**
     * @return array
     * @psalm-api
     */
    public function __toArray(): array
    {
        return $this->toArray();
    }

    /**
     * @return false|string
     */
    public function __toString()
    {
        return json_encode($this->jsonSerialize(), JSON_UNESCAPED_UNICODE);
    }

    /**
     * @return array
     */
    private function getChildFields(): array
    {
        $class = new ReflectionClass($this);

        return $class->getProperties();
    }

    /**
     * @param string $field
     * @param string $prefix
     * @return string
     */
    private function makeMethodName(string $field, string $prefix): string
    {
        return $prefix . ucfirst(preg_replace_callback('/\_+(.)/ui', function ($a) {
                return strtoupper($a[1]);
            }, $field));
    }
}