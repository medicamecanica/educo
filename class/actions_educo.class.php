<?php

/*
 * Copyright (C) 2017	   Andersson Paz   <npander.@hotmail.com>
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
 *       \file       htdocs/miips/class/action_miips.php
 *       \ingroup    miips
 *       \brief      Page for hooks miips
 */
class ActionsEduco {

    function showLinkedObjectBlock($parameters, &$object, &$action, $hookmanager) {
       // var_dump($object->element);
        if($object->element!=='educoacadyear') return 0;
       
        $this->printLinkedObjects($object);
        return 1;
    }

    function printLinkedObjects($object) {
        global $conf,$langs;
        $nbofdifferenttypes = count($object->linkedObjects);

        print '<br><!-- showLinkedObjectBlock -->';
        print load_fiche_titre($langs->trans('RelatedObjects'), $morehtmlright, '');


        print '<div class="div-table-responsive-no-min">';
        print '<table class="noborder allwidth">';

        print '<tr class="liste_titre">';
        print '<td>' . $langs->trans("Type") . '</td>';
        print '<td>' . $langs->trans("Ref") . '</td>';
        print '<td align="center"></td>';
        print '<td align="center">' . $langs->trans("Date") . '</td>';
       // print '<td align="right">' . $langs->trans("AmountHTShort") . '</td>';
        print '<td align="right">' . $langs->trans("Status") . '</td>';
        print '<td></td>';
        print '</tr>';
        $nboftypesoutput = 0;

        foreach ($object->linkedObjects as $objecttype => $objects) {
              if ($objecttype == 'project')         {
        			$tplpath = 'educo';
                                $tplname='linkedprojectblock';
        			if (empty($conf->projet->enabled)) continue;	// Do not show if module disabled
        	}
                 //var_dump( dol_buildpath('/' . $tplpath . '/tpl'. '/' . $tplname . '.tpl.php'));
            // Output template part (modules that overwrite templates must declare this into descriptor)
            $dirtpls = array_merge($conf->modules_parts['tpl'], array('/' . $tplpath . '/tpl'));
            //var_dump('<pre>',$conf->modules_parts['tpl']);
            foreach ($dirtpls as $reldir) {
               // var_dump($reldir.'<br>');
                if ($nboftypesoutput == ($nbofdifferenttypes - 1)) {    // No more type to show after
                    global $noMoreLinkedObjectBlockAfter;
                    $noMoreLinkedObjectBlockAfter = 1;
                }
               
                $res = @include dol_buildpath($reldir . '/' . $tplname . '.tpl.php');
                if ($res) {
                    $nboftypesoutput++;
                    break;
                }
            }
        }

        if (!$nboftypesoutput) {
            print '<tr><td class="impair opacitymedium" colspan="7">' . $langs->trans("None") . '</td></tr>';
        }

        print '</table>';
        print '</div>';

        return $nbofdifferenttypes;
    }

    /**
     * Overloading the doActions function : replacing the parent's function with the one below
     *
     * @param   array()         $parameters     Hook metadatas (context, etc...)
     * @param   CommonObject    &$object        The object to process (an invoice if you are in invoice module, a propale in propale's module, etc...)
     * @param   string          &$action        Current action (if set). Generally create or edit or null
     * @param   HookManager     $hookmanager    Hook manager propagated to allow calling another hook
     * @return  int                             < 0 on error, 0 on success, 1 to replace standard code
     */
    function formObjectOptions($parameters, &$object, &$action, $hookmanager) {
        global $langs;
        $error = 0; // Error counter
        $myvalue = 'test'; // A result value
        // print_r($object);

        if (in_array('actioncard', explode(':', $parameters['context'])) && empty($action)) {
            //   print_r($parameters);
            // echo '</table>';
        }

        if (!$error) {
            $this->results = array('myreturn' => $myvalue);
            $this->resprints = 'A text to show';
            return 0; // or return 1 to replace standard code
        } else {
            $this->errors[] = 'Error message';
            return -1;
        }
    }

}
