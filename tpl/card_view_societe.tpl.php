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

/*
 * View
 */

if (!empty($object->id))
    $res = $object->fetch_optionals($object->id, $extralabels);
//if ($res < 0) { dol_print_error($db); exit; }


//$head = societe_prepare_head($object);

dol_fiche_head($head, 'suport', $langs->trans("Student"), -1, 'generic');

// Confirm delete third party
if ($action == 'delete' || ($conf->use_javascript_ajax && empty($conf->dol_use_jmobile))) {
    print $form->formconfirm($_SERVER["PHP_SELF"] . "?socid=" . $object->id, $langs->trans("DeleteACompany"), $langs->trans("ConfirmDeleteCompany"), "confirm_delete", '', 0, "action-delete");
}

if ($action == 'merge') {
    $formquestion = array(
        array(
            'name' => 'soc_origin',
            'label' => $langs->trans('MergeOriginThirdparty'),
            'type' => 'other',
            'value' => $form->select_company('', 'soc_origin', 's.rowid != ' . $object->id, 'SelectThirdParty', 0, 0, array(), 0, 'minwidth200')
        )
    );

    print $form->formconfirm($_SERVER["PHP_SELF"] . "?socid=" . $object->id, $langs->trans("MergeThirdparties"), $langs->trans("ConfirmMergeThirdparties"), "confirm_merge", $formquestion, 'no', 1, 200);
}

dol_htmloutput_errors($error, $errors);

$linkback = '<a href="' . DOL_URL_ROOT . '/societe/list.php?restore_lastsearch_values=1">' . $langs->trans("BackToList") . '</a>';

dol_banner_tab($object, 'socid', $linkback, ($user->societe_id ? 0 : 1), 'rowid', 'nom');


print '<div class="fichecenter">';
print '<div class="fichehalfleft">';

print '<div class="underbanner clearboth"></div>';
print '<table class="border tableforfield" width="100%">';

// Prospect/Customer
print '<tr><td class="titlefield">' . $langs->trans('ProspectCustomer') . '</td><td>';
print $object->getLibCustProspStatut();
print '</td></tr>';

// Prospect/Customer
print '<tr><td>' . $langs->trans('Supplier') . '</td><td>';
print yn($object->fournisseur);
print '</td></tr>';

// Prefix
if (!empty($conf->global->SOCIETE_USEPREFIX)) {  // Old not used prefix field
    print '<tr><td>' . $langs->trans('Prefix') . '</td><td>' . $object->prefix_comm . '</td>';
    print $htmllogobar;
    $htmllogobar = '';
    print '</tr>';
}

// Customer code
if ($object->client) {
    print '<tr><td>';
    print $langs->trans('CustomerCode') . '</td><td>';
    print $object->code_client;
    if ($object->check_codeclient() <> 0)
        print ' <font class="error">(' . $langs->trans("WrongCustomerCode") . ')</font>';
    print '</td>';
    print $htmllogobar;
    $htmllogobar = '';
    print '</tr>';
}

// Supplier code
if (!empty($conf->fournisseur->enabled) && $object->fournisseur && !empty($user->rights->fournisseur->lire)) {
    print '<tr><td>';
    print $langs->trans('SupplierCode') . '</td><td>';
    print $object->code_fournisseur;
    if ($object->check_codefournisseur() <> 0)
        print ' <font class="error">(' . $langs->trans("WrongSupplierCode") . ')</font>';
    print '</td>';
    print $htmllogobar;
    $htmllogobar = '';
    print '</tr>';
}

// Barcode
if (!empty($conf->barcode->enabled)) {
    print '<tr><td>';
    print $langs->trans('Gencod') . '</td><td>' . $object->barcode;
    print '</td>';
    if ($htmllogobar)
        $htmllogobar .= $form->showbarcode($object);
    print $htmllogobar;
    $htmllogobar = '';
    print '</tr>';
}

// Prof ids
$i = 1;
$j = 0;
while ($i <= 6) {
    $idprof = $langs->transcountry('ProfId' . $i, $object->country_code);
    if ($idprof != '-') {
        //if (($j % 2) == 0) print '<tr>';
        print '<tr>';
        print '<td>' . $idprof . '</td><td>';
        $key = 'idprof' . $i;
        print $object->$key;
        if ($object->$key) {
            if ($object->id_prof_check($i, $object) > 0)
                print ' &nbsp; ' . $object->id_prof_url($i, $object);
            else
                print ' <font class="error">(' . $langs->trans("ErrorWrongValue") . ')</font>';
        }
        print '</td>';
        //if (($j % 2) == 1) print '</tr>';
        print '</tr>';
        $j++;
    }
    $i++;
}
//if ($j % 2 == 1)  print '<td colspan="2"></td></tr>';
// VAT is used
print '<tr><td>';
print $langs->trans('VATIsUsed');
print '</td><td>';
print yn($object->tva_assuj);
print '</td>';
print '</tr>';

// Local Taxes
//TODO: Place into a function to control showing by country or study better option
if ($mysoc->localtax1_assuj == "1" && $mysoc->localtax2_assuj == "1") {
    print '<tr><td>' . $langs->transcountry("LocalTax1IsUsed", $mysoc->country_code) . '</td><td>';
    print yn($object->localtax1_assuj);
    print '</td></tr><tr><td>' . $langs->transcountry("LocalTax2IsUsed", $mysoc->country_code) . '</td><td>';
    print yn($object->localtax2_assuj);
    print '</td></tr>';

    if ($object->localtax1_assuj == "1" && (!isOnlyOneLocalTax(1))) {
        print '<form method="post" action="' . $_SERVER['PHP_SELF'] . '?socid=' . $object->id . '">';
        print '<input type="hidden" name="action" value="set_localtax1">';
        print '<input type="hidden" name="token" value="' . $_SESSION['newtoken'] . '">';
        print '<tr><td>' . $langs->transcountry("TypeLocaltax1", $mysoc->country_code) . ' <a href="' . $_SERVER["PHP_SELF"] . '?action=editRE&amp;socid=' . $object->id . '">' . img_edit($langs->transnoentitiesnoconv('Edit'), 1) . '</td>';
        if ($action == 'editRE') {
            print '<td align="left">';
            $formcompany->select_localtax(1, $object->localtax1_value, "lt1");
            print '<input type="submit" class="button" value="' . $langs->trans("Modify") . '"></td>';
        } else {
            print '<td>' . $object->localtax1_value . '</td>';
        }
        print '</tr></form>';
    }
    if ($object->localtax2_assuj == "1" && (!isOnlyOneLocalTax(2))) {
        print '<form method="post" action="' . $_SERVER['PHP_SELF'] . '?socid=' . $object->id . '">';
        print '<input type="hidden" name="action" value="set_localtax2">';
        print '<input type="hidden" name="token" value="' . $_SESSION['newtoken'] . '">';
        print '<tr><td>' . $langs->transcountry("TypeLocaltax2", $mysoc->country_code) . '<a href="' . $_SERVER["PHP_SELF"] . '?action=editIRPF&amp;socid=' . $object->id . '">' . img_edit($langs->transnoentitiesnoconv('Edit'), 1) . '</td>';
        if ($action == 'editIRPF') {
            print '<td align="left">';
            $formcompany->select_localtax(2, $object->localtax2_value, "lt2");
            print '<input type="submit" class="button" value="' . $langs->trans("Modify") . '"></td>';
        } else {
            print '<td>' . $object->localtax2_value . '</td>';
        }
        print '</tr></form>';
    }
} elseif ($mysoc->localtax1_assuj == "1" && $mysoc->localtax2_assuj != "1") {
    print '<tr><td>' . $langs->transcountry("LocalTax1IsUsed", $mysoc->country_code) . '</td><td>';
    print yn($object->localtax1_assuj);
    print '</td></tr>';
    if ($object->localtax1_assuj == "1" && (!isOnlyOneLocalTax(1))) {
        print '<form method="post" action="' . $_SERVER['PHP_SELF'] . '?socid=' . $object->id . '">';
        print '<input type="hidden" name="action" value="set_localtax1">';
        print '<input type="hidden" name="token" value="' . $_SESSION['newtoken'] . '">';
        print '<tr><td> ' . $langs->transcountry("TypeLocaltax1", $mysoc->country_code) . '<a href="' . $_SERVER["PHP_SELF"] . '?action=editRE&amp;socid=' . $object->id . '">' . img_edit($langs->transnoentitiesnoconv('Edit'), 1) . '</td>';
        if ($action == 'editRE') {
            print '<td align="left">';
            $formcompany->select_localtax(1, $object->localtax1_value, "lt1");
            print '<input type="submit" class="button" value="' . $langs->trans("Modify") . '"></td>';
        } else {
            print '<td>' . $object->localtax1_value . '</td>';
        }
        print '</tr></form>';
    }
} elseif ($mysoc->localtax2_assuj == "1" && $mysoc->localtax1_assuj != "1") {
    print '<tr><td>' . $langs->transcountry("LocalTax2IsUsed", $mysoc->country_code) . '</td><td>';
    print yn($object->localtax2_assuj);
    print '</td></tr>';
    if ($object->localtax2_assuj == "1" && (!isOnlyOneLocalTax(2))) {

        print '<form method="post" action="' . $_SERVER['PHP_SELF'] . '?socid=' . $object->id . '">';
        print '<input type="hidden" name="action" value="set_localtax2">';
        print '<input type="hidden" name="token" value="' . $_SESSION['newtoken'] . '">';
        print '<tr><td> ' . $langs->transcountry("TypeLocaltax2", $mysoc->country_code) . ' <a href="' . $_SERVER["PHP_SELF"] . '?action=editIRPF&amp;socid=' . $object->id . '">' . img_edit($langs->transnoentitiesnoconv('Edit'), 1) . '</td>';
        if ($action == 'editIRPF') {
            print '<td align="left">';
            $formcompany->select_localtax(2, $object->localtax2_value, "lt2");
            print '<input type="submit" class="button" value="' . $langs->trans("Modify") . '"></td>';
        } else {
            print '<td>' . $object->localtax2_value . '</td>';
        }
        print '</tr></form>';
    }
}
/*
  if ($mysoc->country_code=='ES' && $mysoc->localtax2_assuj!="1" && ! empty($conf->fournisseur->enabled) && $object->fournisseur==1)
  {
  print '<tr><td>'.$langs->transcountry("LocalTax2IsUsed",$mysoc->country_code).'</td><td colspan="3">';
  print yn($object->localtax2_assuj);
  print '</td><tr>';
  }
 */

// VAT Code
print '<tr>';
print '<td class="nowrap">' . $langs->trans('VATIntra') . '</td><td>';
if ($object->tva_intra) {
    $s = '';
    $s .= $object->tva_intra;
    $s .= '<input type="hidden" id="tva_intra" name="tva_intra" maxlength="20" value="' . $object->tva_intra . '">';

    if (empty($conf->global->MAIN_DISABLEVATCHECK)) {
        $s .= ' &nbsp; ';

        if ($conf->use_javascript_ajax) {
            print "\n";
            print '<script language="JavaScript" type="text/javascript">';
            print "function CheckVAT(a) {\n";
            print "newpopup('" . DOL_URL_ROOT . "/societe/checkvat/checkVatPopup.php?vatNumber='+a,'" . dol_escape_js($langs->trans("VATIntraCheckableOnEUSite")) . "',500,285);\n";
            print "}\n";
            print '</script>';
            print "\n";
            $s .= '<a href="#" class="hideonsmartphone" onclick="javascript: CheckVAT( $(\'#tva_intra\').val() );">' . $langs->trans("VATIntraCheck") . '</a>';
            $s = $form->textwithpicto($s, $langs->trans("VATIntraCheckDesc", $langs->trans("VATIntraCheck")), 1);
        } else {
            $s .= '<a href="' . $langs->transcountry("VATIntraCheckURL", $object->country_id) . '" class="hideonsmartphone" target="_blank">' . img_picto($langs->trans("VATIntraCheckableOnEUSite"), 'help') . '</a>';
        }
    }
    print $s;
} else {
    print '&nbsp;';
}
print '</td>';
print '</tr>';

// Type + Staff
$arr = $formcompany->typent_array(1);
$object->typent = $arr[$object->typent_code];
print '<tr><td>' . $langs->trans("ThirdPartyType") . '</td><td>' . $object->typent . '</td>';
print '<tr><td>' . $langs->trans("Staff") . '</td><td>' . $object->effectif . '</td></tr>';

print '</table>';

print '</div>';
print '<div class="fichehalfright"><div class="ficheaddleft">';

print '<div class="underbanner clearboth"></div>';
print '<table class="border tableforfield" width="100%">';

// Tags / categories
if (!empty($conf->categorie->enabled) && !empty($user->rights->categorie->lire)) {
    // Customer
    if ($object->prospect || $object->client) {
        print '<tr><td>' . $langs->trans("CustomersCategoriesShort") . '</td>';
        print '<td>';
        print $form->showCategories($object->id, 'customer', 1);
        print "</td></tr>";
    }

    // Supplier
    if ($object->fournisseur) {
        print '<tr><td>' . $langs->trans("SuppliersCategoriesShort") . '</td>';
        print '<td>';
        print $form->showCategories($object->id, 'supplier', 1);
        print "</td></tr>";
    }
}

// Legal
print '<tr><td class="titlefield">' . $langs->trans('JuridicalStatus') . '</td><td>' . $object->forme_juridique . '</td></tr>';

// Capital
print '<tr><td>' . $langs->trans('Capital') . '</td><td>';
if ($object->capital)
    print price($object->capital, '', $langs, 0, -1, -1, $conf->currency);
else
    print '&nbsp;';
print '</td></tr>';

// Default language
if (!empty($conf->global->MAIN_MULTILANGS)) {
    require_once DOL_DOCUMENT_ROOT . '/core/lib/functions2.lib.php';
    print '<tr><td>' . $langs->trans("DefaultLang") . '</td><td>';
    //$s=picto_from_langcode($object->default_lang);
    //print ($s?$s.' ':'');
    $langs->load("languages");
    $labellang = ($object->default_lang ? $langs->trans('Language_' . $object->default_lang) : '');
    print $labellang;
    print '</td></tr>';
}

// Incoterms
if (!empty($conf->incoterm->enabled)) {
    print '<tr><td>';
    print '<table width="100%" class="nobordernopadding"><tr><td>';
    print $langs->trans('IncotermLabel');
    print '<td><td align="right">';
    if ($user->rights->societe->creer)
        print '<a href="' . DOL_URL_ROOT . '/societe/card.php?socid=' . $object->id . '&action=editincoterm">' . img_edit('', 1) . '</a>';
    else
        print '&nbsp;';
    print '</td></tr></table>';
    print '</td>';
    print '<td colspan="3">';
    if ($action != 'editincoterm') {
        print $form->textwithpicto($object->display_incoterms(), $object->libelle_incoterms, 1);
    } else {
        print $form->select_incoterms((!empty($object->fk_incoterms) ? $object->fk_incoterms : ''), (!empty($object->location_incoterms) ? $object->location_incoterms : ''), $_SERVER['PHP_SELF'] . '?socid=' . $object->id);
    }
    print '</td></tr>';
}

// Multicurrency
if (!empty($conf->multicurrency->enabled)) {
    print '<tr>';
    print '<td>' . fieldLabel('Currency', 'multicurrency_code') . '</td>';
    print '<td>';
    print !empty($object->multicurrency_code) ? currency_name($object->multicurrency_code, 1) : '';
    print '</td></tr>';
}

// Other attributes
$parameters = array('socid' => $objectid, 'colspan' => ' colspan="3"', 'colspanvalue' => '3');
include DOL_DOCUMENT_ROOT . '/core/tpl/extrafields_view.tpl.php';

// Parent company
if (empty($conf->global->SOCIETE_DISABLE_PARENTCOMPANY)) {
    // Payment term
    print '<tr><td>';
    print '<table class="nobordernopadding" width="100%"><tr><td>';
    print $langs->trans('ParentCompany');
    print '</td>';
    if ($action != 'editparentcompany')
        print '<td align="right"><a href="' . $_SERVER["PHP_SELF"] . '?action=editparentcompany&amp;socid=' . $object->id . '">' . img_edit($langs->transnoentitiesnoconv('Edit'), 1) . '</a></td>';
    print '</tr></table>';
    print '</td><td colspan="3">';
    if ($action == 'editparentcompany') {
        $form->form_thirdparty($_SERVER['PHP_SELF'] . '?socid=' . $object->id, $object->parent, 'editparentcompany', 's.rowid <> ' . $object->id, 1);
    } else {
        $form->form_thirdparty($_SERVER['PHP_SELF'] . '?socid=' . $object->id, $object->parent, 'none', 's.rowid <> ' . $object->id, 1);
    }
    print '</td>';
    print '</tr>';
}

// Sales representative
include DOL_DOCUMENT_ROOT . '/societe/tpl/linesalesrepresentative.tpl.php';

// Module Adherent
if (!empty($conf->adherent->enabled)) {
    $langs->load("members");
    print '<tr><td>' . $langs->trans("LinkedToDolibarrMember") . '</td>';
    print '<td colspan="3">';
    $adh = new Adherent($db);
    $result = $adh->fetch('', '', $object->id);
    if ($result > 0) {
        $adh->ref = $adh->getFullName($langs);
        print $adh->getNomUrl(1);
    } else {
        print '<span class="opacitymedium">' . $langs->trans("ThirdpartyNotLinkedToMember") . '</span>';
    }
    print '</td>';
    print "</tr>\n";
}

// Webservices url/key
if (!empty($conf->syncsupplierwebservices->enabled)) {
    print '<tr><td>' . $langs->trans("WebServiceURL") . '</td><td>' . dol_print_url($object->webservices_url) . '</td>';
    print '<td class="nowrap">' . $langs->trans('WebServiceKey') . '</td><td>' . $object->webservices_key . '</td></tr>';
}

print '</table>';
print '</div>';

print '</div></div>';
print '<div style="clear:both"></div>';

dol_fiche_end();


/*
 *  Actions
 */
print '<div class="tabsAction">' . "\n";

$parameters = array();
$reshook = $hookmanager->executeHooks('addMoreActionsButtons', $parameters, $object, $action);    // Note that $action and $object may have been modified by hook
if (empty($reshook)) {
    $at_least_one_email_contact = false;
    $TContact = $object->contact_array_objects();
    foreach ($TContact as &$contact) {
        if (!empty($contact->email)) {
            $at_least_one_email_contact = true;
            break;
        }
    }

    if (!empty($object->email) || $at_least_one_email_contact) {
        $langs->load("mails");
        print '<div class="inline-block divButAction"><a class="butAction" href="' . $_SERVER['PHP_SELF'] . '?socid=' . $object->id . '&amp;action=presend&amp;mode=init">' . $langs->trans('SendMail') . '</a></div>';
    } else {
        $langs->load("mails");
        print '<div class="inline-block divButAction"><a class="butActionRefused" href="#" title="' . dol_escape_htmltag($langs->trans("NoEMail")) . '">' . $langs->trans('SendMail') . '</a></div>';
    }

    if ($user->rights->societe->creer) {
        print '<div class="inline-block divButAction"><a class="butAction" href="' . $_SERVER["PHP_SELF"] . '?socid=' . $object->id . '&amp;action=edit">' . $langs->trans("Modify") . '</a></div>' . "\n";
    }

    if ($user->rights->societe->supprimer) {
        print '<div class="inline-block divButAction"><a class="butActionDelete" href="card.php?action=merge&socid=' . $object->id . '" title="' . dol_escape_htmltag($langs->trans("MergeThirdparties")) . '">' . $langs->trans('Merge') . '</a></div>';
    }

    if ($user->rights->societe->supprimer) {
        if ($conf->use_javascript_ajax && empty($conf->dol_use_jmobile)) { // We can't use preloaded confirm form with jmobile
            print '<div class="inline-block divButAction"><span id="action-delete" class="butActionDelete">' . $langs->trans('Delete') . '</span></div>' . "\n";
        } else {
            print '<div class="inline-block divButAction"><a class="butActionDelete" href="' . $_SERVER["PHP_SELF"] . '?socid=' . $object->id . '&amp;action=delete">' . $langs->trans('Delete') . '</a></div>' . "\n";
        }
    }
}

print '</div>' . "\n";

//Select mail models is same action as presend
if (GETPOST('modelselected')) {
    $action = 'presend';
}
if ($action == 'presend') {
    /*
     * Affiche formulaire mail
     */

    // By default if $action=='presend'
    $titreform = 'SendMail';
    $topicmail = '';
    $action = 'send';
    $modelmail = 'thirdparty';

    print '<div id="formmailbeforetitle" name="formmailbeforetitle"></div>';
    print '<div class="clearboth"></div>';
    print '<br>';
    print load_fiche_titre($langs->trans($titreform));

    dol_fiche_head();

    // Define output language
    $outputlangs = $langs;
    $newlang = '';
    if ($conf->global->MAIN_MULTILANGS && empty($newlang) && !empty($_REQUEST['lang_id']))
        $newlang = $_REQUEST['lang_id'];
    if ($conf->global->MAIN_MULTILANGS && empty($newlang))
        $newlang = $object->default_lang;

    // Cree l'objet formulaire mail
    include_once DOL_DOCUMENT_ROOT . '/core/class/html.formmail.class.php';
    $formmail = new FormMail($db);
    $formmail->param['langsmodels'] = (empty($newlang) ? $langs->defaultlang : $newlang);
    $formmail->fromtype = (GETPOST('fromtype') ? GETPOST('fromtype') : (!empty($conf->global->MAIN_MAIL_DEFAULT_FROMTYPE) ? $conf->global->MAIN_MAIL_DEFAULT_FROMTYPE : 'user'));

    if ($formmail->fromtype === 'user') {
        $formmail->fromid = $user->id;
    }
    $formmail->trackid = 'thi' . $object->id;
    if (!empty($conf->global->MAIN_EMAIL_ADD_TRACK_ID) && ($conf->global->MAIN_EMAIL_ADD_TRACK_ID & 2)) { // If bit 2 is set
        include DOL_DOCUMENT_ROOT . '/core/lib/functions2.lib.php';
        $formmail->frommail = dolAddEmailTrackId($formmail->frommail, 'thi' . $object->id);
    }
    $formmail->withfrom = 1;
    $formmail->withtopic = 1;
    $liste = array();
    foreach ($object->thirdparty_and_contact_email_array(1) as $key => $value)
        $liste[$key] = $value;
    $formmail->withto = GETPOST('sendto') ? GETPOST('sendto') : $liste;
    $formmail->withtofree = 1;
    $formmail->withtocc = $liste;
    $formmail->withtoccc = $conf->global->MAIN_EMAIL_USECCC;
    $formmail->withfile = 2;
    $formmail->withbody = 1;
    $formmail->withdeliveryreceipt = 1;
    $formmail->withcancel = 1;
    // Tableau des substitutions
    //$formmail->setSubstitFromObject($object);
    $formmail->substit['__THIRDPARTY_NAME__'] = $object->name;
    $formmail->substit['__SIGNATURE__'] = $user->signature;
    $formmail->substit['__PERSONALIZED__'] = '';
    $formmail->substit['__CONTACTCIVNAME__'] = '';

    //Find the good contact adress
    /*
      $custcontact='';
      $contactarr=array();
      $contactarr=$object->liste_contact(-1,'external');

      if (is_array($contactarr) && count($contactarr)>0)
      {
      foreach($contactarr as $contact)
      {
      if ($contact['libelle']==$langs->trans('TypeContact_facture_external_BILLING')) {

      require_once DOL_DOCUMENT_ROOT . '/contact/class/contact.class.php';

      $contactstatic=new Contact($db);
      $contactstatic->fetch($contact['id']);
      $custcontact=$contactstatic->getFullName($langs,1);
      }
      }

      if (!empty($custcontact)) {
      $formmail->substit['__CONTACTCIVNAME__']=$custcontact;
      }
      } */


    // Tableau des parametres complementaires du post
    $formmail->param['action'] = $action;
    $formmail->param['models'] = $modelmail;
    $formmail->param['models_id'] = GETPOST('modelmailselected', 'int');
    $formmail->param['socid'] = $object->id;
    $formmail->param['returnurl'] = $_SERVER["PHP_SELF"] . '?socid=' . $object->id;

    // Init list of files
    if (GETPOST("mode") == 'init') {
        $formmail->clear_attached_files();
        $formmail->add_attached_files($file, basename($file), dol_mimetype($file));
    }
    print $formmail->get_form();

    dol_fiche_end();
} else {

    if (empty($conf->global->SOCIETE_DISABLE_BUILDDOC)) {
        print '<div class="fichecenter"><div class="fichehalfleft">';
        print '<a name="builddoc"></a>'; // ancre

        /*
         * Documents generes
         */
        $filedir = $conf->societe->multidir_output[$object->entity] . '/' . $object->id;
        $urlsource = $_SERVER["PHP_SELF"] . "?socid=" . $object->id;
        $genallowed = $user->rights->societe->creer;
        $delallowed = $user->rights->societe->supprimer;

        $var = true;

        print $formfile->showdocuments('company', $object->id, $filedir, $urlsource, $genallowed, $delallowed, $object->modelpdf, 0, 0, 0, 28, 0, 'entity=' . $object->entity, 0, '', $object->default_lang);

        print '</div><div class="fichehalfright"><div class="ficheaddleft">';


        print '</div></div></div>';

        print '<br>';
    }

    print '<div class="fichecenter"><br></div>';

    // Subsidiaries list
    if (empty($conf->global->SOCIETE_DISABLE_SUBSIDIARIES)) {
        $result = show_subsidiaries($conf, $langs, $db, $object);
    }

    // Contacts list
    if (empty($conf->global->SOCIETE_DISABLE_CONTACTS)) {
        $result = show_contacts($conf, $langs, $db, $object, $_SERVER["PHP_SELF"] . '?socid=' . $object->id);
    }

    // Addresses list
    if (!empty($conf->global->SOCIETE_ADDRESSES_MANAGEMENT)) {
        $result = show_addresses($conf, $langs, $db, $object, $_SERVER["PHP_SELF"] . '?socid=' . $object->id);
    }
}
    
