<?php

$formcompany = new FormCompany($db);

/*
 * To change this license header, choose License Headers in Project Properties. * To change this template file, choose Tools | Templates
 * and open the template in the editor. */

if ($conf->use_javascript_ajax) {
    print "\n" . '<script type="text/javascript" language="javascript">' . "\n";
    print 'jQuery(document).ready(function () {
                jQuery("#selectcountry_id").change(function() {
                        document.formsoc.action.value="create";
                        document.formsoc.submit();
                });

                $("#copyaddressfromsoc").click(function() {
                        $(\'textarea[name="address"]\').val("' . dol_escape_js($objsoc->address) . '");
                        $(\'input[name="zipcode"]\').val("' . dol_escape_js($objsoc->zip) . '");
                        $(\'input[name="town"]\').val("' . dol_escape_js($objsoc->town) . '");
                        console.log("Set state_id to ' . dol_escape_js($objsoc->state_id) . '");
                        $(\'select[name="state_id"]\').val("' . dol_escape_js($objsoc->state_id) . '").trigger("change");
                        /* set country at end because it will trigger page refresh */
                        console.log("Set country id to ' . dol_escape_js($objsoc->country_id) . '");
                        $(\'select[name="country_id"]\').val("' . dol_escape_js($objsoc->country_id) . '").trigger("change");   /* trigger required to update select2 components */
                });
                 $("input[name=its_cc]").click(function() {
                    if($(this).val()==1){
                        $("#tr_cc").show("slow");
                        $("#tr_othertype").hide("slow");
                        $("#tr_othernum").hide("slow");
                    }else{
                        $("#tr_cc").hide("slow");
                        $("#tr_othertype").show("slow");
                        $("#tr_othernum").show("slow");
                    }
                 });
})' . "\n";
    print '</script>' . "\n";
}
print '<table class="border centpercent">' . "\n";
// print '<tr><td class="fieldrequired">'.$langs->trans("Label").'</td><td><input class="flat" type="text" size="36" name="label" value="'.$label.'"></td></tr>';
// 
//print '<tr><td class="fieldrequired">'.$langs->trans("Fieldref").'</td><td><input class="flat" type="text" name="ref" value="'.$object->ref.'"></td></tr>';
//print '<tr><td class="fieldrequired">'.$langs->trans("Fieldname").'</td><td><input class="flat" type="text" name="name" value="'.$object->name.'"></td></tr>';
print '<tr><td class="fieldrequired">' . $langs->trans("Fieldfirstname") . '</td><td><input class="flat" type="text" name="firstname" value="' . $object->firstname . '"></td></tr>';
print '<tr><td class="fieldrequired">' . $langs->trans("Fieldlastname") . '</td><td><input class="flat" type="text" name="lastname" value="' . $object->lastname . '"></td></tr>';
//select ceduala o other
print '<tr><td class="fieldrequired">' . $langs->trans("") . '</td><td>';

print '<input '.(GETPOST('its_cc')?'checked':'').' class="flat" type="radio" name="its_cc" value="1"><labe for=its_cc"">'. $langs->trans("Fieldcc") .'</label>';
print '&nbsp;&nbsp;&nbsp;&nbsp;<input  '.(!GETPOST('its_cc')?'checked':'').' class="flat" type="radio" name="its_cc" value="0">'. $langs->trans("Other") .'</label>';
print '</td></tr>';
$hidestyle='style="display:none;"';
print '<tr id="tr_cc" '.(!$object->cc?$hidestyle:'').'><td class="fieldrequired">' . $langs->trans("Fieldcc") . '</td><td><input class="flat" type="text" name="cc" value="' . $object->cc . '"></td></tr>';
print '<tr id="tr_othertype" '.($object->cc?$hidestyle:'').'><td class="fieldrequired">' . $langs->trans("Fielddoc_type") . '</td><td>' .$formEduco->select_doctype('doc_type', $object->doc_type,0)  . '</td></tr>';
print '<tr id="tr_othernum" '.($object->cc?$hidestyle:'').'><td  class="fieldrequired">' . $langs->trans("Fielddocument") . '</td><td><input class="flat" type="text" name="document" value="' . $object->document . '"></td></tr>';

    print '<tr><td class="fieldrequired">' . $langs->trans("Fieldsex") . '</td><td>' . $formEduco->select_sex('sex', $object->sex) . '</td></tr>';
    print '<tr><td class="fieldrequired">' . $langs->trans("FieldmaxGrade") . '</td><td><input class="flat" type="number" name="grade_max" value="' . $object->grade_max . '"></td></tr>';
    print '<tr><td class="fieldrequired">' . $langs->trans("Fieldneighborhood") . '</td><td><input  class="flat" type="text" name="neighborhood" value="' . $object->neighborhood . '"></td></tr>';
    print '<tr><td class="fieldrequired">' . $langs->trans("Fieldblood_type") . '</td><td><input size="4" class="flat" type="text" name="blod_type" value="' . $object->blod_type . '"></td></tr>';
    print '<tr><td class="fieldrequired">' . $langs->trans("Fieldstratum") . '</td><td><input size="4" class="flat" type="number" name="stratum" value="' . $object->stratum . '"></td></tr>';
    print '<tr><td class="fieldrequired">' . $langs->trans("Fieldsisben") . '</td><td><input size="4" class="flat" type="number" name="sisben" value="' . $object->sisben . '"></td></tr>';
    print '<tr><td class="fieldrequired">' . $langs->trans("Fieldeps") . '</td><td><input class="flat" type="text" name="eps" value="' . $object->eps . '"></td></tr>';
    print '<tr><td class="fieldrequired">' . $langs->trans("Fieldregime") . '</td><td>' . $formEduco->select_regime('regime', $object->regime) . '</td></tr>';
    print '<tr><td class="fieldrequired">' . $langs->trans("Fieldethnicity") . '</td><td>' . $formEduco->select_ethnicity('ethnicity', $object->ethnicity) . '</td></tr>';

// Photo
    print '<tr><td>' . $langs->trans("Photo") . '</td>';
    print '<td class="hideonsmartphone" valign="middle">';
    //$object->photo = 'photo.png';
   // print $form->showphoto('educo', $object) . "\n";
    //if ($user->rights->educo->write) {
        if ($object->photo)
            print "<br>\n";
        print '<table class="nobordernopadding">';
        if ($object->photo)
            print '<tr><td><input type="checkbox" class="flat photodelete" name="deletephoto" id="photodelete"> ' . $langs->trans("Delete") . '<br><br></td></tr>';
        print '<tr><td>' . $langs->trans("PhotoFile") . '</td></tr>';
        print '<tr><td><input type="file" class="flat" name="photo" id="photoinput"></td></tr>';
        print '</table>';
    //}
    print '</td></tr>';
//$form=new Form($db);
/*
 * Fiche en mode creation
 */
print '</table>';
