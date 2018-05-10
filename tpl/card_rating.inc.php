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
require_once(DOL_DOCUMENT_ROOT.'/educo/class/html.formeduco.class.php');
$formEduco = new FormEduco($db);
// Part to create
if ($action == '')
{
	print load_fiche_titre($langs->trans("NewMyModule"));

	print '<form method="POST" action="'.$_SERVER["PHP_SELF"].'?mainmenu=home&leftmenu=setup"  enctype="multipart/form-data">';
	print '<input type="hidden" name="action" value="add">';
	print '<input type="hidden" name="backtopage" value="'.$backtopage.'">';
        print '<input type="hidden" name="num" value="'.$num.'">';

	dol_fiche_head();

	print '<table class="border centpercent">'."\n";
	// print '<tr><td class="fieldrequired">'.$langs->trans("Label").'</td><td><input class="flat" type="text" size="36" name="label" value="'.$label.'"></td></tr>';
	// 
print '<tr><td class="fieldrequired">'.$langs->trans("Fieldfk_rating").'</td><td>';
$formEduco->select_dictionary('fk_rating', 'educo_typerating','code','code');
        print  '</td></tr>';
//print '<tr><td class="fieldrequired">'.$langs->trans("Fieldref").'</td><td><input class="flat" type="text" name="ref" value="'.GETPOST('ref').'"></td></tr>';
print '<tr><td class="fieldrequired">'.$langs->trans("Fieldlabel").'</td><td><input class="flat" type="text" name="label" value="'.GETPOST('label').'"></td></tr>';
print '<tr><td class="fieldrequired">'.$langs->trans("Fieldlesser_than").'</td><td><input class="flat" type="number" step="0.01" width="5" name="lesser_than" value="'.GETPOST('lesser_than').'"></td></tr>';
print '<tr><td class="fieldrequired">'.$langs->trans("Fieldphoto").'</td><td>'
        . '<input type="file" name="photo" accept="image/*"></td></tr>';

	print '</table>'."\n";

	dol_fiche_end();

	print '<div class="center"><input type="submit" class="button" name="add" value="'.$langs->trans("Create").'"> &nbsp; <input type="submit" class="button" name="cancel" value="'.$langs->trans("Cancel").'"></div>';

	print '</form>';
}



// Part to edit record
if (($id || $ref) && $action == 'edit')
{
	print load_fiche_titre($langs->trans("MyModule"));
    
	print '<form method="POST" action="'.$_SERVER["PHP_SELF"].'">';
	print '<input type="hidden" name="action" value="update">';
	print '<input type="hidden" name="backtopage" value="'.$backtopage.'">';
	print '<input type="hidden" name="id" value="'.$object->id.'">';
	
	dol_fiche_head();

	print '<table class="border centpercent">'."\n";
	// print '<tr><td class="fieldrequired">'.$langs->trans("Label").'</td><td><input class="flat" type="text" size="36" name="label" value="'.$label.'"></td></tr>';
	// 
print '<tr><td class="fieldrequired">'.$langs->trans("Fieldref").'</td><td><input class="flat" type="text" name="ref" value="'.$object->ref.'"></td></tr>';
print '<tr><td class="fieldrequired">'.$langs->trans("Fieldlabel").'</td><td><input class="flat" type="text" name="label" value="'.$object->label.'"></td></tr>';
print '<tr><td class="fieldrequired">'.$langs->trans("Fieldlesser_than").'</td><td><input class="flat" type="text" name="lesser_than" value="'.$object->lesser_than.'"></td></tr>';
print '<tr><td class="fieldrequired">'.$langs->trans("Fieldphoto").'</td><td><input class="flat" type="text" name="photo" value="'.$object->photo.'"></td></tr>';
print '<tr><td class="fieldrequired">'.$langs->trans("Fieldfk_rating").'</td><td><input class="flat" type="text" name="fk_rating" value="'.$object->fk_rating.'"></td></tr>';

	print '</table>';
	
	dol_fiche_end();

	print '<div class="center"><input type="submit" class="button" name="save" value="'.$langs->trans("Save").'">';
	print ' &nbsp; <input type="submit" class="button" name="cancel" value="'.$langs->trans("Cancel").'">';
	print '</div>';

	print '</form>';
}


