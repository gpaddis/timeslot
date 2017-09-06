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
        $timeslot = new Timeslot($datetime);

        $this->assertEquals('2010-04-24 10:00:00', $timeslot->start()->toDateTimeString());
        $this->assertEquals('2010-04-24 10:59:59', $timeslot->end()->toDateTimeString());
    }

    /** @test */
    function it_creates_default_timeslot()
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
    function it_creates_a_custom_timeslot()
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
    public function it_moves_the_current_timeslot_two_hours_in_the_future()
    {
        $timeslot = Timeslot::custom(Carbon::parse('2017-01-18 13:00:00'));

        $timeslot->addHour(2);

        $this->assertEquals('2017-01-18 15:00:00', $timeslot->start());
        $this->assertEquals('2017-01-18 15:59:59', $timeslot->end());
    }
}
