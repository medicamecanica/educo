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
if ($action == 'add'&&!GETPOST('cancel')) {
//    if (GETPOST('cancel')) {
//        $urltogo = $backtopage ? $backtopage : dol_buildpath('/educo/list.php', 1);
//        header("Location: " . $urltogo);
//        exit;
//    }

    $error = 0;

    /* object_prop_getpost_prop */

    $object->ref = $academicid . '-' . GETPOST('grado_code', 'alpha') . '-' . GETPOST('sufix', 'alpha');
    $object->sufix = GETPOST('sufix', 'alpha');
    $object->label = $grade->label . ' ' . GETPOST('sufix', 'alpha');
    $object->fk_academicyear = $academicid;
    $object->grado_code = GETPOST('grado_code', 'alpha');
    $object->statut = 1;
    $object->date_create = dol_now();
    //$object->import_key=GETPOST('import_key','alpha');



    if (empty($object->grado_code)) {
        $error++;
        setEventMessages($langs->trans("ErrorFieldRequired", $langs->transnoentitiesnoconv("Fieldgrado_code")), null, 'errors');
    }
    if (empty($object->sufix)) {
        $error++;
        setEventMessages($langs->trans("ErrorFieldRequired", $langs->transnoentitiesnoconv("Fieldsufix")), null, 'errors');
    }

    if (!$error) {
        $result = $object->create($user);
        // var_dump($object->db->lastquery);
        if ($result > 0) {
            // Creation OK
            //$urltogo=$backtopage?$backtopage:dol_buildpath('/educo/list.php',1);
            //header("Location: ".$urltogo);
            //exit;
            setEventMessages($langs->trans('GroupAdded'),null);
        } {
            // Creation KO
            if (!empty($object->errors))
                setEventMessages(null, $object->errors, 'errors');
            else
                setEventMessages($object->error, null, 'errors');
            $action = 'create';
        }
    }
    else {
        $action = 'create';
    }
}
// Action to delete
if ($action == 'confirm_delete') {
    $result = $object->delete($user);
    if ($result > 0) {
        // Delete OK
        setEventMessages("RecordDeleted", null, 'mesgs');
        //header("Location: ".dol_buildpath('/educo/list.php',1));
        //exit;
    } else {
        if (!empty($object->errors))
            setEventMessages(null, $object->errors, 'errors');
        else
            setEventMessages($object->error, null, 'errors');
    }
}