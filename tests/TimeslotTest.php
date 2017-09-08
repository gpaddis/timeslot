<?php

use Carbon\Carbon;
use Timeslot\Timeslot;
use PHPUnit\Framework\TestCase;

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
        $timeslot = new Timeslot;

        $this->assertTrue($now->between($timeslot->start(), $timeslot->end()));
    }

    /** @test */
    public function carbon_throws_an_exception_if_a_wrong_argument_is_passed()
    {
        $this->expectException('Exception');

        Timeslot::create('some_random_text');
    }

    /** @test */
    public function now_timeslot_is_a_rounded_timeslot()
    {
        $timeslot1 = (new Timeslot)->round();
        $timeslot2 = Timeslot::now();

        $this->assertEquals($timeslot1, $timeslot2);
    }

    /** @test */
    function it_creates_a_custom_timeslot()
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
    public function it_moves_the_current_timeslot_two_hours_in_the_future()
    {
        $timeslot = Timeslot::create(Carbon::parse('2017-01-18 13:00:00'));
        $timeslot->addHour(2);

        $this->assertEquals('2017-01-18 15:00:00', $timeslot->start());
        $this->assertEquals('2017-01-18 15:59:59', $timeslot->end());
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
}
