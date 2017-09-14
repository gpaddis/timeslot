# Timeslot
A simple yet flexible timeslot management API.

[![Build Status](https://travis-ci.org/gpaddis/timeslot.svg?branch=master)](https://travis-ci.org/gpaddis/timeslot)
[![StyleCI](https://styleci.io/repos/102377872/shield?branch=master)](https://styleci.io/repos/102377872)

## Usage
### Creating a new timeslot
**Timeslot** uses [Carbon](https://github.com/briannesbitt/Carbon) extensively. You can create a new timeslot passing it a Carbon instance, but you can also use a DateTime instance or a valid datetime string instead. The complete syntax is `new Timeslot($start, $hours, $minutes)`.
```php
// Create a 30-minutes timeslot starting at 15:00
$timeslot = new Timeslot(Carbon::parse('2017-08-19 15:00:00', 0, 30));

// Create a 90-minutes Timeslot from a DateTime instance
$timeslot = new Timeslot(new DateTime('2010-04-24 10:00:00'), 1, 30);

// Create a default timeslot (1 hour) from a string starting at 15:00
$timeslot = new Timeslot('2017-08-19 15:00:00');
```
If you don't pass any arguments, the timeslot will start at the moment of the instantiation and will have a **default duration of one hour**. The seconds will be rounded up to the minute start, to include the moment of instantiation in the timeslot.
```php
// Timeslot created on 2017-08-19 15:08:35, its start time is set at 15:08:00
$timeslot = new Timeslot();
```
If you want your timeslot to start at the beginning of the current hour, you can call the `round()` method on the instance. In this case, you might find the `Timeslot::create()` fluent syntax more convenient:
```php
$timeslot = Timeslot::create('2017-08-19 15:08:35')->round();
// Will set the start time at 15:00:00 and the end time at 15:59:59
```
Very often, I want to create a default 1-hour timeslot that fits the current hour. The static method `Timeslot::now()` does exactly this:
```php
// Time of instantiation: xx:34:08
$timeslot = Timeslot::now(); // Duration: 1h, start: xx:00:00, end: xx:59:59
```
### Getting start and end
Start() and end() methods return **Carbon instances**. This way, you can manipulate them with Carbon methods (e.g. ->toDateTimeString(), ->timestamp, etc.: see the [API docs](http://carbon.nesbot.com/docs/)).
To get the start and end date of a timeslot, call its `start()` and `end()` methods:
```php
$timeslot = Timeslot::create('2017-08-19 15:08:35')->round();
$timeslot->start(); // Returns a Carbon instance
$timeslot->start()->toDateTimeString(); // Returns 2017-08-19 15:00:00
$timeslot->end()->toDateTimeString(); // Returns 2017-08-19 15:59:59
```
### Creating previous and next timeslot with after() and before()
These methods create timeslots with a duration identical to that of the timeslot passed. The method `before()` will create a timeslot ending where the current starts, the method `after()` will create one that starts where the current ends.
```php
$timeslot = new Timeslot('2017-08-19 15:00:00', 0, 30); // Duration: 30m, start: 15:00:00, end: 15:29:59
$nextTimeslot = Timeslot::after($timeslot); // Duration: 30m, start: 15:30:00, end: 15:59:59
$previousTimeslot = Timeslot::before($timeslot); // Duration: 30m, start: 14:30:00, end: 14:59:59
```

## Timeslot collections
The `TimeslotCollection` class allows you to manage of **groups of Timeslots**:
```php
$timeslot = Timeslot::create('2018-12-23 10:00:00');
$collection = TimeslotCollection::create($timeslot, 8);
// Creates a collection containing eight 1-hour timeslots, starting at 10:00:00 and ending at 17:59:59.
```
You can add timeslots to the collection calling `->add($timeslot)` from the instance:
```php
$timeslot = Timeslot::create('2018-12-23 18:00:00');
$collection->add($timeslot);
// Appends a timeslot to the collection: now the end time is set at 18:59:59.
```
### Getting a timeslot in a collection
You can use `->get($offset)` to get a Timeslot:
```php
$timeslot = Timeslot::create('2018-12-23 10:00:00');
$collection = TimeslotCollection::create($timeslot, 8);

$collection->get(1)->start(); // 2018-12-23 11:00:00
```
You can also nest an arbitrary number of TimeslotCollections and retrieve them:
```php
$hours = Timeslot::create('2018-12-23 10:00:00');
$tenMinutes = Timeslot::create('2018-12-23 13:00:00', 0, 10);
$tenMinutesCollection = TimeslotCollection::create($tenMinutes, 6);

$collection = TimeslotCollection::create($hours, 3);
$collection->add($tenMinutesCollection);

$collection->get(0)->start(); // 2018-12-23 10:00:00
$collection->get(1)->start(); // 2018-12-23 11:00:00
$collection->get(2)->start(); // 2018-12-23 12:00:00
$collection->get(3); // TimeslotCollection, 6 * 10 minutes
$collection->get(3)->get(0)->start(); // 2018-12-23 13:00:00
$collection->get(3)->get(1)->start(); // 2018-12-23 13:10:00
```
...and so on!
