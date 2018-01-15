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
        if (GETPOST('cancel')) {
           // $urltogo = $backtopage ? $backtopage : dol_buildpath('/educo/list.php', 1);
            //header("Location: " . $urltogo);
            //exit;
             $action = '';
        }

        $error = 0;

        /* object_prop_getpost_prop */

        $object->ref = $academic->ref . '-' . GETPOST('grado_code') . '-' . GETPOST('asignature_code');
        $object->grado_code = GETPOST('grado_code');
        $object->asignature_code = GETPOST('asignature_code');
        $object->fk_academicyear = $academicid;
        $object->horas = GETPOST('horas', 'int');
        $object->statut = 1;
        $object->date_create = dol_now();
        $object->tms = dol_now();
        //$object->import_key = GETPOST('import_key', 'alpha');



        if (empty($object->ref)) {
            $error++;
            setEventMessages($langs->trans("ErrorFieldRequired", $langs->transnoentitiesnoconv("Ref")), null, 'errors');
        }
        if (empty($object->grado_code)) {
            $error++;
            setEventMessages($langs->trans("ErrorFieldRequired", $langs->transnoentitiesnoconv("Fieldgrado_code")), null, 'errors');
        }
        if (empty($object->asignature_code)) {
            $error++;
            setEventMessages($langs->trans("ErrorFieldRequired", $langs->transnoentitiesnoconv("Fieldasignature_code")), null, 'errors');
        }
        if (empty($object->horas)) {
            $error++;
            setEventMessages($langs->trans("ErrorFieldRequired", $langs->transnoentitiesnoconv("Fieldhoras")), null, 'errors');
        }

        if (!$error) {
            $result = $object->create($user);
            if ($result > 0) {
                // Creation OK
                // $urltogo = $backtopage ? $backtopage : dol_buildpath('/educo/list.php', 1);
                //header("Location: " . $urltogo);
                //exit;
                setEventMessages(null, $langs->trans('PensumAdded'));
            } {
                // Creation KO
                if (!empty($object->errors))
                    setEventMessages(null, $object->errors, 'errors');
                else
                    setEventMessages($object->error, null, 'errors');
                $action = '';
            }
        }
        else {
            $action = 'create';
        }
    }

    // Action to update record
    if ($action == 'update') {
        $error = 0;


        $object->ref = GETPOST('ref', 'alpha');
        $object->grado_code = GETPOST('grado_code', 'int');
        $object->asignature_code = GETPOST('asignature_code', 'int');
        $object->fk_academicyear = GETPOST('fk_academicyear', 'int');
        $object->horas = GETPOST('horas', 'int');
        $object->statut = GETPOST('statut', 'int');
        $object->import_key = GETPOST('import_key', 'alpha');



        if (empty($object->ref)) {
            $error++;
            setEventMessages($langs->transnoentitiesnoconv("ErrorFieldRequired", $langs->transnoentitiesnoconv("Ref")), null, 'errors');
        }

        if (!$error) {
            $result = $object->update($user);
            if ($result > 0) {
                $action = 'view';
            } else {
                // Creation KO
                if (!empty($object->errors))
                    setEventMessages(null, $object->errors, 'errors');
                else
                    setEventMessages($object->error, null, 'errors');
                $action = 'edit';
            }
        }
        else {
            $action = 'edit';
        }
    }

    // Action to delete
    if ($action == 'confirm_delete') {
        $result = $object->delete($user);
        if ($result > 0) {
            // Delete OK
            setEventMessages("RecordDeleted", null, 'mesgs');
            //    header("Location: " . dol_buildpath('/educo/list.php', 1));
            exit;
        } else {
            if (!empty($object->errors))
                setEventMessages(null, $object->errors, 'errors');
            else
                setEventMessages($object->error, null, 'errors');
        }
    }
// Action to update record
if ($action == 'update_status') {
    $error = 0;


    //$object->ref = GETPOST('ref', 'alpha');
    //$object->fk_grado = GETPOST('fk_grado', 'int');
    //$object->fk_asignatura = GETPOST('fk_asignatura', 'int');
    //$object->fk_academicyear = GETPOST('fk_academicyear', 'int');
    //$object->horas = GETPOST('horas', 'int');
    $object->statut = GETPOST('statut', 'int');
    $object->tms = dol_now();
   
   // $object->import_key = GETPOST('import_key', 'alpha');
//
//
//
//    if (empty($object->ref)) {
//        $error++;
//        setEventMessages($langs->transnoentitiesnoconv("ErrorFieldRequired", $langs->transnoentitiesnoconv("Ref")), null, 'errors');
//    }

    if (!$error) {
        $result = $object->update($user);
       //  var_dump($object->db->lastquery);
        if ($result > 0) {
            $action = 'view';
        } else {
            // Creation KO
            if (!empty($object->errors))
                setEventMessages(null, $object->errors, 'errors');
            else
                setEventMessages($object->error, null, 'errors');
            $action = 'edit';
        }
    }
    else {
        $action = 'edit';
    }
}
 // Action to delete
    if ($action == 'confirm_delete') {
        $result = $object->delete($user);
       // var_dump($object);
        if ($result > 0) {
            // Delete OK
            setEventMessages("RecordDeleted", null, 'mesgs');
            //    header("Location: " . dol_buildpath('/educo/list.php', 1));
            exit;
        } else {
            if (!empty($object->errors))
                setEventMessages(null, $object->errors, 'errors');
            else
                setEventMessages($object->error, null, 'errors');
        }
    }