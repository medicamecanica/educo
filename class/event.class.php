<?php

/*
 * Copyright (C) 2018 ander
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program.  If not, see <http://www.gnu.org/licenses/>.
 */

/**
 * Description of event
 *
 * @author ander
 */
class Event {

    // Tests whether the given ISO8601 string has a time-of-day or not
    const ALL_DAY_REGEX = '/^\d{4}-\d\d-\d\d$/'; // matches strings like "2013-12-29"

    public $id;
    public $title;
    public $allDay = false; // a boolean
    /**
     *
     * @var DateTime
     */
    public $start; // a DateTime
    /**
     *
     * @var DateTime
     */
    public $end; // a DateTime, or null
    public $properties = array(); // an array of other misc properties
    public $resourceIds;
    public function __construct($object) {
        // Record misc properties
        foreach ($object as $name => $value) {
            if (!in_array($name, array('title', 'allDay', 'start', 'end'))) {
                //   $this->properties[$name] = $value;
            }
        }
    }

    function setStart($string) {
        $this->start = new DateTime(
                $string
               ,null
        );
    }

    function setEnd($string) {
        $this->end = new DateTime(
                $string
                // Used only when the string is ambiguous.
                // Ignored if string has a timezone offset in it.
        );
    }

    // Converts this Event object back to a plain data array, to be used for generating JSON
    public function toArray() {

        // Start with the misc properties (don't worry, PHP won't affect the original array)
        $array = $this->properties;
         $array['id'] = $this->id;
        $array['title'] = $this->title;

        // Figure out the date format. This essentially encodes allDay into the date string.
        if ($this->allDay) {
            $format = 'Y-m-d'; // output like "2013-12-29"
        } else {
            $format = 'c'; // full ISO8601 output, like "2013-12-29T09:00:00+08:00"
        }

        // Serialize dates into strings
        $array['start'] = $this->start->format($format);
        if (isset($this->end)) {
            $array['end'] = $this->end->format($format);
        }

        return $array;
    }

    function SyncWeek() {
        $year = date('Y');
        $week = date('W');       
        $day = $this->start->format('w');
        $this->start->setISODate($year, $week, $day);
        $day = $this->end->format('w');
        $this->end->setISODate($year, $week, $day);
    }

}
