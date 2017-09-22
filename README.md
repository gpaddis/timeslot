# Timeslot
A simple yet flexible timeslot management API.

[![Build Status](https://travis-ci.org/gpaddis/timeslot.svg?branch=master)](https://travis-ci.org/gpaddis/timeslot)

**Warning: this library is in development and might change significantly before v1.0 is released.**

**Timeslot** uses [Carbon](https://github.com/briannesbitt/Carbon) to manage date and time.
You can create a new timeslot passing it a Carbon instance, a DateTime instance or a valid datetime string instead. The complete syntax is `new Timeslot($start, $hours, $minutes)`. Fluent methods, getters and setters are available as well.

To get started, read the documentation in the [library wiki](https://github.com/gpaddis/timeslot/wiki/).

## Examples
```php
// Create a 30-minutes timeslot from a string starting at 15:00
$timeslot = new Timeslot('2017-08-19 15:00:00', 0, 30);

// Get its start and end time as datetime strings (Carbon)
$timeslot->start()->toDateTimeString(); // 2017-08-19 15:00:00
$timeslot->end()->toDateTimeString();   // 2017-08-19 15:29:59

// Create a TimeslotCollection based on the $timeslot, containing 4 timeslots
$collection = TimeslotCollection::create($timeslot, 4);

// A TimeslotCollection has a start and end time as well...
$collection->start()->toDateTimeString(); // 2017-08-19 15:00:00
$collection->end()->toDateTimeString();   // 2017-08-19 16:59:59 (2 hours later)

// ...and you can get the single timeslots if you want.
$collection->get(1)->start()->toDateTimeString(); // 2017-08-19 15:30:00 (second timeslot in the collection)
```

[Check the wiki](https://github.com/gpaddis/timeslot/wiki/) for a full description of all available methods.
