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
 * @version         $Id: files.php 10665 2012-12-27 10:14:15Z timgno $
 */
defined('XOOPS_ROOT_PATH') or die("Restricted access");

class TDMCreateFolder extends XoopsFolderHandler 
{
    /**
     * Constructor.
     *
     * @param string $path Path to folder
     * @param bool $create Create folder if not found
     * @param mixed $mode Mode (CHMOD) to apply to created folder, false to ignore
     */
    public function __construct($path = '', $create = true, $mode = false)
    {
        parent::__construct($path, $create, $mode);
    }
	
	/**
     * Create a directory.
     *
     * @param string $path The directory structure to create
     * @param int|bool $mode octal value 0755
     * @access public
     */
	public function create($path = '', $mode = false)
	{
	    $this->create($path, $mode);
	}
	
	/**
     * Change the mode on a directory structure recursively.
     *
     * @param string $path The path to chmod
     * @param int|bool  $mode octal value 0755
     * @param bool $recursive chmod recursively
     * @param array $exceptions array of files, directories to skip
     * @return bool Returns TRUE on success, FALSE on failure
     * @access public
     */
    public function chmod($path, $mode)
    {	
	    $this->chmod($path, $mode);
	}
}

class TDMCreateFile extends XoopsFileHandler 
{
    /**
     * Constructor
     *
     * @param string $path Path to file
     * @param boolean $create Create file if it does not exist (if true)
     * @param integer $mode Mode to apply to the folder holding the file
     * @access private
     */
    public function __construct($path, $create = false, $mode = 0755)
    {
        parent::__construct($path, $create, $mode);  
        $this->create();		
    }
	
	/** 
     * 
     * @param string $path_name                         
     * @param string $module_name         
     * @param string $dirname     
	 * @param string $file
     */
    public function getDirFile($path_name, $module_name, $dirname, $file)
    {
		return $path_name . '/' . $module_name . '/' . $dirname . '/' . $file;
	}
	
	/** 
     * 
     * @param string $path                         
     * @param string $text
	 * @param string $lng_ok
     * @param string $lng_notok   
	 * @param string $file
     */
    public function createFile($path, $text, $lng_ok, $lng_notok, $file)
    {
		$this->path = $path;
		$this->open('a+');
		if ($this->writable())
		{
			if ($this->write($text) === false) {
				echo '<tr>
					 <td>'.sprintf($lng_notok, $file).'</td>
					 <td><img src='. $pathIcon16 .'/off.png></td>
				  </tr>';
				exit;
			}
			echo '<tr>
						  <td>'.sprintf($lng_ok, $file).'</td>
						  <td><img src='. $pathIcon16 .'/on.png></td>
				  </tr>';	   
			$this->close();   
		} else {
			echo '<tr>
						  <td>'.sprintf($lng_notok, $file).'</td>
						  <td><img src='. $pathIcon16 .'/off.png></td>
				  </tr>';
		}
	}
}