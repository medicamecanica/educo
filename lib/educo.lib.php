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
    //$this->tabs = array(
    //	'entity:+tabname:Title:@educo:/educo/mypage.php?id=__ID__'
    //); // to add new tab
    //$this->tabs = array(
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
    $head = array();
    $h = 0;


    $head[$h][0] = dol_buildpath('/educo/academic/card.php?id='.$object->id, 1);
    $head[$h][1] = 'AcademcYear';
    $head[$h][2] = 'fiche';
    
    $h++;
    $head[$h][0] = dol_buildpath('/educo/academic/pensum.php?academicid='.$object->id, 1);
    $head[$h][1] = 'Pensum';
    $head[$h][2] = 'pensum';

    $h++;
    $head[$h][0] = dol_buildpath('/educo/academic/groups.php?academicid='.$object->id, 1);
    $head[$h][1] = 'Groups';
    $head[$h][2] = 'groups';
    $h++;
    $head[$h][0] = dol_buildpath('/educo/academic/teachers_subjects.php?academicid='.$object->id, 1);
    $head[$h][1] = 'TeachersSubjects';
    $head[$h][2] = 'teacherssubjects';

    $h++;
    $head[$h][0] = dol_buildpath('/educo/academic/scheduler.php?academicid='.$object->id, 1);
    $head[$h][1] = 'Scheduler';
    $head[$h][2] = 'scheduler';
    
    return $head;
}
function fetchTeacherByGroup($academiciid,$groupid,$sortorder='', $sortfield='', $limit=0, $offset=0)
	{
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

		
		$sql .= ' FROM ' . MAIN_DB_PREFIX .$table_element. ' as t';

		
		$sql.= ' WHERE 1 = 1';
		if (! empty($conf->multicompany->enabled)) {
		    $sql .= " AND entity IN (" . getEntity("educoteachersubject", 1) . ")";
		}
		
		if (!empty($sortfield)) {
			$sql .=$db->order($sortfield,$sortorder);
		}
		if (!empty($limit)) {
		 $sql .=  ' ' .$db->plimit($limit + 1, $offset);
		}

		$this->lines = array();

		$resql =$db->query($sql);
		if ($resql) {
			$num =$db->num_rows($resql);

			while ($obj =$db->fetch_object($resql)) {
				$line = new EducoteachersubjectLine();

				$line->id = $obj->rowid;
				
				$line->ref = $obj->ref;
				$line->label = $obj->label;
				$line->status = $obj->status;
				$line->datec =$db->jdate($obj->datec);
				$line->tms =$db->jdate($obj->tms);
				$line->asignature_code = $obj->asignature_code;
				$line->fk_user = $obj->fk_user;
				$line->fk_academicyear = $obj->fk_academicyear;
				$line->entity = $obj->entity;
				$line->hours = $obj->hours;

				

				$this->lines[$line->id] = $line;
			}
			$this->db->free($resql);

			return $num;
		} else {
			$this->errors[] = 'Error ' .$db->lasterror();
			dol_syslog(__METHOD__ . ' ' . implode(',',$errors), LOG_ERR);

			return - 1;
		}
	}
