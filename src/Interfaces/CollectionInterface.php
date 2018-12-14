<?php
/**
 * Created by IntelliJ IDEA.
 * User: mcneely
 * Date: 9/20/18
 * Time: 11:15 PM.
 */

namespace Mcneely\Collections\Interfaces;

interface CollectionInterface extends \Traversable, \Countable
{
    /**
     * @return mixed
     */
    public function clear();

    /**
     * @return mixed
     */
    public function copy();

    /**
     * @return int
     */
    public function count();

    /**
     * @return bool
     */
    public function isEmpty();

    /**
     * @return array
     */
    public function toArray();
}
