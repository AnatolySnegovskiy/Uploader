<?php

namespace CarrionGrow\Uploader\Collections;

use ArrayAccess;
use Countable;
use IteratorAggregate;

/**
 * @template TKey
 * @template TValue
 * @extends IteratorAggregate<TKey, TValue>
 * @extends ArrayAccess<TKey, TValue>
 */
interface CollectionInterface extends Countable, IteratorAggregate, ArrayAccess
{
}
