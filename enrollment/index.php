<?php
/*
 * Copyright (C) 2017 ander
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
// Change this following line to use the correct relative path (../, ../../, etc)
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
dol_include_once('/educo/class/educoteachersubject.class.php');
dol_include_once('/educo/class/educohorario.class.php');
dol_include_once('/educo/class/html.formeduco.class.php');
dol_include_once('/educo/class/educogroup.class.php');
dol_include_once('/educo/lib/educo.lib.php');

// Load traductions files requiredby by page
$langs->load("educo");
$langs->load("other");

$title = $langs->trans('Enrollment');
llxHeader('', $title);//, 'manual', null, 0, 0, $js, $css);

print_barre_liste($title, $page, $_SERVER["PHP_SELF"], $param, $sortfield, $sortorder, '', $num, $nbtotalofrecords, 'title_companies', 0, '', '', $limit);

llxFooter();
$db->close();
