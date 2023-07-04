<?php

namespace CarrionGrow\Uploader\Collections;

use ArrayIterator;
use Closure;

abstract class ArrayCollection implements CollectionInterface
{
    /*** @var array */
    private $elements;

    /**
     * @param array $_elements
     */
    public function __construct(array $_elements = [])
    {
        $this->elements = $_elements;
    }

    /**
     * @return array
     * @psalm-api
     */
    public function toArray(): array
    {
        return $this->elements;
    }

    /**
     * @return false|mixed
     * @psalm-api
     */
    public function first()
    {
        return reset($this->elements);
    }

    /**
     * @param array $elements
     * @return static
     * @psalm-api
     */
    protected function createFrom(array $elements): ArrayCollection
    {
        return new static($elements);
    }

    /**
     * @return false|mixed
     * @psalm-api
     */
    public function last()
    {
        return end($this->elements);
    }

    /**
     * @return int|string|null
     * @psalm-api
     */
    public function key()
    {
        return key($this->elements);
    }

    /**
     * @return false|mixed
     * @psalm-api
     */
    public function next()
    {
        return next($this->elements);
    }

    /**
     * @return false|mixed
     * @psalm-api
     */
    public function current()
    {
        return current($this->elements);
    }

    /**
     * @param mixed $key
     * @return mixed|null
     * @psalm-api
     */
    public function remove($key)
    {
        if (!isset($this->elements[$key]) && !array_key_exists($key, $this->elements)) {
            return null;
        }

        $removed = $this->elements[$key];
        unset($this->elements[$key]);

        return $removed;
    }

    /**
     * @param mixed $element
     * @return bool
     * @psalm-api
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
     * @psalm-api
     */
    public function offsetExists($offset): bool
    {
        return $this->containsKey($offset);
    }

    /**
     * @param mixed $offset
     * @return mixed|null
     * @psalm-api
     */
    public function offsetGet($offset)
    {
        return $this->get($offset);
    }

    /**
     * @param mixed $offset
     * @param mixed $value
     * @psalm-api
     */
    public function offsetSet($offset, $value)
    {
        if (!isset($offset)) {
            $this->add($value);

            return;
        }

        $this->set($offset, $value);
    }

    /**
     * @param mixed $offset
     * @psalm-api
     */
    public function offsetUnset($offset)
    {
        $this->remove($offset);
    }

    /**
     * @param mixed $key
     * @return bool
     * @psalm-api
     */
    public function containsKey($key): bool
    {
        return isset($this->elements[$key]) || array_key_exists($key, $this->elements);
    }

    /**
     * @param mixed $element
     * @return bool
     * @psalm-api
     */
    public function contains($element): bool
    {
        return in_array($element, $this->elements, true);
    }

    /**
     * @param Closure $p
     * @return bool
     * @psalm-api
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
     * @param mixed $element
     * @return false|int|string
     * @psalm-api
     */
    public function indexOf($element)
    {
        return array_search($element, $this->elements, true);
    }

    /**
     * @param mixed $key
     * @return mixed|null
     * @psalm-api
     */
    public function get($key)
    {
        return $this->elements[$key] ?? null;
    }

    /**
     * @return array
     * @psalm-api
     */
    public function getKeys(): array
    {
        return array_keys($this->elements);
    }

    /**
     * @return array
     * @psalm-api
     */
    public function getValues(): array
    {
        return array_values($this->elements);
    }

    /**
     * @return int
     * @psalm-api
     */
    public function count(): int
    {
        return count($this->elements);
    }

    /**
     * @param mixed $key
     * @param mixed $value
     * @return void
     * @psalm-api
     */
    protected function set($key, $value)
    {
        $this->elements[$key] = $value;
    }

    /**
     * @param mixed $element
     * @return bool
     * @psalm-api
     */
    public function add($element): bool
    {
        $this->elements[] = $element;

        return true;
    }

    /**
     * @return bool
     * @psalm-api
     */
    public function isEmpty(): bool
    {
        return empty($this->elements);
    }

    /**
     * @return ArrayIterator
     * @psalm-api
     */
    public function getIterator(): ArrayIterator
    {
        return new ArrayIterator($this->elements);
    }

    /**
     * @param Closure $func
     * @return $this
     * @psalm-api
     */
    public function map(Closure $func): ArrayCollection
    {
        return $this->createFrom(array_map($func, $this->elements));
    }

    /**
     * @param Closure $p
     * @return $this
     * @psalm-api
     */
    public function filter(Closure $p): ArrayCollection
    {
        return $this->createFrom(array_filter($this->elements, $p, ARRAY_FILTER_USE_BOTH));
    }

    /**
     * @param Closure $p
     * @return bool
     * @psalm-api
     */
    public function forAll(Closure $p): bool
    {
        foreach ($this->elements as $key => $element) {
            if (!$p($key, $element)) {
                return false;
            }
        }

        return true;
    }

    /**
     * @param Closure $p
     * @return array
     * @psalm-api
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
     * @psalm-api
     */
    public function __toString(): string
    {
        return self::class . '@' . spl_object_hash($this);
    }


    /**
     * @return void
     * @psalm-api
     */
    public function clear()
    {
        $this->elements = [];
    }

    /**
     * @param int $offset
     * @param int|null $length
     * @return array
     * @psalm-api
     */
    public function slice(int $offset, int $length = null): array
    {
        return array_slice($this->elements, $offset, $length, true);
    }
}