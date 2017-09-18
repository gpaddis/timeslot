<?php

use Carbon\Carbon;
use PHPUnit\Framework\TestCase;
use Timeslot\Timeslot;

class TimeslotTest extends TestCase
{
    /** @test */
    public function it_creates_a_timeslot_from_a_DateTime_instance()
    {
        $datetime = new DateTime('2010-04-24 10:24:16');
        $timeslot = Timeslot::create($datetime)->round();

        $this->assertEquals('2010-04-24 10:00:00', $timeslot->start()->toDateTimeString());
        $this->assertEquals('2010-04-24 10:59:59', $timeslot->end()->toDateTimeString());
    }

    /** @test */
    public function it_creates_a_timeslot_from_a_string()
    {
        $datetime = '2017-05-24 12:00:00';
        $timeslot = Timeslot::create($datetime);

        $this->assertEquals('2017-05-24 12:00:00', $timeslot->start()->toDateTimeString());
        $this->assertEquals('2017-05-24 12:59:59', $timeslot->end()->toDateTimeString());
    }

    /** @test */
    public function it_creates_a_default_timeslot_when_no_arguments_are_passed()
    {
        $now = Carbon::now();
        $timeslot = new Timeslot();

        $this->assertTrue($now->between($timeslot->start(), $timeslot->end()));
    }

    /** @test */
    public function carbon_throws_an_exception_if_the_string_cannot_be_parsed()
    {
        $this->expectException('Exception');

        Timeslot::create('some_random_text');
    }

    /** @test */
    public function it_throws_an_exception_if_an_invalid_argument_is_passed()
    {
        $this->expectException('InvalidArgumentException');

        Timeslot::create(16546546546546);
    }

    /** @test */
    public function now_timeslot_is_a_rounded_timeslot()
    {
        $timeslot1 = (new Timeslot())->round();
        $timeslot2 = Timeslot::now();

        $this->assertEquals($timeslot1, $timeslot2);
    }

    /** @test */
    public function it_rounds_a_timeslot_that_spans_many_hours()
    {
        $timeslot = Timeslot::create('2019-11-04 12:15:15', 3, 30)->round();

        $this->assertEquals('2019-11-04 12:00:00', $timeslot->start()->toDateTimeString());
        $this->assertEquals('2019-11-04 15:29:59', $timeslot->end()->toDateTimeString());
    }

    /** @test */
    public function it_creates_a_custom_timeslot()
    {
        $datetime = Carbon::create('2019', '11', '4', '12', '10', '36');
        $timeslot = Timeslot::create($datetime, 3)->round();

        $this->assertEquals('2019-11-04 12:00:00', $timeslot->start()->toDateTimeString());
        $this->assertEquals('2019-11-04 14:59:59', $timeslot->end()->toDateTimeString());
    }

    /** @test */
    public function it_creates_a_30_m_timeslot()
    {
        $timeslot = new Timeslot(Carbon::parse('2017-08-12 14:00:00'), 0, 30);

        $this->assertEquals('2017-08-12 14:00:00', $timeslot->start()->toDateTimeString());
        $this->assertEquals('2017-08-12 14:29:59', $timeslot->end()->toDateTimeString());
    }

    /** @test */
    public function a_timeslot_can_start_at_anytime()
    {
        $timeslot = new Timeslot(Carbon::parse('2017-08-12 14:15:00'), 0, 30);

        $this->assertEquals('2017-08-12 14:15:00', $timeslot->start()->toDateTimeString());
        $this->assertEquals('2017-08-12 14:44:59', $timeslot->end()->toDateTimeString());
    }

    /** @test */
    public function it_returns_the_following_timeslot()
    {
        $timeslot = Timeslot::create('2017-01-18 15:00:00', 0, 30);
        $followingTimeslot = Timeslot::after($timeslot);

        $this->assertEquals('2017-01-18 15:30:00', $followingTimeslot->start());
        $this->assertEquals('2017-01-18 15:59:59', $followingTimeslot->end());
    }

    /** @test */
    public function it_returns_the_preceding_timeslot()
    {
        $timeslot = Timeslot::create('2017-01-18 15:00:00', 0, 30);
        $followingTimeslot = Timeslot::before($timeslot);

        $this->assertEquals('2017-01-18 14:30:00', $followingTimeslot->start());
        $this->assertEquals('2017-01-18 14:59:59', $followingTimeslot->end());
    }

    /** @test */
    public function external_manipulation_of_start_and_end_instances_does_not_affect_the_timeslot()
    {
        $timeslot = new Timeslot('2017-01-18 15:00:00');

        $timeslot->start()->addMinutes(30);
        $timeslot->end()->subHour();

        $this->assertEquals('2017-01-18 15:00:00', $timeslot->start()->toDateTimeString());
        $this->assertEquals('2017-01-18 15:59:59', $timeslot->end()->toDateTimeString());
    }
}
