<?php

use PHPUnit\Framework\TestCase;
use Timeslot\Timeslot;
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

        $collection = new TimeslotCollection($timeslot1);
        $collection->add($timeslot2);
        $collection->add($timeslot3);

        // Add a nested timeslot to $collection
        $collection->add(TimeslotCollection::create($timeslot4, 4));

        $this->assertEquals('2017-02-11 05:00:00', $collection->start()->toDateTimeString());
        $this->assertEquals('2017-02-11 11:59:59', $collection->end()->toDateTimeString());
    }

    /** @test */
    public function it_sorts_a_timeslot_collection()
    {
        $timeslot1 = Timeslot::create('2017-02-11 09:00:00');
        $timeslot2 = Timeslot::create('2017-02-11 10:00:00');
        $timeslot3 = Timeslot::create('2017-02-11 11:00:00');
        $timeslot4 = Timeslot::create('2017-02-11 12:00:00');
        $timeslot5 = Timeslot::create('2017-02-11 13:00:00');

        $collection = TimeslotCollection::create($timeslot3)
        ->add($timeslot4)
        ->add($timeslot5)
        ->add($timeslot1)
        ->add($timeslot2);

        $collection->sort();

        $this->assertEquals(
            [
                $timeslot1,
                $timeslot2,
                $timeslot3,
                $timeslot4,
                $timeslot5,
            ],
            [
                $collection->get(0),
                $collection->get(1),
                $collection->get(2),
                $collection->get(3),
                $collection->get(4),
            ]
        );
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
    public function it_removes_a_timeslot_from_a_collection()
    {
        $timeslot = Timeslot::create('2018-01-10 13:00:00');
        $collection = TimeslotCollection::create($timeslot, 5);

        $collection->remove(0);

        $this->assertEquals('2018-01-10 14:00:00', $collection->start()->toDateTimeString());
        $this->assertEquals('2018-01-10 17:59:59', $collection->end()->toDateTimeString());
    }

    /** @test */
    public function one_cannot_remove_all_timeslots_from_a_collection()
    {
        $this->expectException('Exception');

        $timeslot = Timeslot::create('2018-01-10 13:00:00');
        $collection = TimeslotCollection::create($timeslot);

        $collection->remove(0);
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
