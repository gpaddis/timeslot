<?php

use Carbon\Carbon;
use Timeslot\Timeslot;
use PHPUnit\Framework\TestCase;

class TimeslotTest extends TestCase
{
    /** @test */
    function create_default_timeslot()
    {
        // Given I have a default timeslot (1 hour)
        $timeslot = Timeslot::now();

        // When I compare timeslot's start and end with the current time
        $start = $timeslot->start()->timestamp;
        $end = $timeslot->end()->timestamp;
        $now = Carbon::now()->timestamp;

        // Then the current time is within the timeslot's start and end
        $this->assertGreaterThanOrEqual($start, $now);
        $this->assertLessThanOrEqual($end, $now);
    }

    /** @test */
    function create_custom_timeslot()
    {
        // Create a custom Carbon instance
        $datetime = Carbon::create('2019', '11', '4', '12', '10', '36');

        // Create a 3-hours timeslot from the instance
        $timeslot = Timeslot::custom($datetime, 3);

        // Start and end time should be as expected
        $this->assertEquals('2019-11-04 12:00:00', $timeslot->start()->toDateTimeString());
        $this->assertEquals('2019-11-04 14:59:59', $timeslot->end()->toDateTimeString());
    }

    /** @test */
    function create_todays_timeslot()
    {
        // Given I have a Timeslot::today() instance
        $timeslot = Timeslot::today();

        // When I compare it with today's start and end date/time
        $start = Carbon::now()->startOfDay();
        $end = Carbon::now()->endOfDay();

        // The values correspond
        $this->assertEquals($start, $timeslot->start());
        $this->assertEquals($end, $timeslot->end());
    }

    /** @test */
    function create_this_week_timeslot()
    {
        // Given I have a Timeslot::thisWeek() instance
        $timeslot = Timeslot::thisWeek();

        // When I compare it with this week's start and end date/time
        $start = Carbon::now()->startOfWeek();
        $end = Carbon::now()->endOfWeek();

        // The values correspond
        $this->assertEquals($start, $timeslot->start());
        $this->assertEquals($end, $timeslot->end());
    }

    /** @test */
    function create_last_week_timeslot()
    {
        // Given I have a Timeslot::lastWeek() instance
        $timeslot = Timeslot::lastWeek();

        // When I compare it with last week's start and end date/time
        $start = Carbon::now()->subWeek()->startOfWeek();
        $end = Carbon::now()->subWeek()->endOfWeek();

        // The values correspond
        $this->assertEquals($start, $timeslot->start());
        $this->assertEquals($end, $timeslot->end());
    }

    /** @test */
    function create_this_month_timeslot()
    {
        // Given I have a Timeslot::thisMonth() instance
        $timeslot = Timeslot::thisMonth();

        // When I compare it with this month's start and end date/time
        $start = Carbon::now()->startOfMonth();
        $end = Carbon::now()->endOfMonth();

        // The values correspond
        $this->assertEquals($start, $timeslot->start());
        $this->assertEquals($end, $timeslot->end());
    }

    /** @test */
    function create_last_month_timeslot()
    {
        // Given I have a Timeslot::lastMonth() instance
        $timeslot = Timeslot::lastMonth();

        // When I compare it with last week's start and end date/time
        $start = Carbon::now()->subMonth()->startOfMonth();
        $end = Carbon::now()->subMonth()->endOfMonth();

        // The values correspond
        $this->assertEquals($start, $timeslot->start());
        $this->assertEquals($end, $timeslot->end());
    }

    /** @test */
    public function it_moves_the_current_timeslot_two_hours_in_the_future()
    {
        $timeslot = Timeslot::custom(Carbon::parse('2017-01-18 13:00:00'));

        $timeslot->addHour(2);

        $this->assertEquals('2017-01-18 15:00:00', $timeslot->start());
        $this->assertEquals('2017-01-18 15:59:59', $timeslot->end());
    }
}
