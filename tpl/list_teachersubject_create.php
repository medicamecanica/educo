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


dol_fiche_head($head, 'teacherssubjects', $langs->trans("AcademicYearCard"), 0, 'generic');

//baner
print '<div class="arearef heightref valignmiddle" width="100%">';
print '<table class="tagtable liste" >' . "\n";
print '<tr><td width="30%">' . $langs->trans("AcademicYear") . '</td><td width="70%" colspan="3">';
$academic->next_prev_filter = "te.fournisseur = 1";
print $form->showrefnav($academic, 'academicid', '', ($user->societe_id ? 0 : 1), 'rowid', 'ref', '', '');
print '</td></tr></table>';
// Buttons
if ($action != 'create') {
    print '<div class="tabsAction">' . "\n";
    $parameters = array();
    $reshook = $hookmanager->executeHooks('addMoreActionsButtons', $parameters, $object, $action);    // Note that $action and $object may have been modified by hook
    if ($reshook < 0)
        setEventMessages($hookmanager->error, $hookmanager->errors, 'errors');

    if (empty($reshook)) {
        if ($user->rights->educo->write) {
            print '<div class="inline-block divButAction"><a class="butAction" href="' . $_SERVER["PHP_SELF"] . '?academicid=' . $academicid . '&amp;action=create">' . $langs->trans("Nuevo") . '</a></div>' . "\n";
        }

//    if ($user->rights->educo->delete) {
//        print '<div class="inline-block divButAction"><a class="butActionDelete" href="' . $_SERVER["PHP_SELF"] . '?id=' . $object->id . '&amp;action=delete">' . $langs->trans('Delete') . '</a></div>' . "\n";
//    }
    }
    print '</div>' . "\n";
}
// Part to create
if ($action == 'create') {
    print load_fiche_titre($langs->trans("NewTeacherSubject"));
    print '<form id="formcreate" method="POST" action="' . $_SERVER["PHP_SELF"] . '">';
    print '<input type="hidden" name="action" value="add">';
    print '<input type="hidden" name="backtopage" value="' . $backtopage . '">';
    print '<input type="hidden" name="academicid" value="' . $academicid . '">';
    print '<input type="hidden" id="user_login" name="user_login" value="' . $user_login . '">';
    dol_fiche_head();
    print '<table class="border centpercent">' . "\n";
    print '<tr><td  class="fieldrequired">' . $langs->trans("Fieldlevel") . '</td><td>';
    // var_dump($level);
    print $formeduco->select_level('level', $level)
            . '</td></tr>';
    print '<tr><td class="fieldrequired">' . $langs->trans("Fieldasignature_code") . '</td><td>';
    print $formeduco->select_pensum('fk_pensum', $academicid, $fk_pensum,null,$level);
    print '</td></tr>';
    print '<tr><td class="fieldrequired">' . $langs->trans("Fieldfk_user") . '</td><td>';
    print $form->select_dolusers($object->fk_user, 'fk_user');
    print '</td></tr>';
//print '<tr><td class="fieldrequired">'.$langs->trans("Fieldfk_academicyear").'</td><td><input class="flat" type="text" name="fk_academicyear" value="'.GETPOST('fk_academicyear').'"></td></tr>';
//print '<tr><td class="fieldrequired">'.$langs->trans("Fieldentity").'</td><td><input class="flat" type="text" name="entity" value="'.GETPOST('entity').'"></td></tr>';
    print '</table>' . "\n";
    dol_fiche_end();
    print '<div class="center"><input type="submit" class="button" name="add" value="' . $langs->trans("Create") . '"> &nbsp; <input type="submit" class="button" name="cancel" value="' . $langs->trans("Cancel") . '"></div>';
    print '</form>';
}
print '</div>';
print dol_fiche_end();
