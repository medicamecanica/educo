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
$res = 0;
if (!$res && file_exists("../main.inc.php"))
    $res = @include '../main.inc.php';     // to work if your module directory is into dolibarr root htdocs directory
if (!$res && file_exists("../../main.inc.php"))
    $res = @include '../../main.inc.php';   // to work if your module directory is into a subdir of root htdocs directory
if (!$res && file_exists("../../../dolibarr/htdocs/main.inc.php"))
    $res = @include '../../../dolibarr/htdocs/main.inc.php';     // Used on dev env only
if (!$res && file_exists("../../../../dolibarr/htdocs/main.inc.php"))
    $res = @include '../../../../dolibarr/htdocs/main.inc.php';   // Used on dev env only
if (!$res)
    die("Include of main fails");
// Change this following line to use the correct relative path from htdocs
require_once(DOL_DOCUMENT_ROOT . '/core/class/html.formcompany.class.php');
require_once DOL_DOCUMENT_ROOT . '/core/lib/date.lib.php';
require_once DOL_DOCUMENT_ROOT . '/core/lib/company.lib.php';
dol_include_once('/educo/class/educopensum.class.php');
dol_include_once('/educo/class/educoacadyear.class.php');
dol_include_once('/educo/class/educogroup.class.php');
dol_include_once('/educo/class/html.formeduco.class.php');
dol_include_once('/educo/lib/educo.lib.php');

// Load traductions files requiredby by page
$langs->load("educo");
$langs->load("other");

$academicid = GETPOST('academicid', 'int');
$teacherid = GETPOST('teacherid', 'int');
$groupid= GETPOST('groupid', 'int');
$group=new Educogroup($db);
$group->fetch($groupid);
$subjects = fetchSubjectsPesum($academicid, $group->grado_code,$teacherid);
print json_encode($subjects);

function fetchSubjectsPesum($academicid,$grado_code, $teacherid, $sortorder = '', $sortfield = '', $limit = 0, $offset = 0) {
    global $db, $conf;
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
    $sql .= " ts.rowid as fk_tech_sub,";
    $sql .= " ts.hours,"; 
     $sql .= " s.label as subject_label";   
    $sql .= ' FROM ' . MAIN_DB_PREFIX . 'educo_pensum as t,'
            . MAIN_DB_PREFIX . 'educo_teacher_subject as ts';
    $sql .= ' INNER JOIN ' . MAIN_DB_PREFIX . 'edcuo_c_asignatura AS s ON asignature_code=s.code';
    // Manage filter

    $sql .= ' WHERE 1 = 1';
    if (!empty($conf->multicompany->enabled)) {
        $sql .= " AND entity IN (" . getEntity("educopensum", 1) . ")";
    }
    $sql .= " AND t.fk_academicyear=" . $academicid;
    $sql .= " AND ts.fk_user=" . $teacherid;
    $sql .= " AND t.grado_code=".$grado_code;
    $sql .= " AND ts.asignature_code=t.asignature_code";
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
