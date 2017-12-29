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
    global $langs, $conf, $db, $hookmanager,$form;
    include DOL_DOCUMENT_ROOT . '/educo/tpl/card_create.tpl.php';
    include DOL_DOCUMENT_ROOT . '/educo/tpl/card_create_contact.tpl.php';
    include DOL_DOCUMENT_ROOT . '/educo/tpl/card_create_third.tpl.php';
}
