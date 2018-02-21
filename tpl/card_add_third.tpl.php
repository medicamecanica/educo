<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
require_once DOL_DOCUMENT_ROOT . '/core/lib/functions2.lib.php';




$soc->canvas = $canvas;


$soc->particulier = 1;

$soc->name = dolGetFirstLastname(GETPOST('socfirstname', 'alpha'), GETPOST('soclastname', 'alpha'));
$soc->civility_id = GETPOST('soc_civility_id'); // Note: civility id is a code, not an int
// Add non official properties
//var_dump($soc->name);
$soc->name_bis = $soc->name;
$soc->firstname = GETPOST('socfirstname', 'alpha');
$soc->firstname_ = GETPOST('socfirstname', 'alpha');
$soc->lastname = GETPOST('soclastname', 'alpha');

$soc->name_alias = GETPOST('socname_alias');
$soc->address = GETPOST('socaddress');
$soc->zip = GETPOST('soczipcode', 'alpha');
$soc->town = GETPOST('soctown', 'alpha');
$soc->country_id = GETPOST('soccountry_id', 'int');
$soc->state_id = GETPOST('socstate_id', 'int');
$soc->skype = GETPOST('socskype', 'alpha');
$soc->phone = GETPOST('socphone', 'alpha');
$soc->fax = GETPOST('socfax', 'alpha');
$soc->email = GETPOST('socemail', 'custom', 0, FILTER_SANITIZE_EMAIL);
$soc->url = GETPOST('socurl', 'custom', 0, FILTER_SANITIZE_URL);
$soc->idprof1 = GETPOST('socidprof1', 'alpha');
$soc->idprof2 = GETPOST('socidprof2', 'alpha');
$soc->idprof3 = GETPOST('socidprof3', 'alpha');
$soc->idprof4 = GETPOST('socidprof4', 'alpha');
$soc->idprof5 = GETPOST('socidprof5', 'alpha');
$soc->idprof6 = GETPOST('socidprof6', 'alpha');
$soc->prefix_comm = GETPOST('socprefix_comm', 'alpha');
$soc->code_client = GETPOST('soccode_client', 'alpha');
$soc->code_fournisseur = GETPOST('soccode_fournisseur', 'alpha');
$soc->capital = GETPOST('soccapital', 'alpha');
$soc->barcode = GETPOST('socbarcode', 'alpha');

$soc->tva_intra = GETPOST('soctva_intra', 'alpha');
$soc->tva_assuj = GETPOST('socassujtva_value', 'alpha');
$soc->status = 1;

// Local Taxes
$soc->localtax1_assuj = GETPOST('soclocaltax1assuj_value', 'alpha');
$soc->localtax2_assuj = GETPOST('soclocaltax2assuj_value', 'alpha');

$soc->localtax1_value = GETPOST('soclt1', 'alpha');
$soc->localtax2_value = GETPOST('soclt2', 'alpha');

$soc->forme_juridique_code = GETPOST('socforme_juridique_code', 'int');
$soc->effectif_id = GETPOST('soceffectif_id', 'int');
$soc->typent_id = GETPOST('soctypent_id');

$soc->client = 1;
$soc->fournisseur = 0;

$soc->commercial_id = GETPOST('soccommercial_id', 'int');
$soc->default_lang = GETPOST('socdefault_lang');

// Webservices url/key
$soc->webservices_url = GETPOST('socwebservices_url', 'custom', 0, FILTER_SANITIZE_URL);
$soc->webservices_key = GETPOST('socwebservices_key', 'san_alpha');
if (!$soc->name) {
    setEventMessages($langs->trans("ErrorFieldRequired", $langs->transnoentitiesnoconv("ThirdPartyName")), null, 'errors');
    $error++;
    $action = 'create';
}
if ($soc->client < 0) {
    setEventMessages($langs->trans("ErrorFieldRequired", $langs->transnoentitiesnoconv("ProspectCustomer")), null, 'errors');
    $error++;
    $action = 'create';
}
if ($soc->fournisseur < 0) {
    setEventMessages($langs->trans("ErrorFieldRequired", $langs->transnoentitiesnoconv("Supplier")), null, 'errors');
    $error++;
    $action = 'create';
}

// Incoterms
if (!empty($conf->incoterm->enabled)) {
    $soc->fk_incoterms = GETPOST('incoterm_id', 'int');
    $soc->location_incoterms = GETPOST('location_incoterms', 'alpha');
}

// Multicurrency
if (!empty($conf->multicurrency->enabled)) {
    $soc->multicurrency_code = GETPOST('multicurrency_code', 'alpha');
}

// Fill array 'array_options' with data from add form
$ret = $extrafields->setOptionalsFromPost($extralabels, $soc);
if ($ret < 0) {
    $error++;
    $action = ($action == 'add' ? 'create' : 'edit');
}

if (GETPOST('deletephoto'))
    $soc->logo = '';
else if (!empty($_FILES['photo']['name']))
    $soc->logo = dol_sanitizeFileName($_FILES['photo']['name']);

// Check parameters
if (!GETPOST("cancel")) {
    if (!empty($soc->email) && !isValidEMail($soc->email)) {
        $langs->load("errors");
        $error++;
        $errors[] = $langs->trans("ErrorBadEMail", $soc->email);
        $action = ($action == 'add' ? 'create' : 'edit');
    }
    if (!empty($soc->url) && !isValidUrl($soc->url)) {
        $langs->load("errors");
        $error++;
        $errors[] = $langs->trans("ErrorBadUrl", $soc->url);
        $action = ($action == 'add' ? 'create' : 'edit');
    }
    if ($soc->fournisseur && !$conf->fournisseur->enabled) {
        $langs->load("errors");
        $error++;
        $errors[] = $langs->trans("ErrorSupplierModuleNotEnabled");
        $action = ($action == 'add' ? 'create' : 'edit');
    }
    if (!empty($soc->webservices_url)) {
        //Check if has transport, without any the soap client will give error
        if (strpos($soc->webservices_url, "http") === false) {
            $soc->webservices_url = "http://" . $soc->webservices_url;
        }
        if (!isValidUrl($soc->webservices_url)) {
            $langs->load("errors");
            $error++;
            $errors[] = $langs->trans("ErrorBadUrl", $soc->webservices_url);
            $action = ($action == 'add' ? 'create' : 'edit');
        }
    }

    // We set country_id, country_code and country for the selected country
    $soc->country_id = GETPOST('country_id') != '' ? GETPOST('country_id') : $mysoc->country_id;
    if ($soc->country_id) {
        $tmparray = getCountry($soc->country_id, 'all');
        $soc->country_code = $tmparray['code'];
        $soc->country = $tmparray['label'];
    }

    // Check for duplicate or mandatory prof id
    // Only for companies
    if (!($soc->particulier || $private)) {
        for ($i = 1; $i <= 6; $i++) {
            $slabel = "idprof" . $i;
            $_POST[$slabel] = trim($_POST[$slabel]);
            $vallabel = $_POST[$slabel];
            if ($vallabel && $soc->id_prof_verifiable($i)) {
                if ($soc->id_prof_exists($i, $vallabel, $soc->id)) {
                    $langs->load("errors");
                    $error++;
                    $errors[] = $langs->transcountry('ProfId' . $i, $soc->country_code) . " " . $langs->trans("ErrorProdIdAlreadyExist", $vallabel);
                    $action = (($action == 'add' || $action == 'create') ? 'create' : 'edit');
                }
            }

            // Check for mandatory prof id (but only if country is than than ours)
            if ($mysoc->country_id > 0 && $soc->country_id == $mysoc->country_id) {
                $idprof_mandatory = 'SOCIETE_IDPROF' . ($i) . '_MANDATORY';
                if (!$vallabel && !empty($conf->global->$idprof_mandatory)) {
                    $langs->load("errors");
                    $error++;
                    $errors[] = $langs->trans("ErrorProdIdIsMandatory", $langs->transcountry('ProfId' . $i, $soc->country_code));
                    $action = (($action == 'add' || $action == 'create') ? 'create' : 'edit');
                }
            }
        }
    }
}
