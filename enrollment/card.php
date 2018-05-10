<?php

/* Copyright (C) 2007-2015 Laurent Destailleur  <eldy@users.sourceforge.net>
 * Copyright (C) ---Put here your own copyright and developer email---
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
 */

/**
 *   	\file       educo/educogroupstudent_card.php
 * 		\ingroup    educo
 * 		\brief      This file is an example of a php page
 * 					Initialy built by build_class_from_table on 2018-01-13 12:09
 */
//if (! defined('NOREQUIREUSER'))  define('NOREQUIREUSER','1');
//if (! defined('NOREQUIREDB'))    define('NOREQUIREDB','1');
//if (! defined('NOREQUIRESOC'))   define('NOREQUIRESOC','1');
//if (! defined('NOREQUIRETRAN'))  define('NOREQUIRETRAN','1');
//if (! defined('NOCSRFCHECK'))    define('NOCSRFCHECK','1');			// Do not check anti CSRF attack test
//if (! defined('NOSTYLECHECK'))   define('NOSTYLECHECK','1');			// Do not check style html tag into posted data
//if (! defined('NOTOKENRENEWAL')) define('NOTOKENRENEWAL','1');		// Do not check anti POST attack test
//if (! defined('NOREQUIREMENU'))  define('NOREQUIREMENU','1');			// If there is no need to load and show top and left menu
//if (! defined('NOREQUIREHTML'))  define('NOREQUIREHTML','1');			// If we don't need to load the html.form.class.php
//if (! defined('NOREQUIREAJAX'))  define('NOREQUIREAJAX','1');
//if (! defined("NOLOGIN"))        define("NOLOGIN",'1');				// If this page is public (can be called outside logged session)
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
include_once(DOL_DOCUMENT_ROOT . '/core/class/html.formcompany.class.php');
dol_include_once('/educo/class/educoacadyear.class.php');
dol_include_once('/educo/class/educogroupstudent.class.php');
dol_include_once('/educo/class/educogroup.class.php');
dol_include_once('/educo/class/educostudent.class.php');
dol_include_once('/educo/class/html.formeduco.class.php');
dol_include_once('/educo/lib/educo.lib.php');
// Load traductions files requiredby by page
$langs->load("educo");
$langs->load("other");

// Get parameters
$id = GETPOST('id', 'int');
$academicid = GETPOST('academicid', 'int');
$studentid = GETPOST('studentid', 'int');
$studentref = GETPOST('studentref', 'alpha');
$groupid = GETPOST('groupid', 'int');
$action = GETPOST('action', 'alpha');
$cancel = GETPOST('cancel');
$backtopage = GETPOST('backtopage');
$myparam = GETPOST('myparam', 'alpha');


$search_ref = GETPOST('search_ref', 'alpha');
$search_statut = GETPOST('search_statut', 'int');
$search_fk_grupo = GETPOST('search_fk_grupo', 'int');
$search_fk_estudiante = GETPOST('search_fk_estudiante', 'int');
$search_fk_user = GETPOST('search_fk_user', 'int');
$search_fk_academicyear = GETPOST('search_fk_academicyear', 'int');
$search_entity = GETPOST('search_entity', 'int');



if (empty($action) && empty($id) && empty($ref))
    $action = 'view';

// Protection if external user
if ($user->societe_id > 0) {
    //accessforbidden();
}
//$result = restrictedArea($user, 'educo', $id);

$academic = new Educoacadyear($db);
$object = new Educogroupstudent($db);
$group = new Educogroup($db);
$student = new Educostudent($db);
$enrollman = new User($db);
$extrafields = new ExtraFields($db);

// fetch optionals attributes and labels
$extralabels = $extrafields->fetch_name_optionals_label($object->table_element);

// Load object
include DOL_DOCUMENT_ROOT . '/core/actions_fetchobject.inc.php';  // Must be include, not include_once  // Must be include, not include_once. Include fetch and fetch_thirdparty but not fetch_optionals
// Initialize technical object to manage hooks of modules. Note that conf->hooks_modules contains array array
$hookmanager->initHooks(array('educogroupstudent'));



/* * *****************************************************************
 * ACTIONS
 *
 * Put here all code to do according to value of "action" parameter
 * ****************************************************************** */

$parameters = array();
$reshook = $hookmanager->executeHooks('doActions', $parameters, $object, $action);    // Note that $action and $object may have been modified by some hooks
if ($reshook < 0)
    setEventMessages($hookmanager->error, $hookmanager->errors, 'errors');

if (empty($reshook)) {
    if ($cancel) {
        if ($action != 'addlink') {
            $urltogo = $backtopage ? $backtopage : dol_buildpath('/educo/enrollment/list.php', 1);
            header("Location: " . $urltogo);
            exit;
        }

        if ($id > 0 || !empty($ref))
            $ret = $object->fetch($id, $ref);


        $action = '';
    }
    if ($id > 0 || !empty($ref)) {
        $academicid = $object->fk_academicyear;
        $groupid = $object->fk_grupo;
        $student->fetch($object->fk_estudiante);
    }

    if ($academicid > 0)
        $ret = $academic->fetch($academicid);
    if ($groupid > 0)
        $ret = $group->fetch($groupid);
    // Action to add record
    if ($action == 'add') {
        if (GETPOST('cancel')) {
            $urltogo = $backtopage ? $backtopage : dol_buildpath('/educo/enrollment/list.php', 1);
            header("Location: " . $urltogo);
            exit;
        }

        $error = 0;

        /* object_prop_getpost_prop */

        $object->ref = $academic->ref . '-' . $group->grado_code . '-' . $studentref;
        $object->statut = 1;
        $object->fk_grupo = $groupid;
        $object->fk_estudiante = $studentid;
        $object->fk_user = $user->id;
        $object->fk_academicyear = $academicid;
        $object->entity = $conf->entity;
        $object->datec = dol_now();
         $object->victim = GETPOST('victim', 'int');
        $object->expelled_from = GETPOST('expelled_from', 'alpha');
        $object->capacity = GETPOST('capacity', 'alpha');
        $object->disability = GETPOST('disability', 'alpha');
        $object->from_private = GETPOST('from_private', 'alpha');
        $object->from_public = GETPOST('from_public', 'alpha');
        $object->from = GETPOST('from', 'alpha');
        $object->icbf = GETPOST('icbf', 'alpha');
      //  $object->courses = GETPOST('courses', 'alpha');
        $object->subsidized = GETPOST('subsidized', 'int');
        $object->repeating = GETPOST('repeating', 'int');
        $object->new = GETPOST('new', 'int');
        $object->situation = GETPOST('situation', 'int');
        $courses = GETPOST('courses', 'array');
        $object->courses= implode(',', $courses);


        if (empty($object->ref)) {
            $error++;
            setEventMessages($langs->trans("ErrorFieldRequired", $langs->transnoentitiesnoconv("Ref")), null, 'errors');
        }

        if (!$error) {
            $result = $object->create($user);
            //  print $object->db->lastquery;
            if ($result > 0) {
                // Creation OK
                $urltogo = $backtopage ? $backtopage : dol_buildpath('/educo/enrollment/list.php', 1);
                header("Location: " . $urltogo);
                exit;
            } {
                // Creation KO



                if (!empty($object->errors)) {
                    if (strpos(end($object->errors), 'academicyear_by_student') !== false)
                        setEventMessages($langs->trans('StudenAllReadyEnrrolementInThisYear'), null, 'errors');
                    else
                        setEventMessages(null, $object->errors, 'errors');
                } else
                    setEventMessages($object->error, null, 'errors');
                $action = 'create';
            }
        }
        else {
            $action = 'create';
        }
    }

    // Action to update record
    if ($action == 'update') {
        $error = 0;


        $object->ref = $academic->ref . '-' . $group->grado_code . '-' . $studentref;
        $object->statut = GETPOST('statut', 'int');
        $object->fk_grupo = GETPOST('groupid', 'int');
       // $object->fk_estudiante = GETPOST('fk_estudiante', 'int');
      //  $object->fk_user = GETPOST('fk_user', 'int');
        $object->fk_academicyear = GETPOST('academicid', 'int');
      //  $object->entity = GETPOST('entity', 'int');
        $object->victim = GETPOST('victim', 'int');
        $object->expelled_from = GETPOST('expelled_from', 'alpha');
        $object->capacity = GETPOST('capacity', 'alpha');
        $object->disability = GETPOST('disability', 'alpha');
        $object->from_private = GETPOST('from_private', 'alpha');
        $object->from_public = GETPOST('from_public', 'alpha');
        $object->from = GETPOST('from', 'alpha');
        $object->icbf = GETPOST('icbf', 'alpha');
      //  $object->courses = GETPOST('courses', 'alpha');
        $object->subsidized = GETPOST('subsidized', 'int');
        $object->repeating = GETPOST('repeating', 'int');
        $object->new = GETPOST('new', 'int');
        $object->situation = GETPOST('situation', 'int');
        $courses = GETPOST('courses', 'array');
        $object->courses= implode(',', $courses);
        $object->tms= dol_now();





        if (empty($object->ref)) {
            $error++;
            setEventMessages($langs->transnoentitiesnoconv("ErrorFieldRequired", $langs->transnoentitiesnoconv("Ref")), null, 'errors');
        }

        if (!$error) {
            $result = $object->update($user);
            if ($result > 0) {
                $action = 'view';
            } else {
                var_dump($object->db->lastquery);
                // Creation KO
                if (!empty($object->errors))
                    setEventMessages(null, $object->errors, 'errors');
                else
                    setEventMessages($object->error, null, 'errors');
                $action = 'edit';
            }
        }
        else {
            $action = 'edit';
        }
    }

    // Action to delete
    if ($action == 'confirm_delete') {
        $result = $object->delete($user);
        if ($result > 0) {
            // Delete OK
            setEventMessages("RecordDeleted", null, 'mesgs');
            header("Location: " . dol_buildpath('/educo/enrollment/list.php', 1));
            exit;
        } else {
            if (!empty($object->errors))
                setEventMessages(null, $object->errors, 'errors');
            else
                setEventMessages($object->error, null, 'errors');
        }
    }
}




/* * *************************************************
 * VIEW
 *
 * Put here all code to build page
 * ************************************************** */
$js = array('/educo/js/enrollment.js');
llxHeader('', 'MyPageName', '', '', '', '', $js);

$form = new Form($db);
$formEduco = new FormEduco($db);

// Put here content of your page
// Example : Adding jquery code
print '<script type="text/javascript" language="javascript">
jQuery(document).ready(function() {
	function init_myfunc()
	{
		jQuery("#myid").removeAttr(\'disabled\');
		jQuery("#myid").attr(\'disabled\',\'disabled\');
	}
	init_myfunc();
	jQuery("#mybutton").click(function() {
		init_myfunc();
	});
});
</script>';


// Part to create
if ($action == 'create') {
    print load_fiche_titre($langs->trans("NewEnrollment"));

    print '<form id="form" method="POST" action="' . $_SERVER["PHP_SELF"] . '">';
    print '<input type="hidden" id="action" name="action" value="add">';

    print '<input type="hidden" name="backtopage" value="' . $backtopage . '">';

    dol_fiche_head();

    print '<table class="border centpercent">' . "\n";
    // print '<tr><td class="fieldrequired">'.$langs->trans("Label").'</td><td><input class="flat" type="text" size="36" name="label" value="'.$label.'"></td></tr>';
    // 
    print '<tr><td class="fieldrequired">' . $langs->trans("Fieldfk_academicyear") . '</td>';
    print '<td>';
    print $formEduco->select_academic('academicid', $academicid, 1);
    print '</td></tr>';
//print '<tr><td class="fieldrequired">'.$langs->trans("Fieldref").'</td><td><input class="flat" type="text" name="ref" value="'.GETPOST('ref').'"></td></tr>';
//print '<tr><td class="fieldrequired">'.$langs->trans("Fieldstatut").'</td><td><input class="flat" type="text" name="statut" value="'.GETPOST('statut').'"></td></tr>';
    print '<tr><td class="fieldrequired">' . $langs->trans("Fieldfk_grupo") . '</td><td>';
    print $formEduco->select_groups('groupid', $academicid, $groupid, 1);
    print '</td></tr>';
    print '<tr><td class="fieldrequired">' . $langs->trans("Fieldfk_estudiante") . '</td><td>';
    print $formEduco->select_student('student', $studentid, $studentref, DOL_URL_ROOT . '/educo/ajax/student.php');
    print '</td></tr>';
      print '<tr><td class="fieldrequired">' . $langs->trans("Fieldvictim") . '</td><td>' . $formEduco->select_victim('victim', $object->victim) . '</td></tr>';
    print '<tr><td class="fieldrequired">' . $langs->trans("Fieldexpelled_from") . '</td><td><input class="flat" type="text" name="expelled_from" value="' . $object->expelled_from . '"></td></tr>';
    print '<tr><td class="fieldrequired">' . $langs->trans("Fielddisability") . '</td><td><input class="flat" type="text" name="disability" value="' . $object->disability . '"></td></tr>';
    print '<tr><td class="fieldrequired">' . $langs->trans("Fieldcapacity") . '</td><td><input class="flat" type="text" name="capacity" value="' . $object->capacity . '"></td></tr>';
    print '<tr><td class="fieldrequired">' . $langs->trans("Fieldfrom_private") . '</td><td><input class="flat" type="text" name="from_private" value="' . $object->from_private . '"></td></tr>';
    print '<tr><td class="fieldrequired">' . $langs->trans("Fieldfrom_public") . '</td><td><input class="flat" type="text" name="from_public" value="' . $object->from_public . '"></td></tr>';
    print '<tr><td class="fieldrequired">' . $langs->trans("Fieldfrom") . '</td><td><input class="flat" type="text" name="from" value="' . $object->from . '"></td></tr>';
    print '<tr><td class="fieldrequired">' . $langs->trans("Fieldicbf") . '</td><td><input class="flat" type="text" name="icbf" value="' . $object->icbf . '"></td></tr>';
    $array = array(0, 1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11);
    if(!is_array($courses))
        $courses= empty ($object->courses)?array():explode(',',$object->courses);
    print '<tr><td class="fieldrequired">' . $langs->trans("Fieldcourses") . '</td><td>' . $form->multiselectarray('courses', $array,$courses) . '</td></tr>';
    print '<tr><td class="fieldrequired">' . $langs->trans("Fieldsubsidized") . '</td><td>' . $form->selectyesno('subsidized', $object->subsidized,1) . '</td></tr>';
    print '<tr><td class="fieldrequired">' . $langs->trans("Fieldrepeating") . '</td><td>' . $form->selectyesno('repeating', $object->repeating,1) . '</td></tr>';
    print '<tr><td class="fieldrequired">' . $langs->trans("Fieldnew") . '</td><td>' . $form->selectyesno('new', $object->new,1) . '</td></tr>';
    print '<tr><td class="fieldrequired">' . $langs->trans("Fieldsituation") . '</td><td><input class="flat" type="text" name="situation" value="' . $object->situation . '"></td></tr>';

    //   print '<tr><td class="fieldrequired">' . $langs->trans("Fieldcordinator") . '</td><td><input class="flat" type="text" name="fk_user" value="' . GETPOST('fk_user') . '"></td></tr>';
//print '<tr><td class="fieldrequired">'.$langs->trans("Fieldentity").'</td><td><input class="flat" type="text" name="entity" value="'.GETPOST('entity').'"></td></tr>';

    print '</table>' . "\n";

    dol_fiche_end();

    print '<div class="center"><input type="submit" class="button" name="add" value="' . $langs->trans("Create") . '"> &nbsp; <input type="submit" class="button" name="cancel" value="' . $langs->trans("Cancel") . '"></div>';

    print '</form>';
}



// Part to edit record
if (($id || $ref) && $action == 'edit') {
    print load_fiche_titre($langs->trans("Enrollment"));

    print '<form id="form" method="POST" action="' . $_SERVER["PHP_SELF"] . '">';
    print '<input type="hidden" id="action" name="action" value="update">';
    print '<input type="hidden" name="backtopage" value="' . $backtopage . '">';
    print '<input type="hidden" name="id" value="' . $object->id . '">';

    dol_fiche_head();

    print '<table class="border centpercent">' . "\n";
    // print '<tr><td class="fieldrequired">'.$langs->trans("Label").'</td><td><input class="flat" type="text" size="36" name="label" value="'.$label.'"></td></tr>';
    // 
    print '<tr><td class="fieldrequired">' . $langs->trans("Fieldfk_academicyear") . '</td>';
    print '<td>';
    print $formEduco->select_academic('academicid', $academicid, 1);
    print '</td></tr>';
//print '<tr><td class="fieldrequired">'.$langs->trans("Fieldref").'</td><td><input class="flat" type="text" name="ref" value="'.GETPOST('ref').'"></td></tr>';
//print '<tr><td class="fieldrequired">'.$langs->trans("Fieldstatut").'</td><td><input class="flat" type="text" name="statut" value="'.GETPOST('statut').'"></td></tr>';
    print '<tr><td class="fieldrequired">' . $langs->trans("Fieldfk_grupo") . '</td><td>';
    print $formEduco->select_groups('groupid', $academicid, $groupid, 1);
    print '</td></tr>';
    print '<tr><td class="fieldrequired">' . $langs->trans("Fieldfk_estudiante") . '</td><td>';
    print $student->getNomUrl(1);
    print '<input  type="text" name="studentref" value="' . $student->ref . '">';
    print '</td></tr>';
    print '<tr><td class="fieldrequired">' . $langs->trans("Fieldvictim") . '</td><td>' . $formEduco->select_victim('victim', $object->victim) . '</td></tr>';
    print '<tr><td class="fieldrequired">' . $langs->trans("Fieldexpelled_from") . '</td><td><input class="flat" type="text" name="expelled_from" value="' . $object->expelled_from . '"></td></tr>';
    print '<tr><td class="fieldrequired">' . $langs->trans("Fielddisability") . '</td><td><input class="flat" type="text" name="disability" value="' . $object->disability . '"></td></tr>';
    print '<tr><td class="fieldrequired">' . $langs->trans("Fieldcapacity") . '</td><td><input class="flat" type="text" name="capacity" value="' . $object->capacity . '"></td></tr>';
    print '<tr><td class="fieldrequired">' . $langs->trans("Fieldfrom_private") . '</td><td><input class="flat" type="text" name="from_private" value="' . $object->from_private . '"></td></tr>';
    print '<tr><td class="fieldrequired">' . $langs->trans("Fieldfrom_public") . '</td><td><input class="flat" type="text" name="from_public" value="' . $object->from_public . '"></td></tr>';
    print '<tr><td class="fieldrequired">' . $langs->trans("Fieldfrom") . '</td><td><input class="flat" type="text" name="from" value="' . $object->from . '"></td></tr>';
    print '<tr><td class="fieldrequired">' . $langs->trans("Fieldicbf") . '</td><td><input class="flat" type="text" name="icbf" value="' . $object->icbf . '"></td></tr>';
    $array = array(0, 1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11);
    if(!is_array($courses))
        $courses= empty ($object->courses)?array():explode(',',$object->courses);
    print '<tr><td class="fieldrequired">' . $langs->trans("Fieldcourses") . '</td><td>' . $form->multiselectarray('courses', $array,$courses) . '</td></tr>';
    print '<tr><td class="fieldrequired">' . $langs->trans("Fieldsubsidized") . '</td><td>' . $form->selectyesno('subsidized', $object->subsidized,1) . '</td></tr>';
    print '<tr><td class="fieldrequired">' . $langs->trans("Fieldrepeating") . '</td><td>' . $form->selectyesno('repeating', $object->repeating,1) . '</td></tr>';
    print '<tr><td class="fieldrequired">' . $langs->trans("Fieldnew") . '</td><td>' . $form->selectyesno('new', $object->new,1) . '</td></tr>';
    print '<tr><td class="fieldrequired">' . $langs->trans("Fieldsituation") . '</td><td><input class="flat" type="text" name="situation" value="' . $object->situation . '"></td></tr>';

    print '<tr><td class="fieldrequired">' . $langs->trans("Fieldstatus") . '</td><td>'.$formEduco->select_enrollment_state('statut', $object->statut) .'</td></tr>';

    print '</table>';

    dol_fiche_end();

    print '<div class="center"><input type="submit" class="button" name="save" value="' . $langs->trans("Save") . '">';
    print ' &nbsp; <input type="submit" class="button" name="cancel" value="' . $langs->trans("Cancel") . '">';
    print '</div>';

    print '</form>';
}



// Part to show record
if ($object->id > 0 && (empty($action) || ($action != 'edit' && $action != 'create'))) {
    $res = $object->fetch_optionals($object->id, $extralabels);

    if ($ret > 0)
        $ret = $student->fetch($object->fk_estudiante);
    if ($ret > 0)
        $ret = $group->fetch($object->fk_grupo);
    if ($ret > 0)
        $ret = $enrollman->fetch($object->fk_user);
    print load_fiche_titre($langs->trans("Enrollment"));

    dol_fiche_head();

    if ($action == 'delete') {
        $formconfirm = $form->formconfirm($_SERVER["PHP_SELF"] . '?id=' . $object->id, $langs->trans('DeleteEnrollment'), $langs->trans('ConfirmDeleteEnrollment'), 'confirm_delete', '', 0, 1);
        print $formconfirm;
    }

    print '<table class="border centpercent">' . "\n";
    // print '<tr><td class="fieldrequired">'.$langs->trans("Label").'</td><td>'.$object->label.'</td></tr>';
    // 
    print '<tr><td class="fieldrequired" width="35%">' . $langs->trans("Fieldref") . '</td><td>' . $object->ref . '</td></tr>';
    print '<tr><td class="fieldrequired">' . $langs->trans("Fieldstatut") . '</td><td>' . $langs->trans('EnrollmentState'.$object->statut) . '</td></tr>';
    print '<tr><td class="fieldrequired">' . $langs->trans("Fieldfk_grupo") . '</td><td>' . $group->getNomUrl(1) . '</td></tr>';
    print '<tr><td class="fieldrequired">' . $langs->trans("Fieldfk_estudiante") . '</td><td>' . $student->getNomUrl(1) . '</td></tr>';

    print '<tr><td class="fieldrequired">' . $langs->trans("Fieldvictim") . '</td><td>' . $langs->trans("Victim" . $object->victim) . '</td></tr>';
    print '<tr><td class="fieldrequired">' . $langs->trans("Fieldexpelled_from") . '</td><td>' . $object->expelled_from . '</td></tr>';
    print '<tr><td class="fieldrequired">' . $langs->trans("Fielddisability") . '</td><td>' . $object->disability . '</td></tr>';
    print '<tr><td class="fieldrequired">' . $langs->trans("Fieldcapacity") . '</td><td>' . $object->capacity . '</td></tr>';
    print '<tr><td class="fieldrequired">' . $langs->trans("Fieldfrom_private") . '</td><td>' . $object->from_private . '</td></tr>';
    print '<tr><td class="fieldrequired">' . $langs->trans("Fieldfrom_public") . '</td><td>' . $object->from_public . '</td></tr>';
    print '<tr><td class="fieldrequired">' . $langs->trans("Fieldfrom") . '</td><td>' . $object->from . '</td></tr>';
    print '<tr><td class="fieldrequired">' . $langs->trans("Fieldicbf") . '</td><td>' . $object->icbf . '</td></tr>';
    print '<tr><td class="fieldrequired">' . $langs->trans("Fieldcourses") . '</td><td>' . $object->courses . '</td></tr>';
    print '<tr><td class="fieldrequired">' . $langs->trans("Fieldsubsidized") . '</td><td>' . ($object->subsidized ? $langs->trans('Yes') : $langs->trans('No')) . '</td></tr>';
    print '<tr><td class="fieldrequired">' . $langs->trans("Fieldrepeating") . '</td><td>' . ($object->repeating ? $langs->trans('Yes') : $langs->trans('No')) . '</td></tr>';
    print '<tr><td class="fieldrequired">' . $langs->trans("Fieldnew") . '</td><td>' . ($object->new ? $langs->trans('Yes') : $langs->trans('No')) . '</td></tr>';
    print '<tr><td class="fieldrequired">' . $langs->trans("Fieldsituation") . '</td><td>' . $object->situation . '</td></tr>';
    //  print '<tr><td class="fieldrequired">' . $langs->trans("Fieldregime") . '</td><td>' . $langs->trans("Regime" . $object->regime) . '</td></tr>';
    // print '<tr><td class="fieldrequired">' . $langs->trans("Fieldethnicity") . '</td><td>' . $langs->trans("Ethnicity" . $object->ethnicity) . '</td></tr>';

    print '<tr><td class="fieldrequired">' . $langs->trans("Fieldfk_user") . '</td><td>' . $enrollman->getNomUrl(1) . '</td></tr>';
//    print '<tr><td class="fieldrequired">' . $langs->trans("Fieldfk_academicyear") . '</td><td>' . $academicid->getNomUrl(1) . '</td></tr>';
    //  print '<tr><td class="fieldrequired">' . $langs->trans("Fieldentity") . '</td><td>' . $object->entity . '</td></tr>';

    print '</table>';

    dol_fiche_end();


    // Buttons
    print '<div class="tabsAction">' . "\n";
    $parameters = array();
    $reshook = $hookmanager->executeHooks('addMoreActionsButtons', $parameters, $object, $action);    // Note that $action and $object may have been modified by hook
    if ($reshook < 0)
        setEventMessages($hookmanager->error, $hookmanager->errors, 'errors');

    if (empty($reshook)) {
        if ($user->rights->educo->write) {
            print '<div class="inline-block divButAction"><a class="butAction" href="' . $_SERVER["PHP_SELF"] . '?id=' . $object->id . '&amp;action=edit">' . $langs->trans("Modify") . '</a></div>' . "\n";
        }

        if ($user->rights->educo->delete) {
            print '<div class="inline-block divButAction"><a class="butActionDelete" href="' . $_SERVER["PHP_SELF"] . '?id=' . $object->id . '&amp;action=delete">' . $langs->trans('Delete') . '</a></div>' . "\n";
        }
    }
    print '</div>' . "\n";


    // Example 2 : Adding links to objects
    // Show links to link elements
    //$linktoelem = $form->showLinkToObjectBlock($object, null, array('educogroupstudent'));
    //$somethingshown = $form->showLinkedObjectBlock($object, $linktoelem);
}


// End of page
llxFooter();
$db->close();
