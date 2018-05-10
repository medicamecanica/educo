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

/**
 * Description of FormEduco
 *
 * @author ander
 */
class FormEduco {

    private $db;
    public $error;

    /**
     * 	Constructor
     *
     * 	@param	DoliDB		$db      Database handler
     */
    function __construct($db) {
        $this->db = $db;

        return 1;
    }

    /**
     *  Return a HTML select list of bank accounts
     *
     *  @param  string	$htmlname          	Name of select zone
     *  @param	string	$dictionarytable	Dictionary table
     *  @param	string	$keyfield			Field for key
     *  @param	string	$labelfield			Label field
     *  @param	string	$selected			Selected value
     *  @param  int		$useempty          	1=Add an empty value in list, 2=Add an empty value in list only if there is more than 2 entries.
     *  @param  string  $moreattrib         More attributes on HTML select tag
     * 	@return	void
     */
    function select_dictionary($htmlname, $dictionarytable, $keyfield = 'code', $labelfield = 'label', $selected = '', $useempty = 0, $moreattrib = '',$where=array()) {
        global $langs, $conf;

        $langs->load("admin");

        $sql = "SELECT  " . $keyfield . ", " . $labelfield;
        $sql .= " FROM " . MAIN_DB_PREFIX . $dictionarytable;
        $sql .= " WHERE active=1 ";
        if(!empty($where)){
            foreach ($where as $cond)
            $sql .= " AND ".$cond;
        }
        $sql .= " ORDER BY " . $keyfield;

        dol_syslog(get_class($this) . "::select_dictionary", LOG_DEBUG);
        $result = $this->db->query($sql);
       // echo $sql;
        if ($result) {
            $num = $this->db->num_rows($result);
            $i = 0;
            if ($num) {
                print '<select id="select' . $htmlname . '" class="flat selectdictionary" name="' . $htmlname . '"' . ($moreattrib ? ' ' . $moreattrib : '') . '>';
                if ($useempty == 1 || ($useempty == 2 && $num > 1)) {
                    print '<option value="-1">&nbsp;</option>';
                }

                while ($i < $num) {
                    $obj = $this->db->fetch_object($result);
                    if ($selected == $obj->rowid || $selected == $obj->$keyfield) {
                        print '<option value="' . $obj->$keyfield . '" selected>';
                    } else {
                        print '<option value="' . $obj->$keyfield . '">';
                    }
                    print $obj->$labelfield;
                    print '</option>';
                    $i++;
                }
                print "</select>";
            } else {
                print $langs->trans("DictionaryEmpty");
            }
        } else {
            dol_print_error($this->db);
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
    private function fetchSubjectsPesum($academicid = 0, $sortorder = '', $sortfield = '', $limit = 0, $offset = 0) {
        dol_syslog(__METHOD__, LOG_DEBUG);

        $sql = 'SELECT';
        $sql .= ' distinct( t.rowid),';

        $sql .= " t.ref,";
        $sql .= " t.fk_academicyear,";
        $sql .= " t.horas,";
        $sql .= " t.date_create,";
        $sql .= " t.tms,";
        $sql .= " t.statut,";
        $sql .= " t.import_key,";
        $sql .= " t.asignature_code,";
        $sql .= " t.grado_code,";
        $sql .= " a.label as subject_name,";
        $sql .= " a.code as subject_code,";
         $sql .= "a.level as subject_level,";
        $sql .= " g.label as grado_name";


        $sql .= ' FROM ' . MAIN_DB_PREFIX . 'educo_pensum as t';
        $sql .= ' INNER JOIN ' . MAIN_DB_PREFIX . 'edcuo_c_asignatura as a on t.asignature_code=a.code';
        $sql .= ' INNER JOIN ' . MAIN_DB_PREFIX . 'educo_c_grado as g on t.grado_code=g.code';

        // Manage filter

        $sql .= ' WHERE 1 = 1';
        if (!empty($conf->multicompany->enabled)) {
            $sql .= " AND entity IN (" . getEntity("educopensum", 1) . ")";
        }
        $sql .= " AND t.fk_academicyear=" . $academicid;
        if (!empty($sortfield)) {
            $sql .= $this->db->order($sortfield, $sortorder);
        }
        if (!empty($limit)) {
            $sql .= ' ' . $this->db->plimit($limit + 1, $offset);
        }

        $this->lines = array();
        // var_dump($sql);
        $resql = $this->db->query($sql);
        if ($resql) {
            $num = $this->db->num_rows($resql);
            $line = array();
            while ($obj = $this->db->fetch_object($resql)) {
                $line[] = $obj;
            }
            $this->db->free($resql);

            return $line;
        } else {
            $this->errors[] = 'Error ' . $this->db->lasterror();
            dol_syslog(__METHOD__ . ' ' . implode(',', $this->errors), LOG_ERR);

            return - 1;
        }
    }

    function select_pensum($htmlname, $academicid, $id, $show_empty = 0,$level='0') {
        global $form;
        // $form=new Form($this->db);
        $subjects = $this->fetchSubjectsPesum($academicid);
        $array = array();
        if (is_array($subjects)) {
            foreach ($subjects as $s) {
              //  var_dump($s->subject_level);
                if($s->subject_level===$level)
                $array[$s->rowid] = $s->subject_name;
            }
        } else {
            $array = $this->errors;
        }
        return $form->selectarray($htmlname, $array, $id, $show_empty);
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
    private function fetchGroups($academicid, $sortorder = '', $sortfield = '', $limit = 0, $offset = 0) {
        dol_syslog(__METHOD__, LOG_DEBUG);

        $sql = 'SELECT';
        $sql .= ' t.rowid,';

        $sql .= " t.ref,";
        $sql .= " t.sufix,";
        $sql .= " t.label,";
        $sql .= " t.fk_academicyear,";
        $sql .= " t.grado_code,";
        $sql .= " t.tms,";
        $sql .= " t.date_create,";
        $sql .= " t.statut,";
        $sql .= " t.import_key";


        $sql .= ' FROM ' . MAIN_DB_PREFIX . 'educo_group as t';

        // Manage filter

        $sql .= ' WHERE 1 = 1';
        $sql .= " AND t.fk_academicyear =$academicid";
        if (!empty($conf->multicompany->enabled)) {
            $sql .= " AND entity IN (" . getEntity("educogroup", 1) . ")";
        }

        if (!empty($sortfield)) {
            $sql .= $this->db->order($sortfield, $sortorder);
        }
        if (!empty($limit)) {
            $sql .= ' ' . $this->db->plimit($limit + 1, $offset);
        }
        $lines = array();
        $resql = $this->db->query($sql);
        if ($resql) {
            $num = $this->db->num_rows($resql);

            while ($obj = $this->db->fetch_object($resql)) {
                $lines[] = $obj;
            }
            $this->db->free($resql);
            return $lines;
        } else {
            $this->errors[] = 'Error ' . $this->db->lasterror();
            dol_syslog(__METHOD__ . ' ' . implode(',', $this->errors), LOG_ERR);

            return - 1;
        }
    }

    function select_groups($htmlname, $academicid, $id, $show_empty = 0) {
        global $form, $langs;
        $form = new Form($this->db);
        $groups = $this->fetchGroups($academicid);
        $array = array();
        if ($academicid) {
            if (is_array($groups)) {
                foreach ($groups as $g) {
                    '" code="' . $g->grado_code;
                    $array[$g->rowid] = $g->label;
                }
            } else {
                $array = $this->errors;
            }
        } else
            $array[0] = $langs->trans('SelectAnyAcademic');

        return $form->selectarray($htmlname, $array, $id, $show_empty);
    }

    function select_academic($htmlname, $id, $show_empty = 0) {
        global $form;
        require_once (DOL_DOCUMENT_ROOT . '/educo/class/educoacadyear.class.php');
        $educoacadyear = new Educoacadyear($this->db);
        $academics = $educoacadyear->fetchAll();

        $array = array();

        if (is_array($educoacadyear->lines)) {
            foreach ($educoacadyear->lines as $a) {

                $array[$a->id] = $a->ref;
            }
        } else {
            $array = $this->errors;
        }

        return $form->selectarray($htmlname, $array, $id, $show_empty);
    }

    function select_student($htmlname, $id, $ref, $url) {
        print '<input  type="text" id="' . $htmlname . 'ref" name="' . $htmlname . 'ref" value="' . $ref . '">';
        print '<input class="flat" type="hidden" id="student_url"value="' . $url . '">';
        print '<input class="flat" type="hidden" id="' . $htmlname . 'id" name="' . $htmlname . 'id" value="' . $id . '">';
    }

    public function select_workingday($htmlname, $id, $show_empty) {
        global $form, $langs;
        for ($index = 1; $index < 5; $index++) {
            $array[$index] = $langs->trans('EducoWorkingDay' . $index);
        }

        return $form->selectarray($htmlname, $array, $id, $show_empty);
    }

    public function select_doctype($htmlname = 'doc_type', $id, $show_empty = 1) {
        global $form, $langs;
        // for ($index = 1; $index < 4; $index++) {
        $array['RC'] = $langs->trans('RC');
        $array['TI'] = $langs->trans('TI');
        $array['CE'] = $langs->trans('CE');
        //}

        return $form->selectarray($htmlname, $array, $id, $show_empty);
    }

    public function select_sex($htmlname = 'sex', $id, $show_empty = 0) {
        global $form, $langs;
        for ($index = 1; $index < 4; $index++) {
            $array[$index] = $langs->trans('Sex' . $index);
        }

        return $form->selectarray($htmlname, $array, $id, $show_empty);
    }

    public function select_regime($htmlname = 'regime', $id, $show_empty = 1) {
        global $form, $langs;
        for ($index = 1; $index < 4; $index++) {
            $array[$index] = $langs->trans('Regime' . $index);
        }

        return $form->selectarray($htmlname, $array, $id, $show_empty);
    }

    public function select_ethnicity($htmlname = 'ethnicity', $id, $show_empty = 1) {
        global $form, $langs;
        for ($index = 1; $index < 4; $index++) {
            $array[$index] = $langs->trans('Ethnicity' . $index);
        }

        return $form->selectarray($htmlname, $array, $id, $show_empty);
    }

    public function select_victim($htmlname = 'victim', $id, $show_empty = 1) {
        global $form, $langs;
        for ($index = 1; $index < 4; $index++) {
            $array[$index] = $langs->trans('Victim' . $index);
        }

        return $form->selectarray($htmlname, $array, $id, $show_empty);
    }

    public function select_student_state($htmlname = 'state', $id, $show_empty = 1) {
        global $form, $langs;
        for ($index = 1; $index < 4; $index++) {
            $array[$index] = $langs->trans('StudentState' . $index);
        }

        return $form->selectarray($htmlname, $array, $id, $show_empty);
    }

    public function select_period($htmlname = 'period', $id, $show_empty = 1) {
        global $form, $langs;
        for ($index = 1; $index < 5; $index++) {
            $array[$index] = $langs->trans('Period' . $index);
        }

        return $form->selectarray($htmlname, $array, $id, $show_empty);
    }

    public function select_academicyear($htmlname = 'academic', $id, $show_empty) {
        global $form, $langs;
        require_once DOL_DOCUMENT_ROOT . '/educo/class/educoacadyear.class.php';
        $acadyear = new Educoacadyear($this->db);
        $acadyear->fetchAll(1);
        $array = array();
        foreach ($acadyear->lines as $a) {
            $array[$a->id] = $a->ref;
        }
        return $form->selectarray($htmlname, $array, $id, $show_empty);
    }

    public function select_enrollment_state($htmlname, $id = ' ', $show_empty = 0) {
        global $form, $langs;

        $array = array();
        for ($index = 0; $index < 6; $index++) {
            $array[$index] = $langs->trans('EnrollmentState' . $index);
        }
        return $form->selectarray($htmlname, $array, $id, $show_empty);
    }

    public function select_level($htmlname, $id = ' ', $show_empty = 0) {
        global $form, $langs;

        $array = array(
            0 => $langs->trans('Level0'),
            1 => $langs->trans('Level1'),
            2 => $langs->trans('Level2'),
            3 => $langs->trans('Level3'),
        );

        return $form->selectarray($htmlname, $array, $id, $show_empty);
    }
/**
     *      Return a string to show the box with list of available documents for object.
     *      This also set the property $this->numoffiles
     *
     *      @param      string				$modulepart         Module the files are related to ('propal', 'facture', 'facture_fourn', 'mymodule', 'mymodule_temp', ...)
     *      @param      string				$modulesubdir       Existing (so sanitized) sub-directory to scan (Example: '0/1/10', 'FA/DD/MM/YY/9999'). Use '' if file is not into subdir of module.
     *      @param      string				$filedir            Directory to scan
     *      @param      string				$urlsource          Url of origin page (for return)
     *      @param      int					$genallowed         Generation is allowed (1/0 or array list of templates)
     *      @param      int					$delallowed         Remove is allowed (1/0)
     *      @param      string				$modelselected      Model to preselect by default
     *      @param      integer				$allowgenifempty	Allow generation even if list of template ($genallowed) is empty (show however a warning)
     *      @param      integer				$forcenomultilang	Do not show language option (even if MAIN_MULTILANGS defined)
     *      @param      int					$iconPDF            Deprecated, see getDocumentsLink
     * 		@param		int					$notused	        Not used
     * 		@param		integer				$noform				Do not output html form tags
     * 		@param		string				$param				More param on http links
     * 		@param		string				$title				Title to show on top of form
     * 		@param		string				$buttonlabel		Label on submit button
     * 		@param		string				$codelang			Default language code to use on lang combo box if multilang is enabled
     * 		@param		string				$morepicto			Add more HTML content into cell with picto
     *      @param      Object              $object             Object when method is called from an object card.
     * 		@return		string              					Output string with HTML array of documents (might be empty string)
     */
    public function showdocuments($modulepart,$modulesubdir,$filedir,$urlsource,$genallowed,$delallowed=0,$modelselected='',$allowgenifempty=1,$forcenomultilang=0,$iconPDF=0,$notused=0,$noform=0,$param='',$title='',$buttonlabel='',$codelang='',$morepicto='',$object=null)
    {
		// Deprecation warning
		if (0 !== $iconPDF) {
			dol_syslog(__METHOD__ . ": passing iconPDF parameter is deprecated", LOG_WARNING);
		}

        global $langs, $conf, $user, $hookmanager;
        global $form, $bc;

        if (! is_object($form)) $form=new Form($this->db);

        include_once DOL_DOCUMENT_ROOT.'/core/lib/files.lib.php';

        // For backward compatibility
        if (! empty($iconPDF)) {
        	return $this->getDocumentsLink($modulepart, $modulesubdir, $filedir);
        }

        $printer=0;
        if (in_array($modulepart,array('facture','supplier_proposal','propal','proposal','order','commande','expedition', 'commande_fournisseur', 'expensereport')))	// The direct print feature is implemented only for such elements
        {
            $printer = (!empty($user->rights->printing->read) && !empty($conf->printing->enabled))?true:false;
        }

        $hookmanager->initHooks(array('formfile'));
        $forname='builddoc';
        $out='';

        $headershown=0;
        $showempty=0;
        $i=0;

        $out.= "\n".'<!-- Start show_document -->'."\n";
        //print 'filedir='.$filedir;

        if (preg_match('/massfilesarea_/', $modulepart))
        {
	        $out.='<div id="show_files"><br></div>'."\n";
			$title=$langs->trans("MassFilesArea").' <a href="" id="togglemassfilesarea" ref="shown">('.$langs->trans("Hide").')</a>';
			$title.='<script type="text/javascript" language="javascript">
				jQuery(document).ready(function() {
					jQuery(\'#togglemassfilesarea\').click(function() {
						if (jQuery(\'#togglemassfilesarea\').attr(\'ref\') == "shown")
						{
							jQuery(\'#'.$modulepart.'_table\').hide();
							jQuery(\'#togglemassfilesarea\').attr("ref", "hidden");
							jQuery(\'#togglemassfilesarea\').text("('.dol_escape_js($langs->trans("Show")).')");
						}
						else
						{
							jQuery(\'#'.$modulepart.'_table\').show();
							jQuery(\'#togglemassfilesarea\').attr("ref","shown");
							jQuery(\'#togglemassfilesarea\').text("('.dol_escape_js($langs->trans("Hide")).')");
						}
						return false;
					});
				});
				</script>';
        }

        $titletoshow=$langs->trans("Documents");
        if (! empty($title)) $titletoshow=$title;

        // Show table
        if ($genallowed)
        {
            $modellist=array();

            if ($modulepart == 'educo_student')
            {
                $showempty=1;
                if (is_array($genallowed)) $modellist=$genallowed;
                else
                {
                    include_once DOL_DOCUMENT_ROOT.'/educo/core/modules/educo/modules_student_doc.class.php';
                    $modellist=ModeleStudentDoc::liste_modeles($this->db);
                }
            }
          

            // Set headershown to avoit to have table opened a second time later
            $headershown=1;

            $buttonlabeltoshow=$buttonlabel;
            if (empty($buttonlabel)) $buttonlabel=$langs->trans('Generate');

            if ($conf->browser->layout == 'phone') $urlsource.='#'.$forname.'_form';   // So we switch to form after a generation
            if (empty($noform)) $out.= '<form action="'.$urlsource.(empty($conf->global->MAIN_JUMP_TAG)?'':'#builddoc').'" id="'.$forname.'_form" method="post">';
            $out.= '<input type="hidden" name="action" value="builddoc">';
            $out.= '<input type="hidden" name="token" value="'.$_SESSION['newtoken'].'">';

            $out.= load_fiche_titre($titletoshow, '', '');
            $out.= '<div class="div-table-responsive-no-min">';
            $out.= '<table class="liste formdoc noborder" summary="listofdocumentstable" width="100%">';

            $out.= '<tr class="liste_titre">';

            $addcolumforpicto=($delallowed || $printer || $morepicto);
            $out.= '<th align="center" colspan="'.(3+($addcolumforpicto?'2':'1')).'" class="formdoc liste_titre maxwidthonsmartphone">';

            // Model
            if (! empty($modellist))
            {
                $out.= '<span class="hideonsmartphone">'.$langs->trans('Model').' </span>';
                if (is_array($modellist) && count($modellist) == 1)    // If there is only one element
                {
                    $arraykeys=array_keys($modellist);
                    $modelselected=$arraykeys[0];
                }
                $out.= $form->selectarray('model', $modellist, $modelselected, $showempty, 0, 0, '', 0, 0, 0, '', 'minwidth100');
                $out.= ajax_combobox('model');
            }
            else
            {
                $out.= '<div class="float">'.$langs->trans("Files").'</div>';
            }

            // Language code (if multilang)
            if (($allowgenifempty || (is_array($modellist) && count($modellist) > 0)) && $conf->global->MAIN_MULTILANGS && ! $forcenomultilang && (! empty($modellist) || $showempty))
            {
                include_once DOL_DOCUMENT_ROOT.'/core/class/html.formadmin.class.php';
                $formadmin=new FormAdmin($this->db);
                $defaultlang=$codelang?$codelang:$langs->getDefaultLang();
                $morecss='maxwidth150';
                if (! empty($conf->browser->phone)) $morecss='maxwidth100';
                $out.= $formadmin->select_language($defaultlang, 'lang_id', 0, 0, 0, 0, 0, $morecss);
            }
            else
            {
                $out.= '&nbsp;';
            }

            // Button
            $genbutton = '<input class="button buttongen" id="'.$forname.'_generatebutton" name="'.$forname.'_generatebutton"';
            $genbutton.= ' type="submit" value="'.$buttonlabel.'"';
            if (! $allowgenifempty && ! is_array($modellist) && empty($modellist)) $genbutton.= ' disabled';
            $genbutton.= '>';
            if ($allowgenifempty && ! is_array($modellist) && empty($modellist) && empty($conf->dol_no_mouse_hover) && $modulepart != 'unpaid')
            {
               	$langs->load("errors");
               	$genbutton.= ' '.img_warning($langs->transnoentitiesnoconv("WarningNoDocumentModelActivated"));
            }
            if (! $allowgenifempty && ! is_array($modellist) && empty($modellist) && empty($conf->dol_no_mouse_hover) && $modulepart != 'unpaid') $genbutton='';
            if (empty($modellist) && ! $showempty && $modulepart != 'unpaid') $genbutton='';
            $out.= $genbutton;
            $out.= '</th>';

            if (!empty($hookmanager->hooks['formfile']))
            {
                foreach($hookmanager->hooks['formfile'] as $module)
                {
                    if (method_exists($module, 'formBuilddocLineOptions')) $out .= '<th></th>';
                }
            }
            $out.= '</tr>';

            // Execute hooks
            $parameters=array('socid'=>(isset($GLOBALS['socid'])?$GLOBALS['socid']:''),'id'=>(isset($GLOBALS['id'])?$GLOBALS['id']:''),'modulepart'=>$modulepart);
            if (is_object($hookmanager))
            {
            	$reshook = $hookmanager->executeHooks('formBuilddocOptions',$parameters,$GLOBALS['object']);
            	$out.= $hookmanager->resPrint;
            }

        }

        // Get list of files
        if (! empty($filedir))
        {
            $file_list=dol_dir_list($filedir,'files',0,'','(\.meta|_preview.*.*\.png)$','date',SORT_DESC);

            $link_list = array();
            if (is_object($object))
            {
                require_once DOL_DOCUMENT_ROOT . '/core/class/link.class.php';
                $link = new Link($this->db);
                $sortfield = $sortorder = null;
                $res = $link->fetchAll($link_list, $object->element, $object->id, $sortfield, $sortorder);
            }

            $out.= '<!-- html.formfile::showdocuments -->'."\n";

            // Show title of array if not already shown
            if ((! empty($file_list) || ! empty($link_list) || preg_match('/^massfilesarea/', $modulepart)) && ! $headershown)
            {
                $headershown=1;
                $out.= '<div class="titre">'.$titletoshow.'</div>'."\n";
                $out.= '<div class="div-table-responsive-no-min">';
                $out.= '<table class="noborder" summary="listofdocumentstable" id="'.$modulepart.'_table" width="100%">'."\n";
            }

            // Loop on each file found
			if (is_array($file_list))
			{
				foreach($file_list as $file)
				{
					// Define relative path for download link (depends on module)
					$relativepath=$file["name"];										// Cas general
                    if ($modulesubdir) $relativepath=$modulesubdir."/".$file["name"];	// Cas propal, facture...
					if ($modulepart == 'export') $relativepath = $file["name"];			// Other case

					$out.= '<tr class="oddeven">';

					$documenturl = DOL_URL_ROOT.'/document.php';
					if (isset($conf->global->DOL_URL_ROOT_DOCUMENT_PHP)) $documenturl=$conf->global->DOL_URL_ROOT_DOCUMENT_PHP;    // To use another wrapper

					// Show file name with link to download
					$out.= '<td class="tdoverflowmax300">';
                    $tmp = $this->showPreview($file,$modulepart,$relativepath,0,$param);
                    $out.= ($tmp?$tmp.' ':'');
					$out.= '<a class="documentdownload" href="'.$documenturl.'?modulepart='.$modulepart.'&amp;file='.urlencode($relativepath).($param?'&'.$param:'').'"';
					$mime=dol_mimetype($relativepath,'',0);
					if (preg_match('/text/',$mime)) $out.= ' target="_blank"';
					$out.= ' target="_blank">';
					$out.= img_mime($file["name"],$langs->trans("File").': '.$file["name"]).' '.$file["name"];
					$out.= '</a>'."\n";
					$out.= '</td>';

					// Show file size
					$size=(! empty($file['size'])?$file['size']:dol_filesize($filedir."/".$file["name"]));
					$out.= '<td align="right" class="nowrap">'.dol_print_size($size).'</td>';

					// Show file date
					$date=(! empty($file['date'])?$file['date']:dol_filemtime($filedir."/".$file["name"]));
					$out.= '<td align="right" class="nowrap">'.dol_print_date($date, 'dayhour', 'tzuser').'</td>';

					if ($delallowed || $printer || $morepicto)
					{
						$out.= '<td align="right">';
						if ($delallowed)
						{
							$out.= '<a href="'.$urlsource.(strpos($urlsource,'?')?'&amp;':'?').'action=remove_file&amp;file='.urlencode($relativepath);
							$out.= ($param?'&amp;'.$param:'');
							//$out.= '&modulepart='.$modulepart; // TODO obsolete ?
							//$out.= '&urlsource='.urlencode($urlsource); // TODO obsolete ?
							$out.= '">'.img_picto($langs->trans("Delete"), 'delete.png').'</a>';
							//$out.='</td>';
						}
						if ($printer)
						{
							//$out.= '<td align="right">';
                            $out.= '&nbsp;<a href="'.$urlsource.(strpos($urlsource,'?')?'&amp;':'?').'action=print_file&amp;printer='.$modulepart.'&amp;file='.urlencode($relativepath);
                            $out.= ($param?'&amp;'.$param:'');
                            $out.= '">'.img_picto($langs->trans("PrintFile", $relativepath),'printer.png').'</a>';
						}
						if ($morepicto)
						{
							$morepicto=preg_replace('/__FILENAMEURLENCODED__/',urlencode($relativepath),$morepicto);
                        	$out.=$morepicto;
						}
                        $out.='</td>';
                    }

                    if (is_object($hookmanager))
                    {
            			$parameters=array('socid'=>(isset($GLOBALS['socid'])?$GLOBALS['socid']:''),'id'=>(isset($GLOBALS['id'])?$GLOBALS['id']:''),'modulepart'=>$modulepart,'relativepath'=>$relativepath);
                    	$res = $hookmanager->executeHooks('formBuilddocLineOptions',$parameters,$file);
                        if (empty($res))
                        {
                            $out .= $hookmanager->resPrint;		// Complete line
                            $out.= '</tr>';
                        }
                        else $out = $hookmanager->resPrint;		// Replace line
              		}
				}

                $this->numoffiles++;
            }
            // Loop on each file found
            if (is_array($link_list))
            {
                $colspan=2;

                foreach($link_list as $file)
                {
                    $out.='<tr class="oddeven">';
                    $out.='<td colspan="'.$colspan.'" class="maxwidhtonsmartphone">';
                    $out.='<a data-ajax="false" href="' . $link->url . '" target="_blank">';
                    $out.=$file->label;
                    $out.='</a>';
                    $out.='</td>';
                    $out.='<td align="right">';
                    $out.=dol_print_date($file->datea,'dayhour');
                    $out.='</td>';
                    if ($delallowed || $printer || $morepicto) $out.='<td></td>';
                    $out.='</tr>'."\n";
                }
                $this->numoffiles++;
            }

		 	if (count($file_list) == 0 && count($link_list) == 0 && $headershown)
            {
	        	$out.='<tr class="oddeven"><td colspan="3" class="opacitymedium">'.$langs->trans("None").'</td></tr>'."\n";
    	    }

        }

        if ($headershown)
        {
            // Affiche pied du tableau
            $out.= "</table>\n";
            $out.= "</div>\n";
            if ($genallowed)
            {
                if (empty($noform)) $out.= '</form>'."\n";
            }
        }
        $out.= '<!-- End show_document -->'."\n";
        //return ($i?$i:$headershown);
        return $out;
    }
}

