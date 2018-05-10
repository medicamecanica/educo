<?php
/* Copyright (C) 2007-2012  Laurent Destailleur <eldy@users.sourceforge.net>
 * Copyright (C) 2014-2016  Juanjo Menent       <jmenent@2byte.es>
 * Copyright (C) 2015       Florian Henry       <florian.henry@open-concept.pro>
 * Copyright (C) 2015       Raphaël Doursenaud  <rdoursenaud@gpcsolutions.fr>
 * Copyright (C) ---Put here your own copyright and developer email---
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
 * \file    educo/educogroup.class.php
 * \ingroup educo
 * \brief   This file is an example for a CRUD class file (Create/Read/Update/Delete)
 *          Put some comments here
 */

// Put here all includes required by your class file
require_once DOL_DOCUMENT_ROOT . '/core/class/commonobject.class.php';
//require_once DOL_DOCUMENT_ROOT . '/societe/class/societe.class.php';
//require_once DOL_DOCUMENT_ROOT . '/product/class/product.class.php';

/**
 * Class Educogroup
 *
 * Put here description of your class
 *
 * @see CommonObject
 */
class Educogroup extends CommonObject
{
	/**
	 * @var string Id to identify managed objects
	 */
	public $element = 'educogroup';
	/**
	 * @var string Name of table without prefix where object is stored
	 */
	public $table_element = 'educo_group';

	/**
	 * @var EducogroupLine[] Lines
	 */
	public $lines = array();

	/**
	 */
	
	public $ref;
	public $sufix;
	public $label;
	public $fk_academicyear;
	public $grado_code;
	public $tms = '';
	public $date_create = '';
	public $statut;
	public $import_key;
	public $entity;
	public $workingday;

	/**
	 */
	

	/**
	 * Constructor
	 *
	 * @param DoliDb $db Database handler
	 */
	public function __construct(DoliDB $db)
	{
		$this->db = $db;
	}

	/**
	 * Create object into database
	 *
	 * @param  User $user      User that creates
	 * @param  bool $notrigger false=launch triggers after, true=disable triggers
	 *
	 * @return int <0 if KO, Id of created object if OK
	 */
	public function create(User $user, $notrigger = false)
	{
		dol_syslog(__METHOD__, LOG_DEBUG);

		$error = 0;

		// Clean parameters
		
		if (isset($this->ref)) {
			 $this->ref = trim($this->ref);
		}
		if (isset($this->sufix)) {
			 $this->sufix = trim($this->sufix);
		}
		if (isset($this->label)) {
			 $this->label = trim($this->label);
		}
		if (isset($this->fk_academicyear)) {
			 $this->fk_academicyear = trim($this->fk_academicyear);
		}
		if (isset($this->grado_code)) {
			 $this->grado_code = trim($this->grado_code);
		}
		if (isset($this->statut)) {
			 $this->statut = trim($this->statut);
		}
		if (isset($this->import_key)) {
			 $this->import_key = trim($this->import_key);
		}
		if (isset($this->entity)) {
			 $this->entity = trim($this->entity);
		}
		if (isset($this->workingday)) {
			 $this->workingday = trim($this->workingday);
		}

		

		// Check parameters
		// Put here code to add control on parameters values

		// Insert request
		$sql = 'INSERT INTO ' . MAIN_DB_PREFIX . $this->table_element . '(';
		
		$sql.= 'ref,';
		$sql.= 'sufix,';
		$sql.= 'label,';
		$sql.= 'fk_academicyear,';
		$sql.= 'grado_code,';
		$sql.= 'date_create,';
		$sql.= 'statut,';
		$sql.= 'import_key,';
		$sql.= 'entity,';
		$sql.= 'workingday';

		
		$sql .= ') VALUES (';
		
		$sql .= ' '.(! isset($this->ref)?'NULL':"'".$this->db->escape($this->ref)."'").',';
		$sql .= ' '.(! isset($this->sufix)?'NULL':"'".$this->db->escape($this->sufix)."'").',';
		$sql .= ' '.(! isset($this->label)?'NULL':"'".$this->db->escape($this->label)."'").',';
		$sql .= ' '.(! isset($this->fk_academicyear)?'NULL':$this->fk_academicyear).',';
		$sql .= ' '.(! isset($this->grado_code)?'NULL':"'".$this->db->escape($this->grado_code)."'").',';
		$sql .= ' '.(! isset($this->date_create) || dol_strlen($this->date_create)==0?'NULL':"'".$this->db->idate($this->date_create)."'").',';
		$sql .= ' '.(! isset($this->statut)?'NULL':$this->statut).',';
		$sql .= ' '.(! isset($this->import_key)?'NULL':"'".$this->db->escape($this->import_key)."'").',';
		$sql .= ' '.(! isset($this->entity)?'NULL':$this->entity).',';
		$sql .= ' '.(! isset($this->workingday)?'NULL':$this->workingday);

		
		$sql .= ')';

		$this->db->begin();

		$resql = $this->db->query($sql);
		if (!$resql) {
			$error ++;
			$this->errors[] = 'Error ' . $this->db->lasterror();
			dol_syslog(__METHOD__ . ' ' . implode(',', $this->errors), LOG_ERR);
		}

		if (!$error) {
			$this->id = $this->db->last_insert_id(MAIN_DB_PREFIX . $this->table_element);

			if (!$notrigger) {
				// Uncomment this and change MYOBJECT to your own tag if you
				// want this action to call a trigger.

				//// Call triggers
				//$result=$this->call_trigger('MYOBJECT_CREATE',$user);
				//if ($result < 0) $error++;
				//// End call triggers
			}
		}

		// Commit or rollback
		if ($error) {
			$this->db->rollback();

			return - 1 * $error;
		} else {
			$this->db->commit();

			return $this->id;
		}
	}

	/**
	 * Load object in memory from the database
	 *
	 * @param int    $id  Id object
	 * @param string $ref Ref
	 *
	 * @return int <0 if KO, 0 if not found, >0 if OK
	 */
	public function fetch($id, $ref = null)
	{
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
		$sql .= " t.import_key,";
		$sql .= " t.entity,";
		$sql .= " t.workingday";

		
		$sql .= ' FROM ' . MAIN_DB_PREFIX . $this->table_element . ' as t';
		$sql.= ' WHERE 1 = 1';
		if (! empty($conf->multicompany->enabled)) {
		    $sql .= " AND entity IN (" . getEntity("educogroup", 1) . ")";
		}
		if (null !== $ref) {
			$sql .= ' AND t.ref = ' . '\'' . $ref . '\'';
		} else {
			$sql .= ' AND t.rowid = ' . $id;
		}

		$resql = $this->db->query($sql);
		if ($resql) {
			$numrows = $this->db->num_rows($resql);
			if ($numrows) {
				$obj = $this->db->fetch_object($resql);

				$this->id = $obj->rowid;
				
				$this->ref = $obj->ref;
				$this->sufix = $obj->sufix;
				$this->label = $obj->label;
				$this->fk_academicyear = $obj->fk_academicyear;
				$this->grado_code = $obj->grado_code;
				$this->tms = $this->db->jdate($obj->tms);
				$this->date_create = $this->db->jdate($obj->date_create);
				$this->statut = $obj->statut;
				$this->import_key = $obj->import_key;
				$this->entity = $obj->entity;
				$this->workingday = $obj->workingday;

				
			}
			
			// Retrieve all extrafields for invoice
			// fetch optionals attributes and labels
			require_once DOL_DOCUMENT_ROOT.'/core/class/extrafields.class.php';
			$extrafields=new ExtraFields($this->db);
			$extralabels=$extrafields->fetch_name_optionals_label($this->table_element,true);
			$this->fetch_optionals($this->id,$extralabels);

			// $this->fetch_lines();
			
			$this->db->free($resql);

			if ($numrows) {
				return 1;
			} else {
				return 0;
			}
		} else {
			$this->errors[] = 'Error ' . $this->db->lasterror();
			dol_syslog(__METHOD__ . ' ' . implode(',', $this->errors), LOG_ERR);

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
	public function fetchStudents($pensumid='',$sortorder='', $sortfield='', $limit=0, $offset=0)
	{
		dol_syslog(__METHOD__, LOG_DEBUG);

		$sql = 'SELECT';
		$sql .= ' t.rowid,';
		
		$sql .= " t.ref,";
		$sql .= " t.name,";
		$sql .= " t.firstname,";
		$sql .= " t.lastname,";
		$sql .= " t.doc_type,";
		$sql .= " t.document,";
		$sql .= " t.entity,";
		$sql .= " t.fk_contact,";
		$sql .= " t.date_create,";
		$sql .= " t.fk_soc,";
		$sql .= " t.tms,";
		$sql .= " t.status,";
		$sql .= " t.import_key,";
		$sql .= " t.grade_max,";
		$sql .= " t.photo,";
		$sql .= " t.sex,";
		$sql .= " t.neighborhood,";
		$sql .= " t.blod_type,";
		$sql .= " t.stratum,";
		$sql .= " t.sisben,";
		$sql .= " t.regime,";
		$sql .= " t.eps,";
		$sql .= " t.ethnicity,";
		$sql .= " t.cc";
                if(!empty($pensumid)){
                    for ($p = 1; $p< 5; $p++) {
                      $sql .= ",(SELECT ";    
                        $sql .= " sum(qe.status*qe.value/100) as p".$p;
                        $sql .= ' FROM ' . MAIN_DB_PREFIX . 'educo_qualif_student as qe ';
                        $sql .= ' INNER JOIN ' . MAIN_DB_PREFIX . 'educo_qualification q ';
                        $sql .= ' ON q.rowid=qe.fk_qualification ';
                        $sql .= ' AND q.fk_group='.$this->id;
                        $sql .= ' AND q.fk_pensum='.$pensumid;
                        $sql.='   WHERE t.rowid=qe.fk_student AND q.period='.$p;
                        $sql .= ") as p".$p;      
                    }   
                }
		
		$sql .= ' FROM ' . MAIN_DB_PREFIX . 'educo_student as t';
                $sql .= ' INNER JOIN  ' . MAIN_DB_PREFIX . 'educo_group_student e ON e.fk_estudiante= t.rowid';

		
		$sql.= ' WHERE e.fk_grupo ='.$this->id;
		if (! empty($conf->multicompany->enabled)) {
		    $sql .= " AND entity IN (" . getEntity("educostudent", 1) . ")";
		}
		
		if (!empty($sortfield)) {
			$sql .= $this->db->order($sortfield,$sortorder);
		}
		if (!empty($limit)) {
		 $sql .=  ' ' . $this->db->plimit($limit + 1, $offset);
		}

		$this->lines = array();
               // var_dump($sql);
		$resql = $this->db->query($sql);
		if ($resql) {
			$num = $this->db->num_rows($resql);

			while ($obj = $this->db->fetch_object($resql)) {
				$line = new EducostudentLine();

				$line->id = $obj->rowid;
				
				$line->ref = $obj->ref;
				$line->name = $obj->name;
				$line->firstname = $obj->firstname;
				$line->lastname = $obj->lastname;
				$line->doc_type = $obj->doc_type;
				$line->document = $obj->document;
				$line->entity = $obj->entity;
				$line->fk_contact = $obj->fk_contact;
				$line->date_create = $this->db->jdate($obj->date_create);
				$line->fk_soc = $obj->fk_soc;
				$line->tms = $this->db->jdate($obj->tms);
				$line->status = $obj->status;
				$line->import_key = $obj->import_key;
				$line->grade_max = $obj->grade_max;
				$line->photo = $obj->photo;
				$line->sex = $obj->sex;
				$line->neighborhood = $obj->neighborhood;
				$line->blod_type = $obj->blod_type;
				$line->stratum = $obj->stratum;
				$line->sisben = $obj->sisben;
				$line->regime = $obj->regime;
				$line->eps = $obj->eps;
				$line->ethnicity = $obj->ethnicity;
				$line->cc = $obj->cc;
                                
                                $line->p1 = $obj->p1;
                                $line->p2 = $obj->p2;
                                $line->p3 = $obj->p3;
                                $line->p4 = $obj->p4;

				

				$this->lines[$line->id] = $line;
			}
			$this->db->free($resql);

			return $num;
		} else {
			$this->errors[] = 'Error ' . $this->db->lasterror();
			dol_syslog(__METHOD__ . ' ' . implode(',', $this->errors), LOG_ERR);

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
	public function fetchAll($sortorder='', $sortfield='', $limit=0, $offset=0, array $filter = array(), $filtermode='AND')
	{
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
		$sql .= " t.import_key,";
		$sql .= " t.entity,";
		$sql .= " t.workingday";

		
		$sql .= ' FROM ' . MAIN_DB_PREFIX . $this->table_element. ' as t';

		// Manage filter
		$sqlwhere = array();
		if (count($filter) > 0) {
			foreach ($filter as $key => $value) {
				$sqlwhere [] = $key . ' LIKE \'%' . $this->db->escape($value) . '%\'';
			}
		}
		$sql.= ' WHERE 1 = 1';
		if (! empty($conf->multicompany->enabled)) {
		    $sql .= " AND entity IN (" . getEntity("educogroup", 1) . ")";
		}
		if (count($sqlwhere) > 0) {
			$sql .= ' AND ' . implode(' '.$filtermode.' ', $sqlwhere);
		}
		if (!empty($sortfield)) {
			$sql .= $this->db->order($sortfield,$sortorder);
		}
		if (!empty($limit)) {
		 $sql .=  ' ' . $this->db->plimit($limit + 1, $offset);
		}

		$this->lines = array();

		$resql = $this->db->query($sql);
		if ($resql) {
			$num = $this->db->num_rows($resql);

			while ($obj = $this->db->fetch_object($resql)) {
				$line = new EducogroupLine();

				$line->id = $obj->rowid;
				
				$line->ref = $obj->ref;
				$line->sufix = $obj->sufix;
				$line->label = $obj->label;
				$line->fk_academicyear = $obj->fk_academicyear;
				$line->grado_code = $obj->grado_code;
				$line->tms = $this->db->jdate($obj->tms);
				$line->date_create = $this->db->jdate($obj->date_create);
				$line->statut = $obj->statut;
				$line->import_key = $obj->import_key;
				$line->entity = $obj->entity;
				$line->workingday = $obj->workingday;

				

				$this->lines[$line->id] = $line;
			}
			$this->db->free($resql);

			return $num;
		} else {
			$this->errors[] = 'Error ' . $this->db->lasterror();
			dol_syslog(__METHOD__ . ' ' . implode(',', $this->errors), LOG_ERR);

			return - 1;
		}
	}

	/**
	 * Update object into database
	 *
	 * @param  User $user      User that modifies
	 * @param  bool $notrigger false=launch triggers after, true=disable triggers
	 *
	 * @return int <0 if KO, >0 if OK
	 */
	public function update(User $user, $notrigger = false)
	{
		$error = 0;

		dol_syslog(__METHOD__, LOG_DEBUG);

		// Clean parameters
		
		if (isset($this->ref)) {
			 $this->ref = trim($this->ref);
		}
		if (isset($this->sufix)) {
			 $this->sufix = trim($this->sufix);
		}
		if (isset($this->label)) {
			 $this->label = trim($this->label);
		}
		if (isset($this->fk_academicyear)) {
			 $this->fk_academicyear = trim($this->fk_academicyear);
		}
		if (isset($this->grado_code)) {
			 $this->grado_code = trim($this->grado_code);
		}
		if (isset($this->statut)) {
			 $this->statut = trim($this->statut);
		}
		if (isset($this->import_key)) {
			 $this->import_key = trim($this->import_key);
		}
		if (isset($this->entity)) {
			 $this->entity = trim($this->entity);
		}
		if (isset($this->workingday)) {
			 $this->workingday = trim($this->workingday);
		}

		

		// Check parameters
		// Put here code to add a control on parameters values

		// Update request
		$sql = 'UPDATE ' . MAIN_DB_PREFIX . $this->table_element . ' SET';
		
		$sql .= ' ref = '.(isset($this->ref)?"'".$this->db->escape($this->ref)."'":"null").',';
		$sql .= ' sufix = '.(isset($this->sufix)?"'".$this->db->escape($this->sufix)."'":"null").',';
		$sql .= ' label = '.(isset($this->label)?"'".$this->db->escape($this->label)."'":"null").',';
		$sql .= ' fk_academicyear = '.(isset($this->fk_academicyear)?$this->fk_academicyear:"null").',';
		$sql .= ' grado_code = '.(isset($this->grado_code)?"'".$this->db->escape($this->grado_code)."'":"null").',';
		$sql .= ' tms = '.(dol_strlen($this->tms) != 0 ? "'".$this->db->idate($this->tms)."'" : "'".$this->db->idate(dol_now())."'").',';
		$sql .= ' date_create = '.(! isset($this->date_create) || dol_strlen($this->date_create) != 0 ? "'".$this->db->idate($this->date_create)."'" : 'null').',';
		$sql .= ' statut = '.(isset($this->statut)?$this->statut:"null").',';
		$sql .= ' import_key = '.(isset($this->import_key)?"'".$this->db->escape($this->import_key)."'":"null").',';
		$sql .= ' entity = '.(isset($this->entity)?$this->entity:"null").',';
		$sql .= ' workingday = '.(isset($this->workingday)?$this->workingday:"null");

        
		$sql .= ' WHERE rowid=' . $this->id;

		$this->db->begin();

		$resql = $this->db->query($sql);
		if (!$resql) {
			$error ++;
			$this->errors[] = 'Error ' . $this->db->lasterror();
			dol_syslog(__METHOD__ . ' ' . implode(',', $this->errors), LOG_ERR);
		}

		if (!$error && !$notrigger) {
			// Uncomment this and change MYOBJECT to your own tag if you
			// want this action calls a trigger.

			//// Call triggers
			//$result=$this->call_trigger('MYOBJECT_MODIFY',$user);
			//if ($result < 0) { $error++; //Do also what you must do to rollback action if trigger fail}
			//// End call triggers
		}

		// Commit or rollback
		if ($error) {
			$this->db->rollback();

			return - 1 * $error;
		} else {
			$this->db->commit();

			return 1;
		}
	}

	/**
	 * Delete object in database
	 *
	 * @param User $user      User that deletes
	 * @param bool $notrigger false=launch triggers after, true=disable triggers
	 *
	 * @return int <0 if KO, >0 if OK
	 */
	public function delete(User $user, $notrigger = false)
	{
		dol_syslog(__METHOD__, LOG_DEBUG);

		$error = 0;

		$this->db->begin();

		if (!$error) {
			if (!$notrigger) {
				// Uncomment this and change MYOBJECT to your own tag if you
				// want this action calls a trigger.

				//// Call triggers
				//$result=$this->call_trigger('MYOBJECT_DELETE',$user);
				//if ($result < 0) { $error++; //Do also what you must do to rollback action if trigger fail}
				//// End call triggers
			}
		}

		// If you need to delete child tables to, you can insert them here
		
		if (!$error) {
			$sql = 'DELETE FROM ' . MAIN_DB_PREFIX . $this->table_element;
			$sql .= ' WHERE rowid=' . $this->id;

			$resql = $this->db->query($sql);
			if (!$resql) {
				$error ++;
				$this->errors[] = 'Error ' . $this->db->lasterror();
				dol_syslog(__METHOD__ . ' ' . implode(',', $this->errors), LOG_ERR);
			}
		}

		// Commit or rollback
		if ($error) {
			$this->db->rollback();

			return - 1 * $error;
		} else {
			$this->db->commit();

			return 1;
		}
	}

	/**
	 * Load an object from its id and create a new one in database
	 *
	 * @param int $fromid Id of object to clone
	 *
	 * @return int New id of clone
	 */
	public function createFromClone($fromid)
	{
		dol_syslog(__METHOD__, LOG_DEBUG);

		global $user;
		$error = 0;
		$object = new Educogroup($this->db);

		$this->db->begin();

		// Load source object
		$object->fetch($fromid);
		// Reset object
		$object->id = 0;

		// Clear fields
		// ...

		// Create clone
		$result = $object->create($user);

		// Other options
		if ($result < 0) {
			$error ++;
			$this->errors = $object->errors;
			dol_syslog(__METHOD__ . ' ' . implode(',', $this->errors), LOG_ERR);
		}

		// End
		if (!$error) {
			$this->db->commit();

			return $object->id;
		} else {
			$this->db->rollback();

			return - 1;
		}
	}

	/**
	 *  Return a link to the object card (with optionaly the picto)
	 *
	 *	@param	int		$withpicto			Include picto in link (0=No picto, 1=Include picto into link, 2=Only picto)
	 *	@param	string	$option				On what the link point to
     *  @param	int  	$notooltip			1=Disable tooltip
     *  @param	int		$maxlen				Max length of visible user name
     *  @param  string  $morecss            Add more css on link
	 *	@return	string						String with URL
	 */
	function getNomUrl($withpicto=0, $option='', $notooltip=0, $maxlen=24, $morecss='')
	{
		global $db, $conf, $langs;
        global $dolibarr_main_authentication, $dolibarr_main_demo;
        global $menumanager;

        if (! empty($conf->dol_no_mouse_hover)) $notooltip=1;   // Force disable tooltips
        
        $result = '';
        $companylink = '';

        $label = '<u>' . $langs->trans("MyModule") . '</u>';
        $label.= '<br>';
        $label.= '<b>' . $langs->trans('Ref') . ':</b> ' . $this->ref;

        $url = DOL_URL_ROOT.'/educo/'.$this->table_name.'_card.php?id='.$this->id;
        
        $linkclose='';
        if (empty($notooltip))
        {
            if (! empty($conf->global->MAIN_OPTIMIZEFORTEXTBROWSER))
            {
                $label=$langs->trans("ShowProject");
                $linkclose.=' alt="'.dol_escape_htmltag($label, 1).'"';
            }
            $linkclose.=' title="'.dol_escape_htmltag($label, 1).'"';
            $linkclose.=' class="classfortooltip'.($morecss?' '.$morecss:'').'"';
        }
        else $linkclose = ($morecss?' class="'.$morecss.'"':'');
        
		$linkstart = '<a href="'.$url.'"';
		$linkstart.=$linkclose.'>';
		$linkend='</a>';

        if ($withpicto)
        {
            $result.=($linkstart.img_object(($notooltip?'':$label), 'group@educo', ($notooltip?'':'class="classfortooltip"')).$linkend);
            if ($withpicto != 2) $result.=' ';
		}
		$result.= $linkstart . $this->label . $linkend;
		return $result;
	}

	/**
	 *  Retourne le libelle du status d'un user (actif, inactif)
	 *
	 *  @param	int		$mode          0=libelle long, 1=libelle court, 2=Picto + Libelle court, 3=Picto, 4=Picto + Libelle long, 5=Libelle court + Picto
	 *  @return	string 			       Label of status
	 */
	function getLibStatut($mode=0)
	{
		return $this->LibStatut($this->status,$mode);
	}

	/**
	 *  Return the status
	 *
	 *  @param	int		$status        	Id status
	 *  @param  int		$mode          	0=long label, 1=short label, 2=Picto + short label, 3=Picto, 4=Picto + long label, 5=Short label + Picto, 5=Long label + Picto
	 *  @return string 			       	Label of status
	 */
	static function LibStatut($status,$mode=0)
	{
		global $langs;

		if ($mode == 0)
		{
			$prefix='';
			if ($status == 1) return $langs->trans('Enabled');
			if ($status == 0) return $langs->trans('Disabled');
		}
		if ($mode == 1)
		{
			if ($status == 1) return $langs->trans('Enabled');
			if ($status == 0) return $langs->trans('Disabled');
		}
		if ($mode == 2)
		{
			if ($status == 1) return img_picto($langs->trans('Enabled'),'statut4').' '.$langs->trans('Enabled');
			if ($status == 0) return img_picto($langs->trans('Disabled'),'statut5').' '.$langs->trans('Disabled');
		}
		if ($mode == 3)
		{
			if ($status == 1) return img_picto($langs->trans('Enabled'),'statut4');
			if ($status == 0) return img_picto($langs->trans('Disabled'),'statut5');
		}
		if ($mode == 4)
		{
			if ($status == 1) return img_picto($langs->trans('Enabled'),'statut4').' '.$langs->trans('Enabled');
			if ($status == 0) return img_picto($langs->trans('Disabled'),'statut5').' '.$langs->trans('Disabled');
		}
		if ($mode == 5)
		{
			if ($status == 1) return $langs->trans('Enabled').' '.img_picto($langs->trans('Enabled'),'statut4');
			if ($status == 0) return $langs->trans('Disabled').' '.img_picto($langs->trans('Disabled'),'statut5');
		}
		if ($mode == 6)
		{
			if ($status == 1) return $langs->trans('Enabled').' '.img_picto($langs->trans('Enabled'),'statut4');
			if ($status == 0) return $langs->trans('Disabled').' '.img_picto($langs->trans('Disabled'),'statut5');
		}
	}


	/**
	 * Initialise object with example values
	 * Id must be 0 if object instance is a specimen
	 *
	 * @return void
	 */
	public function initAsSpecimen()
	{
		$this->id = 0;
		
		$this->ref = '';
		$this->sufix = '';
		$this->label = '';
		$this->fk_academicyear = '';
		$this->grado_code = '';
		$this->tms = '';
		$this->date_create = '';
		$this->statut = '';
		$this->import_key = '';
		$this->entity = '';
		$this->workingday = '';

		
	}

}

/**
 * Class EducogroupLine
 */
class EducogroupLine
{
	/**
	 * @var int ID
	 */
	public $id;
	/**
	 * @var mixed Sample line property 1
	 */
	
	public $ref;
	public $sufix;
	public $label;
	public $fk_academicyear;
	public $grado_code;
	public $tms = '';
	public $date_create = '';
	public $statut;
	public $import_key;
	public $entity;
	public $workingday;

	/**
	 * @var mixed Sample line property 2
	 */
	
}
