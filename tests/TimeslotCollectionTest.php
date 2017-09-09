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
        $timeslot2 = Timeslot::after($timeslot1);

        $timeslotCollection = new TimeslotCollection($timeslot1);
        $timeslotCollection->add($timeslot2);

        $this->assertEquals($timeslot1->start(), $timeslotCollection->start());
        $this->assertEquals($timeslot2->end(), $timeslotCollection->end());
    }

    /** @test */
    public function it_generates_a_collection_of_timeslots()
    {
        $datetime = Timeslot::create('2018-12-23 10:00:00');
        $collection = TimeslotCollection::create($datetime, 8);

        $this->assertEquals(8, count($collection));
        $this->assertEquals(8, $collection->count());
        $this->assertEquals('2018-12-23 17:59:59', $collection->end()->toDateTimeString());
    }

    /** @test */
    public function it_can_contain_a_collection_of_timeslots()
    {
        $timeslot1 = Timeslot::now();
        $timeslot2 = Timeslot::after($timeslot1);
        $collection1 = TimeslotCollection::create($timeslot1);
        $collection2 = TimeslotCollection::create($timeslot2, 3);

        $collection1->add($collection2);

        $this->assertEquals(2, $collection1->count());
        $this->assertEquals($collection2, $collection1->get(1));
        $this->assertEquals(3, $collection1->get(1)->count());
    }

    /** @test */
    public function it_throws_an_exception_if_the_offset_is_undefined()
    {
        $this->expectException('OutOfRangeException');
        $collection = TimeslotCollection::create(Timeslot::now());

        $collection->get(20);
    }
}
