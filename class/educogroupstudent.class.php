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
 * \file    educo/educogroupstudent.class.php
 * \ingroup educo
 * \brief   This file is an example for a CRUD class file (Create/Read/Update/Delete)
 *          Put some comments here
 */

// Put here all includes required by your class file
require_once DOL_DOCUMENT_ROOT . '/core/class/commonobject.class.php';
//require_once DOL_DOCUMENT_ROOT . '/societe/class/societe.class.php';
//require_once DOL_DOCUMENT_ROOT . '/product/class/product.class.php';

/**
 * Class Educogroupstudent
 *
 * Put here description of your class
 *
 * @see CommonObject
 */
class Educogroupstudent extends CommonObject
{
	/**
	 * @var string Id to identify managed objects
	 */
	public $element = 'educogroupstudent';
	/**
	 * @var string Name of table without prefix where object is stored
	 */
	public $table_element = 'educo_group_student';

	/**
	 * @var EducogroupstudentLine[] Lines
	 */
	public $lines = array();

	/**
	 */
	
	public $ref;
	public $datec = '';
	public $tms = '';
	public $statut;
	public $fk_estudiante;
	public $fk_grupo;
	public $fk_user;
	public $fk_academicyear;
	public $entity;
	public $victim;
	public $expelled_from;
	public $disability;
	public $capacity;
	public $from_private;
	public $from_public;
	public $from;
	public $icbf;
	public $courses;
	public $subsidized;
	public $repeating;
	public $new;
	public $situation;
	public $note_private;
	public $note_public;
	public $final_situation;
	public $traslate;
	public $date_out = '';
	public $motive;
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
		if (isset($this->statut)) {
			 $this->statut = trim($this->statut);
		}
		if (isset($this->fk_estudiante)) {
			 $this->fk_estudiante = trim($this->fk_estudiante);
		}
		if (isset($this->fk_grupo)) {
			 $this->fk_grupo = trim($this->fk_grupo);
		}
		if (isset($this->fk_user)) {
			 $this->fk_user = trim($this->fk_user);
		}
		if (isset($this->fk_academicyear)) {
			 $this->fk_academicyear = trim($this->fk_academicyear);
		}
		if (isset($this->entity)) {
			 $this->entity = trim($this->entity);
		}
		if (isset($this->victim)) {
			 $this->victim = trim($this->victim);
		}
		if (isset($this->expelled_from)) {
			 $this->expelled_from = trim($this->expelled_from);
		}
		if (isset($this->disability)) {
			 $this->disability = trim($this->disability);
		}
		if (isset($this->capacity)) {
			 $this->capacity = trim($this->capacity);
		}
		if (isset($this->from_private)) {
			 $this->from_private = trim($this->from_private);
		}
		if (isset($this->from_public)) {
			 $this->from_public = trim($this->from_public);
		}
		if (isset($this->from)) {
			 $this->from = trim($this->from);
		}
		if (isset($this->icbf)) {
			 $this->icbf = trim($this->icbf);
		}
		if (isset($this->courses)) {
			 $this->courses = trim($this->courses);
		}
		if (isset($this->subsidized)) {
			 $this->subsidized = trim($this->subsidized);
		}
		if (isset($this->repeating)) {
			 $this->repeating = trim($this->repeating);
		}
		if (isset($this->new)) {
			 $this->new = trim($this->new);
		}
		if (isset($this->situation)) {
			 $this->situation = trim($this->situation);
		}
		if (isset($this->note_private)) {
			 $this->note_private = trim($this->note_private);
		}
		if (isset($this->note_public)) {
			 $this->note_public = trim($this->note_public);
		}
		if (isset($this->final_situation)) {
			 $this->final_situation = trim($this->final_situation);
		}
		if (isset($this->traslate)) {
			 $this->traslate = trim($this->traslate);
		}
		if (isset($this->motive)) {
			 $this->motive = trim($this->motive);
		}
		if (isset($this->workingday)) {
			 $this->workingday = trim($this->workingday);
		}

		

		// Check parameters
		// Put here code to add control on parameters values

		// Insert request
		$sql = 'INSERT INTO ' . MAIN_DB_PREFIX . $this->table_element . '(';
		
		$sql.= 'ref,';
		$sql.= 'datec,';
		$sql.= 'statut,';
		$sql.= 'fk_estudiante,';
		$sql.= 'fk_grupo,';
		$sql.= 'fk_user,';
		$sql.= 'fk_academicyear,';
		$sql.= 'entity,';
		$sql.= 'victim,';
		$sql.= 'expelled_from,';
		$sql.= 'disability,';
		$sql.= 'capacity,';
		$sql.= 'from_private,';
		$sql.= 'from_public,';
		$sql.= '`from`,';
		$sql.= 'icbf,';
		$sql.= 'courses,';
		$sql.= 'subsidized,';
		$sql.= 'repeating,';
		$sql.= 'new,';
		$sql.= 'situation,';
		$sql.= 'note_private,';
		$sql.= 'note_public,';
		$sql.= 'final_situation,';
		$sql.= 'traslate,';
		$sql.= 'date_out,';
		$sql.= 'motive,';
		$sql.= 'workingday';

		
		$sql .= ') VALUES (';
		
		$sql .= ' '.(! isset($this->ref)?'NULL':"'".$this->db->escape($this->ref)."'").',';
		$sql .= ' '."'".$this->db->idate(dol_now())."'".',';
		$sql .= ' '.(! isset($this->statut)?'NULL':$this->statut).',';
		$sql .= ' '.(! isset($this->fk_estudiante)?'NULL':$this->fk_estudiante).',';
		$sql .= ' '.(! isset($this->fk_grupo)?'NULL':$this->fk_grupo).',';
		$sql .= ' '.(! isset($this->fk_user)?'NULL':$this->fk_user).',';
		$sql .= ' '.(! isset($this->fk_academicyear)?'NULL':$this->fk_academicyear).',';
		$sql .= ' '.(! isset($this->entity)?'NULL':$this->entity).',';
		$sql .= ' '.(! isset($this->victim)?'NULL':$this->victim).',';
		$sql .= ' '.(! isset($this->expelled_from)?'NULL':"'".$this->db->escape($this->expelled_from)."'").',';
		$sql .= ' '.(! isset($this->disability)?'NULL':"'".$this->db->escape($this->disability)."'").',';
		$sql .= ' '.(! isset($this->capacity)?'NULL':"'".$this->db->escape($this->capacity)."'").',';
		$sql .= ' '.(! isset($this->from_private)?'NULL':"'".$this->db->escape($this->from_private)."'").',';
		$sql .= ' '.(! isset($this->from_public)?'NULL':"'".$this->db->escape($this->from_public)."'").',';
		$sql .= ' '.(! isset($this->from)?'NULL':"'".$this->db->escape($this->from)."'").',';
		$sql .= ' '.(! isset($this->icbf)?'NULL':"'".$this->db->escape($this->icbf)."'").',';
		$sql .= ' '.(! isset($this->courses)?'NULL':"'".$this->db->escape($this->courses)."'").',';
		$sql .= ' '.(! isset($this->subsidized)?'NULL':$this->subsidized).',';
		$sql .= ' '.(! isset($this->repeating)?'NULL':$this->repeating).',';
		$sql .= ' '.(! isset($this->new)?'NULL':$this->new).',';
		$sql .= ' '.(! isset($this->situation)?'NULL':"'".$this->db->escape($this->situation)."'").',';
		$sql .= ' '.(! isset($this->note_private)?'NULL':"'".$this->db->escape($this->note_private)."'").',';
		$sql .= ' '.(! isset($this->note_public)?'NULL':"'".$this->db->escape($this->note_public)."'").',';
		$sql .= ' '.(! isset($this->final_situation)?'NULL':$this->final_situation).',';
		$sql .= ' '.(! isset($this->traslate)?'NULL':"'".$this->db->escape($this->traslate)."'").',';
		$sql .= ' '.(! isset($this->date_out) || dol_strlen($this->date_out)==0?'NULL':"'".$this->db->idate($this->date_out)."'").',';
		$sql .= ' '.(! isset($this->motive)?'NULL':"'".$this->db->escape($this->motive)."'").',';
		$sql .= ' '.(! isset($this->workingday)?'NULL':$this->workingday);

		
		$sql .= ')';

		$this->db->begin();
                var_dump($sql);
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
		$sql .= " t.datec,";
		$sql .= " t.tms,";
		$sql .= " t.statut,";
		$sql .= " t.fk_estudiante,";
		$sql .= " t.fk_grupo,";
		$sql .= " t.fk_user,";
		$sql .= " t.fk_academicyear,";
		$sql .= " t.entity,";
		$sql .= " t.victim,";
		$sql .= " t.expelled_from,";
		$sql .= " t.disability,";
		$sql .= " t.capacity,";
		$sql .= " t.from_private,";
		$sql .= " t.from_public,";
		$sql .= " t.from,";
		$sql .= " t.icbf,";
		$sql .= " t.courses,";
		$sql .= " t.subsidized,";
		$sql .= " t.repeating,";
		$sql .= " t.new,";
		$sql .= " t.situation,";
		$sql .= " t.note_private,";
		$sql .= " t.note_public,";
		$sql .= " t.final_situation,";
		$sql .= " t.traslate,";
		$sql .= " t.date_out,";
		$sql .= " t.motive,";
		$sql .= " t.workingday";

		
		$sql .= ' FROM ' . MAIN_DB_PREFIX . $this->table_element . ' as t';
		$sql.= ' WHERE 1 = 1';
		if (! empty($conf->multicompany->enabled)) {
		    $sql .= " AND entity IN (" . getEntity("educogroupstudent", 1) . ")";
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
				$this->datec = $this->db->jdate($obj->datec);
				$this->tms = $this->db->jdate($obj->tms);
				$this->statut = $obj->statut;
				$this->fk_estudiante = $obj->fk_estudiante;
				$this->fk_grupo = $obj->fk_grupo;
				$this->fk_user = $obj->fk_user;
				$this->fk_academicyear = $obj->fk_academicyear;
				$this->entity = $obj->entity;
				$this->victim = $obj->victim;
				$this->expelled_from = $obj->expelled_from;
				$this->disability = $obj->disability;
				$this->capacity = $obj->capacity;
				$this->from_private = $obj->from_private;
				$this->from_public = $obj->from_public;
				$this->from = $obj->from;
				$this->icbf = $obj->icbf;
				$this->courses = $obj->courses;
				$this->subsidized = $obj->subsidized;
				$this->repeating = $obj->repeating;
				$this->new = $obj->new;
				$this->situation = $obj->situation;
				$this->note_private = $obj->note_private;
				$this->note_public = $obj->note_public;
				$this->final_situation = $obj->final_situation;
				$this->traslate = $obj->traslate;
				$this->date_out = $this->db->jdate($obj->date_out);
				$this->motive = $obj->motive;
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
	public function fetchAll($sortorder='', $sortfield='', $limit=0, $offset=0, array $filter = array(), $filtermode='AND')
	{
		dol_syslog(__METHOD__, LOG_DEBUG);

		$sql = 'SELECT';
		$sql .= ' t.rowid,';
		
		$sql .= " t.ref,";
		$sql .= " t.datec,";
		$sql .= " t.tms,";
		$sql .= " t.statut,";
		$sql .= " t.fk_estudiante,";
		$sql .= " t.fk_grupo,";
		$sql .= " t.fk_user,";
		$sql .= " t.fk_academicyear,";
		$sql .= " t.entity,";
		$sql .= " t.victim,";
		$sql .= " t.expelled_from,";
		$sql .= " t.disability,";
		$sql .= " t.capacity,";
		$sql .= " t.from_private,";
		$sql .= " t.from_public,";
		$sql .= " t.from,";
		$sql .= " t.icbf,";
		$sql .= " t.courses,";
		$sql .= " t.subsidized,";
		$sql .= " t.repeating,";
		$sql .= " t.new,";
		$sql .= " t.situation,";
		$sql .= " t.note_private,";
		$sql .= " t.note_public,";
		$sql .= " t.final_situation,";
		$sql .= " t.traslate,";
		$sql .= " t.date_out,";
		$sql .= " t.motive,";
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
		    $sql .= " AND entity IN (" . getEntity("educogroupstudent", 1) . ")";
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
				$line = new EducogroupstudentLine();

				$line->id = $obj->rowid;
				
				$line->ref = $obj->ref;
				$line->datec = $this->db->jdate($obj->datec);
				$line->tms = $this->db->jdate($obj->tms);
				$line->statut = $obj->statut;
				$line->fk_estudiante = $obj->fk_estudiante;
				$line->fk_grupo = $obj->fk_grupo;
				$line->fk_user = $obj->fk_user;
				$line->fk_academicyear = $obj->fk_academicyear;
				$line->entity = $obj->entity;
				$line->victim = $obj->victim;
				$line->expelled_from = $obj->expelled_from;
				$line->disability = $obj->disability;
				$line->capacity = $obj->capacity;
				$line->from_private = $obj->from_private;
				$line->from_public = $obj->from_public;
				$line->from = $obj->from;
				$line->icbf = $obj->icbf;
				$line->courses = $obj->courses;
				$line->subsidized = $obj->subsidized;
				$line->repeating = $obj->repeating;
				$line->new = $obj->new;
				$line->situation = $obj->situation;
				$line->note_private = $obj->note_private;
				$line->note_public = $obj->note_public;
				$line->final_situation = $obj->final_situation;
				$line->traslate = $obj->traslate;
				$line->date_out = $this->db->jdate($obj->date_out);
				$line->motive = $obj->motive;
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
		if (isset($this->statut)) {
			 $this->statut = trim($this->statut);
		}
		if (isset($this->fk_estudiante)) {
			 $this->fk_estudiante = trim($this->fk_estudiante);
		}
		if (isset($this->fk_grupo)) {
			 $this->fk_grupo = trim($this->fk_grupo);
		}
		if (isset($this->fk_user)) {
			 $this->fk_user = trim($this->fk_user);
		}
		if (isset($this->fk_academicyear)) {
			 $this->fk_academicyear = trim($this->fk_academicyear);
		}
		if (isset($this->entity)) {
			 $this->entity = trim($this->entity);
		}
		if (isset($this->victim)) {
			 $this->victim = trim($this->victim);
		}
		if (isset($this->expelled_from)) {
			 $this->expelled_from = trim($this->expelled_from);
		}
		if (isset($this->disability)) {
			 $this->disability = trim($this->disability);
		}
		if (isset($this->capacity)) {
			 $this->capacity = trim($this->capacity);
		}
		if (isset($this->from_private)) {
			 $this->from_private = trim($this->from_private);
		}
		if (isset($this->from_public)) {
			 $this->from_public = trim($this->from_public);
		}
		if (isset($this->from)) {
			 $this->from = trim($this->from);
		}
		if (isset($this->icbf)) {
			 $this->icbf = trim($this->icbf);
		}
		if (isset($this->courses)) {
			 $this->courses = trim($this->courses);
		}
		if (isset($this->subsidized)) {
			 $this->subsidized = trim($this->subsidized);
		}
		if (isset($this->repeating)) {
			 $this->repeating = trim($this->repeating);
		}
		if (isset($this->new)) {
			 $this->new = trim($this->new);
		}
		if (isset($this->situation)) {
			 $this->situation = trim($this->situation);
		}
		if (isset($this->note_private)) {
			 $this->note_private = trim($this->note_private);
		}
		if (isset($this->note_public)) {
			 $this->note_public = trim($this->note_public);
		}
		if (isset($this->final_situation)) {
			 $this->final_situation = trim($this->final_situation);
		}
		if (isset($this->traslate)) {
			 $this->traslate = trim($this->traslate);
		}
		if (isset($this->motive)) {
			 $this->motive = trim($this->motive);
		}
		if (isset($this->workingday)) {
			 $this->workingday = trim($this->workingday);
		}

		

		// Check parameters
		// Put here code to add a control on parameters values

		// Update request
		$sql = 'UPDATE ' . MAIN_DB_PREFIX . $this->table_element . ' SET';
		
		$sql .= ' ref = '.(isset($this->ref)?"'".$this->db->escape($this->ref)."'":"null").',';
		$sql .= ' datec = '.(! isset($this->datec) || dol_strlen($this->datec) != 0 ? "'".$this->db->idate($this->datec)."'" : 'null').',';
		$sql .= ' tms = '.(dol_strlen($this->tms) != 0 ? "'".$this->db->idate($this->tms)."'" : "'".$this->db->idate(dol_now())."'").',';
		$sql .= ' statut = '.(isset($this->statut)?$this->statut:"null").',';
		$sql .= ' fk_estudiante = '.(isset($this->fk_estudiante)?$this->fk_estudiante:"null").',';
		$sql .= ' fk_grupo = '.(isset($this->fk_grupo)?$this->fk_grupo:"null").',';
		$sql .= ' fk_user = '.(isset($this->fk_user)?$this->fk_user:"null").',';
		$sql .= ' fk_academicyear = '.(isset($this->fk_academicyear)?$this->fk_academicyear:"null").',';
		$sql .= ' entity = '.(isset($this->entity)?$this->entity:"null").',';
		$sql .= ' victim = '.(isset($this->victim)?$this->victim:"null").',';
		$sql .= ' expelled_from = '.(isset($this->expelled_from)?"'".$this->db->escape($this->expelled_from)."'":"null").',';
		$sql .= ' disability = '.(isset($this->disability)?"'".$this->db->escape($this->disability)."'":"null").',';
		$sql .= ' capacity = '.(isset($this->capacity)?"'".$this->db->escape($this->capacity)."'":"null").',';
		$sql .= ' from_private = '.(isset($this->from_private)?"'".$this->db->escape($this->from_private)."'":"null").',';
		$sql .= ' from_public = '.(isset($this->from_public)?"'".$this->db->escape($this->from_public)."'":"null").',';
		$sql .= ' `from`= '.(isset($this->from)?"'".$this->db->escape($this->from)."'":"null").',';
		$sql .= ' icbf = '.(isset($this->icbf)?"'".$this->db->escape($this->icbf)."'":"null").',';
		$sql .= ' courses = '.(isset($this->courses)?"'".$this->db->escape($this->courses)."'":"null").',';
		$sql .= ' subsidized = '.(isset($this->subsidized)?$this->subsidized:"null").',';
		$sql .= ' repeating = '.(isset($this->repeating)?$this->repeating:"null").',';
		$sql .= ' new = '.(isset($this->new)?$this->new:"null").',';
		$sql .= ' situation = '.(isset($this->situation)?"'".$this->db->escape($this->situation)."'":"null").',';
		$sql .= ' note_private = '.(isset($this->note_private)?"'".$this->db->escape($this->note_private)."'":"null").',';
		$sql .= ' note_public = '.(isset($this->note_public)?"'".$this->db->escape($this->note_public)."'":"null").',';
		$sql .= ' final_situation = '.(isset($this->final_situation)?$this->final_situation:"null").',';
		$sql .= ' traslate = '.(isset($this->traslate)?"'".$this->db->escape($this->traslate)."'":"null").',';
		$sql .= ' date_out = '.(! isset($this->date_out) || dol_strlen($this->date_out) != 0 ? "'".$this->db->idate($this->date_out)."'" : 'null').',';
		$sql .= ' motive = '.(isset($this->motive)?"'".$this->db->escape($this->motive)."'":"null").',';
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
		$object = new Educogroupstudent($this->db);

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

        $label = '<u>' . $langs->trans("Enrollment") . '</u>';
        $label.= '<br>';
        $label.= '<b>' . $langs->trans('Ref') . ':</b> ' . $this->ref;

        $url = DOL_URL_ROOT.'/educo/enrollment/card.php?id='.$this->id;
        
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
            $result.=($linkstart.img_object(($notooltip?'':$label), 'enrollment@educo', ($notooltip?'':'class="classfortooltip"')).$linkend);
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
		$this->datec = '';
		$this->tms = '';
		$this->statut = '';
		$this->fk_estudiante = '';
		$this->fk_grupo = '';
		$this->fk_user = '';
		$this->fk_academicyear = '';
		$this->entity = '';
		$this->victim = '';
		$this->expelled_from = '';
		$this->disability = '';
		$this->capacity = '';
		$this->from_private = '';
		$this->from_public = '';
		$this->from = '';
		$this->icbf = '';
		$this->courses = '';
		$this->subsidized = '';
		$this->repeating = '';
		$this->new = '';
		$this->situation = '';
		$this->note_private = '';
		$this->note_public = '';
		$this->final_situation = '';
		$this->traslate = '';
		$this->date_out = '';
		$this->motive = '';
		$this->workingday = '';

		
	}

}

/**
 * Class EducogroupstudentLine
 */
class EducogroupstudentLine
{
	/**
	 * @var int ID
	 */
	public $id;
	/**
	 * @var mixed Sample line property 1
	 */
	
	public $ref;
	public $datec = '';
	public $tms = '';
	public $statut;
	public $fk_estudiante;
	public $fk_grupo;
	public $fk_user;
	public $fk_academicyear;
	public $entity;
	public $victim;
	public $expelled_from;
	public $disability;
	public $capacity;
	public $from_private;
	public $from_public;
	public $from;
	public $icbf;
	public $courses;
	public $subsidized;
	public $repeating;
	public $new;
	public $situation;
	public $note_private;
	public $note_public;
	public $final_situation;
	public $traslate;
	public $date_out = '';
	public $motive;
	public $workingday;

	/**
	 * @var mixed Sample line property 2
	 */
	
}
