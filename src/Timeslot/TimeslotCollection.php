<?php

namespace Timeslot;

use Countable;
use ArrayIterator;
use Carbon\Carbon;
use IteratorAggregate;
use OutOfRangeException;

class TimeslotCollection implements IteratorAggregate, TimeslotInterface, Countable
{
    /**
     * TimeslotCollection properties.
     *
     * @var array $collection The collection of timeslot components.
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
        $start = $this->collection[0]->start();

        foreach ($this->collection as $timeslot) {
            if ($timeslot->start()->lt($start)) {
                $start = $timeslot->start();
            }
        }
        return $start;
    }

    /**
     * Return the end date & time of the collection.
     *
     * @return Carbon\Carbon
     */
    public function end() : Carbon
    {
        $end = $this->collection[0]->end();

        foreach ($this->collection as $timeslot) {
            if ($timeslot->end()->gt($end)) {
                $end = $timeslot->end();
            }
        }
        return $end;
    }

    /**
     * Add a TimeslotInterface object to the collection.
     *
     * @param TimeslotInterface $timeslot
     */
    public function add(TimeslotInterface $timeslot)
    {
        $this->collection[] = $timeslot;
    }

    /**
     * Create a TimeslotCollection of simple Timeslots.
     *
     * @param  Timeslot     $timeslot
     * @param  int|integer  $quantity
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
     * Get the child object in the current collection.
     *
     * @param  int    $child
     *
     * @throws OutOfRangeException
     *
     * @return TimeslotInterface
     */
    public function get(int $child) : TimeslotInterface
    {
        if (! array_key_exists($child, $this->collection)) {
            throw new OutOfRangeException('The offset does not exist in this collection.');
        }

        return $this->collection[$child];
    }
}
