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
 *   	\file       educo/educoacadyear_card.php
 * 		\ingroup    educo
 * 		\brief      This file is an example of a php page
 * 					Initialy built by build_class_from_table on 2017-12-28 13:49
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
include_once(DOL_DOCUMENT_ROOT . '/core/class/html.formprojet.class.php');
dol_include_once('/educo/class/educoacadyear.class.php');
dol_include_once('/projet/class/project.class.php');
dol_include_once('/educo/lib/educo.lib.php');
dol_include_once('/core/class/doleditor.class.php');
// Load traductions files requiredby by page
$langs->load("educo");
$langs->load("other");

// Get parameters
$id = GETPOST('id', 'int');
$projectid = GETPOST('projectid', 'int');
$action = GETPOST('action', 'alpha');
$cancel = GETPOST('cancel');
$backtopage = GETPOST('backtopage');
$myparam = GETPOST('myparam', 'alpha');
//$datestart = GETPOST('datestart', 'alpha');
//$dateend = GETPOST('dateend', 'alpha');
$datestart = dol_mktime(0, 0, 0, GETPOST('datestartmonth'), GETPOST('datestartday'), GETPOST('datestartyear'));
$dateend = dol_mktime(0, 0, 0, GETPOST('dateendmonth'), GETPOST('dateendday'), GETPOST('dateendyear'));

$search_ref = GETPOST('search_ref', 'alpha');
$search_note_private = GETPOST('search_note_private', 'alpha');
$search_note_public = GETPOST('search_note_public', 'alpha');
$search_status = GETPOST('search_status', 'int');



if (empty($action) && empty($id) && empty($ref))
    $action = 'view';

// Protection if external user
if ($user->societe_id > 0) {
    //accessforbidden();
}
//$result = restrictedArea($user, 'educo', $id);


$object = new Educoacadyear($db);
$extrafields = new ExtraFields($db);
$project = new Project($db);

// fetch optionals attributes and labels
$extralabels = $extrafields->fetch_name_optionals_label($object->table_element);

// Load object
include DOL_DOCUMENT_ROOT . '/core/actions_fetchobject.inc.php';  // Must be include, not include_once  // Must be include, not include_once. Include fetch and fetch_thirdparty but not fetch_optionals
// Initialize technical object to manage hooks of modules. Note that conf->hooks_modules contains array array
$hookmanager->initHooks(array('educoacadyear'));



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
            $urltogo = $backtopage ? $backtopage : dol_buildpath('/educo/academic/list.php', 1);
            header("Location: " . $urltogo);
            exit;
        }
        if ($id > 0 || !empty($ref)) {
            $ret = $object->fetch($id, $ref);
        }
        $action = '';
    }
    if ($action == 'view' && $projectid > 0) {
        $project = new Project($db);
        $res = $project->fetch($projectid);
        if ($res > 0) {
            $object->add_object_linked($project->element, $projectid);
        }
    }
    if ($action == 'delete_project' && $projectid > 0) {
        $project = new Project($db);
        $res = $project->fetch($projectid);
        if ($res > 0) {
            // $object->add_object_linked($project->element, $projectid);
        }
        // $object->fetch($id);
       // $object->db->begin();
        $result = $project->delete($user);
        if ($result > 0) {
             $project->deleteObjectLinked();
            
            setEventMessages($langs->trans("RecordDeleted"), null, 'mesgs');
            
        } else {
            dol_syslog($object->error, LOG_DEBUG);
            setEventMessages($object->error, $object->errors, 'errors');
        }
        $action='view';
    }
    // Action to add record
    if ($action == 'add') {
        if (GETPOST('cancel')) {
            $urltogo = $backtopage ? $backtopage : dol_buildpath('/educo/academic/list.php', 1);
            header("Location: " . $urltogo);
            exit;
        }

        $error = 0;

        /* object_prop_getpost_prop */

        $object->ref = explode('/', GETPOST('datestart', 'alpha'))[2];
        $object->datestart = dol_mktime(0, 0, 0, GETPOST('datestartmonth'), GETPOST('datestartday'), GETPOST('datestartyear'));
        $object->dateend = dol_mktime(0, 0, 0, GETPOST('dateendmonth'), GETPOST('dateendday'), GETPOST('dateendyear'));
        $object->note_private = GETPOST('note_private', 'alpha');
        $object->note_public = GETPOST('note_public', 'alpha');
        $object->status = 0;
        $object->datec = dol_now();
        $object->tms = dol_now();
        $object->entity = $conf->entity;
       // $object->fk_project = GETPOST('fk_project', 'int');



        if (empty($object->ref)) {
            $error++;
            setEventMessages($langs->trans("ErrorFieldRequired", $langs->transnoentitiesnoconv("Ref")), null, 'errors');
        }

        if (!$error) {
            $result = $object->create($user);
            if ($result > 0) {
                // Creation OK
                $urltogo = $backtopage ? $backtopage : dol_buildpath('/educo/academic/list.php', 1);
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


        $object->datestart = dol_mktime(0, 0, 0, GETPOST('datestartmonth'), GETPOST('datestartday'), GETPOST('datestartyear'));
        $object->dateend = dol_mktime(0, 0, 0, GETPOST('dateendmonth'), GETPOST('dateendday'), GETPOST('dateendyear'));

        $object->note_private = GETPOST('note_private', 'alpha');
        $object->note_public = GETPOST('note_public', 'alpha');
        $object->status = GETPOST('status', 'int');
       // $object->fk_project = GETPOST('fk_project', 'int');



        if (empty($object->ref)) {
            $error++;
            setEventMessages($langs->transnoentitiesnoconv("ErrorFieldRequired", $langs->transnoentitiesnoconv("Ref")), null, 'errors');
        }

        if (!$error) {
            $result = $object->update($user);
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
            header("Location: " . dol_buildpath('/educo/academic/list.php', 1));
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
$formProjets = new FormProjets($db);
$head = academicyear_header($object);

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
    print load_fiche_titre($langs->trans("NewAcademicYear"));

    print '<form method="POST" action="' . $_SERVER["PHP_SELF"] . '">';
    print '<input type="hidden" name="action" value="add">';
    print '<input type="hidden" name="backtopage" value="' . $backtopage . '">';

    dol_fiche_head();

    print '<table class="border centpercent">' . "\n";
    // print '<tr><td class="fieldrequired">'.$langs->trans("Label").'</td><td><input class="flat" type="text" size="36" name="label" value="'.$label.'"></td></tr>';
    // 
    print '<tr><td class="fieldrequired">' . $langs->trans("Fielddate_start") . '</td>'
            . '<td>';
    print $form->select_date($object->datestart, 'datestart') . '</td></tr>';
    print '<tr><td class="fieldrequired">' . $langs->trans("Fielddate_end") . '</td>'
            . '<td>';
    print $form->select_date($object->dateend, 'dateend') . '</td></tr>';
//    print '<tr><td class="fieldrequired">' . $langs->trans("Fieldproject") . '</td><td>';
//    print $formProjets->select_projects(-1, $object->fk_project, 'fk_project');
//
//    print '</td></tr>';
    print '<tr><td class="fieldrequired">' . $langs->trans("Fieldnote_private") . '</td><td>';
    $doleditor = new DolEditor("note_private", $object->note_private, '', 160, 'dolibarr_notes', '', false, true, 1, 3, 80);
    $doleditor->Create();
    print '</td></tr>';
    print '<tr><td class="fieldrequired">' . $langs->trans("Fieldnote_public") . '</td><td>';
    $doleditor = new DolEditor("note_public", $object->note_public, '', 160, 'dolibarr_notes', '', false, true, 1, 3, 80);
    $doleditor->Create();
    print '</td></tr>';


    print '</table>' . "\n";

    dol_fiche_end();

    print '<div class="center"><input type="submit" class="button" name="add" value="' . $langs->trans("Create") . '"> &nbsp; <input type="submit" class="button" name="cancel" value="' . $langs->trans("Cancel") . '"></div>';

    print '</form>';
}


if ($object->fk_project > 0)
    $project->fetch($object->fk_project);
// Part to edit record
if (($id || $ref) && $action == 'edit') {
    //print load_fiche_titre($langs->trans("AcademicYear"));

    print '<form method="POST" action="' . $_SERVER["PHP_SELF"] . '">';
    print '<input type="hidden" name="action" value="update">';
    print '<input type="hidden" name="backtopage" value="' . $backtopage . '">';
    print '<input type="hidden" name="id" value="' . $object->id . '">';

    dol_fiche_head($head, 'card', $langs->trans("AcademicYear"), 0, 'academic@educo');

    print '<table class="border centpercent">' . "\n";
    print '<tr><td class="fieldrequired">' . $langs->trans("Fielddate_start") . '</td>'
            . '<td>';
    print $form->select_date($object->datestart, 'datestart') . '</td></tr>';
    print '<tr><td class="fieldrequired">' . $langs->trans("Fielddate_end") . '</td>'
            . '<td>';
    print $form->select_date($object->dateend, 'dateend') . '</td></tr>';
    print '<tr><td class="fieldrequired">' . $langs->trans("Fieldnote_private") . '</td><td>';
    $doleditor = new DolEditor("note_private", $object->note_private, '', 160, 'dolibarr_notes', '', false, true, 1, 3, 80);
    $doleditor->Create();
    print '</td></tr>';
    print '<tr><td class="fieldrequired">' . $langs->trans("Fieldnote_public") . '</td><td>';
    $doleditor = new DolEditor("note_public", $object->note_public, '', 160, 'dolibarr_notes', '', false, true, 1, 3, 80);
    $doleditor->Create();
    print '</td></tr>';
    print '<tr><td class="fieldrequired">' . $langs->trans("Fieldstatus") . '</td><td>';
    print $form->selectarray('status', array(1 => $langs->trans('Active'), 0 => $langs->trans('Inactive')), $object->status);

//    print '</td></tr>';
//    print '<tr><td class="fieldrequired">' . $langs->trans("Fieldproject") . '</td><td>';
//    print $formProjets->select_projects(-1, $object->fk_project, 'fk_project');
//
//    print '</td></tr>';

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


    //print load_fiche_titre($langs->trans("AcademicYear"));

    dol_fiche_head($head, 'card', $langs->trans("AcademicYearCard"), 0, 'academic@educo');

    if ($action == 'delete') {
        $formconfirm = $form->formconfirm($_SERVER["PHP_SELF"] . '?id=' . $object->id, $langs->trans('DeleteAcademicYear'), $langs->trans('ConfirmDeleteAcademicYear'), 'confirm_delete', '', 0, 1);
        print $formconfirm;
    }

    print '<table class="border centpercent border tableforfield">' . "\n";
    // print '<tr><td class="fieldrequired">'.$langs->trans("Label").'</td><td>'.$object->label.'</td></tr>';
    // 
    print '<tr><td class="fieldrequired" class="nowrap">' . $langs->trans("Fieldref") . '</td><td>' . $object->ref . '</td></tr>';
    print '<tr><td class="fieldrequired">' . $langs->trans("Fielddate_start") . '</td>'
            . '<td>' . dol_print_date($object->datestart) . '</td></tr>';
    print '<tr><td class="fieldrequired">' . $langs->trans("Fielddate_end") . '</td>'
            . '<td>' . dol_print_date($object->dateend) . '</td></tr>';

    print '<tr><td class="fieldrequired">' . $langs->trans("Fieldnote_private") . '</td><td>' . $object->note_private . '</td></tr>';
    print '<tr><td class="fieldrequired">' . $langs->trans("Fieldnote_public") . '</td><td>' . $object->note_public . '</td></tr>';
    print '<tr><td class="fieldrequired">' . $langs->trans("Fieldstatus") . '</td><td>' . $object->getLibStatut(2) . '</td></tr>';
//    print '<tr><td class="fieldrequired">' . $langs->trans("Fieldproject") . '</td><td>' . $project->getNomUrl(1) . '</td></tr>';

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
        if ($user->rights->educo->write) {
            // $project_params =;

            $project_params .= '?ref=CRON-' . $object->ref;
            $project_params .= '&public=0';
            $project_params .= ' &projectstart=' . dol_print_date($object->datestart, '%d/%m/%Y');
            $project_params .= ' &projectstartday=' . dol_print_date($object->datestart, '%d');
            $project_params .= '  &projectstartmonth=' . dol_print_date($object->datestart, '%m');
            $project_params .= '  &projectstartyear=' . $object->ref;
            $project_params .= ' &projectend=' . dol_print_date($object->dateend, '%d/%m/%Y');
            $project_params .= '  &projectendday=' . dol_print_date($object->dateend, '%d');
            $project_params .= '  &projectendmonth=' . dol_print_date($object->dateend, '%m');
            $project_params .= '  &projectendyear=' . $object->ref;
            $project_params .= '  &opp_status=1';
            $project_params .= '  &opp_percent=0.00';
            $project_params .= '  &opp_percent_not_set=1';
            $project_params .= '  &token=' . $_SESSION['newtoken'];
            $project_params .= '  &backtopage=' . urlencode($_SERVER["PHP_SELF"] . '?id=' . $object->id . '&amp;action=view');
            // $project_params['backtopage']=$backtopage;
            $parts = parse_url($project_params);
            parse_str($parts['query'], $query);
            //$query['backtopage']=$backtopage;
            print '<form  method="post" id="form_project" action="' . DOL_URL_ROOT . ('/projet/card.php?action=add') . '">';
            foreach ($query as $k => $value) {
                $value = (is_numeric($value) ? (int) $value : $value);
                print '<input type="hidden" name="' . $k . '" value="' . trim($value) . '">';
            }
            print '</form>';

            print '<script>' . "\n";
            print '$(document).ready(function(){$("#addproject").click(function(e){' . "\n";
            print '  e.preventDefault();';
            print '  $("#form_project").submit(); ' . "\n";
            print '});});';
            print '</script>';
            print '<div id="addproject" class="inline-block divButAction"><a class="butAction" href="#">' . $langs->trans("AddProject") . '</a></div>' . "\n";
        }
        if ($user->rights->educo->delete) {
            print '<div class="inline-block divButAction"><a class="butActionDelete" href="' . $_SERVER["PHP_SELF"] . '?id=' . $object->id . '&amp;action=delete">' . $langs->trans('Delete') . '</a></div>' . "\n";
        }
    }
    print '</div>' . "\n";


    // Example 2 : Adding links to objects
    // Show links to link elements
    // $object->fetchObjectLinked();
    //var_dump($object->linkedObjectsIds);
    // $linktoelem = $form->showLinkToObjectBlock($object, null, array('educoacadyear'));
    // var_dump($object->linkedObjects);
    $somethingshown = $form->showLinkedObjectBlock($object);
}
// End of page
llxFooter();
$db->close();
