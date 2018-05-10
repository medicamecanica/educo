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
 * \file    educo/educoqualification.class.php
 * \ingroup educo
 * \brief   This file is an example for a CRUD class file (Create/Read/Update/Delete)
 *          Put some comments here
 */

// Put here all includes required by your class file
require_once DOL_DOCUMENT_ROOT . '/core/class/commonobject.class.php';
//require_once DOL_DOCUMENT_ROOT . '/societe/class/societe.class.php';
//require_once DOL_DOCUMENT_ROOT . '/product/class/product.class.php';

/**
 * Class Educoqualification
 *
 * Put here description of your class
 *
 * @see CommonObject
 */
class Educoqualification extends CommonObject
{
	/**
	 * @var string Id to identify managed objects
	 */
	public $element = 'educoqualification';
	/**
	 * @var string Name of table without prefix where object is stored
	 */
	public $table_element = 'educo_qualification';

	/**
	 * @var EducoqualificationLine[] Lines
	 */
	public $lines = array();

	/**
	 */
	
	public $ref;
	public $label;
	public $datec;
	public $tms;
	public $note_private;
	public $note_public;
	public $value;
	public $period;
	public $status;
	public $import_key;
	public $entity;
	public $fk_group;
	public $fk_pensum;
	public $fk_user;
	public $fk_rating;

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
		if (isset($this->label)) {
			 $this->label = trim($this->label);
		}
		if (isset($this->datec)) {
			 $this->datec = trim($this->datec);
		}
		if (isset($this->tms)) {
			 $this->tms = trim($this->tms);
		}
		if (isset($this->note_private)) {
			 $this->note_private = trim($this->note_private);
		}
		if (isset($this->note_public)) {
			 $this->note_public = trim($this->note_public);
		}
		if (isset($this->value)) {
			 $this->value = trim($this->value);
		}
		if (isset($this->period)) {
			 $this->period = trim($this->period);
		}
		if (isset($this->status)) {
			 $this->status = trim($this->status);
		}
		if (isset($this->import_key)) {
			 $this->import_key = trim($this->import_key);
		}
		if (isset($this->entity)) {
			 $this->entity = trim($this->entity);
		}
		if (isset($this->fk_group)) {
			 $this->fk_group = trim($this->fk_group);
		}
		if (isset($this->fk_pensum)) {
			 $this->fk_pensum = trim($this->fk_pensum);
		}
		if (isset($this->fk_user)) {
			 $this->fk_user = trim($this->fk_user);
		}
		if (isset($this->fk_rating)) {
			 $this->fk_rating = trim($this->fk_rating);
		}

		

		// Check parameters
		// Put here code to add control on parameters values

		// Insert request
		$sql = 'INSERT INTO ' . MAIN_DB_PREFIX . $this->table_element . '(';
		
		$sql.= 'ref,';
		$sql.= 'label,';
		$sql.= 'datec,';
		$sql.= 'note_private,';
		$sql.= 'note_public,';
		$sql.= 'value,';
		$sql.= 'period,';
		$sql.= 'status,';
		$sql.= 'import_key,';
		$sql.= 'entity,';
		$sql.= 'fk_group,';
		$sql.= 'fk_pensum,';
		$sql.= 'fk_user,';
		$sql.= 'fk_rating';

		
		$sql .= ') VALUES (';
		
		$sql .= ' '.(! isset($this->ref)?'NULL':"'".$this->db->escape($this->ref)."'").',';
		$sql .= ' '.(! isset($this->label)?'NULL':"'".$this->db->escape($this->label)."'").',';
		$sql .= ' '."'".$this->db->idate(dol_now())."'".',';
		$sql .= ' '.(! isset($this->note_private)?'NULL':"'".$this->db->escape($this->note_private)."'").',';
		$sql .= ' '.(! isset($this->note_public)?'NULL':"'".$this->db->escape($this->note_public)."'").',';
		$sql .= ' '.(! isset($this->value)?'NULL':"'".$this->value."'").',';
		$sql .= ' '.(! isset($this->period)?'NULL':$this->period).',';
		$sql .= ' '.(! isset($this->status)?'NULL':$this->status).',';
		$sql .= ' '.(! isset($this->import_key)?'NULL':"'".$this->db->escape($this->import_key)."'").',';
		$sql .= ' '.(! isset($this->entity)?'NULL':"'".$this->db->escape($this->entity)."'").',';
		$sql .= ' '.(! isset($this->fk_group)?'NULL':$this->fk_group).',';
		$sql .= ' '.(! isset($this->fk_pensum)?'NULL':$this->fk_pensum).',';
		$sql .= ' '.(! isset($this->fk_user)?'NULL':$this->fk_user).',';
		$sql .= ' '.(! isset($this->fk_rating)?'NULL':"'".$this->db->escape($this->fk_rating)."'");

		
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
		$sql .= " t.label,";
		$sql .= " t.datec,";
		$sql .= " t.tms,";
		$sql .= " t.note_private,";
		$sql .= " t.note_public,";
		$sql .= " t.value,";
		$sql .= " t.period,";
		$sql .= " t.status,";
		$sql .= " t.import_key,";
		$sql .= " t.entity,";
		$sql .= " t.fk_group,";
		$sql .= " t.fk_pensum,";
		$sql .= " t.fk_user,";
		$sql .= " t.fk_rating";

		
		$sql .= ' FROM ' . MAIN_DB_PREFIX . $this->table_element . ' as t';
		$sql.= ' WHERE 1 = 1';
		if (! empty($conf->multicompany->enabled)) {
		    $sql .= " AND entity IN (" . getEntity("educoqualification", 1) . ")";
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
				$this->label = $obj->label;
				$this->datec = $obj->datec;
				$this->tms = $obj->tms;
				$this->note_private = $obj->note_private;
				$this->note_public = $obj->note_public;
				$this->value = $obj->value;
				$this->period = $obj->period;
				$this->status = $obj->status;
				$this->import_key = $obj->import_key;
				$this->entity = $obj->entity;
				$this->fk_group = $obj->fk_group;
				$this->fk_pensum = $obj->fk_pensum;
				$this->fk_user = $obj->fk_user;
				$this->fk_rating = $obj->fk_rating;

				
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
	public function fetchAll($sortorder='', $sortfield='', $limit=0, $offset=0, array $filter = array(), $filtermode='AND')
	{
		dol_syslog(__METHOD__, LOG_DEBUG);

		$sql = 'SELECT';
		$sql .= ' t.rowid,';
		
		$sql .= " t.ref,";
		$sql .= " t.label,";
		$sql .= " t.datec,";
		$sql .= " t.tms,";
		$sql .= " t.note_private,";
		$sql .= " t.note_public,";
		$sql .= " t.value,";
		$sql .= " t.period,";
		$sql .= " t.status,";
		$sql .= " t.import_key,";
		$sql .= " t.entity,";
		$sql .= " t.fk_group,";
		$sql .= " t.fk_pensum,";
		$sql .= " t.fk_user,";
		$sql .= " t.fk_rating";

		
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
		    $sql .= " AND entity IN (" . getEntity("educoqualification", 1) . ")";
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
				$line = new EducoqualificationLine();

				$line->id = $obj->rowid;
				
				$line->ref = $obj->ref;
				$line->label = $obj->label;
				$line->datec = $obj->datec;
				$line->tms = $obj->tms;
				$line->note_private = $obj->note_private;
				$line->note_public = $obj->note_public;
				$line->value = $obj->value;
				$line->period = $obj->period;
				$line->status = $obj->status;
				$line->import_key = $obj->import_key;
				$line->entity = $obj->entity;
				$line->fk_group = $obj->fk_group;
				$line->fk_pensum = $obj->fk_pensum;
				$line->fk_user = $obj->fk_user;
				$line->fk_rating = $obj->fk_rating;

				

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
	public function fetchStudentQualification($qualificationid,$sortorder='', $sortfield='', $limit=0, $offset=0)
	{
            global $conf;
		dol_syslog(__METHOD__, LOG_DEBUG);
                $educoqualifstudent = new Educoqualifstudent($this->db);
                
		$sql = 'SELECT';
		$sql .= ' t.rowid,';
		
		$sql .= " t.ref,";
		$sql .= " t.label,";
		$sql .= " t.value,";
		$sql .= " t.status,";
		$sql .= " t.note_private,";
		$sql .= " t.note_public,";
		$sql .= " t.datec,";
		$sql .= " t.tms,";
		$sql .= " t.import_key,";
		$sql .= " t.entity,";
		$sql .= " t.fk_qualification,";
		$sql .= " t.fk_student";

		
		$sql .= ' FROM ' . MAIN_DB_PREFIX . $educoqualifstudent->table_element. ' as t';

		
		$sql.= ' WHERE fk_qualification='.$qualificationid;
		if (! empty($conf->multicompany->enabled)) {
		    $sql .= " AND entity IN (" . getEntity("educoqualifstudent", 1) . ")";
		}
		
		if (!empty($sortfield)) {
			$sql .= $educoqualifstudent->db->order($sortfield,$sortorder);
		}
		if (!empty($limit)) {
		 $sql .=  ' ' . $educoqualifstudent->db->plimit($limit + 1, $offset);
		}

		$this->lines = array();

		$resql = $educoqualifstudent->db->query($sql);
		if ($resql) {
			$num = $educoqualifstudent->db->num_rows($resql);
                       // var_dump($num);
			while ($obj = $educoqualifstudent->db->fetch_object($resql)) {
				$line = new EducoqualifstudentLine();

				$line->id = $obj->rowid;
				
				$line->ref = $obj->ref;
				$line->label = $obj->label;
				$line->value = $obj->value;
				$line->status = $obj->status;
				$line->note_private = $obj->note_private;
				$line->note_public = $obj->note_public;
				$line->datec = $educoqualifstudent->db->jdate($obj->datec);
				$line->tms = $educoqualifstudent->db->jdate($obj->tms);
				$line->import_key = $obj->import_key;
				$line->entity = $obj->entity;
				$line->fk_qualification = $obj->fk_qualification;
				$line->fk_student = $obj->fk_student;

				

				$this->lines[$line->fk_student] = $line;
			}
			$educoqualifstudent->db->free($resql);

			return $num;
		} else {
			$this->errors[] = 'Error ' . $educoqualifstudent->db->lasterror();
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
		if (isset($this->label)) {
			 $this->label = trim($this->label);
		}
		if (isset($this->datec)) {
			 $this->datec = trim($this->datec);
		}
		if (isset($this->tms)) {
			 $this->tms = trim($this->tms);
		}
		if (isset($this->note_private)) {
			 $this->note_private = trim($this->note_private);
		}
		if (isset($this->note_public)) {
			 $this->note_public = trim($this->note_public);
		}
		if (isset($this->value)) {
			 $this->value = trim($this->value);
		}
		if (isset($this->period)) {
			 $this->period = trim($this->period);
		}
		if (isset($this->status)) {
			 $this->status = trim($this->status);
		}
		if (isset($this->import_key)) {
			 $this->import_key = trim($this->import_key);
		}
		if (isset($this->entity)) {
			 $this->entity = trim($this->entity);
		}
		if (isset($this->fk_group)) {
			 $this->fk_group = trim($this->fk_group);
		}
		if (isset($this->fk_pensum)) {
			 $this->fk_pensum = trim($this->fk_pensum);
		}
		if (isset($this->fk_user)) {
			 $this->fk_user = trim($this->fk_user);
		}
		if (isset($this->fk_rating)) {
			 $this->fk_rating = trim($this->fk_rating);
		}

		

		// Check parameters
		// Put here code to add a control on parameters values

		// Update request
		$sql = 'UPDATE ' . MAIN_DB_PREFIX . $this->table_element . ' SET';
		
		$sql .= ' ref = '.(isset($this->ref)?"'".$this->db->escape($this->ref)."'":"null").',';
		$sql .= ' label = '.(isset($this->label)?"'".$this->db->escape($this->label)."'":"null").',';
		$sql .= ' datec = '.(isset($this->datec)?"'".$this->db->escape($this->datec)."'":"null").',';
		$sql .= ' tms = '.(dol_strlen($this->tms) != 0 ? "'".$this->db->idate($this->tms)."'" : "'".$this->db->idate(dol_now())."'").',';
		$sql .= ' note_private = '.(isset($this->note_private)?"'".$this->db->escape($this->note_private)."'":"null").',';
		$sql .= ' note_public = '.(isset($this->note_public)?"'".$this->db->escape($this->note_public)."'":"null").',';
		$sql .= ' value = '.(isset($this->value)?$this->value:"null").',';
		$sql .= ' period = '.(isset($this->period)?$this->period:"null").',';
		$sql .= ' status = '.(isset($this->status)?$this->status:"null").',';
		$sql .= ' import_key = '.(isset($this->import_key)?"'".$this->db->escape($this->import_key)."'":"null").',';
		$sql .= ' entity = '.(isset($this->entity)?"'".$this->db->escape($this->entity)."'":"null").',';
		$sql .= ' fk_group = '.(isset($this->fk_group)?$this->fk_group:"null").',';
		$sql .= ' fk_pensum = '.(isset($this->fk_pensum)?$this->fk_pensum:"null").',';
		$sql .= ' fk_user = '.(isset($this->fk_user)?$this->fk_user:"null").',';
		$sql .= ' fk_rating = '.(isset($this->fk_rating)?"'".$this->db->escape($this->fk_rating)."'":"null");

        
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
		$object = new Educoqualification($this->db);

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

        $url = DOL_URL_ROOT.'/educo/teacher/qualification.php?id='.$this->id.'&groupid='.$this->fk_group.'&pensumid='.$this->fk_pensum;
        
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
            $result.=($linkstart.img_object(($notooltip?'':$label), 'rating@educo', ($notooltip?'':'class="classfortooltip"')).$linkend);
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
		$this->label = '';
		$this->datec = '';
		$this->tms = '';
		$this->note_private = '';
		$this->note_public = '';
		$this->value = '';
		$this->period = '';
		$this->status = '';
		$this->import_key = '';
		$this->entity = '';
		$this->fk_group = '';
		$this->fk_pensum = '';
		$this->fk_user = '';
		$this->fk_rating = '';

		
	}
/**
	 * Load object in memory from the database
	 *
	 * @param int    $id  Id object
	 * @param string $ref Ref
	 *
	 * @return int <0 if KO, 0 if not found, >0 if OK
	 */
	public function maxWeight()
	{
		dol_syslog(__METHOD__, LOG_DEBUG);

		$sql = 'SELECT';
		
		$sql .= " sum(t.status) as weight";
                
		$sql .= ' FROM ' . MAIN_DB_PREFIX . $this->table_element . ' as t';
		$sql.= ' WHERE 1 = 1';
		if (! empty($conf->multicompany->enabled)) {
		    $sql .= " AND entity IN (" . getEntity("educoqualification", 1) . ")";
		}
		
		$sql .= ' AND t.fk_group = ' . $this->fk_group;
                $sql .= ' AND t.fk_pensum = ' . $this->fk_pensum;
                $sql .= ' AND t.period = ' . $this->period;
		

		$resql = $this->db->query($sql);
		if ($resql) {
			$numrows = $this->db->num_rows($resql);
			if ($numrows) {
				$obj = $this->db->fetch_object($resql);

				$weight= $obj->weight;
				

				
			}			
			
			// $this->fetch_lines();
			
			$this->db->free($resql);

			if ($numrows) {
				return $weight;
			} else {
				return 0;
			}
		} else {
			$this->errors[] = 'Error ' . $this->db->lasterror();
			dol_syslog(__METHOD__ . ' ' . implode(',', $this->errors), LOG_ERR);

			return - 1;
		}
	}
}

/**
 * Class EducoqualificationLine
 */
class EducoqualificationLine
{
	/**
	 * @var int ID
	 */
	public $id;
	/**
	 * @var mixed Sample line property 1
	 */
	
	public $ref;
	public $label;
	public $datec;
	public $tms;
	public $note_private;
	public $note_public;
	public $value;
	public $period;
	public $status;
	public $import_key;
	public $entity;
	public $fk_group;
	public $fk_pensum;
	public $fk_user;
	public $fk_rating;

	/**
	 * @var mixed Sample line property 2
	 */
	
}
