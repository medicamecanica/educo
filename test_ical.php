<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
$timezone = date_default_timezone_get();
$loader = require './include/icalcreator/autoload.php';
//
$tz = date_default_timezone_get();;
// set Your unique id, 
// required if any component UID is missing 
$config = array(kigkonsult\iCalcreator\util\util::$UNIQUE_ID => "kigkonsult.se",
    //opt. set "calendar" timezone 
    kigkonsult\iCalcreator\util\util::$TZID => $tz);
// create a new calendar object instance 
$calendar = new kigkonsult\iCalcreator\vcalendar($config);
// required of some calendar software 
$calendar->setProperty(kigkonsult\iCalcreator\util\util::$METHOD, "PUBLISH");
$calendar->setProperty("x-wr-calname", "Calendar Sample");
$calendar->setProperty("X-WR-CALDESC", "Calendar Description");
$calendar->setProperty("X-WR-TIMEZONE", $tz);
// create an calendar event component
$vevent = $calendar->newVevent();
// set event start 
$vevent->setProperty(
        kigkonsult\iCalcreator\util\util::$DTSTART
        , array("year" => 2017, "month" => 10, "day" => 1, "hour" => 19, "min" => 0, "sec" => 0)
);
// set event end 
$vevent->setProperty( 
        kigkonsult\iCalcreator\util\util::$DTEND
        , array( "year" => 2017, "month" => 10, "day" => 1
            , "hour" => 22, "min" => 30, "sec" => 0 )
        ); 
$vevent->setProperty( kigkonsult\iCalcreator\util\util::$LOCATION, "Central Placa" ); 
$vevent->setProperty( kigkonsult\iCalcreator\util\util::$SUMMARY, "PHP summit" ); 
$vevent->setProperty( kigkonsult\iCalcreator\util\util::$DESCRIPTION, "This is a description" ); 
$vevent->setProperty( kigkonsult\iCalcreator\util\util::$COMMENT, "This is a comment" ); 
$vevent->setProperty( kigkonsult\iCalcreator\util\util::$ATTENDEE, "attendee1@icaldomain.net" );
// create an event alarm 
$valarm = $vevent->newValarm();
$valarm->setProperty(kigkonsult\iCalcreator\util\util::$ACTION, "DISPLAY");
// reuse the event description 
$valarm->setProperty(
        kigkonsult\iCalcreator\util\util::$DESCRIPTION
        , $vevent->getProperty(kigkonsult\iCalcreator\util\util::$DESCRIPTION)
); // a local date 
$d = sprintf('%04d%02d%02d %02d%02d%02d', 2017, 10, 31, 15, 0, 0);
// // create alarm trigger (in UTC datetime) 
kigkonsult\iCalcreator\timezoneHandler::transformDateTime($d, $tz, "UTC", "Ymd\THis\Z");
$valarm->setProperty(kigkonsult\iCalcreator\util\util::$TRIGGER, $d); 
// create another calendar event component 
$vevent = $calendar->newVevent(); 
// alt. date format, here for an all-day event 
$vevent->setProperty( 
        kigkonsult\iCalcreator\util\util::$DTSTART, "20171001"
        , array("VALUE" => "DATE")
        ); 
$vevent->setProperty( kigkonsult\iCalcreator\util\util::$ORGANIZER, "boss@icaldomain.com" ); 
$vevent->setProperty( kigkonsult\iCalcreator\util\util::$SUMMARY, "ALL-DAY event" ); 
$vevent->setProperty( kigkonsult\iCalcreator\util\util::$DESCRIPTION, "This is a description for an all-day event" ); 
$vevent->setProperty( kigkonsult\iCalcreator\util\util::$RESOURCES, "Full attension" ); 
//// weekly, four occasions 
$vevent->setProperty( kigkonsult\iCalcreator\util\util::$RRULE, array( "FREQ" => "DAILY", "INTERVAL"=>1,"COUNT" => 2)); 
//// supporting parse of strict rfc5545 formatted text 
$vevent->parse( "LOCATION:1CP Conference Room 4350" ); 
//// all calendar components are described in rfc5545 
// a complete iCalcreator function list (ex. setProperty) in iCalcreator manual 
// create timezone component(-s) 
//// based on all start dates in events (i.e. all dtstarts) 
//// X-LIC-LOCATION required of some calendar software 
$xprops = array( "X-LIC-LOCATION" => $tz );
kigkonsult\iCalcreator\timezoneHandler::createTimezone($calendar, $tz, $xprops);
$calendar->returnCalendar(); exit;