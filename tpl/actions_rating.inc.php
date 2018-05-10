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
if ($action == 'add') {
    if (GETPOST('cancel')) {
        $urltogo = $backtopage ? $backtopage : dol_buildpath('/educo/list.php', 1);
        header("Location: " . $urltogo);
        exit;
    }

    $error = 0;

    /* object_prop_getpost_prop */

    $object->ref =  GETPOST('fk_rating', 'alpha').'-'.GETPOST('num', 'int');
    $object->label = GETPOST('label', 'alpha');
    $object->lesser_than = GETPOST('lesser_than', 'alpha');
    $object->photo = isset($_FILES['photo']) ? $_FILES['photo']['name'] : '';
    $object->fk_rating = GETPOST('fk_rating', 'alpha');



    if (empty($object->ref)) {
        $error++;
        setEventMessages($langs->trans("ErrorFieldRequired", $langs->transnoentitiesnoconv("Ref")), null, 'errors');
    }
    if (empty($object->label)) {
        $error++;
        setEventMessages($langs->trans("ErrorFieldRequired", $langs->transnoentitiesnoconv("Fieldlabel")), null, 'errors');
    }
    if (empty($object->fk_rating)) {
        $error++;
        setEventMessages($langs->trans("ErrorFieldRequired", $langs->transnoentitiesnoconv("Fieldrating")), null, 'errors');
    }

    if (!$error) {
        $result = $object->create($user);
        if ($result > 0) {
            // Logo/Photo save
            $dir = $conf->educo->dir_output . '/' . get_exdir(0, 0, 0, 1, $object, 'educo') . '/photos';
            $file_OK = is_uploaded_file($_FILES['photo']['tmp_name']);
            //var_dump($file_OK);
            if ($file_OK) {
                if (GETPOST('deletephoto')) {
                    require_once DOL_DOCUMENT_ROOT . '/core/lib/files.lib.php';
                    $fileimg = $conf->educo->dir_output . '/' . get_exdir(0, 0, 0, 1, $object, 'educo') . '/photos/' . $object->photo;
                    $dirthumbs = $conf->educo->dir_output . '/' . get_exdir(0, 0, 0, 1, $object, 'educo') . '/photos/thumbs';
                    dol_delete_file($fileimg);
                    dol_delete_dir_recursive($dirthumbs);
                }

                if (image_format_supported($_FILES['photo']['name']) > 0) {
                    dol_mkdir($dir);

                    if (@is_dir($dir)) {
                        $newfile = $dir . '/' . dol_sanitizeFileName($_FILES['photo']['name']);
                        if (!dol_move_uploaded_file($_FILES['photo']['tmp_name'], $newfile, 1, 0, $_FILES['photo']['error']) > 0) {
                            setEventMessages($langs->trans("ErrorFailedToSaveFile"), null, 'errors');
                        } else {
                            // Create thumbs
                            $object->addThumbs($newfile);
                        }
                    }
                } else {
                    setEventMessages("ErrorBadImageFormat", null, 'errors');
                }
            } else {
                switch ($_FILES['photo']['error']) {
                    case 1: //uploaded file exceeds the upload_max_filesize directive in php.ini
                    case 2: //uploaded file exceeds the MAX_FILE_SIZE directive that was specified in the html form
                        $errors[] = "ErrorFileSizeTooLarge";
                        break;
                    case 3: //uploaded file was only partially uploaded
                        $errors[] = "ErrorFilePartiallyUploaded";
                        break;
                }
            }
            $db->commit();

            $action = '';
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
        $action = '';
    }
}

// Action to update record
if ($action == 'update') {
    $error = 0;


    $object->ref = GETPOST('ref', 'alpha');
    $object->label = GETPOST('label', 'alpha');
    $object->lesser_than = GETPOST('lesser_than', 'alpha');
    $object->photo = GETPOST('photo', 'alpha');
    $object->fk_rating = GETPOST('fk_rating', 'alpha');



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
        header("Location: " . dol_buildpath('/educo/list.php', 1));
        exit;
    } else {
        if (!empty($object->errors))
            setEventMessages(null, $object->errors, 'errors');
        else
            setEventMessages($object->error, null, 'errors');
    }
}