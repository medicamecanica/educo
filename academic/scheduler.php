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
dol_include_once('/educo/class/educoteachersubject.class.php');
dol_include_once('/educo/class/educohorario.class.php');
dol_include_once('/educo/class/html.formeduco.class.php');
dol_include_once('/educo/class/educogroup.class.php');
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
$groupid = GETPOST('groupid', 'int');
$teacherid = GETPOST('fk_user', 'int');
$teachersubjectid = GETPOST('teachersubjectid', 'int');
$jsonevents = GETPOST('events');
$events = json_decode($jsonevents);

//var_dump($gruopid);
$object = new Educopensum($db);
//academic
$academic = new Educoacadyear($db);
$group = new Educogroup($db);
$teachersubject = new Educoteachersubject($db);
$teacher = new User($db);
if ($academicid > 0 || !empty($ref))
    $ret = $academic->fetch($academicid, $ref);
if ($groupid > 0 || !empty($ref))
    $ret = $group->fetch($groupid, $ref);
if ($teacherid > 0 || !empty($ref))
    $ret = $teacher->fetch($teacherid, $ref);
if ($teachersubjectid > 0)
    $ret = $teachersubject->fetch($teachersubjectid);
if (is_array($events) && GETPOST('save')) {
    foreach ($events as $e) {
        $newevent = new Educohorario($db);
        if (!$e->id) {
            $newevent->datep = strtotime($e->start);
            $newevent->datef = strtotime($e->end);
            $newevent->duration = ($newevent->datef - $newevent->datep) / 3600;
            $newevent->fk_group = $groupid;
            $newevent->fk_teach_sub = $teachersubjectid;
            $newevent->grado_code = $group->grado_code;
            $newevent->subject_code = $teachersubject->asignature_code;
            $res = $newevent->create($user);
            if ($res > 0)
                setEventMessages($langs->trans('AddedClass'), null);
            else
                $error++;
        } elseif ($e->edit) {
            $newevent->fetch($e->id);
            //  var_dump($newevent->id);
            $newevent->datep = strtotime($e->start);
            $newevent->datef = strtotime($e->end);
            $newevent->duration = ($newevent->datef - $newevent->datep) / 3600;
            $res = $newevent->update($user);
            if ($res > 0)
                setEventMessages($langs->trans('EditedClass'), null);
            else
                $error++;
        }
        if ($error) {
            // Creation KO
            if (!empty($newevent->errors))
                setEventMessages(null, $newevent->errors, 'errors');
            else
                setEventMessages($newevent->error, null, 'errors');
            $action = 'edit';
        }
    }
}
if ($action == 'delete') {
    //$group=new Educogroup($db);
    // $group->fetch($event['groupid']);   
    $event = new Educohorario($db);
    $event->fetch(GETPOST('id', 'int'));
    $res = $event->delete($user);
    if ($res > 0)
        setEventMessages($langs->trans('ClassDeleted'), null);
    else {
        // Creation KO
        if (!empty($newevent->errors))
            setEventMessages(null, $newevent->errors, 'errors');
        else
            setEventMessages($newevent->error, null, 'errors');
        $action = 'edit';
    }
}

$head = academicyear_header($academic);
//diccioonarios
$formeduco = new FormEduco($db);
$form = new Form($db);

$subjects_grade = fetchSubjectsGrade($academicid, $group->grado_code, $group->id);
//$asig_grade_codes = array_column(is_array($subjects_grade)?$subjects_grade:array(), 'asignature_code');
//var_dump("",$grades);
//$grades = array_column($subjects_grade, 'asignature_code');
$subjects_teacher = fetchTeacherSubjects($academicid, $teacherid);
//var_dump($subjects_teacher);
$css = array(
    'educo/js/fullcalendar/fullcalendar.css',
    'educo/css/scheduler.css'
);
$js = array(
    'educo/js/fullcalendar/lib/moment.min.js',
    'educo/js/fullcalendar/fullcalendar.min.js',
    'educo/js/fullcalendar/locale/es.js',
    'educo/js/scheduler.js'
);
//isprint=GETPOST('optioncss')=='print');
llxHeader('', 'Scheduler', 'manual', null, 0, 0, $js, $css);

dol_fiche_head($head, 'scheduler', $langs->trans("AcademicYearCard"), 0, 'academic@educo');

//baner
print '<div class="arearef heightref valignmiddle" width="100%">';
print '<table class="tagtable liste" >' . "\n";
print '<tr><td width="30%">' . $langs->trans("AcademicYear") . '</td><td width="70%" colspan="3">';
$academic->next_prev_filter = "te.fournisseur = 1";
print $form->showrefnav($academic, 'academicid', '', ($user->societe_id ? 0 : 1), 'rowid', 'ref', '', '');
print '</td></tr></table>';
print '<table class="border centpercent">';
print '<form method="POST" id="form" action="' . $_SERVER["PHP_SELF"] . '">';
print '<input type="hidden" name="action" value="add">';
print '<input type="hidden" name="backtopage" value="' . $backtopage . '">';
print '<input type="hidden" name="academicid" value="' . $academicid . '">';

print '<tr>';
print '<td width="10%">' . $langs->trans('Fieldfk_teacher') . '</td>';
print '<td width="30%"> ';
print  $form->select_dolusers(GETPOST('fk_user', 'int'), 'fk_user', 1) . '<br>';
print '</td> ';
print '<td rowspan="2"> ';
print '<div class="left">'
        // . '<input type="submit" class="button" id="save" name="save" value="' . $langs->trans("Save") . '"> '
        . '<input type="submit" class="button" name="refresh" value="' . $langs->trans("ToFilter") . '"> '
        . '</div>';
print '</td> ';
print '</tr>';
print '<tr>';
print '<td>' . $langs->trans('Fieldfk_group') . '</td>';
print '<td> ';
print $formeduco->select_groups('groupid', $academicid, $groupid, 1) . '<br>';
print '</td> ';
print '</tr>';

$array = array();
//var_dump(count($subjects_teacher),array_column($subjects_teacher, 'asignature_code'),'<br>');
if (is_array($subjects_grade)) {
    if (count($subjects_grade) > 0)
        $subject_codes = array_column($subjects_grade, 'asignature_code');
    else
        $subject_codes = array('');
    if (is_array($subjects_teacher)) {
        foreach ($subjects_teacher as $s) {
            
            $key = !in_array($s->asignature_code, $subject_codes) ? ($s->rowid . '" disabled="disabled' ) : $s->rowid;
            $array[$key] = $s->subject_label;
        }
    }
} else
    $array = array(-1 => $langs->trans('SelectAnyGroup'));
if (count($array) <= 0)
    $array = array(-1 => $langs->trans('SelectAnyTeacher'));
print '<br>';
?>
<br>
<table border="0">
    <thead>
        <tr class="liste_titre">
            <th class="liste_titre" id="title_grade"><?php print $group->label ?></th>
            <?php if($teacherid>0)$hoursteacher= teacherHours($academicid, $teacherid)?>
            <th class="liste_titre" id="title_teacher"><?php print $teacher->getFullName($langs) .($hoursteacher>0?' ('.$hoursteacher.' horas)':'')?></th>
        </tr>
    </thead>
    <tbody>
        <tr class="liste_titre"><td></td><td><?php print $langs->trans('SubjectTeacherAvilable') . $form->selectarray('teachersubjectid', $array, GETPOST('teachersubjectid', 'int')) ?></td></tr>
        <tr>
            <td width="20%" valign="top" halign="right">
                <div class="box">
                    <table style="margin-top: 30px" summary="Estadísticas" class="noborder boxtable nohover" width="100%">
                        <tbody>
                            <tr class="liste_titre">
                                <th class="liste_titre">Materias</th>
                            </tr>
                            <tr class="impair">
                                <td id="pensum_grado" class="tdboxstats nohover flexcontainer">
                                    <?php
                                    foreach (is_array($subjects_grade) ? $subjects_grade : array() as $s) {
                                        print '<a class="boxstatsindicator thumbstat nobold nounderline">';
                                        print'<div class="boxstats" title="' . $s->subject_label . '">';
                                        print '<span class="boxstatstext">' . $s->subject_label . '</span><br>';
                                        print '<span class="boxstatsindicator">' . ($s->total_duration ? $s->total_duration : 0) . '/' . $s->horas . '</span>';
                                        print '<input type="hidden" name="subject_pensum[]" value="">';
                                        print '</div>';
                                        print '</a>';
                                    }
                                    ?>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </td>
            <td ><div style="
                      height: 300px;
                      overflow-x: hidden;
                      overflow-y: auto; ">
                    <div id='calendar' class="ui-sta">
                    </div>
                </div></td>
        </tr>
    </tbody>
</table>
<?php
print '<div class="center">'
        . '<input type="submit" class="button" id="save" name="save" value="' . $langs->trans("Save") . '"> '
        // . '<input type="submit" class="button" name="refresh" value="' . $langs->trans("Refresh") . '"> '
        . '</div>';
?>
</form>
</table>
<input id='dol_url' type="hidden" value="<?php print DOL_URL_ROOT ?>">
<input id='academicid' type="hidden" value="<?php print $academicid ?>">

<script>



</script>
<?php
print '</div>';
dol_fiche_end();
llxFooter();
$db->close();
