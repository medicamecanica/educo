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

// Action to add record
 if ($action == 'add'&&!GETPOST('cancel')) {
//    if (GETPOST('cancel')) {
//        $urltogo = $backtopage ? $backtopage : dol_buildpath('/educo/list.php', 1);
//        header("Location: " . $urltogo);
//        exit;
//    }

    $error = 0;

    /* object_prop_getpost_prop */
    $u=new User($db);
    $u->fetch(GETPOST('fk_user', 'int'));
    $object->ref = $academic->ref . '-' . $u->login . '-' . $pensum->asignature_code;
    //$object->description = GETPOST('description', 'alpha');
    // $object->status = GETPOST('status', 'int');
    $object->asignature_code =$pensum->asignature_code;
    $object->fk_user = GETPOST('fk_user', 'int');
    //$object->fk_academicyear = GETPOST('fk_academicyear', 'int');
    //$object->entity = GETPOST('entity', 'int');
    $object->fk_academicyear = $academicid;
    $object->status = 1;
    $object->entity=$conf->entity;
    var_dump($pensum->horas);
    $object->hours = $pensum->horas;

    if (empty($object->ref)) {
        $error++;
        setEventMessages($langs->trans("ErrorFieldRequired", $langs->transnoentitiesnoconv("Ref")), null, 'errors');
    }

    if (!$error) {
        $result = $object->create($user);
        if ($result > 0) {
            // Creation OK
          //  $urltogo = $backtopage ? $backtopage : dol_buildpath('/educo/list.php', 1);
            //header("Location: " . $urltogo);
          //  exit;
             setEventMessages($user_login.$langs->trans('Teach'). GETPOST('asignature_code'),null);
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
