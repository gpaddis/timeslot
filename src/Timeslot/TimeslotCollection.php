<?php

namespace Timeslot;

use ArrayIterator;
use IteratorAggregate;

class TimeslotCollection implements IteratorAggregate, TimeslotInterface
{
    protected $collection = [];

    /**
     * TimeslotCollection constructor.
     *
     * @param TimeslotInterface $timeslot
     */
    public function __construct(TimeslotInterface $timeslot)
    {
        $this->collection[] = $timeslot;
    }

    public function getIterator()
    {
        return new ArrayIterator($this->collection);
    }

    /**
     * Return the start date & time of the collection.
     *
     * http://be2.php.net/reset
     * @return Carbon\Carbon
     */
    public function start()
    {
        return reset($this->collection)->start();
    }

    /**
     * Return the end date & time of the collection.
     *
     * https://secure.php.net/manual/en/function.end.php
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
}
