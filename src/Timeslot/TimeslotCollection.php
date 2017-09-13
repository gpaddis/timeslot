<?php

namespace Timeslot;

use ArrayIterator;
use Carbon\Carbon;
use Countable;
use Exception;
use IteratorAggregate;
use OutOfRangeException;

class TimeslotCollection implements IteratorAggregate, TimeslotInterface, Countable
{
    /**
     * TimeslotCollection properties.
     *
     * @var array The collection of timeslot components.
     */
    protected $collection = [];

    /**
     * The TimeslotCollection constructor accepts an object that implements
     * TimeslotInterface and adds it to the collection.
     *
     * @param TimeslotInterface $timeslot
     */
    public function __construct(TimeslotInterface $timeslot)
    {
        $this->collection[] = $timeslot;
    }

    /**
     * Get an ArrayIterator for the collection, so that the object can be
     * iterated e.g. with a foreach loop.
     *
     * @return ArrayIterator
     */
    public function getIterator()
    {
        return new ArrayIterator($this->collection);
    }

    /**
     * Return the start date & time of the collection.
     *
     * @return Carbon\Carbon
     */
    public function start() : Carbon
    {
        return $this->get(0)->start();
    }

    /**
     * Return the end date & time of the collection.
     *
     * @return Carbon\Carbon
     */
    public function end() : Carbon
    {
        return end($this->collection)->end();
    }

    /**
     * Add a TimeslotInterface object to the collection.
     *
     * @param TimeslotInterface $timeslot
     */
    public function add(TimeslotInterface $timeslot)
    {
        $this->collection[] = $timeslot;
        $this->sort();
        return $this;
    }

    /**
     * Get the child object in the current collection.
     *
     * @param int $child
     *
     * @throws OutOfRangeException
     *
     * @return TimeslotInterface
     */
    public function get(int $offset) : TimeslotInterface
    {
        if (!array_key_exists($offset, $this->collection)) {
            throw new OutOfRangeException('The offset does not exist in this collection.');
        }

        return $this->collection[$offset];
    }

    /**
     * Remove a Timeslot from the collection.
     *
     * @param int $offset
     *
     * @return void
     */
    public function remove(int $offset)
    {
        if (!array_key_exists($offset, $this->collection)) {
            throw new OutOfRangeException('The offset does not exist in this collection.');
        }

        if (count($this->collection) <= 1) {
            throw new Exception('You cannot remove all timeslots in a collection.');
        }

        unset($this->collection[$offset]);

        $this->sort();
    }

    /**
     * Create a TimeslotCollection of simple Timeslots.
     *
     * @param Timeslot $timeslot
     * @param int|int  $quantity
     *
     * @return static
     */
    public static function create(Timeslot $timeslot, int $quantity = 1)
    {
        $collection = new static($timeslot);

        // The loop starts at one because one timeslot was already saved in the collection.
        for ($i = 1; $i < $quantity; $i++) {
            $timeslot = Timeslot::after($timeslot);
            $collection->add($timeslot);
        }

        return $collection;
    }

    /**
     * Implement Countable interface.
     *
     * @return int
     */
    public function count()
    {
        return count($this->collection);
    }

    /**
     * Sort the collection based on the start time of each of the Timeslots contained.
     *
     * @return void
     */
    public function sort()
    {
        usort($this->collection, function ($left, $right) {
            return $left->start()->timestamp - $right->start()->timestamp;
        });

        return $this;
    }
}
