yiiFlipCountdown
================

Flip countdown widget wrapper for Yii framework

Original JS widget: https://github.com/xdan/flipcountdown/

Installation
===============
1. Copy all files under your "extensions/" folder
2. Call widget from view

Examples:
1) countdown to unixtime
````php
$this->widget('ext.FlipCountDown.FlipCountDown', array(
    // countdown to timestamp + 15 days
    'beforeUnixTime' => time() + 15 * 24 * 3600,
));
````

2) countdown to date and time
````php
$this->widget('ext.FlipCountDown.FlipCountDown', array(
     // target date and time: "mm/dd/yyyy hh:mm:ss"
    'beforeDateTime' => '01/31/2016 23:59:59',
));
````

3) custom value (static)
````php
$this->widget('ext.FlipCountDown.FlipCountDown', array(
     // string/float/int
    'value' => 42,
));
````

4) custom value with refreshed by AJAX
````php
$this->widget('ext.FlipCountDown.FlipCountDown', array(
     // counter value
    'value' => 42,
    // custom tick function (must return int or JS Date object)
    'tick' => 'function(){return $.ajax(newValueUrl);}',
));
````

5) other params
````php
$this->widget('ext.FlipCountDown.FlipCountDown', array(
    // target unixtime
    'beforeUnixTime' => time() + 15 * 24 * 3600,
    //  widget size: 'lg', 'md', 'sm', 'xs'
    'size'       => 'md', 
    // Hide second or minute or hour
    'showHour'   => true,
    'showMinute' => true,
    'showSecond' => true,
    // offset timezone
    'tzoneOffset' => 3,
    // 12 format hours
    'am'          => false,
    // speed animate flip digit (multiply 6 must by less than 1000) default 60
    'speedFlip'   => 30,
));
````