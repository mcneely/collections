<?php
/**
 * Created by IntelliJ IDEA.
 * User: mcneely
 * Date: 9/20/18
 * Time: 11:15 PM
 */

namespace Mcneely\Collections\Interfaces;


interface CollectionInterface extends \Traversable, \Countable
{
    /**
     * @return mixed
     */
    function clear();

    /**
     * @return mixed
     */
    function copy();

    /**
     * @return integer
     */
    function count();

    /**
     * @return boolean
     */
    function isEmpty();

    /**
     * @return array
     */
    function toArray();
}