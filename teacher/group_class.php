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
dol_include_once('/educo/lib/educo.lib.php');
dol_include_once('/educo/class/educostudent.class.php');
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
require_once DOL_DOCUMENT_ROOT . '/educo/class/educogroup.class.php';
require_once DOL_DOCUMENT_ROOT . '/educo/class/educopensum.class.php';
require_once DOL_DOCUMENT_ROOT . '/educo/class/edcuocasignatura.class.php';
require_once DOL_DOCUMENT_ROOT . '/educo/class/educoacadyear.class.php';
$langs->load("users");
$langs->load("other");
$langs->load("commercial");

// Load traductions files requiredby by page
$langs->load("educo");
$langs->load("other");

// Get parameters
$id = GETPOST('id', 'int');
$pensumid = GETPOST('pensumid', 'int');
$groupid = GETPOST('groupid', 'int');
$action = GETPOST('action', 'alpha');
$cancel = GETPOST('cancel');
$backtopage = GETPOST('backtopage');
$myparam = GETPOST('myparam', 'alpha');

/* * *************************************************
 * VIEW
 *
 * Put here all code to build page
 * ************************************************** */

$group = new Educogroup($db);
$group->fetch($groupid);
$group->fetchStudents($pensumid);
$academic = new Educoacadyear($db);
$pensum = new Educopensum($db);
$subject = new Edcuocasignatura($db);
$academic->fetch($group->fk_academicyear);
$pensum->fetch($pensumid);
$subject->fetch('', $pensum->asignature_code);
$group->pensumid = $pensumid;
$header = class_header($group);
//var_dump($pensum->asignature_code,$subject->error);
//var_dump($group->lines);

llxHeader('', 'MyPageName', '');

$form = new Form($db);
// Part to show record
if ($group->id > 0 && (empty($action) || ($action != 'edit' && $action != 'create'))) {
    $res = $group->fetch_optionals($group->id, $extralabels);


    //  print load_fiche_titre($langs->trans("MyModule"));

   dol_fiche_head($header, 'card', $langs->trans("QualificationGroup"), 0, 'rating@educo');

    if ($action == 'delete') {
        $formconfirm = $form->formconfirm($_SERVER["PHP_SELF"] . '?id=' . $group->id, $langs->trans('DeleteMyOjbect'), $langs->trans('ConfirmDeleteMyObject'), 'confirm_delete', '', 0, 1);
        print $formconfirm;
    }

    print '<table class="border centpercent">' . "\n";
// print '<tr><td class="fieldrequired">'.$langs->trans("Label").'</td><td>'.$group->label.'</td></tr>';
//print '<tr><td class="fieldrequired">'.$langs->trans("Fieldref").'</td><td>'.$group->ref.'</td></tr>';
//print '<tr><td class="fieldrequired">'.$langs->trans("Fieldsufix").'</td><td>'.$group->sufix.'</td></tr>';
//print '<tr><td class="fieldrequired">'.$langs->trans("Fieldlabel").'</td><td>'.$group->label.'</td></tr>';
    print '<tr><td class="fieldrequired">' . $langs->trans("Fieldfk_academicyear") . '</td><td>' . $academic->getNomUrl(1) . '</td></tr>';
    print '<tr><td class="fieldrequired">' . $langs->trans("Fieldgrado_code") . '</td><td>' . $group->getNomUrl(1) . '</td></tr>';
    $group->status=$group->statut;
    print '<tr><td class="fieldrequired">' . $langs->trans("Fieldstatut") . '</td><td>' . $group->getLibStatut(2) . '</td></tr>';
//print '<tr><td class="fieldrequired">'.$langs->trans("Fieldimport_key").'</td><td>'.$group->import_key.'</td></tr>';
//print '<tr><td class="fieldrequired">'.$langs->trans("Fieldentity").'</td><td>'.$group->entity.'</td></tr>';
    print '<tr><td class="fieldrequired">' . $langs->trans("Fieldworkingday") . '</td><td>' . img_object($titlealt, 'workingday'.$group->workingday.'@educo').$langs->trans('EducoWorkingDay'.$group->workingday) . '</td></tr>';
    print '<tr><td class="fieldrequired">' . $langs->trans("FieldSubject") . '</td><td>' .img_object('', 'class@educo'). $subject->label . '</td></tr>';
    print '<tr><td class="fieldrequired">' . $langs->trans("FieldHoursWeek") . '</td><td>' . $pensum->horas . '</td></tr>';

    print '</table>';

    dol_fiche_end();


// Buttons
//    print '<div class="tabsAction">' . "\n";
//    $parameters = array();
//    $reshook = $hookmanager->executeHooks('addMoreActionsButtons', $parameters, $group, $action);    // Note that $action and $group may have been modified by hook
//    if ($reshook < 0)
//        setEventMessages($hookmanager->error, $hookmanager->errors, 'errors');
//
//    if (empty($reshook)) {
//        if ($user->rights->educo->write) {
//            print '<div class="inline-block divButAction"><a class="butAction" href="' . $_SERVER["PHP_SELF"] . '?id=' . $group->id . '&amp;action=edit">' . $langs->trans("Modify") . '</a></div>' . "\n";
//        }
//
//        if ($user->rights->educo->delete) {
//            print '<div class="inline-block divButAction"><a class="butActionDelete" href="' . $_SERVER["PHP_SELF"] . '?id=' . $group->id . '&amp;action=delete">' . $langs->trans('Delete') . '</a></div>' . "\n";
//        }
//    }
//    print '</div>' . "\n";

    print '<div class="div-table-responsive">';
    print '<table class="tagtable liste' . ($moreforfilter ? " listwithfilterbefore" : "") . '">' . "\n";

// Fields title
    print '<tr class="liste_titre">';

    print_liste_field_titre('Name', $_SERVER['PHP_SELF'], '', '', $params, '', $sortfield, $sortorder);
    print_liste_field_titre('Period1', $_SERVER['PHP_SELF'], '', '', $params, 'width="10%"', $sortfield, $sortorder);
    print_liste_field_titre('Period2', $_SERVER['PHP_SELF'], '', '', $params, 'width="10%"', $sortfield, $sortorder);
    print_liste_field_titre('Period3', $_SERVER['PHP_SELF'], '', '', $params, 'width="10%"', $sortfield, $sortorder);
    print_liste_field_titre('Period4', $_SERVER['PHP_SELF'], '', '', $params, 'width="10%"', $sortfield, $sortorder);


//print_liste_field_titre($selectedfields, $_SERVER["PHP_SELF"], "", '', '', 'align="right"', $sortfield, $sortorder, 'maxwidthsearch ');
    print '</tr>' . "\n";
// Fields title search
    print '<tr class="liste_titre">';
    $i = 0;
    $var = true;
    $totalarray = array();
    foreach ($group->lines as $e) {
        $student = new Educostudent($db);
        foreach ($e as $f => $v)
            $student->$f = $e->$f;
        $var = !$var;
        print '<tr ' . $bc[$var] . '>';
        print '<td>' . $student->getNomUrl(1) . '</td>';
        print '<td>' .number_format ( $e->p1,1 ). '</td>';
        print '<td>' .number_format ( $e->p2 ,1). '</td>';
        print '<td>' .number_format ( $e->p3 ,1). '</td>';
        print '<td>' .number_format ( $e->p4,1) . '</td>';
    }
    print '</tr>';
    print '</table>';
    print '</div>';
// Example 2 : Adding links to objects
// Show links to link elements
//$linktoelem = $form->showLinkToObjectBlock($group, null, array('educogroupstudent'));
//$somethingshown = $form->showLinkedObjectBlock($group, $linktoelem);
}


print '<br>';
print '<br>';
print '<br>';
print '<br>';
// End of page
llxFooter();
$db->close();

