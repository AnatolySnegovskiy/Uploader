<?php

namespace CarrionGrow\Uploader\Entity;

use JsonSerializable;
use ReflectionClass;

class Entity implements JsonSerializable, ToArrayInterface
{
    public function jsonSerialize(): array
    {
        return $this->toArray();
    }

    public function toArray(): array
    {
        $properties = $this->getChildFields();
        $result = [];

        foreach ($properties as $property) {
            $propertyName = $property->getName();
            $method = $this->makeMethodName($propertyName);

            if (method_exists($this, $method)) {
                $result[$propertyName] = $this->$method();
            }
        }

        return $result;
    }

    /**
     * @psalm-api
     */
    public function __toArray(): array
    {
        return $this->toArray();
    }

    public function __toString(): string
    {
        $json = json_encode($this->jsonSerialize(), JSON_UNESCAPED_UNICODE);
        return $json !== false ? $json : '';
    }

    private function getChildFields(): array
    {
        $class = new ReflectionClass($this);

        return $class->getProperties();
    }

    private function makeMethodName(string $field): string
    {
        return 'get' .
            ucfirst(
                preg_replace_callback(
                    '/_+(.)/ui',
                    static fn ($a) => strtoupper($a[1]),
                    $field
                )
            );
    }
}
