<?php
/*
 You may not change or alter any portion of this comment or credits
 of supporting developers from this source code or any supporting source code
 which is considered copyrighted (c) material of the original comment or credit authors.

 This program is distributed in the hope that it will be useful,
 but WITHOUT ANY WARRANTY; without even the implied warranty of
 MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
*/

/**
 * tdmcreate module
 *
 * @copyright       The XOOPS Project http://sourceforge.net/projects/xoops/
 * @license         GNU GPL 2 (http://www.gnu.org/licenses/old-licenses/gpl-2.0.html)
 * @package         tdmcreate
 * @since           2.6.0
 * @author          Timgno <txmodxoops@gmail.com>
 * @version         $Id: structure.php 10665 2012-12-27 10:14:15Z timgno $
 */

class TDMCreateStructure extends TDMCreateFile 
{
    /**
     * folder object of the File
     *
     * @var XoopsFolderHandler
     * @access public
     */
    public $mod_name = null;
	
    /**
     * folder object of the File
     *
     * @var XoopsFolderHandler
     * @access public
     */
    public $file_name = null;
	
    /**
     * folder object of the File
     *
     * @var XoopsFolderHandler
     * @access public
     */ 
    public $path = null;
	
    /**
     * folder object of the File
     *
     * @var XoopsFolderHandler
     * @access public
     */
    public $copyFile = null;
	
    /**
     * Constructor.
     *
     * @param string $path
     */
    public function __construct($path) 
	{    
	    $this->path = $path;	         
    }    
	
    /**
     *	
     * @param string $path
     */
    public function makeDir($path)
    {   
        $this->path = $path;        
	    if(!is_dir($this->path)) {
		    mkdir($this->path, 0777);
		    chmod($this->path, 0777);	       
	    }
    }
	
    /** 
	 *
     * @param string $folder_name                                 
     */
    public function makeDirInModule($folder_name)
    {   
        $this->folder = $folder_name;   
        $fname = $this->path . '/' .$this->mod_name. '/' .$this->folder; 	   
	    if(!is_dir($fname)) {
	  	    mkdir($fname, 0777);
		    chmod($fname, 0777);	       
	    }
    }
	
    /** 
     * 
     * @param string $folder_name                                 
     * @param string $copyFile            
     * @param string $file                           
     */
    public function makeDirAndCopyFile($folder_name, $copyFile, $file)
    {
        $this->file_name = $file;
        $this->folder = $folder_name;
	    $this->copyFile = $copyFile;	
        $fname = $this->path . '/' .$this->mod_name. '/' .$this->folder; 	   
	    if(!is_dir($fname)) {
	     	mkdir($fname, 0777);
		    chmod($fname, 0777);
	        $this->copyFile($this->folder, $this->copyFile, $this->file_name);	       
	    } else {
	        $this->copyFile($this->folder, $this->copyFile, $this->file_name);
	    }
    }
	
    /** 
     * 
     * @param string $folder_name                                 
     * @param string $copyFile            
     * @param string $file	
     */
    public function copyFile($folder_name, $copyFile, $file)
    {	   
	    $this->file_name = $file;
	    $this->folder = $folder_name;
	    $this->copyFile = $copyFile;
	    $fname = $this->path . '/' .$this->mod_name. '/' .$this->folder. '/'. $this->file_name;
	    if(is_dir($this->folder) && XoopsLoad::fileExists($fname)) {		   
		    chmod($fname, 0777);
	        copy($this->copieFile, $fname);
	    } else {
	        copy($this->copyFile, $fname);
	    }	   
    }	
}

class TDMCreateXoopsVersionFile extends TDMCreateFile
{
    /**
     * Constructor
     *
     * @param string $path Path to file
     * @param boolean $create Create file if it does not exist (if true)
     * @param integer $mode Mode to apply to the folder holding the file
     * @access private
     */
    private function __construct($path, $create = false, $mode = 0755)
    {
        parent::__construct($path, $create, $mode);        
    }
	
	/* 
     * 
     * @param string $folder                                 
     * @param string $file	
     */
    public function createXoopsVersionFile($folder, $file, $elements = array())
    {	   
	    $this->file = $file;
	    $this->folder = $folder;
    }
}

class TDMCreateClassesFile
{
    /**
     * @var null|array
     */
    public $_class = null;
	
	/**
     * @var bool
     */
    public $is_form = false;
	
    /**
     * Constructor
     *
     * @param obj $class 
     */
    public function __construct($class)
    {
        $this->_class = $class;       
    }	
	
	/** 
     * 
     * @param string $key                                 
     * @param mixed $value	
	 * @param boolean $not_gpc	
     */
    public function tdmc_setVar($key, $value, $not_gpc = false)
    {	
	    $res = '';
        if ( $not_gpc ) {
			$res .= '$this->setVar(\''.$key.'\', '. $value .', ' . $not_gpc .');';
		} else {
			$res .= '$this->setVar(\''.$key.'\', '. $value .');';
		}	
	    return $res;
    }

	/** 
     * 
     * @param array $var_arr                                 
	 * @param boolean $not_gpc	
     */
    public function tdmc_setVars($var_arr, $not_gpc = false)
    {	
        $comma = ', ';	$c = 0;
	    foreach ($var_arr as $key => $value) {		    
			$_array[$c] = $key.'\' => '. $value;
			$c++;
		}
		$res = '';
		for ($i = 0; $i < $c; $i++)
		{
			if ( $i != $c - 1 ) {
				$res .= $_array[$i] . $comma;
			} else {
				$res .= $_array[$i];
			}
		}
		if ( $not_gpc ) {
			$res .= '$this->setVars(array(\''. $res .'), ' . $not_gpc .');';
		} else {
			$res .= '$this->setVars(array(\''. $res .'));';
		}	
	    return $res;
    }
	
	/** 
     * 
     * @param int $i                                 
     * @param string $modname	
	 * @param string $tablename	
	 * @param string $fieldname	
	 * @param string $fieldelements	
	 * @param string $langform
	 * @param array $structure
     */
    public function tdmc_formElements($i, $modname, $tablename, $fieldname, $fieldelements, $langform, $structure)
    {	  
	    return null;
    }
	
	/** 
     * 
     * @param string $key	
	 * @param string $value	
	 * @param string $sort	
	 * @param string $order	
	 * @param int $tree
	 * @param int $id
     */
    public function tdmc_Criteria($key, $value, $sort = '', $order = '', $id = null)
    {	  
	    $criteria = '$criteria = new CriteriaCompo();';
		if($id) {
		    $criteria = '$criteria->add(new Criteria(\''.$key.'\', '.$value.')));';
		} else {
		    $criteria = '$criteria->add(new Criteria(\''.$key.'\', '.$id.', '.$value.')));';
		}
		if($sort != '') {
			$criteria = '$criteria->setSort(\''.$sort.'\');';
		}
		if($order != '') {
			$criteria = '$criteria->setOrder(\''.$order.'\');';
		}
		return $criteria;
    }
}

class TDMCreateDbFile
{
    /**
     * @var null|array
     */
    public $_class = null;
	
    /**
     * Constructor
     *
     * @param obj $class 
     */
    public function __construct($class)
    {
        $this->_class = $class;       
    }
	
	/** 
     * 
     * @param string $var                                 
     * @param integer $nb_fields	
	 * @param mixed $data_type
	 * @param boolean $required	
	 * @param mixed $handler	
	 * @param string $options	
     *
     * @return string
     */
    public function tdmc_dbTable($tablename, $nb_fields = null, $data_type = 'int', $handler = null, $options = '')
    {	  
	    $ret = '#
# Structure for table `'.strtolower($tablename).'` '.$nb_fields.'
#
		
CREATE TABLE  `'.strtolower($tablename).'` (';

        $j = 0;
		for ($i = 0; $i < $nb_fields; $i++)
		{
			$structure = explode(":", $fields[$i]);
			
			//Debut
			if ( $structure[0] != ' ' )
			{
				//If as text, (not value)
                if ( $structure[1] == 'text' || $structure[1] == 'date' || $structure[1] == 'timestamp' ) {			
				    $type = $structure[1];
			    } else {
				    $type = $structure[1].' ('.$structure[2].')';
			    }			
                //If as empty is default not string(not value), if as text not default, if as numeric default is 0 or 0.0000
				if ( empty($structure[5]) ) {
					$default = "default ''";				
				} elseif ( $structure[1] == 'text' ) { 
				    $default = "";
				} elseif ( $structure[1] == 'int' || $structure[1] == 'tinyint' || $structure[1] == 'mediumint' || $structure[1] == 'smallint') {
					$default = "default '0'";
				} elseif ( $structure[1] == 'decimal' || $structure[1] == 'double' || $structure[1] == 'float' ) {
					$default = "default '0.0000'";
				} elseif ( $structure[1] == 'date' ) {
					$default = "default '0000-00-00'";
				} elseif ( $structure[1] == 'datetime' || $structure[1] == 'timestamp') {
					$default = "default '0000-00-00 00:00:00'";				
				} elseif ( $structure[1] == 'time' ) {
					$default = "default '00:00:00'";
				} elseif ( $structure[1] == 'year' ) {
					$default = "default '0000'";
				} else {
					$default = "default '".$structure[5]."'";
				}
				
				if ( $i == 0 ) {
					$comma[$j] = 'PRIMARY KEY (`'.$structure[0].'`)';
					$j++;
					$ret .= '`'.$structure[0].'` '.$type.' '.$structure[3].' '.$structure[4].' auto_increment,
';
				} else {
					if ( $structure[6] == 'unique' || $structure[6] ==  'index' || $structure[6] ==  'fulltext')
					{
						if ( $structure[6] == 'unique' ) {
							$ret .= '`'.$structure[0].'` '.$type.' '.$structure[3].' '.$structure[4].' '.$default.',
';
							$comma[$j] = 'KEY `'.$structure[0].'` (`'.$structure[0].'`)';
						} else if ( $structure[6] == 'index' ) {
							$ret .= '`'.$structure[0].'` '.$type.' '.$structure[3].' '.$structure[4].' '.$default.',
';
							$comma[$j] = 'INDEX (`'.$structure[0].'`)';
						} else if ( $structure[6] == 'fulltext' ) {
							$ret .= '`'.$structure[0].'` '.$type.' '.$structure[3].' '.$structure[4].' '.$default.',
';
							$comma[$j] = 'FULLTEXT KEY `'.$structure[0].'` (`'.$structure[0].'`)';	
						}
						$j++;
					} else {
						$ret .= '`'.$structure[0].'` '.$type.' '.$structure[3].' '.$structure[4].' '.$default.',
';
					}
				}
			}
		}
		
		//Problem comma
		$key = '';
		for ($i = 0; $i < $j; $i++)
		{
			if ( $i != $j - 1 ) {
				$key .= $comma[$i].',
';
			} else {
				$key .= $comma[$i].'
';
			}
		}
		$ret .= $key;
$ret .= ') ENGINE=MyISAM;';
		return $ret;
    }
}