<?php
/*
 * Copyright (C) 2016	   Andersson Paz   <npander@hotmail.com>
 *
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 3 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program. If not, see <http://www.gnu.org/licenses/>.
 * 
 */
require_once ("../main.inc.php");
dol_include_once('/user/class/user.class.php');
require_once(DOL_DOCUMENT_ROOT . '/user/class/usergroup.class.php');
require_once(DOL_DOCUMENT_ROOT . '/miips/lib/miips.lib.php');
dol_include_once('/core/lib/agenda.lib.php');
$langs->load('admin');
$langs->load('company');
$langs->load('miips@miips');

$css = array('medicamecanica/js/fullcalendar/fullcalendar.css'
);
$js = array('miips/js/fullcalendar/lib/moment.min.js',
    //'medicamecanica/js/fullcalendar/lib/jquery.min.js',
    'miips/js/fullcalendar/fullcalendar.min.js',
    'miips/js/fullcalendar/locale/es.js',
        //'miips/js/jquery.validate.min.js',
        //'miips/js/get_paciente.js'
);
llxHeader('', 'Agenda MiIPS', 'manual/citas', 'Citas', 0, 0, $js, $css);
?>
<div id='calendar' class="ui-sta"></div>
<script>
    $('#calendar').fullCalendar({
        lang: 'es',
        defaultDate: moment('<?php echo ($date ? $date : date('Y-m-d')) ?>'),
// defaultDate: '<?php echo date('Y-m-d') ?>',
        defaultView: 'agendaWeek',
        editable: true,
        eventLimit: true, // allow "more" link when too many events
        start: '2014-01-01',
        end: '2016-01-01',
        slotDuration: '00:30',
        minTime: '8:00',
        maxTime: '12:00',
        contentHeight: "auto"
        
//        events: {
//            url: 'get-events.php?user_id=' + user_id,
//            error: function () {
//                $('#script-warning').show();
//            },
//            success: function () {
//                var view = $('#calendar').fullCalendar('getView');
////                  
//            }
//
//        }
    });
</script>
<?php
llxFooter();
$db->close();