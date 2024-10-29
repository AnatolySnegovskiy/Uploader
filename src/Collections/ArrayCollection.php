<?php

namespace CarrionGrow\Uploader\Collections;

use ArrayIterator;
use Closure;

/**
 * @template TKey
 * @template TValue
 * @implements CollectionInterface<TKey, TValue>
 */
abstract class ArrayCollection implements CollectionInterface
{
    private array $elements;

    final public function __construct(array $_elements = [])
    {
        $this->elements = $_elements;
    }

    /**
     * @psalm-api
     */
    public function toArray(): array
    {
        return $this->elements;
    }

    /**
     * @psalm-api
     */
    public function first(): mixed
    {
        return reset($this->elements);
    }

    /**
     * @psalm-api
     */
    protected function createFrom(array $elements): ArrayCollection
    {
        return new static($elements);
    }

    /**
     * @psalm-api
     */
    public function last(): mixed
    {
        return end($this->elements);
    }

    /**
     * @psalm-api
     */
    public function key(): int|string|null
    {
        return key($this->elements);
    }

    /**
     * @psalm-api
     */
    public function next(): mixed
    {
        return next($this->elements);
    }

    /**
     * @psalm-api
     */
    public function current(): mixed
    {
        return current($this->elements);
    }

    /**
     * @psalm-api
     */
    public function remove(mixed $key): mixed
    {
        if (!isset($this->elements[$key]) && !array_key_exists($key, $this->elements)) {
            return null;
        }

        $removed = $this->elements[$key];
        unset($this->elements[$key]);

        return $removed;
    }

    /**
     * @psalm-api
     */
    public function removeElement(mixed $element): bool
    {
        $key = array_search($element, $this->elements, true);

        if ($key === false) {
            return false;
        }

        unset($this->elements[$key]);

        return true;
    }

    /**
     * @psalm-api
     */
    public function offsetExists(mixed $offset): bool
    {
        return $this->containsKey($offset);
    }

    /**
     * @psalm-api
     */
    public function offsetGet(mixed $offset): mixed
    {
        return $this->get($offset);
    }

    /**
     * @psalm-api
     */
    public function offsetSet(mixed $offset, mixed $value): void
    {
        if (!isset($offset)) {
            $this->add($value);

            return;
        }

        $this->set($offset, $value);
    }

    /**
     * @psalm-api
     */
    public function offsetUnset(mixed $offset): void
    {
        $this->remove($offset);
    }

    /**
     * @psalm-api
     */
    public function containsKey(mixed $key): bool
    {
        return isset($this->elements[$key]) || array_key_exists($key, $this->elements);
    }

    /**
     * @psalm-api
     */
    public function contains(mixed $element): bool
    {
        return in_array($element, $this->elements, true);
    }

    /**
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
     * @psalm-api
     */
    public function indexOf(mixed $element): bool|int|string
    {
        return array_search($element, $this->elements, true);
    }

    /**
     * @psalm-api
     */
    public function get(mixed $key): mixed
    {
        return $this->elements[$key] ?? null;
    }

    /**
     * @psalm-api
     */
    public function getKeys(): array
    {
        return array_keys($this->elements);
    }

    /**
     * @psalm-api
     */
    public function getValues(): array
    {
        return array_values($this->elements);
    }

    /**
     * @psalm-api
     */
    public function count(): int
    {
        return count($this->elements);
    }

    /**
     * @psalm-api
     */
    public function set(mixed $key, mixed $value): void
    {
        $this->elements[$key] = $value;
    }

    /**
     * @psalm-api
     */
    public function add(mixed $element): bool
    {
        $this->elements[] = $element;

        return true;
    }

    /**
     * @psalm-api
     */
    public function isEmpty(): bool
    {
        return empty($this->elements);
    }

    /**
     * @psalm-api
     */
    public function getIterator(): ArrayIterator
    {
        return new ArrayIterator($this->elements);
    }

    /**
     * @psalm-api
     */
    public function map(Closure $func): ArrayCollection
    {
        return $this->createFrom(array_map($func, $this->elements));
    }

    /**
     * @psalm-api
     */
    public function filter(Closure $p): ArrayCollection
    {
        return $this->createFrom(array_filter($this->elements, $p, ARRAY_FILTER_USE_BOTH));
    }

    /**
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
     * @psalm-api
     */
    public function __toString(): string
    {
        return self::class . '@' . spl_object_hash($this);
    }

    /**
     * @psalm-api
     */
    public function clear(): void
    {
        $this->elements = [];
    }

    /**
     * @psalm-api
     */
    public function slice(int $offset, int $length = null): array
    {
        return array_slice($this->elements, $offset, $length, true);
    }
}
