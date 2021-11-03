<?php

namespace CarrionGrow\Uploader\Collections;

use ArrayIterator;
use CarrionGrow\Uploader\Entity\Configs\Config;
use CarrionGrow\Uploader\Entity\Entity;
use Closure;

class ArrayCollection implements CollectionInterface
{
    /*** @var array */
    private $elements;

    /**
     * @param array $elements
     */
    public function __construct(array $elements = [])
    {
        $this->elements = $elements;
    }

    /**
     * @return array
     */
    public function toArray(): array
    {
        return $this->elements;
    }

    /**
     * @return false|mixed
     */
    public function first()
    {
        return reset($this->elements);
    }

    /**
     * @param array $elements
     * @return $this
     */
    protected function createFrom(array $elements)
    {
        return new static($elements);
    }

    /**
     * @return false|mixed
     */
    public function last()
    {
        return end($this->elements);
    }

    /**
     * @return int|string|null
     */
    public function key()
    {
        return key($this->elements);
    }

    /**
     * @return false|mixed
     */
    public function next()
    {
        return next($this->elements);
    }

    /**
     * @return false|mixed
     */
    public function current()
    {
        return current($this->elements);
    }

    /**
     * @param $key
     * @return mixed|null
     */
    public function remove($key)
    {
        if (! isset($this->elements[$key]) && ! array_key_exists($key, $this->elements)) {
            return null;
        }

        $removed = $this->elements[$key];
        unset($this->elements[$key]);

        return $removed;
    }

    /**
     * @param $element
     * @return bool
     */
    public function removeElement($element): bool
    {
        $key = array_search($element, $this->elements, true);

        if ($key === false) {
            return false;
        }

        unset($this->elements[$key]);

        return true;
    }

    /**
     * @param mixed $offset
     * @return bool
     */
    public function offsetExists($offset): bool
    {
        return $this->containsKey($offset);
    }

    /**
     * @param mixed $offset
     * @return mixed|null
     */
    public function offsetGet($offset)
    {
        return $this->get($offset);
    }

    /**
     * @param mixed $offset
     * @param mixed $value
     */
    public function offsetSet($offset, $value)
    {
        if (! isset($offset)) {
            $this->add($value);

            return;
        }

        $this->set($offset, $value);
    }

    /**
     * @param mixed $offset
     */
    public function offsetUnset($offset)
    {
        $this->remove($offset);
    }

    /**
     * @param $key
     * @return bool
     */
    public function containsKey($key): bool
    {
        return isset($this->elements[$key]) || array_key_exists($key, $this->elements);
    }

    /**
     * @param $element
     * @return bool
     */
    public function contains($element): bool
    {
        return in_array($element, $this->elements, true);
    }

    /**
     * @param Closure $p
     * @return bool
     */
    public function exists(Closure $p): bool
    {
        foreach ($this->elements as $key => $element) {
            if ($p($key, $element)) {
                return true;
            }
        }

        return false;
    }

    /**
     * @param $element
     * @return false|int|string
     */
    public function indexOf($element)
    {
        return array_search($element, $this->elements, true);
    }

    /**
     * @param $key
     * @return mixed|null
     */
    public function get($key)
    {
        return $this->elements[$key] ?? null;
    }

    /**
     * @return array
     */
    public function getKeys(): array
    {
        return array_keys($this->elements);
    }

    /**
     * @return array
     */
    public function getValues(): array
    {
        return array_values($this->elements);
    }

    /**
     * @return int
     */
    public function count(): int
    {
        return count($this->elements);
    }

    /**
     * @param mixed $key
     * @param mixed $value
     */
    public function set($key, $value)
    {
        $this->elements[$key] = $value;
    }

    /**
     * @param $element
     * @return bool
     */
    public function add($element): bool
    {
        $this->elements[] = $element;

        return true;
    }

    /**
     * @return bool
     */
    public function isEmpty(): bool
    {
        return empty($this->elements);
    }

    /**
     * @return ArrayIterator
     */
    public function getIterator(): ArrayIterator
    {
        return new ArrayIterator($this->elements);
    }

    /**
     * @param Closure $func
     * @return $this
     */
    public function map(Closure $func): ArrayCollection
    {
        return $this->createFrom(array_map($func, $this->elements));
    }

    /**
     * @param Closure $p
     * @return $this
     */
    public function filter(Closure $p): ArrayCollection
    {
        return $this->createFrom(array_filter($this->elements, $p, ARRAY_FILTER_USE_BOTH));
    }

    /**
     * @param Closure $p
     * @return bool
     */
    public function forAll(Closure $p): bool
    {
        foreach ($this->elements as $key => $element) {
            if (! $p($key, $element)) {
                return false;
            }
        }

        return true;
    }

    /**
     * @param Closure $p
     * @return array
     */
    public function partition(Closure $p): array
    {
        $matches = $noMatches = [];

        foreach ($this->elements as $key => $element) {
            if ($p($key, $element)) {
                $matches[$key] = $element;
            } else {
                $noMatches[$key] = $element;
            }
        }

        return [$this->createFrom($matches), $this->createFrom($noMatches)];
    }

    /**
     * @return string
     */
    public function __toString(): string
    {
        return self::class . '@' . spl_object_hash($this);
    }


    public function clear()
    {
        $this->elements = [];
    }

    /**
     * @param $offset
     * @param null $length
     * @return array
     */
    public function slice($offset, $length = null): array
    {
        return array_slice($this->elements, $offset, $length, true);
    }
}