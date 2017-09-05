<?php

namespace Timeslot;

use ArrayIterator;
use IteratorAggregate;

class TimeslotCollection implements IteratorAggregate, TimeslotInterface
{
    protected $collection = [];

    public function __construct(Timeslot $timeslot)
    {
        $this->collection[] = $timeslot;
    }

    public function getIterator()
    {
        return new ArrayIterator($this->collection);
    }

    /**
     * http://be2.php.net/reset
     * @return Carbon\Carbon
     */
    public function start()
    {
        return reset($this->collection)->start();
    }

    /**
     * https://secure.php.net/manual/en/function.end.php
     * @return Carbon\Carbon
     */
    public function end()
    {
        return end($this->collection)->end();
    }
}
