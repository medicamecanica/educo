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
 * \file    educo/educohorario.class.php
 * \ingroup educo
 * \brief   This file is an example for a CRUD class file (Create/Read/Update/Delete)
 *          Put some comments here
 */

// Put here all includes required by your class file
require_once DOL_DOCUMENT_ROOT . '/core/class/commonobject.class.php';
//require_once DOL_DOCUMENT_ROOT . '/societe/class/societe.class.php';
//require_once DOL_DOCUMENT_ROOT . '/product/class/product.class.php';

/**
 * Class Educohorario
 *
 * Put here description of your class
 *
 * @see CommonObject
 */
class Educohorario extends CommonObject
{
	/**
	 * @var string Id to identify managed objects
	 */
	public $element = 'educohorario';
	/**
	 * @var string Name of table without prefix where object is stored
	 */
	public $table_element = 'educo_horario';

	/**
	 * @var EducohorarioLine[] Lines
	 */
	public $lines = array();

	/**
	 */
	
	public $ref;
	public $label;
	public $datep = '';
	public $datef = '';
	public $duration;
	public $note_private;
	public $grado_code;
	public $subject_code;
	public $datec = '';
	public $tms = '';
	public $fk_group;
	public $fk_teach_sub;
	public $entity;

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
		if (isset($this->duration)) {
			 $this->duration = trim($this->duration);
		}
		if (isset($this->note_private)) {
			 $this->note_private = trim($this->note_private);
		}
		if (isset($this->grado_code)) {
			 $this->grado_code = trim($this->grado_code);
		}
		if (isset($this->subject_code)) {
			 $this->subject_code = trim($this->subject_code);
		}
		if (isset($this->fk_group)) {
			 $this->fk_group = trim($this->fk_group);
		}
		if (isset($this->fk_teach_sub)) {
			 $this->fk_teach_sub = trim($this->fk_teach_sub);
		}
		if (isset($this->entity)) {
			 $this->entity = trim($this->entity);
		}

		

		// Check parameters
		// Put here code to add control on parameters values

		// Insert request
		$sql = 'INSERT INTO ' . MAIN_DB_PREFIX . $this->table_element . '(';
		
		$sql.= 'rowid,';
		$sql.= 'ref,';
		$sql.= 'label,';
		$sql.= 'datep,';
		$sql.= 'datef,';
		$sql.= 'duration,';
		$sql.= 'note_private,';
		$sql.= 'grado_code,';
		$sql.= 'subject_code,';
		$sql.= 'datec,';
		$sql.= 'fk_group,';
		$sql.= 'fk_teach_sub,';
		$sql.= 'entity';

		
		$sql .= ') VALUES (';
		
		$sql .= ' '.(! isset($this->id)?'NULL':$this->id).',';
		$sql .= ' '.(! isset($this->ref)?'NULL':"'".$this->db->escape($this->ref)."'").',';
		$sql .= ' '.(! isset($this->label)?'NULL':"'".$this->db->escape($this->label)."'").',';
		$sql .= ' '.(! isset($this->datep) || dol_strlen($this->datep)==0?'NULL':"'".$this->db->idate($this->datep)."'").',';
		$sql .= ' '.(! isset($this->datef) || dol_strlen($this->datef)==0?'NULL':"'".$this->db->idate($this->datef)."'").',';
		$sql .= ' '.(! isset($this->duration)?'NULL':$this->duration).',';
		$sql .= ' '.(! isset($this->note_private)?'NULL':"'".$this->db->escape($this->note_private)."'").',';
		$sql .= ' '.(! isset($this->grado_code)?'NULL':"'".$this->db->escape($this->grado_code)."'").',';
		$sql .= ' '.(! isset($this->subject_code)?'NULL':"'".$this->db->escape($this->subject_code)."'").',';
		$sql .= ' '."'".$this->db->idate(dol_now())."'".',';
		$sql .= ' '.(! isset($this->fk_group)?'NULL':$this->fk_group).',';
		$sql .= ' '.(! isset($this->fk_teach_sub)?'NULL':$this->fk_teach_sub).',';
		$sql .= ' '.(! isset($this->entity)?'NULL':$this->entity);

		
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
		$sql .= " t.datep,";
		$sql .= " t.datef,";
		$sql .= " t.duration,";
		$sql .= " t.note_private,";
		$sql .= " t.grado_code,";
		$sql .= " t.subject_code,";
		$sql .= " t.datec,";
		$sql .= " t.tms,";
		$sql .= " t.fk_group,";
		$sql .= " t.fk_teach_sub,";
		$sql .= " t.entity";

		
		$sql .= ' FROM ' . MAIN_DB_PREFIX . $this->table_element . ' as t';
		$sql.= ' WHERE 1 = 1';
		if (! empty($conf->multicompany->enabled)) {
		    $sql .= " AND entity IN (" . getEntity("educohorario", 1) . ")";
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
				$this->datep = $this->db->jdate($obj->datep);
				$this->datef = $this->db->jdate($obj->datef);
				$this->duration = $obj->duration;
				$this->note_private = $obj->note_private;
				$this->grado_code = $obj->grado_code;
				$this->subject_code = $obj->subject_code;
				$this->datec = $this->db->jdate($obj->datec);
				$this->tms = $this->db->jdate($obj->tms);
				$this->fk_group = $obj->fk_group;
				$this->fk_teach_sub = $obj->fk_teach_sub;
				$this->entity = $obj->entity;

				
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
		$sql .= " t.datep,";
		$sql .= " t.datef,";
		$sql .= " t.duration,";
		$sql .= " t.note_private,";
		$sql .= " t.grado_code,";
		$sql .= " t.subject_code,";
		$sql .= " t.datec,";
		$sql .= " t.tms,";
		$sql .= " t.fk_group,";
		$sql .= " t.fk_teach_sub,";
		$sql .= " t.entity";

		
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
		    $sql .= " AND entity IN (" . getEntity("educohorario", 1) . ")";
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
				$line = new EducohorarioLine();

				$line->id = $obj->rowid;
				
				$line->ref = $obj->ref;
				$line->label = $obj->label;
				$line->datep = $this->db->jdate($obj->datep);
				$line->datef = $this->db->jdate($obj->datef);
				$line->duration = $obj->duration;
				$line->note_private = $obj->note_private;
				$line->grado_code = $obj->grado_code;
				$line->subject_code = $obj->subject_code;
				$line->datec = $this->db->jdate($obj->datec);
				$line->tms = $this->db->jdate($obj->tms);
				$line->fk_group = $obj->fk_group;
				$line->fk_teach_sub = $obj->fk_teach_sub;
				$line->entity = $obj->entity;

				

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
		if (isset($this->label)) {
			 $this->label = trim($this->label);
		}
		if (isset($this->duration)) {
			 $this->duration = trim($this->duration);
		}
		if (isset($this->note_private)) {
			 $this->note_private = trim($this->note_private);
		}
		if (isset($this->grado_code)) {
			 $this->grado_code = trim($this->grado_code);
		}
		if (isset($this->subject_code)) {
			 $this->subject_code = trim($this->subject_code);
		}
		if (isset($this->fk_group)) {
			 $this->fk_group = trim($this->fk_group);
		}
		if (isset($this->fk_teach_sub)) {
			 $this->fk_teach_sub = trim($this->fk_teach_sub);
		}
		if (isset($this->entity)) {
			 $this->entity = trim($this->entity);
		}

		

		// Check parameters
		// Put here code to add a control on parameters values

		// Update request
		$sql = 'UPDATE ' . MAIN_DB_PREFIX . $this->table_element . ' SET';
		
		$sql .= ' rowid = '.(isset($this->id)?$this->id:"null").',';
		$sql .= ' ref = '.(isset($this->ref)?"'".$this->db->escape($this->ref)."'":"null").',';
		$sql .= ' label = '.(isset($this->label)?"'".$this->db->escape($this->label)."'":"null").',';
		$sql .= ' datep = '.(! isset($this->datep) || dol_strlen($this->datep) != 0 ? "'".$this->db->idate($this->datep)."'" : 'null').',';
		$sql .= ' datef = '.(! isset($this->datef) || dol_strlen($this->datef) != 0 ? "'".$this->db->idate($this->datef)."'" : 'null').',';
		$sql .= ' duration = '.(isset($this->duration)?$this->duration:"null").',';
		$sql .= ' note_private = '.(isset($this->note_private)?"'".$this->db->escape($this->note_private)."'":"null").',';
		$sql .= ' grado_code = '.(isset($this->grado_code)?"'".$this->db->escape($this->grado_code)."'":"null").',';
		$sql .= ' subject_code = '.(isset($this->subject_code)?"'".$this->db->escape($this->subject_code)."'":"null").',';
		$sql .= ' datec = '.(! isset($this->datec) || dol_strlen($this->datec) != 0 ? "'".$this->db->idate($this->datec)."'" : 'null').',';
		$sql .= ' tms = '.(dol_strlen($this->tms) != 0 ? "'".$this->db->idate($this->tms)."'" : "'".$this->db->idate(dol_now())."'").',';
		$sql .= ' fk_group = '.(isset($this->fk_group)?$this->fk_group:"null").',';
		$sql .= ' fk_teach_sub = '.(isset($this->fk_teach_sub)?$this->fk_teach_sub:"null").',';
		$sql .= ' entity = '.(isset($this->entity)?$this->entity:"null");

        
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
		$object = new Educohorario($this->db);

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
            $result.=($linkstart.img_object(($notooltip?'':$label), 'label', ($notooltip?'':'class="classfortooltip"')).$linkend);
            if ($withpicto != 2) $result.=' ';
		}
		$result.= $linkstart . $this->ref . $linkend;
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
		$this->datep = '';
		$this->datef = '';
		$this->duration = '';
		$this->note_private = '';
		$this->grado_code = '';
		$this->subject_code = '';
		$this->datec = '';
		$this->tms = '';
		$this->fk_group = '';
		$this->fk_teach_sub = '';
		$this->entity = '';

		
	}

}

/**
 * Class EducohorarioLine
 */
class EducohorarioLine
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
	public $datep = '';
	public $datef = '';
	public $duration;
	public $note_private;
	public $grado_code;
	public $subject_code;
	public $datec = '';
	public $tms = '';
	public $fk_group;
	public $fk_teach_sub;
	public $entity;

	/**
	 * @var mixed Sample line property 2
	 */
	
}
