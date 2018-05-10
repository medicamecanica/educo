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
include_once(DOL_DOCUMENT_ROOT . '/core/class/html.formcompany.class.php');
include_once(DOL_DOCUMENT_ROOT . '/educo/class/html.formeduco.class.php');
dol_include_once('/educo/lib/educo.lib.php');
dol_include_once('/educo/class/educostudent.class.php');
dol_include_once('/educo/class/educoacadyear.class.php');
require_once DOL_DOCUMENT_ROOT . '/comm/action/class/actioncomm.class.php';
require_once DOL_DOCUMENT_ROOT . '/contact/class/contact.class.php';
require_once DOL_DOCUMENT_ROOT . '/core/lib/contact.lib.php';
require_once DOL_DOCUMENT_ROOT . '/core/lib/company.lib.php';
require_once DOL_DOCUMENT_ROOT . '/core/lib/images.lib.php';
require_once DOL_DOCUMENT_ROOT . '/core/lib/files.lib.php';
require_once DOL_DOCUMENT_ROOT . '/core/class/html.formcompany.class.php';
require_once DOL_DOCUMENT_ROOT . '/core/class/extrafields.class.php';
require_once DOL_DOCUMENT_ROOT . '/core/class/doleditor.class.php';
require_once DOL_DOCUMENT_ROOT . '/core/class/html.form.class.php';
require_once DOL_DOCUMENT_ROOT . '/user/class/user.class.php';
require_once DOL_DOCUMENT_ROOT . '/categories/class/categorie.class.php';
$langs->load("companies");
$langs->load("users");
$langs->load("other");
$langs->load("commercial");

// Load traductions files requiredby by page
$langs->load("educo");
$langs->load("other");

// Get parameters
$id = GETPOST('id', 'int');
$action = GETPOST('action', 'alpha');
$cancel = GETPOST('cancel');
$backtopage = GETPOST('backtopage');
$academicid = GETPOST('academicid', 'alpha');
$acadyear = new Educoacadyear($db);
if (empty($academicid)) {
    $acadyear->fetchMax();
    $academicid = $acadyear->id;
}
$limit = GETPOST("limit") ? GETPOST("limit", "int") : $conf->liste_limit;
//var_dump($acadyear);
$header = array();
$arrayfields = array(
//'t.ref'=>array('label'=>$langs->trans("Fieldref"), 'checked'=>1),
    't.group_label' => array('label' => $langs->trans("FieldGroup"), 'checked' => 1),
    't.subject_label' => array('label' => $langs->trans("FieldSubject"), 'checked' => 1),
    't.hours' => array('label' => $langs->trans("Fieldhours"), 'checked' => 1),
    't.hours_asigned' => array('label' => $langs->trans("Fieldhours_asigned"), 'checked' => 1)
);
/* * *************************************************
 * VIEW
 *
 * Put here all code to build page
 * ************************************************** */
$sql = 'SELECT ';
$sql .= "sum( t.duration) as hours_asigned,";
$sql .= " max(p.horas) as hours,";
$sql .= " g.sufix,";
$sql .= " g.rowid as groupid,";
$sql .= " t.grado_code, ";
$sql .= " p.rowid as pensumid, ";
$sql .= " (s.label) as subject_label";


$sql .= ' FROM ' . MAIN_DB_PREFIX . 'educo_horario as t';
$sql .= ' INNER JOIN ' . MAIN_DB_PREFIX . 'edcuo_c_asignatura AS s ON subject_code=s.code';
$sql .= ' INNER JOIN ' . MAIN_DB_PREFIX . 'educo_teacher_subject AS ts ON t.fk_teach_sub=ts.rowid';
$sql .= ' INNER JOIN ' . MAIN_DB_PREFIX . 'user AS u ON ts.fk_user=u.rowid';
$sql .= ' INNER JOIN ' . MAIN_DB_PREFIX . 'educo_group AS g ON t.fk_group=g.rowid';
$sql .= ' INNER JOIN ' . MAIN_DB_PREFIX . 'educo_pensum AS p ON p.fk_academicyear=ts.fk_academicyear AND p.asignature_code=t.subject_code AND p.grado_code=t.grado_code';

// Manage filter

$sql .= ' WHERE 1 = 1';
if (!empty($conf->multicompany->enabled)) {
    $sql .= " AND entity IN (" . getEntity("educopensum", 1) . ")";
}

$sql .= " AND ts.fk_academicyear=" . $academicid;
//$sql .= " AND t.grado_code=" . $grado_code;
$sql .= " AND ts.fk_user=" . $user->id;
$sql .= " GROUP BY g.rowid,p.rowid";
$sql .= $db->order($sortfield, $sortorder);
//$sql.= $db->plimit($conf->liste_limit+1, $offset);
// Count total nb of records
$nbtotalofrecords = '';
if (empty($conf->global->MAIN_DISABLE_FULL_SCANLIST)) {
    $result = $db->query($sql);
    $nbtotalofrecords = $db->num_rows($result);
}

$sql .= $db->plimit($limit + 1, $offset);
$resql = $db->query($sql);
if (!$resql) {
    dol_print_error($db);
    exit;
}

$num = $db->num_rows($resql);
llxHeader('', 'MyPageName', '');
$form = new Form($db);
$formEduco = new FormEduco($db);
dol_fiche_head($header);
print '<form method="POST" id="searchFormList" action="' . $_SERVER["PHP_SELF"] . '">';
print '<table class="nobordernopadding centpercent">';
print '<tr>';
print '<td>';
print $langs->trans('AcademcYear');
print '</td>';
print '<td>';
print $formEduco->select_academic('academicid', $academicid);
print '</td>';

print '<td>';
print '<input type="submit" class="button" style="min-width:120px" name="refresh" value="Refrescar">';
print '</tr>';
print '</table>';
print '</form>';

dol_fiche_end();
print '<br>';
print '<div class="div-table-responsive">';
print '<table class="tagtable liste' . ($moreforfilter ? " listwithfilterbefore" : "") . '">' . "\n";

// Fields title
print '<tr class="liste_titre">';
// 
foreach ($arrayfields as $k => $value) {
    if (!empty($arrayfields[$k]['checked']))
        print_liste_field_titre($arrayfields[$k]['label'], $_SERVER['PHP_SELF'], '', '', $params, '', $sortfield, $sortorder);
}

//print_liste_field_titre($selectedfields, $_SERVER["PHP_SELF"], "", '', '', 'align="right"', $sortfield, $sortorder, 'maxwidthsearch ');
print '</tr>' . "\n";

// Fields title search
print '<tr class="liste_titre">';
$i = 0;
$var = true;
$totalarray = array();

while ($i < min($num, $limit)) {

    $obj = $db->fetch_object($resql);
    //  var_dump($obj, '<br>');
    if ($obj) {
        $var = !$var;

        // Show here line of result
        print '<tr ' . $bc[$var] . '>';
        // LIST_OF_TD_FIELDS_LIST
        foreach ($arrayfields as $key => $value) {
            if (!empty($arrayfields[$key]['checked'])) {
                $key2 = str_replace('t.', '', $key);
                if ($key2 == 'group_label') {
                    $class = $obj->grado_code . '-' . $obj->sufix;
                    $link=dol_buildpath('/educo/teacher/group_class.php?groupid=' . $obj->groupid . '&pensumid=' . $obj->pensumid, 1);
                    print '<td><a href="' . $link . '">' . img_object($class, 'group@educo') .  '</a><a href="' . $link . '">'.$class.'</a></td>';
                } elseif ($key2 == 'subject_label') {
                    print '<td><a href="' . dol_buildpath('/educo/teacher/group_class.php?groupid=' . $obj->groupid . '&pensumid=' . $obj->pensumid, 1) . '">' .img_object($class,'class@educo').$obj->subject_label . '</a></td>';
                } else
                    print '<td>' . $obj->$key2 . '</td>';
                if (!$i)
                    $totalarray['nbfield'] ++;
            }
        }
    }
    $i++;
}
print '</tr>';
print '</table>';
print '</div>';


// End of page
llxFooter();
$db->close();

