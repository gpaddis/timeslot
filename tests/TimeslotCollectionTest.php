<?php

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
    public function it_updates_the_collection_each_time_a_new_timeslot_is_added()
    {
        $timeslot1 = Timeslot::create('2017-02-11 10:00:00');
        $timeslot2 = Timeslot::create('2017-02-11 11:00:00');
        $timeslot3 = Timeslot::create('2017-02-11 09:00:00');
        $timeslot4 = Timeslot::create('2017-02-11 05:00:00');

        $timeslotCollection = new TimeslotCollection($timeslot1);
        $timeslotCollection->add($timeslot2);
        $timeslotCollection->add($timeslot3);

        // Add a nested timeslot to $timeslotCollection
        $timeslotCollection->add(TimeslotCollection::create($timeslot4, 4));

        $this->assertEquals('2017-02-11 05:00:00', $timeslotCollection->start()->toDateTimeString());
        $this->assertEquals('2017-02-11 11:59:59', $timeslotCollection->end()->toDateTimeString());
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
