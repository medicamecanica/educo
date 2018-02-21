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

function student_header($object) {
    global $langs;
    $head = array();
    $h = 0;


    $head[$h][0] = dol_buildpath('/educo/student/card.php?id=' . $object->id, 1);
    $head[$h][1] = $langs->trans("Card");
    $head[$h][2] = 'card';

    $h++;
    $head[$h][0] = dol_buildpath('/educo/student/contact.php?id=' . $object->id, 1);
    $head[$h][1] = $langs->trans("Contact");
    $head[$h][2] = 'contact';

    $h++;
    $head[$h][0] = dol_buildpath('/educo/student/suport.php?id=' . $object->id, 1);
    $head[$h][1] = $langs->trans("Suport");
    $head[$h][2] = 'suport';
    
    $h++;
    $head[$h][0] = dol_buildpath('/educo/student/relatives.php?id=' . $object->id, 1);
    $head[$h][1] = $langs->trans("Relatives");
    $head[$h][2] = 'relatives';
    
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
    $sql .= " s.label as subject_label,";
    $sql .= " concat(t.grado_code,' ',g.sufix) as group_label";
    $sql .= ' FROM ' . MAIN_DB_PREFIX . 'educo_horario as t';
    $sql .= ' INNER JOIN ' . MAIN_DB_PREFIX . 'edcuo_c_asignatura AS s ON subject_code=s.code';
    $sql .= ' INNER JOIN ' . MAIN_DB_PREFIX . 'educo_teacher_subject AS ts ON t.fk_teach_sub=ts.rowid';
    $sql .= ' INNER JOIN ' . MAIN_DB_PREFIX . 'user AS u ON ts.fk_user=u.rowid';
    $sql .= ' INNER JOIN ' . MAIN_DB_PREFIX . 'educo_group AS g ON t.fk_group=g.rowid';

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
    //var_dump($sql);
    $lines = array();

    $resql = $db->query($sql);
    if ($resql) {
        $num = $db->num_rows($resql);

        while ($obj = $db->fetch_object($resql)) {


            $lines[] = $obj;
        }
        $db->free($resql);
        //var_dump(count($lines));
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
    //print $sql;
    if ($resql) {
        $num = $db->num_rows($resql);

        while ($obj = $db->fetch_object($resql)) {
               $lines[] = $obj;
        }
        $db->free($resql);
   //     var_dump(count($lines));
        return $lines;
    } else {
        $errors[] = 'Error ' . $db->lasterror();
        dol_syslog(__METHOD__ . ' ' . implode(',', $errors), LOG_ERR);

        return - 1;
    }
}
/**
 * 		Show html area for list of contacts
 *
 *		@param	Conf		$conf		Object conf
 * 		@param	Translate	$langs		Object langs
 * 		@param	DoliDB		$db			Database handler
 * 		@param	Societe		$object		Third party object
 *      @param  string		$backtopage	Url to go once contact is created
 *      @return	void
 */
function show_relatives($conf,$langs,$db,$object,$backtopage='')
{
    global $user,$conf;
    global $bc;

    $form= new Form($db);

    $sortfield = GETPOST("sortfield",'alpha');
    $sortorder = GETPOST("sortorder",'alpha');
    $page = GETPOST('page','int');
    $search_status		= GETPOST("search_status",'int');
    if ($search_status=='') $search_status=1; // always display activ customer first
    $search_name = GETPOST("search_name",'alpha');
    $search_addressphone = GETPOST("search_addressphone",'alpha');

    if (! $sortorder) $sortorder="ASC";
    if (! $sortfield) $sortfield="p.lastname";

    $i=-1;

    $contactstatic = new Contact($db);

    if (! empty($conf->clicktodial->enabled))
    {
        $user->fetch_clicktodial(); // lecture des infos de clicktodial
    }

    $buttoncreate='';
    if ($user->rights->societe->contact->creer)
    {
    	$addcontact = (! empty($conf->global->SOCIETE_ADDRESSES_MANAGEMENT) ? $langs->trans("AddContact") : $langs->trans("AddContactAddress"));
		$buttoncreate='<a class="addnewrecord" href="'.DOL_URL_ROOT.'/contact/card.php?socid='.$object->id.'&amp;action=create&amp;backtopage='.urlencode($backtopage).'">'.$addcontact;
		if (empty($conf->dol_optimize_smallscreen)) $buttoncreate.=' '.img_picto($addcontact,'filenew');
		$buttoncreate.='</a>'."\n";
    }

    print "\n";

    $title =  $langs->trans("ContactsForStudent") ;
    print load_fiche_titre($title,$buttoncreate,'');

    print '<form method="GET" action="'.$_SERVER["PHP_SELF"].'" name="formfilter">';
    print '<input type="hidden" name="socid" value="'.$object->id.'">';
    print '<input type="hidden" name="sortorder" value="'.$sortorder.'">';
    print '<input type="hidden" name="sortfield" value="'.$sortfield.'">';
    print '<input type="hidden" name="page" value="'.$page.'">';

    print "\n".'<table class="noborder" width="100%">'."\n";

    $param="socid=".$object->id;
    if ($search_status != '') $param.='&amp;search_status='.$search_status;
    if ($search_name != '') $param.='&amp;search_name='.urlencode($search_name);

    $colspan=9;
    print '<tr class="liste_titre">';
    print_liste_field_titre("Name",$_SERVER["PHP_SELF"],"p.lastname","",$param,'',$sortfield,$sortorder);
    print_liste_field_titre("Type",$_SERVER["PHP_SELF"],"p.poste","",$param,'',$sortfield,$sortorder);
    print_liste_field_titre( $langs->trans("Address").' / '.$langs->trans("Phone").' / '.$langs->trans("Email"),$_SERVER["PHP_SELF"],"","",$param,'',$sortfield,$sortorder);
    print_liste_field_titre("Status",$_SERVER["PHP_SELF"],"p.statut","",$param,'',$sortfield,$sortorder);
    // Add to agenda
    if (! empty($conf->agenda->enabled) && ! empty($user->rights->agenda->myactions->create))
    {
    	$colspan++;
        print_liste_field_titre('');
    }
    // Edit
    print_liste_field_titre('');
	print "</tr>\n";


    $sql = "SELECT p.rowid as contact_id, p.lastname, p.firstname, p.fk_pays as country_id, p.civility, p.poste, p.phone as phone_pro, p.phone_mobile, p.phone_perso, p.fax, p.email, p.skype, p.statut, p.photo,";
    $sql .= " p.civility as civility_id, p.address, p.zip, p.town,";
    $sql.="cs.rowid, `ref`, `fk_contact`, `fk_estudiante`, `type`, `note`";
    $sql .= " FROM ".MAIN_DB_PREFIX."educo_contact_student as cs";
     $sql .= " INNER JOIN ".MAIN_DB_PREFIX."socpeople p ON cs.fk_contact=p.rowid";
    $sql .= " WHERE cs.fk_estudiante = ".$object->id;
    if ($search_status!='' && $search_status != '-1') $sql .= " AND p.statut = ".$db->escape($search_status);
    if ($search_name)       $sql .= " AND (p.lastname LIKE '%".$db->escape($search_name)."%' OR p.firstname LIKE '%".$db->escape($search_name)."%')";
    $sql.= " ORDER BY $sortfield $sortorder";

    dol_syslog('core/lib/company.lib.php :: show_contacts', LOG_DEBUG);
    $result = $db->query($sql);
    if (! $result) dol_print_error($db);

    $num = $db->num_rows($result);

	$var=true;
	if ($num || (GETPOST('button_search') || GETPOST('button_search.x') || GETPOST('button_search_x')))
    {
        print '<tr class="liste_titre">';

        // Photo - Name
        print '<td class="liste_titre">';
        print '<input type="text" class="flat" name="search_name" size="20" value="'.$search_name.'">';
        print '</td>';

        // Position
        print '<td class="liste_titre">';
        print '</td>';

        // Address - Phone - Email
        print '<td class="liste_titre">&nbsp;</td>';

        // Status
        print '<td class="liste_titre maxwidthonsmartphone">';
        print $form->selectarray('search_status', array('-1'=>'','0'=>$contactstatic->LibStatut(0,1),'1'=>$contactstatic->LibStatut(1,1)),$search_status);
        print '</td>';

        // Add to agenda
        if (! empty($conf->agenda->enabled) && $user->rights->agenda->myactions->create)
        {
        	$colspan++;
            print '<td class="liste_titre">&nbsp;</td>';
        }

    	// Edit
        print '<td class="liste_titre" align="right">';
        print '<input type="image" class="liste_titre" name="button_search" src="'.img_picto($langs->trans("Search"),'search.png','','',1).'" value="'.dol_escape_htmltag($langs->trans("Search")).'" title="'.dol_escape_htmltag($langs->trans("Search")).'">';
        print '</td>';

        print "</tr>";

        $i=0;

        while ($i < $num)
        {
            $obj = $db->fetch_object($result);

            $contactstatic->id = $obj->rowid;
            $contactstatic->ref = $obj->ref;
            $contactstatic->statut = $obj->statut;
            $contactstatic->lastname = $obj->lastname;
            $contactstatic->firstname = $obj->firstname;
            $contactstatic->civility_id = $obj->civility_id;
            $contactstatic->civility_code = $obj->civility_id;
            $contactstatic->poste = $obj->poste;
            $contactstatic->address = $obj->address;
            $contactstatic->zip = $obj->zip;
            $contactstatic->town = $obj->town;
            $contactstatic->phone_pro = $obj->phone_pro;
            $contactstatic->phone_mobile = $obj->phone_mobile;
            $contactstatic->phone_perso = $obj->phone_perso;
            $contactstatic->email = $obj->email;
            $contactstatic->web = $obj->web;
            $contactstatic->skype = $obj->skype;
            $contactstatic->photo = $obj->photo;

            $country_code = getCountry($obj->country_id, 2);
            $contactstatic->country_code = $country_code;

            $contactstatic->setGenderFromCivility();

            print "<tr>";

			// Photo - Name
			print '<td>';
            print $form->showphoto('contact',$contactstatic,0,0,0,'photorefnoborder valignmiddle marginrightonly','small',1,0,1);
			print $contactstatic->getNomUrl(0,'',0,'&backtopage='.urlencode($backtopage));
			print '</td>';

			// Job position
			print '<td>';
            if ($obj->type) print $obj->type;
            print '</td>';

            // Address - Phone - Email
            print '<td>';
            print $contactstatic->getBannerAddress('contact', $object);
            print '</td>';

            // Status
			print '<td>'.$contactstatic->getLibStatut(5).'</td>';

            // Add to agenda
            if (! empty($conf->agenda->enabled) && $user->rights->agenda->myactions->create)
            {
                print '<td align="center">';
                print '<a href="'.DOL_URL_ROOT.'/comm/action/card.php?action=create&actioncode=&contactid='.$obj->rowid.'&socid='.$object->id.'&backtopage='.urlencode($backtopage).'">';
                print img_object($langs->trans("Event"),"action");
                print '</a></td>';
            }

            // Edit
            if ($user->rights->societe->contact->creer)
            {
                print '<td align="right">';
                print '<a href="'.DOL_URL_ROOT.'/contact/card.php?action=edit&amp;id='.$obj->rowid.'&amp;backtopage='.urlencode($backtopage).'">';
                print img_edit();
                print '</a></td>';
            }
            else print '<td>&nbsp;</td>';

            print "</tr>\n";
            $i++;
        }
    }
    else
	{
        print "<tr ".$bc[! $var].">";
        print '<td colspan="'.$colspan.'" class="opacitymedium">'.$langs->trans("None").'</td>';
        print "</tr>\n";
    }
    print "\n</table>\n";

    print '</form>'."\n";

    return $i;
}