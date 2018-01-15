<?php

/* Copyright (C) 2017 SuperAdmin
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

/**
 * \file    lib/educo.lib.php
 * \ingroup educo
 * \brief   Example module library.
 *
 * Put detailed description here.
 */

/**
 * Prepare admin pages header
 *
 * @return array
 */
function educoAdminPrepareHead() {
    global $langs, $conf;

    $langs->load("educo@educo");

    $h = 0;
    $head = array();

    $head[$h][0] = dol_buildpath("/educo/admin/setup.php", 1);
    $head[$h][1] = $langs->trans("Settings");
    $head[$h][2] = 'settings';
    $h++;
    $head[$h][0] = dol_buildpath("/educo/admin/about.php", 1);
    $head[$h][1] = $langs->trans("About");
    $head[$h][2] = 'about';
    $h++;

    // Show more tabs from modules
    // Entries must be declared in modules descriptor with line
    //$tabs = array(
    //	'entity:+tabname:Title:@educo:/educo/mypage.php?id=__ID__'
    //); // to add new tab
    //$tabs = array(
    //	'entity:-tabname:Title:@educo:/educo/mypage.php?id=__ID__'
    //); // to remove a tab
    complete_head_from_modules($conf, $langs, $object, $head, $h, 'educo');

    return $head;
}

function card_create($object, $contact, $soc) {
    global $langs, $conf, $db, $hookmanager, $form;
    include DOL_DOCUMENT_ROOT . '/educo/tpl/card_create.tpl.php';
    include DOL_DOCUMENT_ROOT . '/educo/tpl/card_create_contact.tpl.php';
    include DOL_DOCUMENT_ROOT . '/educo/tpl/card_create_third.tpl.php';
}

function academicyear_header($object) {
    global $langs;
    $head = array();
    $h = 0;


    $head[$h][0] = dol_buildpath('/educo/academic/card.php?id=' . $object->id, 1);
    $head[$h][1] = $langs->trans("Card");
    $head[$h][2] = 'card';

    $h++;
    $head[$h][0] = dol_buildpath('/educo/academic/pensum.php?academicid=' . $object->id, 1);
    $head[$h][1] = $langs->trans("Pensum");
    $head[$h][2] = 'pensum';

    $h++;
    $head[$h][0] = dol_buildpath('/educo/academic/groups.php?academicid=' . $object->id, 1);
    $head[$h][1] = $langs->trans("Groups");
    $head[$h][2] = 'groups';
    $h++;
    $head[$h][0] = dol_buildpath('/educo/academic/teachers_subjects.php?academicid=' . $object->id, 1);
    $head[$h][1] = $langs->trans("TeachersSubjects");
    $head[$h][2] = 'teacherssubjects';

    $h++;
    $head[$h][0] = dol_buildpath('/educo/academic/scheduler.php?academicid=' . $object->id, 1);
    $head[$h][1] = $langs->trans("Scheduler");
    $head[$h][2] = 'scheduler';

    return $head;
}

function fetchTeacherByGroup($academiciid, $groupid, $sortorder = '', $sortfield = '', $limit = 0, $offset = 0) {
    dol_syslog(__METHOD__, LOG_DEBUG);

    $sql = 'SELECT';
    $sql .= ' t.rowid,';

    $sql .= " t.ref,";
    $sql .= " t.label,";
    $sql .= " t.status,";
    $sql .= " t.datec,";
    $sql .= " t.tms,";
    $sql .= " t.asignature_code,";
    $sql .= " t.fk_user,";
    $sql .= " t.fk_academicyear,";
    $sql .= " t.entity,";
    $sql .= " t.hours";


    $sql .= ' FROM ' . MAIN_DB_PREFIX . $table_element . ' as t';


    $sql .= ' WHERE 1 = 1';
    if (!empty($conf->multicompany->enabled)) {
        $sql .= " AND entity IN (" . getEntity("educoteachersubject", 1) . ")";
    }

    if (!empty($sortfield)) {
        $sql .= $db->order($sortfield, $sortorder);
    }
    if (!empty($limit)) {
        $sql .= ' ' . $db->plimit($limit + 1, $offset);
    }

    $lines = array();

    $resql = $db->query($sql);
    if ($resql) {
        $num = $db->num_rows($resql);

        while ($obj = $db->fetch_object($resql)) {
            $line = new EducoteachersubjectLine();

            $line->id = $obj->rowid;

            $line->ref = $obj->ref;
            $line->label = $obj->label;
            $line->status = $obj->status;
            $line->datec = $db->jdate($obj->datec);
            $line->tms = $db->jdate($obj->tms);
            $line->asignature_code = $obj->asignature_code;
            $line->fk_user = $obj->fk_user;
            $line->fk_academicyear = $obj->fk_academicyear;
            $line->entity = $obj->entity;
            $line->hours = $obj->hours;



            $lines[$line->id] = $line;
        }
        $db->free($resql);

        return $num;
    } else {
        $errors[] = 'Error ' . $db->lasterror();
        dol_syslog(__METHOD__ . ' ' . implode(',', $errors), LOG_ERR);

        return - 1;
    }
}

function fetchSubjectsPesum($academicid, $grado_code, $groupid, $sortorder = '', $sortfield = '', $limit = 0, $offset = 0) {
    global $db, $conf;
    dol_syslog(__METHOD__, LOG_DEBUG);

    $sql = 'SELECT';
    $sql .= ' t.rowid,';

    $sql .= " t.ref,";
    //$sql .= " t.fk_academicyear,";
    $sql .= " t.horas,";
    //$sql .= " t.date_create,";
    //$sql .= " t.tms,";
    $sql .= " t.statut,";
    $sql .= ' (SELECT sum(duration) FROM ' . MAIN_DB_PREFIX . 'educo_horario as h '
            . ' inner join ' . MAIN_DB_PREFIX . 'educo_teacher_subject as ts on h.fk_teach_sub=ts.rowid '
            . ' where h.fk_teach_sub=ts.rowid and h.fk_group=' . $groupid . ' and ts.fk_academicyear=' . $academicid . ' ) as count,';
    $sql .= " t.asignature_code,";
    $sql .= " t.grado_code,";
    $sql .= " s.label as subject_label";
    $sql .= ' FROM ' . MAIN_DB_PREFIX . 'educo_pensum as t';
    $sql .= ' INNER JOIN ' . MAIN_DB_PREFIX . 'edcuo_c_asignatura AS s ON asignature_code=s.code';
    // Manage filter

    $sql .= ' WHERE 1 = 1';
    if (!empty($conf->multicompany->enabled)) {
        $sql .= " AND entity IN (" . getEntity("educopensum", 1) . ")";
    }
    $sql .= " AND t.fk_academicyear=" . $academicid;
    $sql .= " AND t.grado_code=" . $grado_code;
    if (!empty($sortfield)) {
        $sql .= $db->order($sortfield, $sortorder);
    }
    if (!empty($limit)) {
        $sql .= ' ' . $db->plimit($limit + 1, $offset);
    }

    $lines = array();
    // var_dump($sql);
    $resql = $db->query($sql);
    if ($resql) {
        $num = $db->num_rows($resql);
        while ($obj = $db->fetch_object($resql)) {
            $lines[] = $obj;
        }
        $db->free($resql);

        return $lines;
    } else {
        // print 'Error ' . $db->lasterror();

        return - 1;
    }
}

/**
 * Load object in memory from the database
 *
 * @param string $sortorder Sort Order
 * @param string $sortfield Sort field
 * @param int    $limit     offset limit
 * @param int    $offset    offset limit
 * @param array  $filter    filter array
 * @param string $filtermode filter mode (AND or OR)
 *
 * @return int <0 if KO, >0 if OK
 */
function fetchHorario($academicid, $groupid, $teacherid, $sortorder = '', $sortfield = '', $limit = 0, $offset = 0) {
    global $db, $conf;
    $table_element = 'educo_horario';
    dol_syslog(__METHOD__, LOG_DEBUG);

    $sql = 'SELECT';
    $sql .= ' t.rowid,';
    $sql .= " t.ref,";
    $sql .= " t.label,";
    $sql .= " t.datep,";
    $sql .= " t.datef,";
    $sql .= " t.duration,";
    $sql .= " t.note_private,";
    $sql .= " t.grado_code,";
    $sql .= " t.subject_code,";
    $sql .= " t.datec,";
    $sql .= " t.tms,";
    $sql .= " t.fk_group,";
    $sql .= " t.fk_teach_sub,";
    $sql .= " concat(u.firstname,' ',u.lastname)as teacher_name,";
    $sql .= " s.label as subject_label,";
    $sql .= " t.entity";


    $sql .= ' FROM ' . MAIN_DB_PREFIX . $table_element . ' as t';
    $sql .= ' INNER JOIN ' . MAIN_DB_PREFIX . 'edcuo_c_asignatura AS s ON subject_code=s.code';
    $sql .= ' INNER JOIN ' . MAIN_DB_PREFIX . 'educo_group as g';
    $sql .= ' INNER JOIN ' . MAIN_DB_PREFIX . 'educo_teacher_subject AS ts ON t.fk_teach_sub=ts.rowid';
    $sql .= ' INNER JOIN ' . MAIN_DB_PREFIX . 'user AS u ON ts.fk_user=u.rowid';

    $sql .= ' WHERE 1 = 1';
    $sql .= " AND ts.fk_academicyear=" . $academicid;
    $sql .= " AND g.fk_academicyear=" . $academicid;
    $sql .= " AND t.fk_group=" . $groupid;
    if (!empty($conf->multicompany->enabled)) {
        $sql .= " AND entity IN (" . getEntity("educohorario", 1) . ")";
    }

    if (!empty($sortfield)) {
        $sql .= $db->order($sortfield, $sortorder);
    }
    if (!empty($limit)) {
        $sql .= ' ' . $db->plimit($limit + 1, $offset);
    }

    $lines = array();

    $resql = $db->query($sql);
    if ($resql) {
        $num = $db->num_rows($resql);
        while ($obj = $db->fetch_object($resql)) {
            $lines[$obj->rowid] = $obj;
        }
        $db->free($resql);

        return $lines;
    } else {
        $errors[] = 'Error ' . $db->lasterror();
        dol_syslog(__METHOD__ . ' ' . implode(',', $errors), LOG_ERR);
        var_dump($errors);
        return - 1;
    }
}

function fetchSubjectsTeacher($academicid, $teacherid, $sortorder = '', $sortfield = '', $limit = 0, $offset = 0) {
    global $db, $conf;
    dol_syslog(__METHOD__, LOG_DEBUG);

    $sql = 'SELECT';
    $sql .= ' t.rowid,';

    $sql .= " t.ref,";
    $sql .= " t.datep,";
    $sql .= " t.datef,";
    //$sql .= " t.fk_academicyear,";
    $sql .= " t.duration,";
    //$sql .= " t.date_create,";
    //$sql .= " t.tms,";
    // $sql .= " t.status,";
    //  $sql .= " t.import_key,";
    $sql .= " t.subject_code,";
    $sql .= " t.grado_code,";
    $sql .= " concat(u.firstname,' ',u.lastname)as teacher_name,";
    $sql .= " s.label as subject_label";
    $sql .= ' FROM ' . MAIN_DB_PREFIX . 'educo_horario as t';
    $sql .= ' INNER JOIN ' . MAIN_DB_PREFIX . 'edcuo_c_asignatura AS s ON subject_code=s.code';
    $sql .= ' INNER JOIN ' . MAIN_DB_PREFIX . 'educo_teacher_subject AS ts ON t.fk_teach_sub=ts.rowid';
    $sql .= ' INNER JOIN ' . MAIN_DB_PREFIX . 'user AS u ON ts.fk_user=u.rowid';

    // Manage filter

    $sql .= ' WHERE 1 = 1';
    if (!empty($conf->multicompany->enabled)) {
        $sql .= " AND entity IN (" . getEntity("educopensum", 1) . ")";
    }
    $sql .= " AND ts.fk_academicyear=" . $academicid;
    //$sql .= " AND t.grado_code=" . $grado_code;
    $sql .= " AND ts.fk_user=" . $teacherid;
    if (!empty($sortfield)) {
        $sql .= $db->order($sortfield, $sortorder);
    }
    if (!empty($limit)) {
        $sql .= ' ' . $db->plimit($limit + 1, $offset);
    }

    $lines = array();
    //var_dump($sql);
    $resql = $db->query($sql);
    if ($resql) {
        $num = $db->num_rows($resql);
        while ($obj = $db->fetch_object($resql)) {
            $lines[] = $obj;
        }
        $db->free($resql);

        return $lines;
    } else {
        print 'Error ' . $db->lasterror();

        return - 1;
    }
}

/**
 * Load object in memory from the database
 *
 * @param string $sortorder Sort Order
 * @param string $sortfield Sort field
 * @param int    $limit     offset limit
 * @param int    $offset    offset limit
 * @param array  $filter    filter array
 * @param string $filtermode filter mode (AND or OR)
 *
 * @return int <0 if KO, >0 if OK
 */
function fetchTeacherSubjects($academicid, $teacherid, $sortorder = '', $sortfield = '', $limit = 0, $offset = 0) {
    global $db, $conf;
    dol_syslog(__METHOD__, LOG_DEBUG);
    $table_element = 'educo_teacher_subject';
    $sql = 'SELECT';
    $sql .= ' t.rowid,';
    $sql .= " t.ref,";
    $sql .= " t.label,";
    $sql .= " t.status,";
    $sql .= " t.datec,";
    $sql .= " t.tms,";
    $sql .= " t.asignature_code,";
    $sql .= " t.fk_user,";
    $sql .= " t.fk_academicyear,";
    $sql .= " t.entity,";
    $sql .= " t.hours,";
    $sql .= " s.label as subject_label";
    $sql .= ' FROM ' . MAIN_DB_PREFIX . $table_element . ' as t';
    $sql .= ' INNER JOIN ' . MAIN_DB_PREFIX . 'edcuo_c_asignatura AS s ON asignature_code=s.code';
    // Manage filter

    $sql .= ' WHERE 1 = 1';
    $sql .= ' AND t.fk_academicyear = ' . $academicid;
    $sql .= ' AND t.fk_user = ' . $teacherid;
    if (!empty($conf->multicompany->enabled)) {
        $sql .= " AND entity IN (" . getEntity("educoteachersubject", 1) . ")";
    }

    if (!empty($sortfield)) {
        $sql .= $db->order($sortfield, $sortorder);
    }
    if (!empty($limit)) {
        $sql .= ' ' . $db->plimit($limit + 1, $offset);
    }
    // var_dump($sql);
    $lines = array();

    $resql = $db->query($sql);
    if ($resql) {
        $num = $db->num_rows($resql);

        while ($obj = $db->fetch_object($resql)) {


            $lines[] = $obj;
        }
        $db->free($resql);

        return $lines;
    } else {
       // print 'Error ' . $db->lasterror();
        // dol_syslog(__METHOD__ . ' ' . implode(',', $errors), LOG_ERR);

        return - 1;
    }
}

function fetchSubjectsGrade($academicid, $grado_code,$groupid, $sortorder = '', $sortfield = '', $limit = 0, $offset = 0) {
    global $conf, $db;
    dol_syslog(__METHOD__, LOG_DEBUG);

    $sql = 'SELECT';
    $sql .= ' t.rowid,';

    $sql .= " t.ref,";
    $sql .= " t.fk_academicyear,";
    $sql .= " t.horas,";
    $sql .= " t.date_create,";
    $sql .= " t.tms,";
    $sql .= " t.statut,";
    $sql .= " t.import_key,";
    $sql .= " t.asignature_code,";
    $sql .= " t.grado_code,";
    $sql .= " s.label as subject_label,";

    $sql .= '(SELECT';
    $sql .= " sum(h.duration) ";
    $sql .= ' FROM ' . MAIN_DB_PREFIX . 'educo_horario as h';
    $sql .= ' WHERE h.fk_group=' . $groupid.' AND h.subject_code=t.asignature_code'
            . ') as total_duration';

    $sql .= ' FROM ' . MAIN_DB_PREFIX . 'educo_pensum as t';
    //$sql .= ' INNER JOIN ' . MAIN_DB_PREFIX . 'educo_acad_year as a ON a.rowid=t.fk_academic_year';
    $sql .= ' INNER JOIN ' . MAIN_DB_PREFIX . 'edcuo_c_asignatura as s ON s.code=t.asignature_code';
    $sql .= ' WHERE t.fk_academicyear=' . $academicid;
    $sql .= ' AND grado_code=' . $grado_code;

    if (!empty($sortfield)) {
        $sql .= $db->order($sortfield, $sortorder);
    }
    if (!empty($limit)) {
        $sql .= ' ' . $db->plimit($limit + 1, $offset);
    }
    //var_dump($sql);
    $lines = array();

    $resql = $db->query($sql);
    //print $db->lasterror;
    if ($resql) {
        $num = $db->num_rows($resql);

        while ($obj = $db->fetch_object($resql)) {
            $line = new EducopensumLine();

            $line->id = $obj->rowid;

            $line->ref = $obj->ref;
            $line->fk_academicyear = $obj->fk_academicyear;
            $line->horas = $obj->horas;
            $line->date_create = $db->jdate($obj->date_create);
            $line->tms = $db->jdate($obj->tms);
            $line->statut = $obj->statut;
            $line->import_key = $obj->import_key;
            $line->asignature_code = $obj->asignature_code;
            $line->grado_code = $obj->grado_code;
            $line->subject_label = $obj->subject_label;
            $line->total_duration = $obj->total_duration?$obj->total_duration:0;



            $lines[$line->id] = $line;
        }
        $db->free($resql);

        return $lines;
    } else {
        $errors[] = 'Error ' . $db->lasterror();
        dol_syslog(__METHOD__ . ' ' . implode(',', $errors), LOG_ERR);

        return - 1;
    }
}
