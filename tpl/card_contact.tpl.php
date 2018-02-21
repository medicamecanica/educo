<?php

/* Copyright (C) 2004-2005 Rodolphe Quiedeville <rodolphe@quiedeville.org>
 * Copyright (C) 2004-2015 Laurent Destailleur  <eldy@users.sourceforge.net>
 * Copyright (C) 2004      Benoit Mortier       <benoit.mortier@opensides.be>
 * Copyright (C) 2005-2012 Regis Houssin        <regis.houssin@capnetworks.com>
 * Copyright (C) 2007      Franky Van Liedekerke <franky.van.liedekerke@telenet.be>
 * Copyright (C) 2013      Florian Henry		<florian.henry@open-concept.pro>
 * Copyright (C) 2013-2016 Alexandre Spangaro 	<aspangaro.dolibarr@gmail.com>
 * Copyright (C) 2014      Juanjo Menent	 	<jmenent@2byte.es>
 * Copyright (C) 2015      Jean-François Ferry	<jfefe@aternatik.fr> 
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
  if (! empty($id) && $action != 'edit' && $action != 'create')
    {
        $objsoc = new Societe($db);

        /*
         * Fiche en mode visualisation
         */

        dol_htmloutput_errors($error,$errors);

        dol_fiche_head($head, 'contact',  $langs->trans("Student"), -1, 'generic');

        if ($action == 'create_user')
        {
            // Full firstname and lastname separated with a dot : firstname.lastname
            include_once DOL_DOCUMENT_ROOT.'/core/lib/functions2.lib.php';
            $login=dol_buildlogin($contact->lastname,$contact->firstname);

            $generated_password='';
            if (! $ldap_sid) // TODO ldap_sid ?
            {
                require_once DOL_DOCUMENT_ROOT.'/core/lib/security2.lib.php';
                $generated_password=getRandomPassword(false);
            }
            $password=$generated_password;

            // Create a form array
            $formquestion=array(
            array('label' => $langs->trans("LoginToCreate"), 'type' => 'text', 'name' => 'login', 'value' => $login),
            array('label' => $langs->trans("Password"), 'type' => 'text', 'name' => 'password', 'value' => $password),
            //array('label' => $form->textwithpicto($langs->trans("Type"),$langs->trans("InternalExternalDesc")), 'type' => 'select', 'name' => 'intern', 'default' => 1, 'values' => array(0=>$langs->trans('Internal'),1=>$langs->trans('External')))
            );
            $text=$langs->trans("ConfirmCreateContact").'<br>';
            if (! empty($conf->societe->enabled))
            {
                if ($contact->socid > 0) $text.=$langs->trans("UserWillBeExternalUser");
                else $text.=$langs->trans("UserWillBeInternalUser");
            }
            print $form->formconfirm($_SERVER["PHP_SELF"]."?id=".$contact->id,$langs->trans("CreateDolibarrLogin"),$text,"confirm_create_user",$formquestion,'yes');

        }

        $linkback = '<a href="'.DOL_URL_ROOT.'/educo/student/list.php">'.$langs->trans("BackToList").'</a>';

        $morehtmlref='<div class="refidno">';
        if (empty($conf->global->SOCIETE_DISABLE_CONTACTS))
        {
            $objsoc->fetch($contact->socid);
            // Thirdparty
            $morehtmlref.=$langs->trans('ThirdParty') . ' : ';
            if ($objsoc->id > 0) $morehtmlref.=$objsoc->getNomUrl(1);
            else $morehtmlref.=$langs->trans("ContactNotLinkedToCompany");
        }
        $morehtmlref.='</div>';

        dol_banner_tab($contact, 'id', $linkback, 1, 'rowid', 'ref', $morehtmlref);


        print '<div class="fichecenter">';
        print '<div class="fichehalfleft">';

        print '<div class="underbanner clearboth"></div>';
        print '<table class="border tableforfield" width="100%">';

        // Civility
        print '<tr><td class="titlefield">'.$langs->trans("UserTitle").'</td><td>';
        print $contact->getCivilityLabel();
        print '</td></tr>';

        // Role
        print '<tr><td>'.$langs->trans("PostOrFunction").'</td><td>'.$contact->poste.'</td></tr>';

        // Email
        if (! empty($conf->mailing->enabled))
        {
            $langs->load("mails");
            print '<tr><td>'.$langs->trans("NbOfEMailingsSend").'</td>';
            print '<td><a href="'.DOL_URL_ROOT.'/comm/mailing/list.php?filteremail='.urlencode($contact->email).'">'.$contact->getNbOfEMailings().'</a></td></tr>';
        }

        // Instant message and no email
        print '<tr><td>'.$langs->trans("IM").'</td><td>'.$contact->jabberid.'</td></tr>';
        if (!empty($conf->mailing->enabled))
        {
        	print '<tr><td>'.$langs->trans("No_Email").'</td><td>'.yn($contact->no_email).'</td></tr>';
        }

        print '<tr><td>'.$langs->trans("ContactVisibility").'</td><td>';
        print $contact->LibPubPriv($contact->priv);
        print '</td></tr>';

        print '</table>';

        print '</div>';
        print '<div class="fichehalfright"><div class="ficheaddleft">';

        print '<div class="underbanner clearboth"></div>';
        print '<table class="border tableforfield" width="100%">';

		// Categories
		if (! empty($conf->categorie->enabled)  && ! empty($user->rights->categorie->lire)) {
			print '<tr><td class="titlefield">' . $langs->trans("Categories") . '</td>';
			print '<td colspan="3">';
			print $form->showCategories( $contact->id, 'contact', 1 );
			print '</td></tr>';
		}

    	// Other attributes
    	$cols = 3;
    	$parameyers=array('socid'=>$socid);
    	include DOL_DOCUMENT_ROOT . '/core/tpl/extrafields_view.tpl.php';

        $contact->load_ref_elements();

        if (! empty($conf->propal->enabled))
        {
            print '<tr><td class="titlefield">'.$langs->trans("ContactForProposals").'</td><td colspan="3">';
            print $contact->ref_propal?$contact->ref_propal:$langs->trans("NoContactForAnyProposal");
            print '</td></tr>';
        }

        if (! empty($conf->commande->enabled) || ! empty($conf->expedition->enabled))
        {
            print '<tr><td>';
            if (! empty($conf->expedition->enabled)) { print $langs->trans("ContactForOrdersOrShipments"); }
            else print $langs->trans("ContactForOrders");
            print '</td><td colspan="3">';
            $none=$langs->trans("NoContactForAnyOrder");
            if  (! empty($conf->expedition->enabled)) { $none=$langs->trans("NoContactForAnyOrderOrShipments"); }
            print $contact->ref_commande?$contact->ref_commande:$none;
            print '</td></tr>';
        }

        if (! empty($conf->contrat->enabled))
        {
            print '<tr><td>'.$langs->trans("ContactForContracts").'</td><td colspan="3">';
            print $contact->ref_contrat?$contact->ref_contrat:$langs->trans("NoContactForAnyContract");
            print '</td></tr>';
        }

        if (! empty($conf->facture->enabled))
        {
            print '<tr><td>'.$langs->trans("ContactForInvoices").'</td><td colspan="3">';
            print $contact->ref_facturation?$contact->ref_facturation:$langs->trans("NoContactForAnyInvoice");
            print '</td></tr>';
        }

        print '<tr><td>'.$langs->trans("DolibarrLogin").'</td><td colspan="3">';
        if ($contact->user_id)
        {
            $dolibarr_user=new User($db);
            $result=$dolibarr_user->fetch($contact->user_id);
            print $dolibarr_user->getLoginUrl(1);
        }
        else print $langs->trans("NoDolibarrAccess");
        print '</td></tr>';

        print '<tr><td>';
        print $langs->trans("VCard").'</td><td colspan="3">';
		print '<a href="'.DOL_URL_ROOT.'/contact/vcard.php?id='.$contact->id.'">';
		print img_picto($langs->trans("Download"),'vcard.png').' ';
		print $langs->trans("Download");
		print '</a>';
        print '</td></tr>';

        print "</table>";

        print '</div></div></div>';
        print '<div style="clear:both"></div>';

        print dol_fiche_end();

        // Barre d'actions
        print '<div class="tabsAction">';

		$parameters=array();
		$reshook=$hookmanager->executeHooks('addMoreActionsButtons',$parameters,$contact,$action);    // Note that $action and $contact may have been modified by hook
		if (empty($reshook))
		{
        	if ($user->rights->societe->contact->creer)
            {
                print '<a class="butAction" href="'.$_SERVER['PHP_SELF'].'?id='.$contact->id.'">'.$langs->trans('View').'</a>';
            }

          
        }

        print "</div>";

    }

