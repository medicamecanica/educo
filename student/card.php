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
 *   	\file       educo/educostudent_card.php
 * 		\ingroup    educo
 * 		\brief      This file is an example of a php page
 * 					Initialy built by build_class_from_table on 2017-12-15 14:20
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
$myparam = GETPOST('myparam', 'alpha');


$search_ref = GETPOST('search_ref', 'alpha');
$search_name = GETPOST('search_name', 'alpha');
$search_firstname = GETPOST('search_firstname', 'alpha');
$search_lastname = GETPOST('search_lastname', 'alpha');
$search_doc_type = GETPOST('search_doc_type', 'alpha');
$search_document = GETPOST('search_document', 'alpha');
$search_entity = GETPOST('search_entity', 'int');
$search_fk_contact = GETPOST('search_fk_contact', 'int');
$search_fk_soc = GETPOST('search_fk_soc', 'int');
$search_status = GETPOST('search_status', 'int');
$search_import_key = GETPOST('search_import_key', 'alpha');



if (empty($action) && empty($id) && empty($ref))
    $action = 'view';

// Protection if external user
if ($user->societe_id > 0) {
    //accessforbidden();
}
//$result = restrictedArea($user, 'educo', $id);


$object = new Educostudent($db);
$contact = new Contact($db);
$soc = new Societe($db);
$extrafields = new ExtraFields($db);

// fetch optionals attributes and labels
$extralabels = $extrafields->fetch_name_optionals_label($object->table_element);

// Load object
include DOL_DOCUMENT_ROOT . '/core/actions_fetchobject.inc.php';  // Must be include, not include_once  // Must be include, not include_once. Include fetch and fetch_thirdparty but not fetch_optionals
// Initialize technical object to manage hooks of modules. Note that conf->hooks_modules contains array array
$hookmanager->initHooks(array('educostudent'));



/* * *****************************************************************
 * ACTIONS
 *
 * Put here all code to do according to value of "action" parameter
 * ****************************************************************** */

$parameters = array();
$reshook = $hookmanager->executeHooks('doActions', $parameters, $object, $action);    // Note that $action and $object may have been modified by some hooks
if ($reshook < 0)
    setEventMessages($hookmanager->error, $hookmanager->errors, 'errors');
$head= student_header($object);
if (empty($reshook)) {
    if ($cancel) {
        if ($action != 'addlink') {
            $urltogo = $backtopage ? $backtopage : dol_buildpath('/educo/student/list.php', 1);
            header("Location: " . $urltogo);
            exit;
        }
        if ($id > 0 || !empty($ref))
            $ret = $object->fetch($id, $ref);

        $action = '';
    }

    // Action to add record
    if ($action == 'add') {
        if (GETPOST('cancel')) {
            $urltogo = $backtopage ? $backtopage : dol_buildpath('/educo/student/list.php', 1);
            header("Location: " . $urltogo);
            exit;
        }

        $error = 0;

        /* object_prop_getpost_prop */

        $object->ref = GETPOST('doc_type', 'alpha') . GETPOST('document', 'alpha');
        $object->name = dolGetFirstLastname(GETPOST('firstname', 'alpha'), GETPOST('lastname', 'alpha'));
        $object->firstname = GETPOST('firstname', 'alpha');
        $object->lastname = GETPOST('lastname', 'alpha');
        $object->doc_type = GETPOST('doc_type', 'alpha');
        $object->document = GETPOST('document', 'alpha');
        $object->entity = $conf->entity;
        $object->date_create = dol_now();
        $object->tms = dol_now();
        //  $object->fk_contact = GETPOST('fk_contact', 'int');
        //$object->fk_soc = GETPOST('fk_soc', 'int');
        $object->status = 1;

        include DOL_DOCUMENT_ROOT . '/educo/tpl/card_add_contact.tpl.php';
        if (GETPOST('newsoc'))
            include DOL_DOCUMENT_ROOT . '/educo/tpl/card_add_third.tpl.php';
        else
            $object->fk_soc = GETPOST('fk_soc');

        if (empty($object->firstname)) {
            $error++;
            setEventMessages($langs->trans("ErrorFieldRequired", $langs->transnoentitiesnoconv("FieldFirstname")), null, 'errors');
        }
        if (empty($object->lastname)) {
            $error++;
            setEventMessages($langs->trans("ErrorFieldRequired", $langs->transnoentitiesnoconv("FieldLastname")), null, 'errors');
        }
        if (empty($object->document)) {
            $error++;
            setEventMessages($langs->trans("ErrorFieldRequired", $langs->transnoentitiesnoconv("FieldDocument")), null, 'errors');
        }
        $db->begin();

        $result = $contact->create($user);
        if ($result > 0) {
            setEventMessages(null, $langs->trans("ContactCreated") . $contact->id);
            $object->fk_contact = $contact->id;
            if (GETPOST('newsoc')) {
                $result = $soc->create($user);
                if ($result > 0) {
                    $object->fk_soc = $soc->id;
                    setEventMessages(null, $langs->trans("ThirdCreated") . $soc->id);
                } else {
                    $error++;
                    setEventMessages($soc->name, null, 'errors');
                    setEventMessages(null, 'AddSocieteError', 'errors');
                    if (!empty($soc->errors))
                        setEventMessages(null, $soc->errors, 'errors');
                    else
                        setEventMessages($soc->error, null, 'errors');
                    array_merge($object->errors, $soc->errors);
                }
            }
        }else {
            $error++;
            setEventMessages(null, 'AddContactError', 'errors');
            $object->errors[] = $contact->error;
        }
        if (!$error) {

            $result = $object->create($user);

            if ($result > 0) {
                // Logo/Photo save
                $dir = $conf->educo->dir_output . '/' . get_exdir(0, 0, 0, 1, $object, 'educo') . '/photos';
                $file_OK = is_uploaded_file($_FILES['photo']['tmp_name']);
                //var_dump($file_OK);
                if ($file_OK) {
                    if (GETPOST('deletephoto')) {
                        require_once DOL_DOCUMENT_ROOT . '/core/lib/files.lib.php';
                        $fileimg = $conf->educo->dir_output . '/' . get_exdir(0, 0, 0, 1, $object, 'member') . '/photos/' . $object->photo;
                        $dirthumbs = $conf->educo->dir_output . '/' . get_exdir(0, 0, 0, 1, $object, 'member') . '/photos/thumbs';
                        dol_delete_file($fileimg);
                        dol_delete_dir_recursive($dirthumbs);
                    }

                    if (image_format_supported($_FILES['photo']['name']) > 0) {
                        dol_mkdir($dir);

                        if (@is_dir($dir)) {
                            $newfile = $dir . '/' . dol_sanitizeFileName($_FILES['photo']['name']);
                            if (!dol_move_uploaded_file($_FILES['photo']['tmp_name'], $newfile, 1, 0, $_FILES['photo']['error']) > 0) {
                                setEventMessages($langs->trans("ErrorFailedToSaveFile"), null, 'errors');
                            } else {
                                // Create thumbs
                                $object->addThumbs($newfile);
                            }
                        }
                    } else {
                        setEventMessages("ErrorBadImageFormat", null, 'errors');
                    }
                } else {
                    switch ($_FILES['photo']['error']) {
                        case 1: //uploaded file exceeds the upload_max_filesize directive in php.ini
                        case 2: //uploaded file exceeds the MAX_FILE_SIZE directive that was specified in the html form
                            $errors[] = "ErrorFileSizeTooLarge";
                            break;
                        case 3: //uploaded file was only partially uploaded
                            $errors[] = "ErrorFilePartiallyUploaded";
                            break;
                    }
                }
                $db->commit();
                // Creation OK
                $urltogo = $backtopage ? $backtopage : dol_buildpath('/educo/student/list.php', 1);
                header("Location: " . $urltogo);
                exit;
            } {

                $db->rollback();
                // Creation KO
                if (!empty($object->errors))
                    setEventMessages(null, $object->errors, 'errors');
                else
                    setEventMessages($object->error, null, 'errors');
               // setEventMessages($object->db->lastquery, null, 'errors');
                $action = 'create';
            }
        }
        else {
            //setEventMessages($object->db->lastquery, null, 'errors');
            $object->db->rollback();
            setEventMessages(null, $object->errors, 'errors');
            $action = 'create';
        }
    }

    // Action to update record
    if ($action == 'update') {
        $error = 0;


        $object->ref = GETPOST('ref', 'alpha');
        $object->name = dolGetFirstLastname(GETPOST('firstname', 'alpha'), GETPOST('lastname', 'alpha'));
        $object->firstname = GETPOST('firstname', 'alpha');
        $object->lastname = GETPOST('lastname', 'alpha');
        $object->doc_type = GETPOST('doc_type', 'alpha');
        $object->document = GETPOST('document', 'alpha');
        //$object->entity = GETPOST('entity', 'int');
        //$object->fk_contact = GETPOST('fk_contact', 'int');
        // $object->fk_soc = GETPOST('fk_soc', 'int');
        $object->status = GETPOST('status', 'int');
        if (GETPOST('deletephoto'))
            $object->photo = '';
        elseif (!empty($_FILES['photo']['name']))
            $object->photo = dol_sanitizeFileName($_FILES['photo']['name']);

        // $object->import_key = GETPOST('import_key', 'alpha');



        if (empty($object->ref)) {
            $error++;
            setEventMessages($langs->transnoentitiesnoconv("ErrorFieldRequired", $langs->transnoentitiesnoconv("Ref")), null, 'errors');
        }

        if (!$error) {
            $result = $object->update($user);
            if ($result > 0) {

                //$categories = GETPOST('memcats', 'array');
                //$object->setCategories($categories);
                // Logo/Photo save
                $dir = $conf->educo->dir_output . '/' . get_exdir(0, 0, 0, 1, $object, 'educo') . '/photos';
                $file_OK = is_uploaded_file($_FILES['photo']['tmp_name']);
                //var_dump($file_OK);
                if ($file_OK) {
                    if (GETPOST('deletephoto')) {
                        require_once DOL_DOCUMENT_ROOT . '/core/lib/files.lib.php';
                        $fileimg = $conf->educo->dir_output . '/' . get_exdir(0, 0, 0, 1, $object, 'member') . '/photos/' . $object->photo;
                        $dirthumbs = $conf->educo->dir_output . '/' . get_exdir(0, 0, 0, 1, $object, 'member') . '/photos/thumbs';
                        dol_delete_file($fileimg);
                        dol_delete_dir_recursive($dirthumbs);
                    }

                    if (image_format_supported($_FILES['photo']['name']) > 0) {
                        dol_mkdir($dir);

                        if (@is_dir($dir)) {
                            $newfile = $dir . '/' . dol_sanitizeFileName($_FILES['photo']['name']);
                            if (!dol_move_uploaded_file($_FILES['photo']['tmp_name'], $newfile, 1, 0, $_FILES['photo']['error']) > 0) {
                                setEventMessages($langs->trans("ErrorFailedToSaveFile"), null, 'errors');
                            } else {
                                // Create thumbs
                                $object->addThumbs($newfile);
                            }
                        }
                    } else {
                        setEventMessages("ErrorBadImageFormat", null, 'errors');
                    }
                } else {
                    switch ($_FILES['photo']['error']) {
                        case 1: //uploaded file exceeds the upload_max_filesize directive in php.ini
                        case 2: //uploaded file exceeds the MAX_FILE_SIZE directive that was specified in the html form
                            $errors[] = "ErrorFileSizeTooLarge";
                            break;
                        case 3: //uploaded file was only partially uploaded
                            $errors[] = "ErrorFilePartiallyUploaded";
                            break;
                    }
                }
                //die;
                $rowid = $object->id;
                $id = $object->id;
                $action = '';

                if (!empty($backtopage)) {
                    header("Location: " . $backtopage);
                    exit;
                }

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
            header("Location: " . dol_buildpath('/educo/student/list.php', 1));
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
    print load_fiche_titre($langs->trans("NewStudent"));

    print '<form method="POST" action="' . $_SERVER["PHP_SELF"] . '" enctype="multipart/form-data">';
    print '<input type="hidden" name="action" value="add">';
    print '<input type="hidden" name="backtopage" value="' . $backtopage . '">';

    dol_fiche_head();
    include DOL_DOCUMENT_ROOT . '/educo/tpl/card_create.tpl.php';
    include DOL_DOCUMENT_ROOT . '/educo/tpl/card_create_contact.tpl.php';
    include DOL_DOCUMENT_ROOT . '/educo/tpl/card_create_third.tpl.php';
    dol_fiche_end();

    print '<div class="center"><input type="submit" class="button" name="add" value="' . $langs->trans("Create") . '"> &nbsp; <input type="submit" class="button" name="cancel" value="' . $langs->trans("Cancel") . '"></div>';

    print '</form>';
}



// Part to edit record
if (($id || $ref) && $action == 'edit') {
    print load_fiche_titre($langs->trans("Student"));

    print '<form method="POST" action="' . $_SERVER["PHP_SELF"] . '"  enctype="multipart/form-data">';
    print '<input type="hidden" name="action" value="update">';
    print '<input type="hidden" name="backtopage" value="' . $backtopage . '">';
    print '<input type="hidden" name="id" value="' . $object->id . '">';

    dol_fiche_head($head,'card',$langs->trans('Card'));

    print '<table class="border centpercent">' . "\n";
    // print '<tr><td class="fieldrequired">'.$langs->trans("Label").'</td><td><input class="flat" type="text" size="36" name="label" value="'.$label.'"></td></tr>';
    // 
    print '<tr><td class="fieldrequired">' . $langs->trans("Fieldref") . '</td><td><input class="flat" type="text" name="ref" value="' . $object->ref . '"></td></tr>';
    // print '<tr><td class="fieldrequired">' . $langs->trans("Fieldname") . '</td><td><input class="flat" type="text" name="name" value="' . $object->name . '"></td></tr>';
    print '<tr><td class="fieldrequired">' . $langs->trans("Fieldfirstname") . '</td><td><input class="flat" type="text" name="firstname" value="' . $object->firstname . '"></td></tr>';
    print '<tr><td class="fieldrequired">' . $langs->trans("Fieldlastname") . '</td><td><input class="flat" type="text" name="lastname" value="' . $object->lastname . '"></td></tr>';
    print '<tr><td class="fieldrequired">' . $langs->trans("Fielddoc_type") . '</td><td><input class="flat" type="text" name="doc_type" value="' . $object->doc_type . '"></td></tr>';
    print '<tr><td class="fieldrequired">' . $langs->trans("Fielddocument") . '</td><td><input class="flat" type="text" name="document" value="' . $object->document . '"></td></tr>';
    //print '<tr><td class="fieldrequired">' . $langs->trans("Fieldentity") . '</td><td><input class="flat" type="text" name="entity" value="' . $object->entity . '"></td></tr>';
    //print '<tr><td class="fieldrequired">' . $langs->trans("Fieldfk_contact") . '</td><td><input class="flat" type="text" name="fk_contact" value="' . $object->fk_contact . '"></td></tr>';
    // print '<tr><td class="fieldrequired">' . $langs->trans("Fieldfk_soc") . '</td><td><input class="flat" type="text" name="fk_soc" value="' . $object->fk_soc . '"></td></tr>';
    print '<tr><td class="fieldrequired">' . $langs->trans("Fieldstatus") . '</td><td>'
            . $form->selectarray('status', array(1 => $langs->trans('Enabled'), 0 => $langs->trans('Disable')), $object->status)
            . '</td></tr>';
    //print '<tr><td class="fieldrequired">' . $langs->trans("Fieldimport_key") . '</td><td><input class="flat" type="text" name="import_key" value="' . $object->import_key . '"></td></tr>';
    // Photo
    print '<tr><td>' . $langs->trans("Photo") . '</td>';
    print '<td class="hideonsmartphone" valign="middle">';
    //$object->photo = 'photo.png';
    print $form->showphoto('educo', $object) . "\n";
    if ($user->rights->educo->write) {
        if ($object->photo)
            print "<br>\n";
        print '<table class="nobordernopadding">';
        if ($object->photo)
            print '<tr><td><input type="checkbox" class="flat photodelete" name="deletephoto" id="photodelete"> ' . $langs->trans("Delete") . '<br><br></td></tr>';
        print '<tr><td>' . $langs->trans("PhotoFile") . '</td></tr>';
        print '<tr><td><input type="file" class="flat" name="photo" id="photoinput"></td></tr>';
        print '</table>';
    }
    print '</td></tr>';
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
    $soc->fetch($object->fk_soc);
    $contact->fetch($object->fk_contact);


   // print load_fiche_titre($langs->trans("Student"));

    dol_fiche_head($head,'card',$langs->trans('Student'),0,'generic');

    if ($action == 'delete') {
        $formconfirm = $form->formconfirm($_SERVER["PHP_SELF"] . '?id=' . $object->id, $langs->trans('DeleteStudent'), $langs->trans('ConfirmDeleteStudent'), 'confirm_delete', '', 0, 1);
        print $formconfirm;
    }

    print '<table class="border centpercent">' . "\n";
    // print '<tr><td class="fieldrequired">'.$langs->trans("Label").'</td><td>'.$object->label.'</td></tr>';
    // Photo
    print '<tr><td>' . $langs->trans("Photo") . '</td>';
    print '<td class="hideonsmartphone" valign="middle">';
    //$object->photo = 'photo.png';
    print $form->showphoto('educo', $object) . "\n";

    print '</td></tr>';
    print '<tr><td class="fieldrequired">' . $langs->trans("Fieldref") . '</td><td>' . $object->ref . '</td></tr>';
    // print '<tr><td class="fieldrequired">' . $langs->trans("Fieldname") . '</td><td>' . $object->name . '</td></tr>';
    print '<tr><td class="fieldrequired">' . $langs->trans("Fieldfirstname") . '</td><td>' . $object->firstname . '</td></tr>';
    print '<tr><td class="fieldrequired">' . $langs->trans("Fieldlastname") . '</td><td>' . $object->lastname . '</td></tr>';
    print '<tr><td class="fieldrequired">' . $langs->trans("Fielddoc_type") . '</td>'
            . '<td>' . $langs->trans($object->doc_type) . '</td></tr>';
    print '<tr><td class="fieldrequired">' . $langs->trans("Fielddocument") . '</td><td>' . $object->document . '</td></tr>';
    //print '<tr><td class="fieldrequired">' . $langs->trans("Fieldentity") . '</td><td>' . $object->entity . '</td></tr>';
    print '<tr><td class="fieldrequired">' . $langs->trans("Fieldfk_contact") . '</td><td>' . $soc->getNomUrl(1) . '</td></tr>';
    print '<tr><td class="fieldrequired">' . $langs->trans("Fieldfk_soc") . '</td><td>' . $contact->getNomUrl(1) . '</td></tr>';
    print '<tr><td class="fieldrequired">' . $langs->trans("Fieldstatus") . '</td>'
            . '<td>' . $object->getLibStatut(2) . '</td></tr>';

    //print '<tr><td class="fieldrequired">' . $langs->trans("Fieldimport_key") . '</td><td>' . $object->import_key . '</td></tr>';

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
    //$linktoelem = $form->showLinkToObjectBlock($object, null, array('educostudent'));
    //$somethingshown = $form->showLinkedObjectBlock($object, $linktoelem);
}


// End of page
llxFooter();
$db->close();
