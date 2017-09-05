<?php

namespace Timeslot;

use ArrayIterator;
use Carbon\Carbon;
use IteratorAggregate;

class Timeslot implements IteratorAggregate, TimeslotInterface
{
    protected $start;
    protected $hours;
    protected $end;

    protected $collection = [];

    protected function __construct(Carbon $start, int $hours)
    {
        $this->start = $start;
        $this->hours = $hours;
        $this->end = $this->setEnd($hours);

        // TODO: check the logic here. Objects are passed by reference, not by value.
        $this->collection[] = $this;
    }

    public function getIterator()
    {
        return new ArrayIterator($this->collection);
    }

    /**
     * Set the start date / time for the timeslot.
     *
     * @param Carbon\Carbon $start
     */
    protected function setStart($start)
    {
        $this->start = $start->minute(0)->second(0);
    }

    /**
     * Set the end date / time for the timeslot.
     *
     * @param Carbon\Carbon $end
     */
    protected function setEnd($hours)
    {
        // If the interval is 1 hour, set it to 0 hours, 59 mins and 59 secs
        $hours -= 1;

        $this->end = clone $this->start;
        $this->end->addHours($hours)->minute(59)->second(59);
    }

    /**
     * Add a specific number of $hours to the timeslot's start and end date / time.
     *
     * @param int $hours
     */
    public function addHour(int $hours = 1)
    {
        $this->start->addHour($hours);
        $this->end->addHour($hours);

        return $this;
    }

    /**
     * Get the start date / time.
     *
     * @return Carbon\Carbon
     */
    public function start()
    {
        return $this->start;
    }

    /**
     * Get the end date / time.
     *
     * @return Carbon\Carbon
     */
    public function end()
    {
        return $this->end;
    }

    /**
     * Get an array of start and end date / time.
     *
     * @return array
     */
    public function toArray()
    {
        return [
            'start' => $this->start(),
            'end' => $this->end()
        ];
    }

    /**
     * Return a Timeslot on a custom date / time.
     *
     * @param  Carbon\Carbon $start
     * @param  integer $hours
     * @return App\Timeslot
     */
    public static function custom($start, $hours = 1)
    {
        $timeslot = new Timeslot($start, $hours);
        $timeslot->setStart($timeslot->start);
        $timeslot->setEnd($timeslot->hours);
        return $timeslot;
    }

    /**
     * Create a new Timeslot instance based on the current date / time.
     *
     * @param  integer $hours
     * @return App\Timeslot
     */
    public static function now($hours = 1)
    {
        return Timeslot::custom(Carbon::now(), $hours);
    }

    /**
     * Return a Timeslot with start date / time today 00:00:00
     * and end date / time today 23:59:59.
     *
     * @return App\Timeslot
     */
    public static function today()
    {
        $timeslot = Timeslot::now();
        $timeslot->start->startOfDay();
        $timeslot->end->endOfDay();
        return $timeslot;
    }

    /**
     * Return a Timeslot with start date / time last Monday 00:00:00
     * and end date / time next Sunday 23:59:59.
     *
     * @return App\Timeslot
     */
    public static function thisWeek()
    {
        $timeslot = Timeslot::now();
        $timeslot->start->startOfWeek();
        $timeslot->end->endOfWeek();
        return $timeslot;
    }

    /**
     * Like thisWeek(), but one week before.
     *
     * @return App\Timeslot
     */
    public static function lastWeek()
    {
        $timeslot = Timeslot::thisWeek();
        $timeslot->start->subWeek();
        $timeslot->end->subWeek();
        return $timeslot;
    }

    /**
     * Create a Timeslot with start date / time on the first day
     * of the current month - 00:00:00 and end date / time
     * on the last day of the current month - 23:59:59.
     *
     * @return App\Timeslot
     */
    public static function thisMonth()
    {
        $timeslot = Timeslot::now();
        $timeslot->start->startOfMonth();
        $timeslot->end->endOfMonth();
        return $timeslot;
    }

    /**
     * Like thisMonth(), but one month before.
     *
     * @return App\Timeslot
     */
    public static function lastMonth()
    {
        $timeslot = Timeslot::custom(Carbon::now()->subMonth());
        $timeslot->start->startOfMonth();
        $timeslot->end->endOfMonth();
        return $timeslot;
    }
}
