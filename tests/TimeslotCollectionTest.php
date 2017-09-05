<?php

use Timeslot\Timeslot;
use PHPUnit\Framework\TestCase;
use Timeslot\TimeslotCollection;

class TimeslotCollectionTest extends TestCase
{
    /** @test */
    // https://stackoverflow.com/questions/44591972/testing-iterables-in-phpunit
    // public function it_is_iterable()
    // {
    //     $timeslot = Timeslot::now();
    //     $timeslotCollection = new TimeslotCollection($timeslot);

    //     $this->assertEquals($timeslot, iterator_to_array($timeslotCollection));
    // }

    /** @test */
    public function it_has_the_same_start_and_end_time_of_the_timeslot_contained()
    {
        $timeslot = Timeslot::now();
        $timeslotCollection = new TimeslotCollection($timeslot);

        $this->assertEquals($timeslot->start(), $timeslotCollection->start());
        $this->assertEquals($timeslot->end(), $timeslotCollection->end());
    }
}
