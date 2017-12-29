<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
//      //CONTACT
        $contact = new Contact($db);
        $contact->socid = GETPOST("socid", 'int');
        $contact->lastname = GETPOST("lastname");
        $contact->firstname = GETPOST("firstname");
        $contact->civility_id = GETPOST("civility_id", 'alpha');
        $contact->poste = GETPOST("poste");
        $contact->address = GETPOST("address");
        $contact->zip = GETPOST("zipcode");
        $contact->town = GETPOST("town");
        $contact->country_id = GETPOST("country_id", 'int');
        $contact->state_id = GETPOST("state_id", 'int');
        $contact->skype = GETPOST("skype");
        $contact->email = GETPOST("email", 'alpha');
        $contact->phone_pro = GETPOST("phone_pro");
        $contact->phone_perso = GETPOST("phone_perso");
        $contact->phone_mobile = GETPOST("phone_mobile");
        $contact->fax = GETPOST("fax");
        $contact->jabberid = GETPOST("jabberid", 'alpha');
        $contact->no_email = GETPOST("no_email", 'int');
        $contact->priv = GETPOST("priv", 'int');
        $contact->note_public = GETPOST("note_public");
        $contact->note_private = GETPOST("note_private");
        $contact->statut = 1; //Defult status to Actif
        // Note: Correct date should be completed with location to have exact GM time of birth.
        $contact->birthday = dol_mktime(0, 0, 0, GETPOST("birthdaymonth", 'int'), GETPOST("birthdayday", 'int'), GETPOST("birthdayyear", 'int'));
        $contact->birthday_alert = GETPOST("birthday_alert", 'alpha');

        // Fill array 'array_options' with data from add form
        $ret = $extrafields->setOptionalsFromPost($extralabels, $contact);
        if ($ret < 0) {
            $error++;
            $action = 'create';
        }

        if (!GETPOST("lastname")) {
            $error++;
            $errors[] = $langs->trans("ErrorFieldRequired", $langs->transnoentities("Lastname") . ' / ' . $langs->transnoentities("Label"));
            $action = 'create';
        }
        
