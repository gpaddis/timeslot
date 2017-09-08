<?php

namespace Timeslot;

use DateTime;
use Countable;
use ArrayIterator;
use Carbon\Carbon;
use IteratorAggregate;

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
    public function start()
    {
        return reset($this->collection)->start();
    }

    /**
     * Return the end date & time of the collection.
     *
     * @return Carbon\Carbon
     */
    public function end()
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
        $start = $collection->start();

        // The loop starts at one because one timeslot was already saved in the collection.
        for ($i = 1; $i < $quantity; $i++) {
            $newSlot = new Timeslot(Carbon::instance($start)->addHour($i));
            $collection->add($newSlot);
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
}
