<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */


$contact->canvas = $canvas;

$contact->state_id = GETPOST("state_id");

// We set country_id, country_code and label for the selected country
$contact->country_id = $_POST["country_id"] ? GETPOST("country_id") : (empty($objsoc->country_id) ? $mysoc->country_id : $objsoc->country_id);
if ($contact->country_id) {
    $tmparray = getCountry($contact->country_id, 'all');
    $contact->country_code = $tmparray['code'];
    $contact->country = $tmparray['label'];
}

$title = $addcontact =  $langs->trans("AddStudentCOntact");
$linkback = '';
print load_fiche_titre($title, $linkback, 'title_companies.png');

// Affiche les erreurs
dol_htmloutput_errors(is_numeric($error) ? '' : $error, $errors);

print '<table class="border" width="100%">';

//// Name
//print '<tr><td class="titlefieldcreate fieldrequired"><label for="lastname">' . $langs->trans("Lastname") . ' / ' . $langs->trans("Label") . '</label></td>';
//print '<td><input name="lastname" id="lastname" type="text" class="maxwidth100onsmartphone" maxlength="80" value="' . dol_escape_htmltag(GETPOST("lastname") ? GETPOST("lastname") : $contact->lastname) . '" autofocus="autofocus"></td>';
//print '<td><label for="firstname">' . $langs->trans("Firstname") . '</label></td>';
//print '<td><input name="firstname" id="firstname"type="text" class="maxwidth100onsmartphone" maxlength="80" value="' . dol_escape_htmltag(GETPOST("firstname") ? GETPOST("firstname") : $contact->firstname) . '"></td></tr>';
//
//// Company
//if (empty($conf->global->SOCIETE_DISABLE_CONTACTS)) {
//    if ($socid > 0) {
//        print '<tr><td><label for="socid">' . $langs->trans("ThirdParty") . '</label></td>';
//        print '<td colspan="3" class="maxwidthonsmartphone">';
//        print $objsoc->getNomUrl(1);
//        print '</td>';
//        print '<input type="hidden" name="socid" id="socid" value="' . $objsoc->id . '">';
//        print '</td></tr>';
//    } else {
//        print '<tr><td><label for="socid">' . $langs->trans("ThirdParty") . '</label></td><td colspan="3" class="maxwidthonsmartphone">';
//        print $form->select_company($socid, 'socid', '', 'SelectThirdParty');
//        print '</td></tr>';
//    }
//}
// Civility
print '<tr><td><label for="civility_id">' . $langs->trans("UserTitle") . '</label></td><td colspan="3">';
print $formcompany->select_civility(GETPOST("civility_id", 'alpha') ? GETPOST("civility_id", 'alpha') : $contact->civility_id);
print '</td></tr>';
//
//print '<tr><td><label for="title">' . $langs->trans("PostOrFunction") . '</label></td>';
//print '<td colspan="3"><input name="poste" id="title" type="text" class="minwidth100" maxlength="80" value="' . dol_escape_htmltag(GETPOST("poste", 'alpha') ? GETPOST("poste", 'alpha') : $contact->poste) . '"></td>';

$colspan = 3;
if ($conf->use_javascript_ajax && $socid > 0)
    $colspan = 2;

// Address
if (($objsoc->typent_code == 'TE_PRIVATE' || !empty($conf->global->CONTACT_USE_COMPANY_ADDRESS)) && dol_strlen(trim($contact->address)) == 0)
    $contact->address = $objsoc->address; // Predefined with third party
print '<tr><td><label for="address">' . $langs->trans("Address") . '</label></td>';
print '<td colspan="' . $colspan . '"><textarea class="flat quatrevingtpercent" name="address" id="address" rows="' . ROWS_2 . '">' . (GETPOST("address", 'alpha') ? GETPOST("address", 'alpha') : $contact->address) . '</textarea></td>';

if ($conf->use_javascript_ajax && $socid > 0) {
    $rowspan = 3;
    if (empty($conf->global->SOCIETE_DISABLE_STATE))
        $rowspan++;

    print '<td valign="middle" align="center" rowspan="' . $rowspan . '">';
    print '<a href="#" id="copyaddressfromsoc">' . $langs->trans('CopyAddressFromSoc') . '</a>';
    print '</td>';
}
print '</tr>';

// Zip / Town
if (($objsoc->typent_code == 'TE_PRIVATE' || !empty($conf->global->CONTACT_USE_COMPANY_ADDRESS)) && dol_strlen(trim($contact->zip)) == 0)
    $contact->zip = $objsoc->zip;   // Predefined with third party
if (($objsoc->typent_code == 'TE_PRIVATE' || !empty($conf->global->CONTACT_USE_COMPANY_ADDRESS)) && dol_strlen(trim($contact->town)) == 0)
    $contact->town = $objsoc->town; // Predefined with third party
print '<tr><td><label for="zipcode">' . $langs->trans("Zip") . '</label> / <label for="town">' . $langs->trans("Town") . '</label></td><td colspan="' . $colspan . '" class="maxwidthonsmartphone">';
print $formcompany->select_ziptown((GETPOST("zipcode") ? GETPOST("zipcode") : $contact->zip), 'zipcode', array('town', 'selectcountry_id', 'state_id'), 6) . '&nbsp;';
print $formcompany->select_ziptown((GETPOST("town") ? GETPOST("town") : $contact->town), 'town', array('zipcode', 'selectcountry_id', 'state_id'));
print '</td></tr>';

// Country
$contact->country_id=70;
print '<tr><td><label for="selectcountry_id">' . $langs->trans("Country") . '</label></td><td colspan="' . $colspan . '" class="maxwidthonsmartphone">';
print $form->select_country((GETPOST("country_id", 'alpha') ? GETPOST("country_id", 'alpha') : $contact->country_id), 'country_id');
if ($user->admin)
    print info_admin($langs->trans("YouCanChangeValuesForThisListFromDictionarySetup"), 1);
print '</td></tr>';

// State
if (empty($conf->global->SOCIETE_DISABLE_STATE)) {
    print '<tr><td><label for="state_id">' . $langs->trans('State') . '</label></td><td colspan="' . $colspan . '" class="maxwidthonsmartphone">';
    if ($contact->country_id) {
        print $formcompany->select_state(GETPOST("state_id", 'alpha') ? GETPOST("state_id", 'alpha') : $contact->state_id, $contact->country_code, 'state_id');
    } else {
        print $countrynotdefined;
    }
    print '</td></tr>';
}

// Phone / Fax
if (($objsoc->typent_code == 'TE_PRIVATE' || !empty($conf->global->CONTACT_USE_COMPANY_ADDRESS)) && dol_strlen(trim($contact->phone_pro)) == 0)
    $contact->phone_pro = $objsoc->phone; // Predefined with third party
print '<tr>';/*<td><label for="phone_pro">' . $langs->trans("PhonePro") . '</label></td>';
print '<td><input name="phone_pro" id="phone_pro" type="text" class="maxwidth100onsmartphone" maxlength="80" value="' . dol_escape_htmltag(GETPOST("phone_pro") ? GETPOST("phone_pro") : $contact->phone_pro) . '"></td>';
*/print '<td><label for="phone_perso">' . $langs->trans("PhonePerso") . '</label></td>';
print '<td><input name="phone_perso" id="phone_perso" type="text" class="maxwidth100onsmartphone" maxlength="80" value="' . dol_escape_htmltag(GETPOST("phone_perso") ? GETPOST("phone_perso") : $contact->phone_perso) . '"></td></tr>';

if (($objsoc->typent_code == 'TE_PRIVATE' || !empty($conf->global->CONTACT_USE_COMPANY_ADDRESS)) && dol_strlen(trim($contact->fax)) == 0)
    $contact->fax = $objsoc->fax; // Predefined with third party
print '<tr><td><label for="phone_mobile">' . $langs->trans("PhoneMobile") . '</label></td>';
print '<td><input name="phone_mobile" id="phone_mobile" type="text" class="maxwidth100onsmartphone" maxlength="80" value="' . dol_escape_htmltag(GETPOST("phone_mobile") ? GETPOST("phone_mobile") : $contact->phone_mobile) . '"></td>';
//print '<td><label for="fax">' . $langs->trans("Fax") . '</label></td>';
//print '<td><input name="fax" id="fax" type="text" class="maxwidth100onsmartphone" maxlength="80" value="' . dol_escape_htmltag(GETPOST("fax", 'alpha') ? GETPOST("fax", 'alpha') : $contact->fax) . '"></td></tr>';

// EMail
if (($objsoc->typent_code == 'TE_PRIVATE' || !empty($conf->global->CONTACT_USE_COMPANY_ADDRESS)) && dol_strlen(trim($contact->email)) == 0)
    $contact->email = $objsoc->email; // Predefined with third party
print '<tr><td><label for="email">' . $langs->trans("Email") . '</label></td>';
print '<td><input name="email" id="email" type="text" class="maxwidth100onsmartphone" value="' . (GETPOST("email", 'alpha') ? GETPOST("email", 'alpha') : $contact->email) . '"></td>';
if (!empty($conf->mailing->enabled)) {
    print '<td><label for="no_email">' . $langs->trans("No_Email") . '</label></td>';
    print '<td>' . $form->selectyesno('no_email', (GETPOST("no_email", 'alpha') ? GETPOST("no_email", 'alpha') : $contact->no_email), 1) . '</td>';
} else {
    print '<td colspan="2">&nbsp;</td>';
}
print '</tr>';

// Instant message and no email
//print '<tr><td><label for="jabberid">' . $langs->trans("IM") . '</label></td>';
//print '<td colspan="3"><input name="jabberid" id="jabberid" type="text" class="minwidth100" maxlength="80" value="' . (GETPOST("jabberid", 'alpha') ? GETPOST("jabberid", 'alpha') : $contact->jabberid) . '"></td></tr>';

// Skype
if (!empty($conf->skype->enabled)) {
    print '<tr><td><label for="skype">' . $langs->trans("Skype") . '</label></td>';
    print '<td colspan="3"><input name="skype" id="skype" type="text" class="minwidth100" maxlength="80" value="' . (GETPOST("skype", 'alpha') ? GETPOST("skype", 'alpha') : $contact->skype) . '"></td></tr>';
}

// Visibility
print '<tr><td><label for="priv">' . $langs->trans("ContactVisibility") . '</label></td><td colspan="3">';
$selectarray = array('0' => $langs->trans("ContactPublic"), '1' => $langs->trans("ContactPrivate"));
print $form->selectarray('priv', $selectarray, (GETPOST("priv", 'alpha') ? GETPOST("priv", 'alpha') : $contact->priv), 0);
print '</td></tr>';

// Categories
if (!empty($conf->categorie->enabled) && !empty($user->rights->categorie->lire)) {
    print '<tr><td>' . fieldLabel('Categories', 'contcats') . '</td><td colspan="3">';
    $cate_arbo = $form->select_all_categories(Categorie::TYPE_CONTACT, null, 'parent', null, null, 1);
    print $form->multiselectarray('contcats', $cate_arbo, GETPOST('contcats', 'array'), null, null, null, null, '90%');
    print "</td></tr>";
}

// Other attributes
$parameters = array('colspan' => ' colspan="3"', 'cols' => 3);
$reshook = $hookmanager->executeHooks('formObjectOptions', $parameters, $contact, $action);    // Note that $action and $contact may have been modified by hook
print $hookmanager->resPrint;
if (empty($reshook) && !empty($extrafields->attribute_label)) {
    print $contact->showOptionals($extrafields, 'edit');
}

print "</table><br>";

print '<hr style="margin-bottom: 20px">';

// Add personnal information
print load_fiche_titre('<div class="comboperso">' . $langs->trans("PersonalInformations") . '</div>', '', '');

print '<table class="border" width="100%">';

// Date To Birth
print '<tr><td width="20%"><label for="birthday">' . $langs->trans("DateToBirth") . '</label></td><td width="30%">';
$form = new Form($db);
if ($contact->birthday) {
    print $form->select_date($contact->birthday, 'birthday', 0, 0, 0, "perso", 1, 0, 1);
} else {
    print $form->select_date('', 'birthday', 0, 0, 1, "perso", 1, 0, 1);
}
print '</td>';

print '<td colspan="2"><label for="birthday_alert">' . $langs->trans("Alert") . '</label>: ';
if ($contact->birthday_alert) {
    print '<input type="checkbox" name="birthday_alert" id="birthday_alert" checked></td>';
} else {
    print '<input type="checkbox" name="birthday_alert" id="birthday_alert"></td>';
}
print '</tr>';
print '</table>';
