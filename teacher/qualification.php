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
 *   	\file       educo/educoqualification_card.php
 * 		\ingroup    educo
 * 		\brief      This file is an example of a php page
 * 					Initialy built by build_class_from_table on 2018-03-05 16:12
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
include_once(DOL_DOCUMENT_ROOT . '/educo/class/html.formeduco.class.php');
dol_include_once('/educo/class/educoqualification.class.php');
require_once DOL_DOCUMENT_ROOT . '/educo/lib/educo.lib.php';
require_once DOL_DOCUMENT_ROOT . '/educo/class/educogroup.class.php';
require_once DOL_DOCUMENT_ROOT . '/educo/class/educopensum.class.php';
require_once DOL_DOCUMENT_ROOT . '/educo/class/edcuocasignatura.class.php';
require_once DOL_DOCUMENT_ROOT . '/educo/class/educoacadyear.class.php';
dol_include_once('/educo/class/educostudent.class.php');
require_once DOL_DOCUMENT_ROOT . '/educo/class/educoqualifstudent.class.php';
require_once DOL_DOCUMENT_ROOT . '/educo/class/educotyperatingval.class.php';

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
$studentids =  GETPOST('students', 'array');
$qualificatonids =  GETPOST('qualifications', 'array');
//var_dump('<pre>', $studentids,$qualificatonids, '</pre>');
$group = new Educogroup($db);
$group->fetch($groupid);

$group->fetchStudents();
$students = $group->lines;
$academic = new Educoacadyear($db);
$pensum = new Educopensum($db);
$subject = new Edcuocasignatura($db);
$ratingval = new Educotyperatingval($db);

$academic->fetch($group->fk_academicyear);
$pensum->fetch($pensumid);
$subject->fetch('', $pensum->asignature_code);
$group->pensumid = $pensumid;
$header = class_header($group);

$search_ref = GETPOST('search_ref', 'alpha');
$search_label = GETPOST('search_label', 'alpha');
$search_datec = GETPOST('search_datec', 'alpha');
$search_tms = GETPOST('search_tms', 'alpha');
$search_note_private = GETPOST('search_note_private', 'alpha');
$search_note_public = GETPOST('search_note_public', 'alpha');
$search_value = GETPOST('search_value', 'alpha');
$search_period = GETPOST('search_period', 'int');
$search_status = GETPOST('search_status', 'int');
$search_import_key = GETPOST('search_import_key', 'alpha');
$search_entity = GETPOST('search_entity', 'alpha');
$search_fk_group = GETPOST('search_fk_group', 'int');
$search_fk_pensum = GETPOST('search_fk_pensum', 'int');
$search_fk_user = GETPOST('search_fk_user', 'int');
$search_fk_rating = GETPOST('search_fk_rating', 'alpha');



if (empty($action) && empty($id) && empty($ref))
    $action = 'view';

// Protection if external user
if ($user->societe_id > 0) {
    //accessforbidden();
}
//$result = restrictedArea($user, 'educo', $id);


$object = new Educoqualification($db);
$extrafields = new ExtraFields($db);

 $qualifstudent = new Educoqualifstudent($db);
// fetch optionals attributes and labels
$extralabels = $extrafields->fetch_name_optionals_label($object->table_element);

// Load object
include DOL_DOCUMENT_ROOT . '/core/actions_fetchobject.inc.php';  // Must be include, not include_once  // Must be include, not include_once. Include fetch and fetch_thirdparty but not fetch_optionals
// Initialize technical object to manage hooks of modules. Note that conf->hooks_modules contains array array
$hookmanager->initHooks(array('educoqualification'));



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
             $urltogo = $backtopage ? $backtopage : dol_buildpath('/educo/teacher/qualifications.php?groupid='.$groupid.'&pensumid='.$pensumid, 1);
            header("Location: " . $urltogo);
            exit;
        }
        if ($id > 0 || !empty($ref))
            $ret = $object->fetch($id, $ref);
        $action = '';
    }
    if ($action != 'addqual') {
        $i = 1;
        foreach ($studentids as $studid => $value) {
           $qualifstudent->initAsSpecimen();
            $qualifstudent->fk_qualification = $id;
            $qualifstudent->fk_student = $studid;
            $qualifstudent->ref = $object->ref . '-' . $i;
            $qualifstudent->label = $object->label;
            $qualifstudent->status = $object->status;
            $qualifstudent->value = $value;
            $qualifstudent->entity = $conf->entity;
            $qualifstudent->create($user);
            //var_dump("student",$qualifstudent->db->lastqueryerror);
        }
        foreach ($qualificatonids as $qualid => $value) {
          //  $qualifstudent = new Educoqualifstudent($db);
           // $qualifstudent->fk_qualification = $id;
         //   $qualifstudent->fk_student = $studid;
         $fetch = $qualifstudent->fetch($qualid);
            $qualifstudent->ref = $object->ref . '-' . $i;
            $qualifstudent->label = $object->label;
            $qualifstudent->value = $value;
            $qualifstudent->status = $object->status;
            $qualifstudent->update($user);
           //  var_dump("quals",$fetch,$qualid,$qualifstudent->errors);
           
        }
    }
    // Action to add record
    if ($action == 'add') {
        if (GETPOST('cancel')) {
            $urltogo = $backtopage ? $backtopage : dol_buildpath('/educo/teacher/qualifications.php?groupid='.$groupid.'&pensumid='.$pensumid, 1);
            header("Location: " . $urltogo);
            exit;
        }

        $error = 0;

        /* object_prop_getpost_prop */

        $object->ref = GETPOST('ref', 'alpha');
        $object->label = GETPOST('label', 'alpha');
        $object->datec = dol_now();
        //  $object->tms = GETPOST('tms', 'alpha');
        $object->note_private = GETPOST('note_private', 'alpha');
        $object->note_public = GETPOST('note_public', 'alpha');
        //  $object->value = GETPOST('value', 'alpha');
        $object->period = GETPOST('period', 'int');
        $object->status = GETPOST('status', 'int');
        //$object->import_key = GETPOST('import_key', 'alpha');
        $object->entity = $conf->entity;
        $object->fk_group = $groupid;
        $object->fk_pensum = $pensumid;
        $object->fk_user = $user->id;
        $object->fk_rating = GETPOST('fk_rating', 'alpha');



        if (empty($object->ref)) {
            $error++;
            setEventMessages($langs->trans("ErrorFieldRequired", $langs->transnoentitiesnoconv("Ref")), null, 'errors');
        }
       /// var_dump($object->period);
        
        if (empty($object->fk_group)) {
            $error++;
            setEventMessages($langs->trans("ErrorFieldRequired", $langs->transnoentitiesnoconv("Fieldfk_group")), null, 'errors');
        }
        if (empty($object->fk_pensum)) {
            $error++;
            setEventMessages($langs->trans("ErrorFieldRequired", $langs->transnoentitiesnoconv("Fieldfk_pensum")), null, 'errors');
        }if ($object->period<0) {
            $error++;
            setEventMessages($langs->trans("ErrorFieldRequired", $langs->transnoentitiesnoconv("Fieldperiod")), null, 'errors');
        }
        if (empty($object->status)) {
            $error++;
            setEventMessages($langs->trans("ErrorFieldRequired", $langs->transnoentitiesnoconv("Weight")), null, 'errors');
        }
        $object->status= abs($object->status);
        $max= 100-$object->maxWeight();
        if($max<=0){
          $error++;
          setEventMessages($langs->transnoentitiesnoconv("MaxWeight"), null, 'errors');  
        }else
        if (!($object->status>=0&&$object->status<=$max)) {
            $error++;
            setEventMessages($langs->transnoentitiesnoconv("NotWeightBetween",$max), null, 'errors');
        }
        if (!$error) {
            $result = $object->create($user);
            if ($result > 0) {
                // Creation OK
                $urltogo = $backtopage ? $backtopage : dol_buildpath('/educo/teacher/qualification.php?id=' . $object->id . '&groupid=' . $groupid . '&pensumid=' . $pensumid, 1);
                header("Location: " . $urltogo);
                exit;
            } {
                // Creation KO
                if (!empty($object->errors))
                    setEventMessages(null, $object->errors, 'errors');
                else
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


      //  $object->ref = GETPOST('ref', 'alpha');
        $object->label = GETPOST('label', 'alpha');
        $object->datec = GETPOST('datec', 'alpha');
        $object->tms = dol_now();
        $object->note_private = GETPOST('note_private', 'alpha');
        $object->note_public = GETPOST('note_public', 'alpha');
        $object->value = 0;
        $object->period = GETPOST('period', 'int');
        $object->status = GETPOST('status', 'int');
       // $object->import_key = GETPOST('import_key', 'alpha');
       -// $object->entity = GETPOST('entity', 'alpha');
        $object->fk_group = GETPOST('groupid', 'int');
        $object->fk_pensum = GETPOST('pensumid', 'int');
      //  $object->fk_user = GETPOST('fk_user', 'int');
       // $object->fk_rating = GETPOST('fk_rating', 'alpha');



        if (empty($object->ref)) {
            $error++;
            setEventMessages($langs->transnoentitiesnoconv("ErrorFieldRequired", $langs->transnoentitiesnoconv("Ref")), null, 'errors');
        }

        if (!$error) {
            $result = $object->update($user);
           // var_dump($object->db->lastquery);
            if ($result > 0) {
                $action = 'view';
            } else {
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
            header("Location: " . dol_buildpath('/educo/list.php', 1));
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

llxHeader('', 'MyPageName', '');

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
	$("#addNote").click(function() {
              $( "#formQual" ).submit();                
	});
       
      
});
</script>';


// Part to create
if ($action == 'create') {
   // print load_fiche_titre($langs->trans("NewMyModule"));

    print '<form method="POST" action="' . $_SERVER["PHP_SELF"] . '">';
    print '<input type="hidden" name="action" value="add">';
    print '<input type="hidden" name="backtopage" value="' . $backtopage . '">';
    print '<input type="hidden" name="groupid" value="' . $groupid . '">';
    print '<input type="hidden" name="pensumid" value="' . $pensumid . '">';

    dol_fiche_head();

    print '<table class="border centpercent">' . "\n";
    // print '<tr><td class="fieldrequired">'.$langs->trans("Label").'</td><td><input class="flat" type="text" size="36" name="label" value="'.$label.'"></td></tr>';
    print '<tr><td class="fieldrequired">' . $langs->trans("Fieldfk_rating") . '</td><td>';
    print $formEduco->select_dictionary('fk_rating', 'educo_typerating', 'code', 'code');
    print '</td></tr>';
    print '<tr><td class="fieldrequired">' . $langs->trans("Fieldperiod") . '</td><td>';
    print $formEduco->select_period('period', $object->period);
    print '</td></tr>';
    print '<tr><td class="fieldrequired">' . $langs->trans("Fieldref") . '</td><td><input class="flat" type="text" name="ref" value="' . GETPOST('ref') . '">';
    print img_info($langs->trans('QualificationRefInfo')).'</td></tr>';
    print '<tr><td class="fieldrequired">' . $langs->trans("Fieldlabel") . '</td><td><input class="flat" type="text" name="label" value="' . GETPOST('label') . '">';
    print  img_info($langs->trans('QualificationLabelInfo')).'</td></tr>';
//print '<tr><td class="fieldrequired">'.$langs->trans("Fielddatec").'</td><td><input class="flat" type="text" name="datec" value="'.GETPOST('datec').'"></td></tr>';
//print '<tr><td class="fieldrequired">'.$langs->trans("Fieldtms").'</td><td><input class="flat" type="text" name="tms" value="'.GETPOST('tms').'"></td></tr>';
    print '<tr><td class="fieldrequired">' . $langs->trans("Fieldnote_private") . '</td><td><input class="flat" type="text" name="note_private" value="' . GETPOST('note_private') . '">';
     print img_info($langs->trans('QualificationNotePrivateInfo')).'</td></tr>';
    print '<tr><td class="fieldrequired">' . $langs->trans("Fieldnote_public") . '</td><td><input class="flat" type="text" name="note_public" value="' . GETPOST('note_public') . '">';
     print img_info($langs->trans('QualificationNotePublicInfo')).'</td></tr>';
//print '<tr><td class="fieldrequired">'.$langs->trans("Fieldvalue").'</td><td><input class="flat" type="text" name="value" value="'.GETPOST('value').'"></td></tr>';

    print '<tr><td class="fieldrequired">' . $langs->trans("Weight") . '(%)</td><td><input class="flat" type="text" name="status" value="' . GETPOST('status') . '">';
     print img_info($langs->trans('QualificationWeightInfo')).'</td></tr>';
//print '<tr><td class="fieldrequired">'.$langs->trans("Fieldimport_key").'</td><td><input class="flat" type="text" name="import_key" value="'.GETPOST('import_key').'"></td></tr>';
//print '<tr><td class="fieldrequired">'.$langs->trans("Fieldentity").'</td><td><input class="flat" type="text" name="entity" value="'.GETPOST('entity').'"></td></tr>';
//print '<tr><td class="fieldrequired">'.$langs->trans("Fieldfk_group").'</td><td><input class="flat" type="text" name="fk_group" value="'.GETPOST('fk_group').'"></td></tr>';
//print '<tr><td class="fieldrequired">'.$langs->trans("Fieldfk_pensum").'</td><td><input class="flat" type="text" name="fk_pensum" value="'.GETPOST('fk_pensum').'"></td></tr>';
//print '<tr><td class="fieldrequired">'.$langs->trans("Fieldfk_user").'</td><td><input class="flat" type="text" name="fk_user" value="'.GETPOST('fk_user').'"></td></tr>';


    print '</table>' . "\n";

    dol_fiche_end();

    print '<div class="center"><input type="submit" class="button" name="add" value="' . $langs->trans("Create") . '"> &nbsp; <input type="submit" class="button" name="cancel" value="' . $langs->trans("Cancel") . '"></div>';

    print '</form>';
}



// Part to edit record
if (($id || $ref) && $action == 'edit') {
    //print load_fiche_titre($langs->trans("MyModule"));

    print '<form method="POST" action="' . $_SERVER["PHP_SELF"] . '">';
    print '<input type="hidden" name="action" value="update">';
    print '<input type="hidden" name="backtopage" value="' . $backtopage . '">';
    print '<input type="hidden" name="id" value="' . $object->id . '">';
     print '<input type="hidden" name="groupid" value="' . $groupid . '">';
    print '<input type="hidden" name="pensumid" value="' . $pensumid . '">';

    dol_fiche_head();

    print '<table class="border centpercent">' . "\n";
    // print '<tr><td class="fieldrequired">'.$langs->trans("Label").'</td><td><input class="flat" type="text" size="36" name="label" value="'.$label.'"></td></tr>';
     print '<tr><td class="fieldrequired">' . $langs->trans("Fieldfk_rating") . '</td><td><input class="flat" type="text" name="fk_rating" value="' . $object->fk_rating . '"></td></tr>';
    print '<tr><td class="fieldrequired">' . $langs->trans("Fieldref") . '</td><td><input class="flat" type="text" name="ref" value="' . $object->ref . '"></td></tr>';
    print '<tr><td class="fieldrequired">' . $langs->trans("Fieldlabel") . '</td><td><input class="flat" type="text" name="label" value="' . $object->label . '"></td></tr>';
//print '<tr><td class="fieldrequired">'.$langs->trans("Fielddatec").'</td><td><input class="flat" type="text" name="datec" value="'.$object->datec.'"></td></tr>';
//print '<tr><td class="fieldrequired">'.$langs->trans("Fieldtms").'</td><td><input class="flat" type="text" name="tms" value="'.$object->tms.'"></td></tr>';
    print '<tr><td class="fieldrequired">' . $langs->trans("Fieldnote_private") . '</td><td><input class="flat" type="text" name="note_private" value="' . $object->note_private . '"></td></tr>';
    print '<tr><td class="fieldrequired">' . $langs->trans("Fieldnote_public") . '</td><td><input class="flat" type="text" name="note_public" value="' . $object->note_public . '"></td></tr>';
//print '<tr><td class="fieldrequired">'.$langs->trans("Fieldvalue").'</td><td><input class="flat" type="text" name="value" value="'.$object->value.'"></td></tr>';
    print '<tr><td class="fieldrequired">' . $langs->trans("Fieldperiod") . '</td><td><input class="flat" type="text" name="period" value="' . $object->period . '"></td></tr>';
print '<tr><td class="fieldrequired">'.$langs->trans("FieldWeight").'</td><td><input class="flat" type="text" name="status" value="'.$object->status.'"></td></tr>';
//print '<tr><td class="fieldrequired">'.$langs->trans("Fieldimport_key").'</td><td><input class="flat" type="text" name="import_key" value="'.$object->import_key.'"></td></tr>';
//print '<tr><td class="fieldrequired">'.$langs->trans("Fieldentity").'</td><td><input class="flat" type="text" name="entity" value="'.$object->entity.'"></td></tr>';
//print '<tr><td class="fieldrequired">'.$langs->trans("Fieldfk_group").'</td><td><input class="flat" type="text" name="fk_group" value="'.$object->fk_group.'"></td></tr>';
//print '<tr><td class="fieldrequired">'.$langs->trans("Fieldfk_pensum").'</td><td><input class="flat" type="text" name="fk_pensum" value="'.$object->fk_pensum.'"></td></tr>';
//print '<tr><td class="fieldrequired">'.$langs->trans("Fieldfk_user").'</td><td><input class="flat" type="text" name="fk_user" value="'.$object->fk_user.'"></td></tr>';
   

    print '</table>';

    dol_fiche_end();

    print '<div class="center"><input type="submit" class="button" name="save" value="' . $langs->trans("Save") . '">';
    print ' &nbsp; <input type="submit" class="button" name="cancel" value="' . $langs->trans("Cancel") . '">';
    print '</div>';

    print '</form>';
}



// Part to show record
if ($object->id > 0 && (empty($action) || ($action != 'edit' && $action != 'create'))) {
    $maxValue = $ratingval->maxValue($object->fk_rating);
    //var_dump($object->error);
    
    $object->fetchStudentQualification($object->id);
    $qualifications = $object->lines;
    $res = $object->fetch_optionals($object->id, $extralabels);


    //print load_fiche_titre($langs->trans("MyModule"));

    dol_fiche_head($header, 'qualifications', $langs->trans("QualificationGroup"), 0, 'rating@educo');

    if ($action == 'delete') {
        $formconfirm = $form->formconfirm($_SERVER["PHP_SELF"] . '?id=' . $object->id, $langs->trans('DeleteMyOjbect'), $langs->trans('ConfirmDeleteMyObject'), 'confirm_delete', '', 0, 1);
        print $formconfirm;
    }

    print '<table class="border centpercent">' . "\n";
    // print '<tr><td class="fieldrequired">'.$langs->trans("Label").'</td><td>'.$object->label.'</td></tr>';
   
//print '<tr><td class="fieldrequired">'.$langs->trans("Fieldfk_user").'</td><td>'.$object->fk_user.'</td></tr>';
    print '<tr><td class="fieldrequired">' . $langs->trans("Fieldfk_rating") . '</td><td>' . $object->fk_rating . '</td></tr>';
 //print '<tr><td class="fieldrequired">' . $langs->trans("Fieldfk_pensum") . '</td><td>' . $object->fk_pensum . '</td></tr>';
//print '<tr><td class="fieldrequired">'.$langs->trans("Fieldref").'</td><td>'.$object->ref.'</td></tr>';
    print '<tr><td class="fieldrequired">' . $langs->trans("Fieldlabel") . '</td><td>' . $object->label . '</td></tr>';
    print '<tr><td class="fieldrequired">' . $langs->trans("Fielddatec") . '</td><td>' .dol_print_date( $object->datec) . '</td></tr>';
//print '<tr><td class="fieldrequired">'.$langs->trans("Fieldtms").'</td><td>'.$object->tms.'</td></tr>';
    print '<tr><td class="fieldrequired">' . $langs->trans("Fieldnote_private") . '</td><td>' . $object->note_private . '</td></tr>';
    print '<tr><td class="fieldrequired">' . $langs->trans("Fieldnote_public") . '</td><td>' . $object->note_public . '</td></tr>';
//print '<tr><td class="fieldrequired">'.$langs->trans("Fieldvalue").'</td><td>'.$object->value.'</td></tr>';
    print '<tr><td class="fieldrequired">' . $langs->trans("Fieldperiod") . '</td><td>' . $object->period . '</td></tr>';
    print '<tr><td class="fieldrequired">' . $langs->trans("FieldWeight") . '</td><td>' . $object->status . '</td></tr>';
//print '<tr><td class="fieldrequired">'.$langs->trans("Fieldimport_key").'</td><td>'.$object->import_key.'</td></tr>';
//print '<tr><td class="fieldrequired">'.$langs->trans("Fieldentity").'</td><td>'.$object->entity.'</td></tr>';
    print '<tr><td class="fieldrequired">' . $langs->trans("Fieldfk_group") . '</td><td>' . $object->fk_group . '</td></tr>';
   
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
            print '<div class="inline-block divButAction"><a class="butAction" href="' . $_SERVER["PHP_SELF"] . '?id=' . $object->id . '&amp;action=edit&amp;groupid=' . $groupid . '&pensumid=' . $pensumid.'">' . $langs->trans("Modify") . '</a></div>' . "\n";
        }

        if ($user->rights->educo->delete) {
            print '<div class="inline-block divButAction"><a class="butActionDelete" href="' . $_SERVER["PHP_SELF"] . '?id=' . $object->id . '&amp;action=delete">' . $langs->trans('Delete') . '</a></div>' . "\n";
        }
    }
    print '</div>' . "\n";

    print '<div class="div-table-responsive">';
    print '<form method="POST" id="formQual" action="' . $_SERVER["PHP_SELF"] . '#note">';
    print '<input type="hidden" name="action" value="add_qual">';
    print '<input type="hidden" name="backtopage" value="' . $backtopage . '">';
    print '<input type="hidden" name="id" value="' . $id . '">';
    print '<input type="hidden" name="groupid" value="' . $groupid . '">';
    print '<input type="hidden" name="pensumid" value="' . $pensumid . '">';
    print '<table class="tagtable liste' . ($moreforfilter ? " listwithfilterbefore" : "") . '">' . "\n";

// Fields title
    print '<tr class="liste_titre">';

    print_liste_field_titre('Name', $_SERVER['PHP_SELF'], '', '', $params, '', $sortfield, $sortorder);
    print_liste_field_titre('Note', $_SERVER['PHP_SELF'], '', '', $params, 'width="10%"', $sortfield, $sortorder);


//print_liste_field_titre($selectedfields, $_SERVER["PHP_SELF"], "", '', '', 'align="right"', $sortfield, $sortorder, 'maxwidthsearch ');
    print '</tr>' . "\n";
// Fields title search
    print '<tr class="liste_titre">';
    $i = 1;
    $var = true;
    $totalarray = array();
    foreach ($group->lines as $e) {
        $student = new Educostudent($db);
         foreach ($e as $f => $v)
            $student->$f = $e->$f;
        $value = null;
        $element = 'students';
        $elementid=$e->id;
        if (isset($qualifications[$e->id])) {
            if($qualifications[$e->id]->id>0){
            $value = $qualifications[$e->id]->value;
            $elementid=$qualifications[$e->id]->id;
            $element = 'qualifications';
            }
        }

       
        $var = !$var;
        print '<tr ' . $bc[$var] . '>';
        print '<td>' . $student->getNomUrl(1) . '</td>';
        print '<td><input id="'.$i.'" type="number" step="0.01" min="0" max="'.$maxValue.'" name="' . $element. '[' .  $elementid. ']" value="' . $value . '"></td>';
        $i++;
    }
    print '</tr>';
    print '</table>';
    print '</form>';
    print '</div>';
    // Buttons
    print '<div class="tabsAction">' . "\n";

    if (empty($reshook)) {
        if ($user->rights->educo->write) {
            print '<a name="note">';
            print '<div class="inline-block divButAction"><a id="addNote"  class="butAction" href="#note">' . $langs->trans("Save") . '</a></div>' . "\n";
        }
    }
    print '</div>' . "\n";
    // Example 2 : Adding links to objects
    // Show links to link elements
    //$linktoelem = $form->showLinkToObjectBlock($object, null, array('educoqualification'));
    //$somethingshown = $form->showLinkedObjectBlock($object, $linktoelem);
}


// End of page
llxFooter();
$db->close();
