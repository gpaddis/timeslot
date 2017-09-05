<?php

use Timeslot\Timeslot;
use PHPUnit\Framework\TestCase;

class IterationTest extends TestCase
{
    /** @test */
    // https://stackoverflow.com/questions/44591972/testing-iterables-in-phpunit
    public function it_can_be_iterated_with_a_foreach_loop()
    {
        $timeslot = Timeslot::now();
        $expected = [$timeslot];
        // var_dump($timeslot);

        $this->assertEquals($expected, iterator_to_array($timeslot));
    }
}
