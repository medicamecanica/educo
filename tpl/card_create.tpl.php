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
print '<tr><td class="fieldrequired">' . $langs->trans("Fielddoc_type") . '</td><td>' . $form->selectarray('doc_type', array('CC' => 'CC', 'TI' => 'TI', 'RC' => 'RC', $object->doc_type)) . '</td></tr>';
print '<tr><td class="fieldrequired">' . $langs->trans("Fielddocument") . '</td><td><input class="flat" type="text" name="document" value="' . $object->document . '"></td></tr>';
//print '<tr><td class="fieldrequired">' . $langs->trans("Fieldentity") . '</td><td><input class="flat" type="text" name="entity" value="' . $object->entity . '"></td></tr>';
//print '<tr><td class="fieldrequired">' . $langs->trans("Fieldfk_contact") . '</td><td><input class="flat" type="text" name="fk_contact" value="' . $object->fk_contact . '"></td></tr>';

//print '<tr><td class="fieldrequired">'.$langs->trans("Fieldstatus").'</td><td><input class="flat" type="text" name="status" value="'.$object->status.'"></td></tr>';
//print '<tr><td class="fieldrequired">'.$langs->trans("Fieldimport_key").'</td><td><input class="flat" type="text" name="import_key" value="'.$object->import_key.'"></td></tr>';
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
