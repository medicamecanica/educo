<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
print '<script>$(document).ready(function() {
    $("input[type=radio][name=newsoc]").change(function() {
    console.log(this.value);
        if (this.value == "0") {
         $("#s2id_fk_soc").show("slow");
          $("#fk_soc").select2("enable",true);
          $("#create_soc").hide("slow");
        }
        else {
       $("#s2id_fk_soc").hide("slow");
            $("#fk_soc").select2("enable",false);
          
            $("#create_soc").show("slow");  
          
        }
    });
});</script>';
print '<table class="border centpercent">';
 $showempty=1;
 $morecss="minwidth200";
print '<tr><td width="20%" class="fieldrequired">' . $langs->trans("Fieldfk_soc") . '</td><td width="20%">'
       
        . $form->select_company($object->fk_soc, 'fk_soc', $filter, $showempty, $showtype, $forcecombo, $events, $limit, $morecss, $moreparam, $selected_input_value, $hidelabel, $ajaxoptions)
        . '</td><td width="10%"><input type="radio"  id="#newsoc" name="newsoc" value="1"> Nuevo<br>'
        . '</td><td ><input type="radio" id="#existentsoc" checked name="newsoc" value="0"> Existente<br>'
        . '</td></tr>';
print '</table>';
print '<div id="create_soc" style="display: none;">';
/**
 *  Creation
 */
$private = GETPOST("private", "int");
if (!empty($conf->global->THIRDPARTY_DEFAULT_CREATE_CONTACT) && !isset($_GET['private']) && !isset($_POST['private']))
    $private = 1;
if (empty($private))
    $private = 0;

// Load object modCodeTiers
$module = (!empty($conf->global->SOCIETE_CODECLIENT_ADDON) ? $conf->global->SOCIETE_CODECLIENT_ADDON : 'mod_codeclient_leopard');
if (substr($module, 0, 15) == 'mod_codeclient_' && substr($module, -3) == 'php') {
    $module = substr($module, 0, dol_strlen($module) - 4);
}
$dirsociete = array_merge(array('/core/modules/societe/'), $conf->modules_parts['societe']);
foreach ($dirsociete as $dirroot) {
    $res = dol_include_once($dirroot . $module . '.php');
    if ($res)
        break;
}
$modCodeClient = new $module;
// Load object modCodeFournisseur
$module = (!empty($conf->global->SOCIETE_CODECLIENT_ADDON) ? $conf->global->SOCIETE_CODECLIENT_ADDON : 'mod_codeclient_leopard');
if (substr($module, 0, 15) == 'mod_codeclient_' && substr($module, -3) == 'php') {
    $module = substr($module, 0, dol_strlen($module) - 4);
}
$dirsociete = array_merge(array('/core/modules/societe/'), $conf->modules_parts['societe']);
foreach ($dirsociete as $dirroot) {
    $res = dol_include_once($dirroot . $module . '.php');
    if ($res)
        break;
}
$modCodeFournisseur = new $module;

// Define if customer/prospect or supplier status is set or not
if (GETPOST("type") != 'f') {
    $soc->client = -1;
    if (!empty($conf->global->THIRDPARTY_CUSTOMERPROSPECT_BY_DEFAULT)) {
        $soc->client = 3;
    }
}
if (GETPOST("type") == 'c') {
    $soc->client = 3;
}   // Prospect / Customer
if (GETPOST("type") == 'p') {
    $soc->client = 2;
}
if (!empty($conf->fournisseur->enabled) && (GETPOST("type") == 'f' || (GETPOST("type") == '' && !empty($conf->global->THIRDPARTY_SUPPLIER_BY_DEFAULT)))) {
    $soc->fournisseur = 1;
}

$soc->name = GETPOST('name', 'alpha');
$soc->firstname = GETPOST('firstname', 'alpha');
$soc->particulier = $private;
$soc->prefix_comm = GETPOST('prefix_comm');
$soc->client = GETPOST('client') ? GETPOST('client') : $soc->client;

if (empty($duplicate_code_error)) {
    $soc->code_client = GETPOST('code_client', 'alpha');
    $soc->fournisseur = GETPOST('fournisseur') ? GETPOST('fournisseur') : $soc->fournisseur;
} else {
    setEventMessages($langs->trans('NewCustomerSupplierCodeProposed'), '', 'warnings');
}

$soc->code_fournisseur = GETPOST('code_fournisseur', 'alpha');
$soc->address = GETPOST('address', 'alpha');
$soc->zip = GETPOST('zipcode', 'alpha');
$soc->town = GETPOST('town', 'alpha');
$soc->state_id = GETPOST('state_id', 'int');
$soc->skype = GETPOST('skype', 'alpha');
$soc->phone = GETPOST('phone', 'alpha');
$soc->fax = GETPOST('fax', 'alpha');
$soc->email = GETPOST('email', 'custom', 0, FILTER_SANITIZE_EMAIL);
$soc->url = GETPOST('url', 'custom', 0, FILTER_SANITIZE_URL);
$soc->capital = GETPOST('capital', 'alpha');
$soc->barcode = GETPOST('barcode', 'alpha');
$soc->idprof1 = GETPOST('idprof1', 'alpha');
$soc->idprof2 = GETPOST('idprof2', 'alpha');
$soc->idprof3 = GETPOST('idprof3', 'alpha');
$soc->idprof4 = GETPOST('idprof4', 'alpha');
$soc->idprof5 = GETPOST('idprof5', 'alpha');
$soc->idprof6 = GETPOST('idprof6', 'alpha');
$soc->typent_id = GETPOST('typent_id', 'int');
$soc->effectif_id = GETPOST('effectif_id', 'int');
$soc->civility_id = GETPOST('civility_id', 'int');

$soc->tva_assuj = GETPOST('assujtva_value', 'int');
$soc->status = GETPOST('status', 'int');

//Local Taxes
$soc->localtax1_assuj = GETPOST('localtax1assuj_value', 'int');
$soc->localtax2_assuj = GETPOST('localtax2assuj_value', 'int');

$soc->localtax1_value = GETPOST('lt1', 'int');
$soc->localtax2_value = GETPOST('lt2', 'int');

$soc->tva_intra = GETPOST('tva_intra', 'alpha');

$soc->commercial_id = GETPOST('commercial_id', 'int');
$soc->default_lang = GETPOST('default_lang');

$soc->logo = (isset($_FILES['photo']) ? dol_sanitizeFileName($_FILES['photo']['name']) : '');

// Gestion du logo de la société
$dir = $conf->societe->multidir_output[$conf->entity] . "/" . $soc->id . "/logos";
$file_OK = (isset($_FILES['photo']) ? is_uploaded_file($_FILES['photo']['tmp_name']) : false);
if ($file_OK) {
    if (image_format_supported($_FILES['photo']['name'])) {
        dol_mkdir($dir);

        if (@is_dir($dir)) {
            $newfile = $dir . '/' . dol_sanitizeFileName($_FILES['photo']['name']);
            $result = dol_move_uploaded_file($_FILES['photo']['tmp_name'], $newfile, 1);

            if (!$result > 0) {
                $errors[] = "ErrorFailedToSaveFile";
            } else {
                // Create thumbs
                $soc->addThumbs($newfile);
            }
        }
    }
}

// We set country_id, country_code and country for the selected country
$soc->country_id = GETPOST('country_id') ? GETPOST('country_id') : $mysoc->country_id;
if ($soc->country_id) {
    $tmparray = getCountry($soc->country_id, 'all');
    $soc->country_code = $tmparray['code'];
    $soc->country = $tmparray['label'];
}
$soc->forme_juridique_code = GETPOST('forme_juridique_code');
/* Show create form */

$linkback = "";
print load_fiche_titre($langs->trans("AddStudentSuport"), $linkback, 'title_companies.png');

if (!empty($conf->use_javascript_ajax)) {
    print "\n" . '<script type="text/javascript">';
    print '$(document).ready(function () {
						id_te_private=8;
                        id_ef15=1;
                        is_private=' . $private . ';
						if (is_private) {
							$(".individualline").show();
						} else {
							$(".individualline").hide();
						}
                        $("#radiocompany").click(function() {
                        	$(".individualline").hide();
                        	$("#typent_id").val(0);
                        	$("#effectif_id").val(0);
                        	$("#TypeName").html(document.formsoc.ThirdPartyName.value);
                        	document.formsoc.private.value=0;
                        });
                        $("#radioprivate").click(function() {
                        	$(".individualline").show();
                        	$("#typent_id").val(id_te_private);
                        	$("#effectif_id").val(id_ef15);
                        	$("#TypeName").html($("#LastName").val());
                        	//document.formsoc.private.value=1;
                        });
                        $("#selectcountry_id").change(function() {
                        	document.formsoc.action.value="create";
                        	document.formsoc.submit();
                        });
                         $("#radioprivate").trigger("click");
                     });';
    print '</script>' . "\n";

    print '<div id="selectthirdpartytype">';
    print '<div class="hideonsmartphone float">';
    print $langs->trans("ThirdPartyType") . ': &nbsp; &nbsp; ';
    print '</div>';
    print '<label for="radiocompany">';
    print '<input type="radio" id="radiocompany" disabled class="flat" name="socprivate"  value="0"' . ($private ? '' : ' checked') . '>';
    print '&nbsp;';
    print $langs->trans("CreateThirdPartyOnly");
    print '</label>';
    print ' &nbsp; &nbsp; ';
    print '<label for="radioprivate">';
    $text = '<input type="radio" id="radioprivate"  class="flat" name="socprivate" value="1"' . ($private ? ' checked' : '') . '>';
    $text .= '&nbsp;';
    $text .= $langs->trans("CreateThirdPartyAndContact");
    $htmltext = $langs->trans("ToCreateContactWithSameName");
    print $form->textwithpicto($text, $htmltext, 1, 'help', '', 0, 3);
    print '</label>';
    print '</div>';
    print "<br>\n";
}

dol_htmloutput_mesg(is_numeric($error) ? '' : $error, $errors, 'error');

//print '<form enctype="multipart/form-data" action="'.$_SERVER["PHP_SELF"].'" method="post" name="socformsoc">';
// print '<input type="hidden" name="socaction" value="add">';
// print '<input type="hidden" name="socbacktopage" value="'.$backtopage.'">';
// print '<input type="hidden" name="soctoken" value="'.$_SESSION['newtoken'].'">';
print '<input type="hidden" name="socprivate" value=' . $soc->particulier . '>';
print '<input type="hidden" name="soctype" value=' . GETPOST("type") . '>';
print '<input type="hidden" id="LastName" name="socLastName" value="' . $langs->trans('LastName') . '">';
print '<input type="hidden" name="socThirdPartyName" value="' . $langs->trans('ThirdPartyName') . '">';
if ($modCodeClient->code_auto || $modCodeFournisseur->code_auto)
    print '<input type="hidden" name="soccode_auto" value="1">';

dol_fiche_head(null, 'card', '', 0, '');

print '<table class="border" width="100%">';
// If javascript on, we show option individual
if ($conf->use_javascript_ajax) {
    print '<tr class="individualline fieldrequired"><td>' . fieldLabel('FirstName', 'firstname') . '</td>';
    print '<td colspan="3"><input type="text" class="minwidth300" maxlength="128" name="socfirstname" id="firstname" value="' . $soc->firstname_ . '"></td>';
    print '</tr>';
}
// Name, firstname
print '<tr><td class="titlefieldcreate">';
if ($soc->particulier || $private) {
    print '<span id="TypeName" class="fieldrequired">' . $langs->trans('LastName', 'name') . '</span>';
} else {
    print '<span id="TypeName" class="fieldrequired">' . fieldLabel('ThirdPartyName', 'name') . '</span>';
}
print '</td><td' . (empty($conf->global->SOCIETE_USEPREFIX) ? ' colspan="3"' : '') . '>';
print '<input type="text" class="minwidth300" maxlength="128" name="soclastname" id="name" value="' . $soc->lastname . '" autofocus="autofocus"></td>';
if (!empty($conf->global->SOCIETE_USEPREFIX)) {  // Old not used prefix field
    print '<td>' . $langs->trans('Prefix') . '</td><td><input type="text" size="5" maxlength="5" name="socprefix_comm" value="' . $soc->prefix_comm . '"></td>';
}
print '</tr>';

if ($conf->use_javascript_ajax) {
    // Title
    print '<tr class="individualline"><td>' . fieldLabel('UserTitle', 'civility_id') . '</td><td colspan="3" class="maxwidthonsmartphone">';
    print $formcompany->select_civility($soc->civility_id, 'civility_id', 'maxwidth100') . '</td>';
    print '</tr>';
}
//
//        // Alias names (commercial, trademark or alias names)
//        print '<tr id="name_alias"><td><label for="name_alias_input">'.$langs->trans('AliasNames').'</label></td>';
//	    print '<td colspan="3"><input type="text" class="minwidth300" name="socname_alias" id="name_alias_input" value="'.$soc->name_alias.'"></td></tr>';
// Prospect/Customer
print '<tr><td class="titlefieldcreate">' . fieldLabel('ProspectCustomer', 'customerprospect', 1) . '</td>';
print '<td class="maxwidthonsmartphone">';
$selected = isset($_POST['client']) ? GETPOST('client') : $soc->client;
print '<select class="flat" name="socclient" id="customerprospect">';
if (GETPOST("type") == '')
    print '<option value="-1">&nbsp;</option>';
if (empty($conf->global->SOCIETE_DISABLE_PROSPECTS))
    print '<option value="2"' . ($selected == 2 ? ' selected' : '') . '>' . $langs->trans('Prospect') . '</option>';
if (empty($conf->global->SOCIETE_DISABLE_PROSPECTS) && empty($conf->global->SOCIETE_DISABLE_CUSTOMERS) && empty($conf->global->SOCIETE_DISABLE_PROSPECTSCUSTOMERS))
    print '<option value="3"' . ($selected == 3 ? ' selected' : '') . '>' . $langs->trans('ProspectCustomer') . '</option>';
if (empty($conf->global->SOCIETE_DISABLE_CUSTOMERS))
    print '<option value="1"' . ($selected == 1 ? ' selected' : '') . '>' . $langs->trans('Customer') . '</option>';
print '<option value="0"' . ((string) $selected == '0' ? ' selected' : '') . '>' . $langs->trans('NorProspectNorCustomer') . '</option>';
print '</select></td>';

print '<td>' . fieldLabel('CustomerCode', 'customer_code') . '</td><td>';
print '<table class="nobordernopadding"><tr><td>';
$tmpcode = $soc->code_client;
if (empty($tmpcode) && !empty($modCodeClient->code_auto))
    $tmpcode = $modCodeClient->getNextValue($soc, 0);
print '<input type="text" name="soccode_client" id="customer_code" class="maxwidthonsmartphone" value="' . dol_escape_htmltag($tmpcode) . '" maxlength="15">';
print '</td><td>';
$s = $modCodeClient->getToolTip($langs, $soc, 0);
print $form->textwithpicto('', $s, 1);
print '</td></tr></table>';
print '</td></tr>';

if (!empty($conf->fournisseur->enabled) && !empty($user->rights->fournisseur->lire)) {
    // Supplier
    print '<tr>';
    print '<td>' . fieldLabel('Supplier', 'fournisseur', 1) . '</td><td>';
    $default = -1;
    if (!empty($conf->global->THIRDPARTY_SUPPLIER_BY_DEFAULT))
        $default = 1;
    print $form->selectyesno("fournisseur", (isset($_POST['fournisseur']) ? GETPOST('fournisseur') : (GETPOST("type") == '' ? $default : $soc->fournisseur)), 1, 0, (GETPOST("type") == '' ? 1 : 0));
    print '</td>';
    print '<td>' . fieldLabel('SupplierCode', 'supplier_code') . '</td><td>';
    print '<table class="nobordernopadding"><tr><td>';
    $tmpcode = $soc->code_fournisseur;
    if (empty($tmpcode) && !empty($modCodeFournisseur->code_auto))
        $tmpcode = $modCodeFournisseur->getNextValue($soc, 1);
    print '<input type="text" name="soccode_fournisseur" id="supplier_code" class="maxwidthonsmartphone" value="' . dol_escape_htmltag($tmpcode) . '" maxlength="15">';
    print '</td><td>';
    $s = $modCodeFournisseur->getToolTip($langs, $soc, 1);
    print $form->textwithpicto('', $s, 1);
    print '</td></tr></table>';
    print '</td></tr>';
}

// Status
print '<tr><td>' . fieldLabel('Status', 'status') . '</td><td colspan="3">';
print $form->selectarray('status', array('0' => $langs->trans('ActivityCeased'), '1' => $langs->trans('InActivity')), 1);
print '</td></tr>';

// Barcode
if (!empty($conf->barcode->enabled)) {
    print '<tr><td>' . fieldLabel('Gencod', 'barcode') . '</td>';
    print '<td colspan="3"><input type="text" name="socbarcode" id="barcode" value="' . $soc->barcode . '">';
    print '</td></tr>';
}

// Address
print '<tr><td class="tdtop">' . fieldLabel('Address', 'address') . '</td>';
print '<td colspan="3"><textarea name="socaddress" id="address" class="quatrevingtpercent" rows="' . _ROWS_2 . '" wrap="soft">';
print $soc->address;
print '</textarea></td></tr>';

// Zip / Town
print '<tr><td>' . fieldLabel('Zip', 'zipcode') . '</td><td>';
print $formcompany->select_ziptown($soc->zip, 'zipcode', array('town', 'selectcountry_id', 'state_id'), 0, 0, '', 'maxwidth100 quatrevingtpercent');
print '</td><td>' . fieldLabel('Town', 'town') . '</td><td>';
print $formcompany->select_ziptown($soc->town, 'town', array('zipcode', 'selectcountry_id', 'state_id'), 0, 0, '', 'maxwidth100 quatrevingtpercent');
print '</td></tr>';

// Country
print '<tr><td>' . fieldLabel('Country', 'selectcountry_id') . '</td><td colspan="3" class="maxwidthonsmartphone">';
print $form->select_country((GETPOST('country_id') != '' ? GETPOST('country_id') : $soc->country_id));
if ($user->admin)
    print info_admin($langs->trans("YouCanChangeValuesForThisListFromDictionarySetup"), 1);
print '</td></tr>';

// State
if (empty($conf->global->SOCIETE_DISABLE_STATE)) {
    print '<tr><td>' . fieldLabel('State', 'state_id') . '</td><td colspan="3" class="maxwidthonsmartphone">';
    if ($soc->country_id)
        print $formcompany->select_state($soc->state_id, $soc->country_code);
    else
        print $countrynotdefined;
    print '</td></tr>';
}

// Email web
print '<tr><td>' . fieldLabel('EMail', 'email') . (!empty($conf->global->SOCIETE_MAIL_REQUIRED) ? '*' : '') . '</td>';
print '<td colspan="3"><input type="text" name="socemail" id="email" value="' . $soc->email . '"></td></tr>';
print '<tr><td>' . fieldLabel('Web', 'url') . '</td>';
print '<td colspan="3"><input type="text" name="socurl" id="url" value="' . $soc->url . '"></td></tr>';

// Skype
if (!empty($conf->skype->enabled)) {
    print '<tr><td>' . fieldLabel('Skype', 'skype') . '</td>';
    print '<td colspan="3"><input type="text" name="socskype" id="skype" value="' . $soc->skype . '"></td></tr>';
}

// Phone / Fax
print '<tr><td>' . fieldLabel('Phone', 'phone') . '</td>';
print '<td><input type="text" name="socphone" id="phone" class="maxwidth100onsmartphone quatrevingtpercent" value="' . $soc->phone . '"></td>';
print '<td>' . fieldLabel('Fax', 'fax') . '</td>';
print '<td><input type="text" name="socfax" id="fax" class="maxwidth100onsmartphone quatrevingtpercent" value="' . $soc->fax . '"></td></tr>';

// Prof ids
$i = 2;
$j = 0;
while ($i <= 2) {
    $idprof = $langs->transcountry('ProfId' . $i, $soc->country_code);
    if ($idprof != '-') {
        $key = 'idprof' . $i;

        if (($j % 2) == 0)
            print '<tr>';

        $idprof_mandatory = 'SOCIETE_IDPROF' . ($i) . '_MANDATORY';
        print '<td>' . fieldLabel($idprof, $key, (empty($conf->global->$idprof_mandatory) ? 0 : 1)) . '</td><td>';

        print $formcompany->get_input_id_prof($i, $key, $soc->$key, $soc->country_code);
        print '</td>';
        if (($j % 2) == 1)
            print '</tr>';
        $j++;
    }
    $i++;
}
if ($j % 2 == 1)
    print '<td colspan="2"></td></tr>';

// Vat is used
print '<tr><td>' . fieldLabel('VATIsUsed', 'assujtva_value') . '</td>';
print '<td>';
print $form->selectyesno('assujtva_value', (isset($conf->global->THIRDPARTY_DEFAULT_USEVAT) ? $conf->global->THIRDPARTY_DEFAULT_USEVAT : 1), 1);     // Assujeti par defaut en creation
print '</td>';
print '<td class="nowrap">' . fieldLabel('VATIntra', 'intra_vat') . '</td>';
print '<td class="nowrap">';
$s = '<input type="text" class="flat maxwidthonsmartphone" name="soctva_intra" id="intra_vat" maxlength="20" value="' . $soc->tva_intra . '">';

if (empty($conf->global->MAIN_DISABLEVATCHECK)) {
    $s .= ' ';

    if (!empty($conf->use_javascript_ajax)) {
        print "\n";
        print '<script language="JavaScript" type="text/javascript">';
        print "function CheckVAT(a) {\n";
        print "newpopup('" . DOL_URL_ROOT . "/societe/checkvat/checkVatPopup.php?vatNumber='+a,'" . dol_escape_js($langs->trans("VATIntraCheckableOnEUSite")) . "',500,300);\n";
        print "}\n";
        print '</script>';
        print "\n";
        $s .= '<a href="#" class="hideonsmartphone" onclick="javascript: CheckVAT(document.formsoc.tva_intra.value);">' . $langs->trans("VATIntraCheck") . '</a>';
        $s = $form->textwithpicto($s, $langs->trans("VATIntraCheckDesc", $langs->trans("VATIntraCheck")), 1);
    } else {
        $s .= '<a href="' . $langs->transcountry("VATIntraCheckURL", $soc->country_id) . '" target="_blank">' . img_picto($langs->trans("VATIntraCheckableOnEUSite"), 'help') . '</a>';
    }
}
print $s;
print '</td>';
print '</tr>';

// Local Taxes
//TODO: Place into a function to control showing by country or study better option
if ($mysoc->localtax1_assuj == "1" && $mysoc->localtax2_assuj == "1") {
    print '<tr><td>' . $langs->transcountry("LocalTax1IsUsed", $mysoc->country_code) . '</td><td>';
    print $form->selectyesno('localtax1assuj_value', (isset($conf->global->THIRDPARTY_DEFAULT_USELOCALTAX1) ? $conf->global->THIRDPARTY_DEFAULT_USELOCALTAX1 : 0), 1);
    print '</td><td>' . $langs->transcountry("LocalTax2IsUsed", $mysoc->country_code) . '</td><td>';
    print $form->selectyesno('localtax2assuj_value', (isset($conf->global->THIRDPARTY_DEFAULT_USELOCALTAX2) ? $conf->global->THIRDPARTY_DEFAULT_USELOCALTAX2 : 0), 1);
    print '</td></tr>';
} elseif ($mysoc->localtax1_assuj == "1") {
    print '<tr><td>' . $langs->transcountry("LocalTax1IsUsed", $mysoc->country_code) . '</td><td colspan="3">';
    print $form->selectyesno('localtax1assuj_value', (isset($conf->global->THIRDPARTY_DEFAULT_USELOCALTAX1) ? $conf->global->THIRDPARTY_DEFAULT_USELOCALTAX1 : 0), 1);
    print '</td></tr>';
} elseif ($mysoc->localtax2_assuj == "1") {
    print '<tr><td>' . $langs->transcountry("LocalTax2IsUsed", $mysoc->country_code) . '</td><td colspan="3">';
    print $form->selectyesno('localtax2assuj_value', (isset($conf->global->THIRDPARTY_DEFAULT_USELOCALTAX2) ? $conf->global->THIRDPARTY_DEFAULT_USELOCALTAX2 : 0), 1);
    print '</td></tr>';
}

// Type - Size
print '<tr><td>' . fieldLabel('ThirdPartyType', 'typent_id') . '</td><td class="maxwidthonsmartphone">' . "\n";
$sortparam = (empty($conf->global->SOCIETE_SORT_ON_TYPEENT) ? 'ASC' : $conf->global->SOCIETE_SORT_ON_TYPEENT); // NONE means we keep sort of original array, so we sort on position. ASC, means next function will sort on label.
print $form->selectarray("typent_id", $formcompany->typent_array(0), $soc->typent_id, 0, 0, 0, '', 0, 0, 0, $sortparam);
if ($user->admin)
    print ' ' . info_admin($langs->trans("YouCanChangeValuesForThisListFromDictionarySetup"), 1);
print '</td>';
print '<td>' . fieldLabel('Staff', 'effectif_id') . '</td><td class="maxwidthonsmartphone">';
print $form->selectarray("effectif_id", $formcompany->effectif_array(0), $soc->effectif_id);
if ($user->admin)
    print ' ' . info_admin($langs->trans("YouCanChangeValuesForThisListFromDictionarySetup"), 1);
print '</td></tr>';

// Legal Form
print '<tr><td>' . fieldLabel('JuridicalStatus', 'forme_juridique_code') . '</td>';
print '<td colspan="3" class="maxwidthonsmartphone">';
if ($soc->country_id) {
    print $formcompany->select_juridicalstatus($soc->forme_juridique_code, $soc->country_code, '', 'forme_juridique_code');
} else {
    print $countrynotdefined;
}
print '</td></tr>';

// Capital
print '<tr><td>' . fieldLabel('Capital', 'capital') . '</td>';
print '<td colspan="3"><input type="text" name="soccapital" id="capital" size="10" value="' . $soc->capital . '"> ';
print '<span class="hideonsmartphone">' . $langs->trans("Currency" . $conf->currency) . '</span></td></tr>';

if (!empty($conf->global->MAIN_MULTILANGS)) {
    print '<tr><td>' . fieldLabel('DefaultLang', 'default_lang') . '</td><td colspan="3" class="maxwidthonsmartphone">' . "\n";
    print $formadmin->select_language(($soc->default_lang ? $soc->default_lang : $conf->global->MAIN_LANG_DEFAULT), 'default_lang', 0, 0, 1, 0, 0, 'maxwidth200onsmartphone');
    print '</td>';
    print '</tr>';
}

if ($user->rights->societe->client->voir) {
    // Assign a Name
    print '<tr>';
    print '<td>' . fieldLabel('AllocateCommercial', 'commercial_id') . '</td>';
    print '<td colspan="3" class="maxwidthonsmartphone">';
    print $form->select_dolusers((!empty($soc->commercial_id) ? $soc->commercial_id : $user->id), 'commercial_id', 1); // Add current user by default
    print '</td></tr>';
}

// Incoterms
if (!empty($conf->incoterm->enabled)) {
    print '<tr>';
    print '<td>' . fieldLabel('IncotermLabel', 'incoterm_id') . '</td>';
    print '<td colspan="3" class="maxwidthonsmartphone">';
    print $form->select_incoterms((!empty($soc->fk_incoterms) ? $soc->fk_incoterms : ''), (!empty($soc->location_incoterms) ? $soc->location_incoterms : ''));
    print '</td></tr>';
}

// Categories
if (!empty($conf->categorie->enabled) && !empty($user->rights->categorie->lire)) {
    $langs->load('categories');

    // Customer
    if ($soc->prospect || $soc->client) {
        print '<tr><td class="toptd">' . fieldLabel('CustomersCategoriesShort', 'custcats') . '</td><td colspan="3">';
        $cate_arbo = $form->select_all_categories(Categorie::TYPE_CUSTOMER, null, 'parent', null, null, 1);
        print $form->multiselectarray('custcats', $cate_arbo, GETPOST('custcats', 'array'), null, null, null, null, "90%");
        print "</td></tr>";
    }

    // Supplier
    if ($soc->fournisseur) {
        print '<tr><td class="toptd">' . fieldLabel('SuppliersCategoriesShort', 'suppcats') . '</td><td colspan="3">';
        $cate_arbo = $form->select_all_categories(Categorie::TYPE_SUPPLIER, null, 'parent', null, null, 1);
        print $form->multiselectarray('suppcats', $cate_arbo, GETPOST('suppcats', 'array'), null, null, null, null, "90%");
        print "</td></tr>";
    }
}

//		// Multicurrency
//		if (! empty($conf->multicurrency->enabled))
//		{
//			print '<tr>';
//			print '<td>'.fieldLabel('Currency','multicurrency_code').'</td>';
//	        print '<td colspan="3" class="maxwidthonsmartphone">';
//	        print $form->selectMultiCurrency(($soc->multicurrency_code ? $soc->multicurrency_code : $conf->currency), 'multicurrency_code', 1);
//			print '</td></tr>';
//		}
// Other attributes
$parameters = array('colspan' => ' colspan="3"', 'colspanvalue' => '3');
$reshook = $hookmanager->executeHooks('formObjectOptions', $parameters, $soc, $action);    // Note that $action and $soc may have been modified by hook
print $hookmanager->resPrint;
if (empty($reshook) && !empty($extrafields->attribute_label)) {
    print $soc->showOptionals($extrafields, 'edit');
}



print '</table>' . "\n";
print '</div>' . "\n";

