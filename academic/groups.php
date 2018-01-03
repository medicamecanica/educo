<?php
/* Copyright (C) 2007-2016 Laurent Destailleur  <eldy@users.sourceforge.net>
 * Copyright (C) 2014-2016 Juanjo Menent        <jmenent@2byte.es>
 * Copyright (C) 2016      Jean-François Ferry	<jfefe@aternatik.fr>
 * Copyright (C) 2017      Nicolas ZABOURI	<info@inovea-conseil.com>
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
 *   	\file       educo/educogroup_list.php
 * 		\ingroup    educo
 * 		\brief      This file is an example of a php page
 * 					Initialy built by build_class_from_table on 2017-12-31 15:24
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
require_once(DOL_DOCUMENT_ROOT . '/core/class/html.formcompany.class.php');
require_once DOL_DOCUMENT_ROOT . '/core/lib/date.lib.php';
require_once DOL_DOCUMENT_ROOT . '/core/lib/company.lib.php';
dol_include_once('/educo/class/educogroup.class.php');
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
$grado_code = GETPOST('grado_code', 'alpha');
$grado_label = GETPOST('grado_label', 'alpha');


//academic
$academic = new Educoacadyear($db);
if ($academicid > 0 || !empty($ref))
    $ret = $academic->fetch($academicid, $ref);
$head = academicyear_header($academic);
//diccioonarios
$formeduco = new FormEduco($db);

$backtopage = GETPOST('backtopage');
$myparam = GETPOST('myparam', 'alpha');

$search_all = trim(GETPOST("sall"));

$search_ref = GETPOST('search_ref', 'alpha');
$search_sufix = GETPOST('search_sufix', 'alpha');
$search_label = GETPOST('search_label', 'alpha');
$search_fk_academicyear = GETPOST('search_fk_academicyear', 'int');
$search_grado_code = GETPOST('search_grado_code', 'alpha');
$search_statut = GETPOST('search_statut', 'int');
$search_import_key = GETPOST('search_import_key', 'alpha');


$search_myfield = GETPOST('search_myfield');
$optioncss = GETPOST('optioncss', 'alpha');

// Load variable for pagination
$limit = GETPOST("limit") ? GETPOST("limit", "int") : $conf->liste_limit;
$sortfield = GETPOST('sortfield', 'alpha');
$sortorder = GETPOST('sortorder', 'alpha');
$page = GETPOST('page', 'int');
if ($page == -1) {
    $page = 0;
}
$offset = $limit * $page;
$pageprev = $page - 1;
$pagenext = $page + 1;
if (!$sortfield)
    $sortfield = "t.rowid"; // Set here default search field
if (!$sortorder)
    $sortorder = "ASC";

// Protection if external user
$socid = 0;
if ($user->societe_id > 0) {
    $socid = $user->societe_id;
    //accessforbidden();
}

// Initialize technical object to manage context to save list fields
$contextpage = GETPOST('contextpage', 'aZ') ? GETPOST('contextpage', 'aZ') : 'educolist';

// Initialize technical object to manage hooks. Note that conf->hooks_modules contains array
$hookmanager->initHooks(array('educolist'));
$extrafields = new ExtraFields($db);

// fetch optionals attributes and labels
$extralabels = $extrafields->fetch_name_optionals_label('educo');
$search_array_options = $extrafields->getOptionalsFromPost($extralabels, '', 'search_');

// List of fields to search into when doing a "search in all"
$fieldstosearchall = array(
    't.ref' => 'Ref',
    't.note_public' => 'NotePublic',
);
if (empty($user->socid))
    $fieldstosearchall["t.note_private"] = "NotePrivate";

// Definition of fields for list
$arrayfields = array(
    't.ref' => array('label' => $langs->trans("Fieldref"), 'checked' => 1),
    't.sufix' => array('label' => $langs->trans("Fieldsufix"), 'checked' => 1),
    't.label' => array('label' => $langs->trans("Fieldlabel"), 'checked' => 1),
    //'t.fk_academicyear' => array('label' => $langs->trans("Fieldfk_academicyear"), 'checked' => 1),
    't.grado_code' => array('label' => $langs->trans("Fieldgrado_code"), 'checked' => 1),
    //'t.statut' => array('label' => $langs->trans("Fieldstatut"), 'checked' => 1),
    //'t.import_key' => array('label' => $langs->trans("Fieldimport_key"), 'checked' => 1),
    //'t.entity'=>array('label'=>$langs->trans("Entity"), 'checked'=>1, 'enabled'=>(! empty($conf->multicompany->enabled) && empty($conf->multicompany->transverse_mode))),
    't.datec' => array('label' => $langs->trans("DateCreationShort"), 'checked' => 0, 'position' => 500),
    't.tms' => array('label' => $langs->trans("DateModificationShort"), 'checked' => 0, 'position' => 500),
        //'t.statut'=>array('label'=>$langs->trans("Status"), 'checked'=>1, 'position'=>1000),
);
// Extra fields
if (is_array($extrafields->attribute_label) && count($extrafields->attribute_label)) {
    foreach ($extrafields->attribute_label as $key => $val) {
        $arrayfields["ef." . $key] = array('label' => $extrafields->attribute_label[$key], 'checked' => $extrafields->attribute_list[$key], 'position' => $extrafields->attribute_pos[$key], 'enabled' => $extrafields->attribute_perms[$key]);
    }
}


// Load object if id or ref is provided as parameter
$object = new Educogroup($db);
if (($id > 0 || !empty($ref)) && $action != 'add') {
    $result = $object->fetch($id, $ref);
    if ($result < 0)
        dol_print_error($db);
}


/* * *****************************************************************
 * ACTIONS
 *
 * Put here all code to do according to value of "action" parameter
 * ****************************************************************** */

if (GETPOST('cancel')) {
    $action = 'list';
    $massaction = '';
}
if (!GETPOST('confirmmassaction') && $massaction != 'presend' && $massaction != 'confirm_presend') {
    $massaction = '';
}

$parameters = array();
$reshook = $hookmanager->executeHooks('doActions', $parameters, $object, $action);    // Note that $action and $object may have been modified by some hooks
if ($reshook < 0)
    setEventMessages($hookmanager->error, $hookmanager->errors, 'errors');

if (empty($reshook)) {
    // Selection of new fields
    include DOL_DOCUMENT_ROOT . '/core/actions_changeselectedfields.inc.php';

    // Purge search criteria
    if (GETPOST("button_removefilter_x") || GETPOST("button_removefilter.x") || GETPOST("button_removefilter")) { // All tests are required to be compatible with all browsers

        $search_ref = '';
        $search_sufix = '';
        $search_label = '';
        $search_fk_academicyear = '';
        $search_grado_code = '';
        $search_statut = '';
        $search_import_key = '';


        $search_date_creation = '';
        $search_date_update = '';
        $toselect = '';
        $search_array_options = array();
    }

    // Mass actions
    $objectclass = 'Skeleton';
    $objectlabel = 'Skeleton';
    $permtoread = $user->rights->educo->read;
    $permtodelete = $user->rights->educo->delete;
    $uploaddir = $conf->educo->dir_output;
    include DOL_DOCUMENT_ROOT . '/core/actions_massactions.inc.php';
    // Action to add record
	if ($action == 'add')
	{
		if (GETPOST('cancel'))
		{
			$urltogo=$backtopage?$backtopage:dol_buildpath('/educo/list.php',1);
			header("Location: ".$urltogo);
			exit;
		}

		$error=0;

		/* object_prop_getpost_prop */
		
	$object->ref=$academicid.'-'.GETPOST('grado_code','alpha').'-'.GETPOST('sufix','alpha');
	$object->sufix=GETPOST('sufix','alpha');
	$object->label=$grado_label.' '.GETPOST('sufix','alpha');
	$object->fk_academicyear=$academicid;
	$object->grado_code=GETPOST('grado_code','alpha');
	$object->statut=1;
        $object->date_create= dol_now();
       	//$object->import_key=GETPOST('import_key','alpha');

		

		if (empty($object->grado_code))
		{
			$error++;
			setEventMessages($langs->trans("ErrorFieldRequired",$langs->transnoentitiesnoconv("Fieldgrado_code")), null, 'errors');
		}
                if (empty($object->sufix))
		{
			$error++;
			setEventMessages($langs->trans("ErrorFieldRequired",$langs->transnoentitiesnoconv("Fieldsufix")), null, 'errors');
		}

		if (! $error)
		{
			$result=$object->create($user);
                       // var_dump($object->db->lastquery);
			if ($result > 0)
			{
				// Creation OK
				//$urltogo=$backtopage?$backtopage:dol_buildpath('/educo/list.php',1);
				//header("Location: ".$urltogo);
				//exit;
                            setEventMessages($langs->trans('GroupAdded'));
			}
			{
				// Creation KO
				if (! empty($object->errors)) setEventMessages(null, $object->errors, 'errors');
				else  setEventMessages($object->error, null, 'errors');
				$action='create';
			}
		}
		else
		{
			$action='create';
		}
	}
}



/* * *************************************************
 * VIEW
 *
 * Put here all code to build page
 * ************************************************** */

$now = dol_now();

$form = new Form($db);

//$help_url="EN:Module_Customers_Orders|FR:Module_Commandes_Clients|ES:Módulo_Pedidos_de_clientes";
$help_url = '';
$title = $langs->trans('MyModuleListTitle');

// Put here content of your page
// Example : Adding jquery code



$sql = "SELECT";
$sql .= " t.rowid,";

$sql .= " t.ref,";
$sql .= " t.sufix,";
$sql .= " t.label,";
$sql .= " t.fk_academicyear,";
$sql .= " t.grado_code,";
$sql .= " t.tms,";
$sql .= " t.date_create,";
$sql .= " t.statut,";
$sql .= " t.import_key";


// Add fields from extrafields
foreach ($extrafields->attribute_label as $key => $val)
    $sql .= ($extrafields->attribute_type[$key] != 'separate' ? ",ef." . $key . ' as options_' . $key : '');
// Add fields from hooks
$parameters = array();
$reshook = $hookmanager->executeHooks('printFieldListSelect', $parameters);    // Note that $action and $object may have been modified by hook
$sql .= $hookmanager->resPrint;
$sql .= " FROM " . MAIN_DB_PREFIX . "educo_group as t";
if (is_array($extrafields->attribute_label) && count($extrafields->attribute_label))
    $sql .= " LEFT JOIN " . MAIN_DB_PREFIX . "educo_group_extrafields as ef on (t.rowid = ef.fk_object)";
$sql .= " WHERE 1 = 1";
//$sql.= " WHERE u.entity IN (".getEntity('mytable',1).")";

if ($search_ref)
    $sql .= natural_search("ref", $search_ref);
if ($search_sufix)
    $sql .= natural_search("sufix", $search_sufix);
if ($search_label)
    $sql .= natural_search("label", $search_label);
if ($search_fk_academicyear)
    $sql .= natural_search("fk_academicyear", $search_fk_academicyear);
if ($search_grado_code)
    $sql .= natural_search("grado_code", $search_grado_code);
if ($search_statut)
    $sql .= natural_search("statut", $search_statut);
if ($search_import_key)
    $sql .= natural_search("import_key", $search_import_key);


if ($sall)
    $sql .= natural_search(array_keys($fieldstosearchall), $sall);
// Add where from extra fields
foreach ($search_array_options as $key => $val) {
    $crit = $val;
    $tmpkey = preg_replace('/search_options_/', '', $key);
    $typ = $extrafields->attribute_type[$tmpkey];
    $mode = 0;
    if (in_array($typ, array('int', 'double')))
        $mode = 1;    // Search on a numeric
    if ($val && ( ($crit != '' && !in_array($typ, array('select'))) || !empty($crit))) {
        $sql .= natural_search('ef.' . $tmpkey, $crit, $mode);
    }
}
// Add where from hooks
$parameters = array();
$reshook = $hookmanager->executeHooks('printFieldListWhere', $parameters);    // Note that $action and $object may have been modified by hook
$sql .= $hookmanager->resPrint;
$sql .= $db->order($sortfield, $sortorder);
//$sql.= $db->plimit($conf->liste_limit+1, $offset);
// Count total nb of records
$nbtotalofrecords = '';
if (empty($conf->global->MAIN_DISABLE_FULL_SCANLIST)) {
    $result = $db->query($sql);
    $nbtotalofrecords = $db->num_rows($result);
}

$sql .= $db->plimit($limit + 1, $offset);

dol_syslog($script_file, LOG_DEBUG);
$resql = $db->query($sql);
if (!$resql) {
    dol_print_error($db);
    exit;
}

$num = $db->num_rows($resql);

// Direct jump if only one record found
if ($num == 1 && !empty($conf->global->MAIN_SEARCH_DIRECT_OPEN_IF_ONLY_ONE) && $search_all) {
    $obj = $db->fetch_object($resql);
    $id = $obj->rowid;
    header("Location: " . DOL_URL_ROOT . '/educogroup/card.php?id=' . $id);
    exit;
}

llxHeader('', $title, $help_url);
print '<script type="text/javascript" language="javascript">
$(document).ready(function() {
	function init_myfunc()
	{
		$("#myid").removeAttr(\'disabled\');
		$("#myid").attr(\'disabled\',\'disabled\');
	}
	init_myfunc();
	$("#mybutton").click(function() {
		init_myfunc();
	});
       $("#selectgrado_code").change(function() {
		$("#grado_label").val($(this).text());
                console.log($(this).text());
	});
});
</script>';
$arrayofselected = is_array($toselect) ? $toselect : array();

$param = '';
if (!empty($contextpage) && $contextpage != $_SERVER["PHP_SELF"])
    $param .= '&contextpage=' . $contextpage;
if ($limit > 0 && $limit != $conf->liste_limit)
    $param .= '&limit=' . $limit;
if ($search_field1 != '')
    $param .= '&amp;search_field1=' . urlencode($search_field1);
if ($search_field2 != '')
    $param .= '&amp;search_field2=' . urlencode($search_field2);
if ($optioncss != '')
    $param .= '&optioncss=' . $optioncss;
// Add $param from extra fields
foreach ($search_array_options as $key => $val) {
    $crit = $val;
    $tmpkey = preg_replace('/search_options_/', '', $key);
    if ($val != '')
        $param .= '&search_options_' . $tmpkey . '=' . urlencode($val);
}

$arrayofmassactions = array(
    'presend' => $langs->trans("SendByMail"),
    'builddoc' => $langs->trans("PDFMerge"),
);
if ($user->rights->educo->supprimer)
    $arrayofmassactions['delete'] = $langs->trans("Delete");
if ($massaction == 'presend')
    $arrayofmassactions = array();
$massactionbutton = $form->selectMassAction('', $arrayofmassactions);

print '<form method="POST" id="searchFormList" action="' . $_SERVER["PHP_SELF"] . '">';
if ($optioncss != '')
    print '<input type="hidden" name="optioncss" value="' . $optioncss . '">';
print '<input type="hidden" name="token" value="' . $_SESSION['newtoken'] . '">';
print '<input type="hidden" name="formfilteraction" id="formfilteraction" value="list">';
print '<input type="hidden" name="action" value="list">';
print '<input type="hidden" name="sortfield" value="' . $sortfield . '">';
print '<input type="hidden" name="sortorder" value="' . $sortorder . '">';
print '<input type="hidden" name="contextpage" value="' . $contextpage . '">';

print_barre_liste($title, $page, $_SERVER["PHP_SELF"], $param, $sortfield, $sortorder, '', $num, $nbtotalofrecords, 'title_companies', 0, '', '', $limit);

if ($sall) {
    foreach ($fieldstosearchall as $key => $val)
        $fieldstosearchall[$key] = $langs->trans($val);
    print $langs->trans("FilterOnInto", $sall) . join(', ', $fieldstosearchall);
}

$moreforfilter = '';
$moreforfilter .= '<div class="divsearchfield">';
$moreforfilter .= $langs->trans('MyFilter') . ': <input type="text" name="search_myfield" value="' . dol_escape_htmltag($search_myfield) . '">';
$moreforfilter .= '</div>';

$parameters = array();
$reshook = $hookmanager->executeHooks('printFieldPreListTitle', $parameters);    // Note that $action and $object may have been modified by hook
if (empty($reshook))
    $moreforfilter .= $hookmanager->resPrint;
else
    $moreforfilter = $hookmanager->resPrint;

//if (! empty($moreforfilter))
//{
//	print '<div class="liste_titre liste_titre_bydiv centpercent">';
//	print $moreforfilter;
//    print '</div>';
//}

$varpage = empty($contextpage) ? $_SERVER["PHP_SELF"] : $contextpage;
$selectedfields = $form->multiSelectArrayWithCheckbox('selectedfields', $arrayfields, $varpage); // This also change content of $arrayfields
//tab academic

dol_fiche_head($head, 'groups');
//baner
print '<div class="arearef heightref valignmiddle" width="100%">';
print '<table class="tagtable liste" >' . "\n";
print '<tr><td width="30%">' . $langs->trans("AcademicYear") . '</td><td width="70%" colspan="3">';
$academic->next_prev_filter = "te.fournisseur = 1";
print $form->showrefnav($academic, 'academicid', '', ($user->societe_id ? 0 : 1), 'rowid', 'ref', '', '');
print '</td></tr></table>';

// Part to create
//if ($action == 'create')
//{
	print load_fiche_titre($langs->trans("NewMyModule"));

	print '<form method="POST" action="'.$_SERVER["PHP_SELF"].'">';
	print '<input type="hidden" name="action" value="add">';
	print '<input type="hidden" name="backtopage" value="'.$backtopage.'">';
        print '<input type="hidden" name="academicid" value="' . $academicid . '">';
        print '<input type="hidden" name="grado_label" id="grado_label" value="' . $grado_lsbel. '">';

	dol_fiche_head();

	print '<table class="border centpercent">'."\n";
	// print '<tr><td class="fieldrequired">'.$langs->trans("Label").'</td><td><input class="flat" type="text" size="36" name="label" value="'.$label.'"></td></tr>';
	// 
//print '<tr><td class="fieldrequired">'.$langs->trans("Fieldref").'</td><td><input class="flat" type="text" name="ref" value="'.GETPOST('ref').'"></td></tr>';

//print '<tr><td class="fieldrequired">'.$langs->trans("Fieldlabel").'</td><td><input class="flat" type="text" name="label" value="'.GETPOST('label').'"></td></tr>';
//print '<tr><td class="fieldrequired">'.$langs->trans("Fieldfk_academicyear").'</td><td><input class="flat" type="text" name="fk_academicyear" value="'.GETPOST('fk_academicyear').'"></td></tr>';
print '<tr><td class="fieldrequired">'.$langs->trans("Fieldgrado_code").'</td><td>';
print    $formeduco->select_dictionary('grado_code', 'educo_c_grado', 'code', 'label', $grado_code,1).'</td></tr>';
print '</td></tr>';
print '<tr><td class="fieldrequired">'.$langs->trans("Fieldsufix").'</td><td><input class="flat" type="text" name="sufix" value="'.GETPOST('sufix').'"></td></tr>';
//print '<tr><td class="fieldrequired">'.$langs->trans("Fieldstatut").'</td><td><input class="flat" type="text" name="statut" value="'.GETPOST('statut').'"></td></tr>';
//print '<tr><td class="fieldrequired">'.$langs->trans("Fieldimport_key").'</td><td><input class="flat" type="text" name="import_key" value="'.GETPOST('import_key').'"></td></tr>';

	print '</table>'."\n";

	dol_fiche_end();

	print '<div class="center"><input type="submit" class="button" name="add" value="'.$langs->trans("Create").'"> &nbsp; <input type="submit" class="button" name="cancel" value="'.$langs->trans("Cancel").'"></div>';

	print '</form>';
//}
print '</div>';
print '<div class="div-table-responsive">';
print '<table class="tagtable liste' . ($moreforfilter ? " listwithfilterbefore" : "") . '">' . "\n";

// Fields title
print '<tr class="liste_titre">';
// 
if (!empty($arrayfields['t.ref']['checked']))
    print_liste_field_titre($arrayfields['t.ref']['label'], $_SERVER['PHP_SELF'], 't.ref', '', $params, '', $sortfield, $sortorder);
if (!empty($arrayfields['t.sufix']['checked']))
    print_liste_field_titre($arrayfields['t.sufix']['label'], $_SERVER['PHP_SELF'], 't.sufix', '', $params, '', $sortfield, $sortorder);
if (!empty($arrayfields['t.label']['checked']))
    print_liste_field_titre($arrayfields['t.label']['label'], $_SERVER['PHP_SELF'], 't.label', '', $params, '', $sortfield, $sortorder);
if (!empty($arrayfields['t.fk_academicyear']['checked']))
    print_liste_field_titre($arrayfields['t.fk_academicyear']['label'], $_SERVER['PHP_SELF'], 't.fk_academicyear', '', $params, '', $sortfield, $sortorder);
if (!empty($arrayfields['t.grado_code']['checked']))
    print_liste_field_titre($arrayfields['t.grado_code']['label'], $_SERVER['PHP_SELF'], 't.grado_code', '', $params, '', $sortfield, $sortorder);
if (!empty($arrayfields['t.statut']['checked']))
    print_liste_field_titre($arrayfields['t.statut']['label'], $_SERVER['PHP_SELF'], 't.statut', '', $params, '', $sortfield, $sortorder);
if (!empty($arrayfields['t.import_key']['checked']))
    print_liste_field_titre($arrayfields['t.import_key']['label'], $_SERVER['PHP_SELF'], 't.import_key', '', $params, '', $sortfield, $sortorder);

//if (! empty($arrayfields['t.field1']['checked'])) print_liste_field_titre($arrayfields['t.field1']['label'],$_SERVER['PHP_SELF'],'t.field1','',$param,'',$sortfield,$sortorder);
//if (! empty($arrayfields['t.field2']['checked'])) print_liste_field_titre($arrayfields['t.field2']['label'],$_SERVER['PHP_SELF'],'t.field2','',$param,'',$sortfield,$sortorder);
// Extra fields
if (is_array($extrafields->attribute_label) && count($extrafields->attribute_label)) {
    foreach ($extrafields->attribute_label as $key => $val) {
        if (!empty($arrayfields["ef." . $key]['checked'])) {
            $align = $extrafields->getAlignFlag($key);
            print_liste_field_titre($extralabels[$key], $_SERVER["PHP_SELF"], "ef." . $key, "", $param, ($align ? 'align="' . $align . '"' : ''), $sortfield, $sortorder);
        }
    }
}
// Hook fields
$parameters = array('arrayfields' => $arrayfields);
$reshook = $hookmanager->executeHooks('printFieldListTitle', $parameters);    // Note that $action and $object may have been modified by hook
print $hookmanager->resPrint;
if (!empty($arrayfields['t.datec']['checked']))
    print_liste_field_titre($arrayfields['t.datec']['label'], $_SERVER["PHP_SELF"], "t.datec", "", $param, 'align="center" class="nowrap"', $sortfield, $sortorder);
if (!empty($arrayfields['t.tms']['checked']))
    print_liste_field_titre($arrayfields['t.tms']['label'], $_SERVER["PHP_SELF"], "t.tms", "", $param, 'align="center" class="nowrap"', $sortfield, $sortorder);
//if (! empty($arrayfields['t.status']['checked'])) print_liste_field_titre($langs->trans("Status"),$_SERVER["PHP_SELF"],"t.status","",$param,'align="center"',$sortfield,$sortorder);
print_liste_field_titre($selectedfields, $_SERVER["PHP_SELF"], "", '', '', 'align="right"', $sortfield, $sortorder, 'maxwidthsearch ');
print '</tr>' . "\n";

// Fields title search
print '<tr class="liste_titre">';
// 
if (!empty($arrayfields['t.ref']['checked']))
    print '<td class="liste_titre"><input type="text" class="flat" name="search_ref" value="' . $search_ref . '" size="10"></td>';
if (!empty($arrayfields['t.sufix']['checked']))
    print '<td class="liste_titre"><input type="text" class="flat" name="search_sufix" value="' . $search_sufix . '" size="10"></td>';
if (!empty($arrayfields['t.label']['checked']))
    print '<td class="liste_titre"><input type="text" class="flat" name="search_label" value="' . $search_label . '" size="10"></td>';
if (!empty($arrayfields['t.fk_academicyear']['checked']))
    print '<td class="liste_titre"><input type="text" class="flat" name="search_fk_academicyear" value="' . $search_fk_academicyear . '" size="10"></td>';
if (!empty($arrayfields['t.grado_code']['checked']))
    print '<td class="liste_titre"><input type="text" class="flat" name="search_grado_code" value="' . $search_grado_code . '" size="10"></td>';
if (!empty($arrayfields['t.statut']['checked']))
    print '<td class="liste_titre"><input type="text" class="flat" name="search_statut" value="' . $search_statut . '" size="10"></td>';
if (!empty($arrayfields['t.import_key']['checked']))
    print '<td class="liste_titre"><input type="text" class="flat" name="search_import_key" value="' . $search_import_key . '" size="10"></td>';

//if (! empty($arrayfields['t.field1']['checked'])) print '<td class="liste_titre"><input type="text" class="flat" name="search_field1" value="'.$search_field1.'" size="10"></td>';
//if (! empty($arrayfields['t.field2']['checked'])) print '<td class="liste_titre"><input type="text" class="flat" name="search_field2" value="'.$search_field2.'" size="10"></td>';
// Extra fields
if (is_array($extrafields->attribute_label) && count($extrafields->attribute_label)) {
    foreach ($extrafields->attribute_label as $key => $val) {
        if (!empty($arrayfields["ef." . $key]['checked'])) {
            $align = $extrafields->getAlignFlag($key);
            $typeofextrafield = $extrafields->attribute_type[$key];
            print '<td class="liste_titre' . ($align ? ' ' . $align : '') . '">';
            if (in_array($typeofextrafield, array('varchar', 'int', 'double', 'select'))) {
                $crit = $val;
                $tmpkey = preg_replace('/search_options_/', '', $key);
                $searchclass = '';
                if (in_array($typeofextrafield, array('varchar', 'select')))
                    $searchclass = 'searchstring';
                if (in_array($typeofextrafield, array('int', 'double')))
                    $searchclass = 'searchnum';
                print '<input class="flat' . ($searchclass ? ' ' . $searchclass : '') . '" size="4" type="text" name="search_options_' . $tmpkey . '" value="' . dol_escape_htmltag($search_array_options['search_options_' . $tmpkey]) . '">';
            }
            print '</td>';
        }
    }
}
// Fields from hook
$parameters = array('arrayfields' => $arrayfields);
$reshook = $hookmanager->executeHooks('printFieldListOption', $parameters);    // Note that $action and $object may have been modified by hook
print $hookmanager->resPrint;
if (!empty($arrayfields['t.datec']['checked'])) {
    // Date creation
    print '<td class="liste_titre">';
    print '</td>';
}
if (!empty($arrayfields['t.tms']['checked'])) {
    // Date modification
    print '<td class="liste_titre">';
    print '</td>';
}
/* if (! empty($arrayfields['u.statut']['checked']))
  {
  // Status
  print '<td class="liste_titre" align="center">';
  print $form->selectarray('search_statut', array('-1'=>'','0'=>$langs->trans('Disabled'),'1'=>$langs->trans('Enabled')),$search_statut);
  print '</td>';
  } */
// Action column
print '<td class="liste_titre" align="right">';
$searchpitco = $form->showFilterAndCheckAddButtons($massactionbutton ? 1 : 0, 'checkforselect', 1);
print $searchpitco;
print '</td>';
print '</tr>' . "\n";


$i = 0;
$var = true;
$totalarray = array();
while ($i < min($num, $limit)) {
    $obj = $db->fetch_object($resql);
    if ($obj) {
        $var = !$var;

        // Show here line of result
        print '<tr ' . $bc[$var] . '>';
        // LIST_OF_TD_FIELDS_LIST
        foreach ($arrayfields as $key => $value) {
            if (!empty($arrayfields[$key]['checked'])) {
                $key2 = str_replace('t.', '', $key);
                print '<td>' . $obj->$key2 . '</td>';
                if (!$i)
                    $totalarray['nbfield'] ++;
            }
        }
        // Extra fields
        if (is_array($extrafields->attribute_label) && count($extrafields->attribute_label)) {
            foreach ($extrafields->attribute_label as $key => $val) {
                if (!empty($arrayfields["ef." . $key]['checked'])) {
                    print '<td';
                    $align = $extrafields->getAlignFlag($key);
                    if ($align)
                        print ' align="' . $align . '"';
                    print '>';
                    $tmpkey = 'options_' . $key;
                    print $extrafields->showOutputField($key, $obj->$tmpkey, '', 1);
                    print '</td>';
                    if (!$i)
                        $totalarray['nbfield'] ++;
                }
            }
        }
        // Fields from hook
        $parameters = array('arrayfields' => $arrayfields, 'obj' => $obj);
        $reshook = $hookmanager->executeHooks('printFieldListValue', $parameters);    // Note that $action and $object may have been modified by hook
        print $hookmanager->resPrint;
        // Date creation
        if (!empty($arrayfields['t.datec']['checked'])) {
            print '<td align="center">';
            print dol_print_date($db->jdate($obj->date_creation), 'dayhour');
            print '</td>';
            if (!$i)
                $totalarray['nbfield'] ++;
        }
        // Date modification
        if (!empty($arrayfields['t.tms']['checked'])) {
            print '<td align="center">';
            print dol_print_date($db->jdate($obj->date_update), 'dayhour');
            print '</td>';
            if (!$i)
                $totalarray['nbfield'] ++;
        }
        // Status
        /*
          if (! empty($arrayfields['u.statut']['checked']))
          {
          $userstatic->statut=$obj->statut;
          print '<td align="center">'.$userstatic->getLibStatut(3).'</td>';
          } */

        // Action column
        print '<td class="nowrap" align="center">';
        if ($massactionbutton || $massaction) {   // If we are in select mode (massactionbutton defined) or if we have already selected and sent an action ($massaction) defined
            $selected = 0;
            if (in_array($obj->rowid, $arrayofselected))
                $selected = 1;
            print '<input id="cb' . $obj->rowid . '" class="flat checkforselect" type="checkbox" name="toselect[]" value="' . $obj->rowid . '"' . ($selected ? ' checked="checked"' : '') . '>';
        }
        print '</td>';
        if (!$i)
            $totalarray['nbfield'] ++;

        print '</tr>';
    }
    $i++;
}

// Show total line
if (isset($totalarray['totalhtfield'])) {
    print '<tr class="liste_total">';
    $i = 0;
    while ($i < $totalarray['nbfield']) {
        $i++;
        if ($i == 1) {
            if ($num < $limit && empty($offset))
                print '<td align="left">' . $langs->trans("Total") . '</td>';
            else
                print '<td align="left">' . $langs->trans("Totalforthispage") . '</td>';
        }
        elseif ($totalarray['totalhtfield'] == $i)
            print '<td align="right">' . price($totalarray['totalht']) . '</td>';
        elseif ($totalarray['totalvatfield'] == $i)
            print '<td align="right">' . price($totalarray['totalvat']) . '</td>';
        elseif ($totalarray['totalttcfield'] == $i)
            print '<td align="right">' . price($totalarray['totalttc']) . '</td>';
        else
            print '<td></td>';
    }
    print '</tr>';
}

$db->free($resql);

$parameters = array('arrayfields' => $arrayfields, 'sql' => $sql);
$reshook = $hookmanager->executeHooks('printFieldListFooter', $parameters);    // Note that $action and $object may have been modified by hook
print $hookmanager->resPrint;

print '</table>' . "\n";
dol_fiche_end();
print '</div>' . "\n";

print '</form>' . "\n";


if ($massaction == 'builddoc' || $action == 'remove_file' || $show_files) {
    // Show list of available documents
    $urlsource = $_SERVER['PHP_SELF'] . '?sortfield=' . $sortfield . '&sortorder=' . $sortorder;
    $urlsource .= str_replace('&amp;', '&', $param);

    $filedir = $diroutputmassaction;
    $genallowed = $user->rights->facture->lire;
    $delallowed = $user->rights->facture->lire;

    print $formfile->showdocuments('massfilesarea_educo', '', $filedir, $urlsource, 0, $delallowed, '', 1, 1, 0, 48, 1, $param, $title, '');
} else {
    print '<br><a name="show_files"></a><a href="' . $_SERVER["PHP_SELF"] . '?show_files=1' . $param . '#show_files">' . $langs->trans("ShowTempMassFilesArea") . '</a>';
}


// End of page
llxFooter();
$db->close();