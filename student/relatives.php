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


/* ACTIONS
 *
 * Put here all code to do according to value of "action" parameter
 * ****************************************************************** */

$parameters = array();
$reshook = $hookmanager->executeHooks('doActions', $parameters, $object, $action);    // Note that $action and $object may have been modified by some hooks
if ($reshook < 0)
    setEventMessages($hookmanager->error, $hookmanager->errors, 'errors');
$head = student_header($object);
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
}
if ($object->id > 0 && (empty($action) || ($action != 'edit' && $action != 'create'))) {
    $res = $object->fetch_optionals($object->id, $extralabels);
    //$soc->fetch($object->fk_soc);
    $contact->fetch($object->fk_contact);
}
/* * *************************************************
 * VIEW
 *
 * Put here all code to build page
 * ************************************************** */
// Part to show record

llxHeader('', 'MyPageName', '');

$form = new Form($db);
dol_fiche_head($head, 'relatives', $langs->trans("Student"), 0, 'student@educo');

// Part to create

    print load_fiche_titre($langs->trans("NewContactStudent"));

    print '<form method="POST" action="' . $_SERVER["PHP_SELF"] . '">';
    print '<input type="hidden" name="action" value="add">';
    print '<input type="hidden" name="backtopage" value="' . $backtopage . '">';
    print '<table class="border centpercent">' . "\n";
    // print '<tr><td class="fieldrequired">'.$langs->trans("Label").'</td><td><input class="flat" type="text" size="36" name="label" value="'.$label.'"></td></tr>';
    // 
   // print '<tr><td class="fieldrequired">' . $langs->trans("Fieldref") . '</td><td><input class="flat" type="text" name="ref" value="' . GETPOST('ref') . '"></td></tr>';
    print '<tr><td class="fieldrequired">' . $langs->trans("Fieldfk_contact") . '</td><td>';
     print $form->select_contacts(0);
  print'</td></tr>';
  //  print '<tr><td class="fieldrequired">' . $langs->trans("Fieldfk_estudiante") . '</td><td><input class="flat" type="text" name="fk_estudiante" value="' . GETPOST('fk_estudiante') . '"></td></tr>';
    print '<tr><td class="fieldrequired">' . $langs->trans("Fieldtype") . '</td><td><input class="flat" type="text" name="type" value="' . GETPOST('type') . '"></td></tr>';
   // print '<tr><td class="fieldrequired">' . $langs->trans("Fieldnote") . '</td><td><input class="flat" type="text" name="note" value="' . GETPOST('note') . '"></td></tr>';
   // print '<tr><td class="fieldrequired">' . $langs->trans("Fieldentity") . '</td><td><input class="flat" type="text" name="entity" value="' . GETPOST('entity') . '"></td></tr>';

    print '</table>' . "\n";
    print '<div class="center">'
    . '<input type="submit" class="button" name="add" value="' . $langs->trans("Create") . '"> ';
           // . '&nbsp; <input type="submit" class="button" name="cancel" value="' . $langs->trans("Cancel") . '">
     print '</div>';

    print '</form>';



show_relatives($conf, $langs, $db, $object, $_SERVER["PHP_SELF"].'?id='.$object->id);
dol_fiche_end();
// End of page
llxFooter();
$db->close();

