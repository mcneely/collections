<?php

declare(strict_types=1);

namespace Mcneely\Collections;

use ArrayAccess;
use Iterator;
use Mcneely\Collections\Interfaces\CollectionInterface;
use Mcneely\Collections\Traits\CollectionTrait;
use Mcneely\Core\Traits\ArrayAccessTrait;
use Mcneely\Core\Traits\CoreTrait;
use Mcneely\Core\Traits\CountableTrait;
use Mcneely\Core\Traits\IteratorTrait;
use Traversable;

/**
 * Class AbstractCollection.
 *
 * @package Mcneely\Collections
 */
abstract class AbstractCollection implements Iterator, CollectionInterface, ArrayAccess
{
    use CoreTrait;
    use CountableTrait;
    use ArrayAccessTrait;
    use IteratorTrait;
    use CollectionTrait;

    /**
     * AbstractCollection constructor.
     *
     * @param array|Traversable $items
     */
    public function __construct($items = [])
    {
        $this->setIterator($items);
        $this->CoreTrait_fireEvents($this, __CLASS__, __METHOD__, __TRAIT__);
    }
}
