<?php
/*
 * Copyright (C) 2017 ander
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
// Change this following line to use the correct relative path (../, ../../, etc)
$res = 0;
if (!$res && file_exists("../main.inc.php"))
    $res = @include '../main.inc.php';     // to work if your module directory is into dolibarr root htdocs directory
if (!$res && file_exists("../../main.inc.php"))
    $res = @include '../../main.inc.php';   // to work if your module directory is into a subdir of root htdocs directory
if (!$res && file_exists("../../../dolibarr/htdocs/main.inc.php"))
    $res = @include '../../../dolibarr/htdocs/main.inc.php';     // Used on dev env only
if (!$res && file_exists("../../../../dolibarr/htdocs/main.inc.php"))
    $res = @include '../../../../dolibarr/htdocs/main.inc.php';   // Used on dev env only
if (!$res)
    die("Include of main fails");
// Change this following line to use the correct relative path from htdocs
require_once(DOL_DOCUMENT_ROOT . '/core/class/html.formcompany.class.php');
require_once DOL_DOCUMENT_ROOT . '/core/lib/date.lib.php';
require_once DOL_DOCUMENT_ROOT . '/core/lib/company.lib.php';
dol_include_once('/educo/class/educopensum.class.php');
dol_include_once('/educo/class/educoacadyear.class.php');
dol_include_once('/educo/class/html.formeduco.class.php');
dol_include_once('/educo/lib/educo.lib.php');

// Load traductions files requiredby by page
$langs->load("educo");
$langs->load("other");

$action = GETPOST('action', 'alpha');
$massaction = GETPOST('massaction', 'alpha');
$show_files = GETPOST('show_files', 'int');
$confirm = GETPOST('confirm', 'alpha');
$toselect = GETPOST('toselect', 'array');

$id = GETPOST('id', 'int');
$academicid = GETPOST('academicid', 'int');

$object = new Educopensum($db);
//academic
$academic = new Educoacadyear($db);
if ($academicid > 0 || !empty($ref))
    $ret = $academic->fetch($academicid, $ref);
$head = academicyear_header($academic);
//diccioonarios
$formeduco = new FormEduco($db);
$form=new Form($db);
$css = array(
    'educo/js/fullcalendar/fullcalendar.css',
      'educo/css/scheduler.css'
);
$js = array(
    'educo/js/fullcalendar/lib/moment.min.js',
    'educo/js/fullcalendar/fullcalendar.min.js',
    'educo/js/fullcalendar/locale/es.js',
    'educo/js/scheduler_subjects.js'
);

llxHeader('', 'Scheduler', 'manual', null, 0, 0, $js, $css);


dol_fiche_head($head, 'scheduler');
//baner
print '<div class="arearef heightref valignmiddle" width="100%">';
print '<table class="tagtable liste" >' . "\n";
print '<tr><td width="30%">' . $langs->trans("AcademicYear") . '</td><td width="70%" colspan="3">';
$academic->next_prev_filter = "te.fournisseur = 1";
print $form->showrefnav($academic, 'academicid', '', ($user->societe_id ? 0 : 1), 'rowid', 'ref', '', '');
print '</td></tr></table>';
print 'grupo: '.$formeduco->select_groups('fk_group', $academicid, GETPOST('fk_group','int')).'<br>';
print 'docente: '. $form->select_dolusers($fk_user, 'fk_user', $show_empty).'<br>';
print 'materias: '. $form->selectarray('subject', array()).'<br>';
?>
<div id='calendar' class="ui-sta"></div>
<input id='dol_url' type="hidden" value="<?php print DOL_URL_ROOT?>">
<input id='academicid' type="hidden" value="<?php print $academicid?>">

<script>
    var events_array = [
        {
            title: 'Test1',
            start: new Date(2017, 12, 20),
            tip: 'Personal tip 1'},
        {
            title: 'Test2',
            start: new Date(2017, 12, 21),
            tip: 'Personal tip 2'}
    ];
   
    var id=0;
    $('#calendar').fullCalendar({
        columnFormat: 'dddd',
        slotLabelFormat: 'h(:mm)a',
        allDaySlot: false,
        height: 'auto',
        selectable: true,
        events: events_array,
        eventRender: function (event, element) {
            element.attr('title', event.tip);
        },
        select: function (start, end, jsEvent, view) {
            //var abc = prompt('Enter Title');
          //  var allDay = !start.hasTime && !end.hasTime;
            var newEvent = new Object();
            newEvent.title = $("#fk_user").select2('data').text+"\n"+ $('#subject').text();
            newEvent.id=id++;
            newEvent.start = moment(start).format();
             newEvent.end = moment(end).format();
            newEvent.allDay = false;
            $('#calendar').fullCalendar('renderEvent', newEvent);
        },
        // snapDuration:"00:30",
        // titleFormat: "[<?php print $medico->firstname . ' ' . $medico->lastname . ' ' . $medico->job; ?>, citas] D [de] MMMM YYYY",
//        customButtons: {
//            myCustomButton: {
//                text: 'custom!',
//                click: function () {
//                    alert('clicked the custom button!');
//                }
//            }
//        },
        header: {
            left: 'myCustomButton',
            center: '',
            right: ''
        },
        lang: 'es',
        //   defaultDate: moment('<?php echo ($date ? $date : date('Y-m-d')) ?>'),
// defaultDate: '<?php echo date('Y-m-d') ?>',
        defaultView: 'agendaWeek',
        editable: true,
        eventLimit: true, // allow "more" link when too many events
        start: '2014-01-01',
        end: '2016-01-01',
        slotDuration: '1:00',
        //  minTime: '<?php echo $conf->global->MIIPS_HORA_INICIO ?>',
        //maxTime: '<?php echo$conf->global->MIIPS_HORA_FIN ?>'
        minTime: '7:00',
        maxTime: '18:00',
        droppable: true, // this allows things to be dropped onto the calendar
        dragRevertDuration: 0
       
    });
   $( ".fc-agenda-slots" ).css( "height", 200 );
</script>
<?php
print '</div>';
dol_fiche_end();
llxFooter();
$db->close();
