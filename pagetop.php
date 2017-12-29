<?php

/* Copyright (C) 2004-2005 Rodolphe Quiedeville <rodolphe@quiedeville.org>
 * Copyright (C) 2004-2015 Laurent Destailleur  <eldy@users.sourceforge.net>
 * Copyright (C) 2004      Benoit Mortier       <benoit.mortier@opensides.be>
 * Copyright (C) 2005-2012 Regis Houssin        <regis.houssin@capnetworks.com>
 * Copyright (C) 2007      Franky Van Liedekerke <franky.van.liedekerke@telenet.be>
 * Copyright (C) 2013      Florian Henry		<florian.henry@open-concept.pro>
 * Copyright (C) 2013-2015 Alexandre Spangaro 	<aspangaro.dolibarr@gmail.com>
 * Copyright (C) 2014      Juanjo Menent	 	<jmenent@2byte.es>
 * Copyright (C) 2015      Jean-François Ferry	<jfefe@aternatik.fr>
 * Copyright (C) 2017      Andersson Paz	<npander@hotmail.com>
 *
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 3 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program. If not, see <http://www.gnu.org/licenses/>.
 */

/**
 *       \file       htdocs/contact/card.php
 *       \ingroup    societe
 *       \brief      Card of a contact
 */
require '../main.inc.php';
require_once DOL_DOCUMENT_ROOT . '/comm/action/class/actioncomm.class.php';
require_once DOL_DOCUMENT_ROOT . '/contact/class/contact.class.php';
require_once DOL_DOCUMENT_ROOT . '/core/lib/contact.lib.php';
require_once DOL_DOCUMENT_ROOT . '/core/lib/company.lib.php';
require_once DOL_DOCUMENT_ROOT . '/core/lib/images.lib.php';
require_once DOL_DOCUMENT_ROOT . '/core/lib/files.lib.php';
require_once DOL_DOCUMENT_ROOT . '/core/class/html.formcompany.class.php';
require_once DOL_DOCUMENT_ROOT . '/core/class/extrafields.class.php';
require_once DOL_DOCUMENT_ROOT . '/core/class/doleditor.class.php';
require_once DOL_DOCUMENT_ROOT . '/core/class/html.form.class.php';
require_once DOL_DOCUMENT_ROOT . '/user/class/user.class.php';
require_once DOL_DOCUMENT_ROOT . '/categories/class/categorie.class.php';
require_once DOL_DOCUMENT_ROOT . '/educo/class/educoestudiante.class.php';
//$langs->load("companies");
//$langs->load("users");
$langs->load("educo@educo");
//$langs->load("other");
//$langs->load("commercial");

$mesg = '';
$error = 0;
$errors = array();

$action = (GETPOST('action', 'alpha') ? GETPOST('action', 'alpha') : 'view');
$confirm = GETPOST('confirm', 'alpha');
$backtopage = GETPOST('backtopage', 'alpha');
$id = GETPOST('id', 'int');
$socid = GETPOST('socid', 'int');
if ($user->societe_id)
    $socid = $user->societe_id;

$object = new Contact($db);
$extrafields = new ExtraFields($db);
$estudiante = new Educoestudiante($db);
// fetch optionals attributes and labels
$extralabels = $extrafields->fetch_name_optionals_label($object->table_element);

//
$help_url = 'EN:Module_Third_Parties|FR:Module_Tiers|ES:Educación';
llxHeader('', $title, $help_url);
llxFooter();

$db->close();
