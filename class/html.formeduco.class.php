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
     *	Constructor
     *
     *	@param	DoliDB		$db      Database handler
     */
    function __construct($db)
    {
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
    function select_dictionary($htmlname,$dictionarytable,$keyfield='code',$labelfield='label',$selected='',$useempty=0,$moreattrib='')
    {
        global $langs, $conf;

        $langs->load("admin");

        $sql = "SELECT rowid, ".$keyfield.", ".$labelfield;
        $sql.= " FROM ".MAIN_DB_PREFIX.$dictionarytable;
        $sql.= " WHERE active=1 ";
        $sql.= " ORDER BY ".$labelfield;

        dol_syslog(get_class($this)."::select_dictionary", LOG_DEBUG);
        $result = $this->db->query($sql);
        if ($result)
        {
            $num = $this->db->num_rows($result);
            $i = 0;
            if ($num)
            {
                print '<select id="select'.$htmlname.'" class="flat selectdictionary" name="'.$htmlname.'"'.($moreattrib?' '.$moreattrib:'').'>';
                if ($useempty == 1 || ($useempty == 2 && $num > 1))
                {
                    print '<option value="-1">&nbsp;</option>';
                }

                while ($i < $num)
                {
                    $obj = $this->db->fetch_object($result);
                    if ($selected == $obj->rowid || $selected == $obj->$keyfield)
                    {
                        print '<option value="'.$obj->$keyfield.'" selected>';
                    }
                    else
                    {
                        print '<option value="'.$obj->$keyfield.'">';
                    }
                    print $obj->$labelfield;
                    print '</option>';
                    $i++;
                }
                print "</select>";
            }
            else
			{
                print $langs->trans("DictionaryEmpty");
            }
        }
        else {
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
	private function fetchSubjectsPesum($academicid=0,$sortorder='', $sortfield='', $limit=0, $offset=0)
	{
		dol_syslog(__METHOD__, LOG_DEBUG);

		$sql = 'SELECT';
		$sql .= ' t.rowid,';
		
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
                $sql .= " g.label as grado_name";

		
		$sql .= ' FROM ' . MAIN_DB_PREFIX . 'educo_pensum as t';
                $sql .= ' INNER JOIN ' . MAIN_DB_PREFIX . 'edcuo_c_asignatura as a on t.asignature_code=a.code';
                $sql .= ' INNER JOIN ' . MAIN_DB_PREFIX . 'educo_c_grado as g on t.grado_code=g.code';

		// Manage filter
		
		$sql.= ' WHERE 1 = 1';
		if (! empty($conf->multicompany->enabled)) {
		    $sql .= " AND entity IN (" . getEntity("educopensum", 1) . ")";
		}
		 $sql .= " AND t.fk_academicyear=".$academicid;
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
                        $line =  array();
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
        function select_pensum($htmlname,$academicid,$id,$show_empty=0) {
            global $form;
           // $form=new Form($this->db);
            $subjects = $this->fetchSubjectsPesum($academicid);
             $array=array();
            if(is_array($subjects)){            
                foreach ($subjects as $s) {
                    $array[$s->rowid]=$s->grado_name.' - '.$s->subject_name;
                }
            }else{
                $array=$this->errors;
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
	private function fetchGroups($academicid,$sortorder='', $sortfield='', $limit=0, $offset=0)
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
		$sql .= " t.import_key";

		
		$sql .= ' FROM ' . MAIN_DB_PREFIX . 'educo_group as t';

		// Manage filter
		
		$sql.= ' WHERE 1 = 1';
                 $sql .= " AND t.fk_academicyear =$academicid";
		if (! empty($conf->multicompany->enabled)) {
		    $sql .= " AND entity IN (" . getEntity("educogroup", 1) . ")";
		}
		
		if (!empty($sortfield)) {
			$sql .= $this->db->order($sortfield,$sortorder);
		}
		if (!empty($limit)) {
		 $sql .=  ' ' . $this->db->plimit($limit + 1, $offset);
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
         function select_groups($htmlname,$academicid,$id,$show_empty=0) {
            global $form;
           // $form=new Form($this->db);
            $groups = $this->fetchGroups($academicid);
             $array=array();
            
            if(is_array($groups)){            
                foreach ($groups as $g) {
                    $array[$g->rowid]=$g->label;                   
                }
            }else{
                $array=$this->errors;
            }
            
            return $form->selectarray($htmlname, $array, $id, $show_empty);            
        }
}
