<?php

use Carbon\Carbon;
use Timeslot\Timeslot;
use PHPUnit\Framework\TestCase;
use Timeslot\TimeslotCollection;

class TimeslotCollectionTest extends TestCase
{
    /** @test */
    public function it_has_the_same_start_and_end_time_of_the_timeslot_contained()
    {
        $timeslot = Timeslot::now();
        $timeslotCollection = new TimeslotCollection($timeslot);

        $this->assertEquals($timeslot->start(), $timeslotCollection->start());
        $this->assertEquals($timeslot->end(), $timeslotCollection->end());
    }

    /** @test */
    public function it_updates_the_end_time_when_a_new_timeslot_is_added()
    {
        $timeslot1 = Timeslot::now();
        $timeslot2 = Timeslot::now()->addHour();

        $timeslotCollection = new TimeslotCollection($timeslot1);
        $timeslotCollection->add($timeslot2);

        $this->assertEquals($timeslot1->start(), $timeslotCollection->start());
        $this->assertEquals($timeslot2->end(), $timeslotCollection->end());
    }

    /** @test */
    public function it_generates_a_collection_of_timeslots()
    {
        $now = Timeslot::now();
        $collection = TimeslotCollection::create($now, 8);

        $this->assertEquals(8, count($collection));
        $this->assertEquals(8, $collection->count());
        $this->assertEquals(Timeslot::now(8)->end(), $collection->end());
    }
}
