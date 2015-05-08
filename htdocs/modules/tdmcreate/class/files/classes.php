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
 * @version         $Id: classes.php 10665 2012-12-27 10:14:15Z timgno $
 */
defined('XOOPS_ROOT_PATH') or die("Restricted access");

class TDMCreateClasses extends TDMCreateFile
{
    /**
     * Constructor
     *
     * @param TDMCreateFile|null $file
     * @param string $module
     */
	public function __construct(TDMCreateFile $file = null, $module = '', $text = '')
    {
        if (isset($file)) {
            $this->create($file, $module);
			$this->text = $text;
        }
    }
	
	/**
     * @param string $module
	 * @param string $module_name
     * @return $this->text
     */
    public function classFile($module = null, $module_name = null)
    {
        $this->text[] = '<?php';
		$this->text[] = TDMCreateCommon::getCommonHeader($module);
		return $this->text;
    }
	
	/** 
     * 
     * @param string $var                                 
     * @param object $data_type	
	 * @param boolean $required	
	 * @param integer $maxlength	
	 * @param string $options	
     */
    public function classInitVar($key, $data_type = 'INT', $required = false, $maxlength = null, $options = '')
    {	  
     	$r = $required == true ? ', ' . $required : '';
		$m = ($maxlength != null) ? ', ' . $maxlength : $maxlength;
		$o = ($options != '') ? ', ' . $options : $options;
	    return '$this->initVar(\''.$key.'\', XOBJ_DTYPE_'. $data_type .', null' . $r . $m . $o .');';
    }
	
	/**
	 * @param string $caption
	 * @param string $name
	 * @param integer $width
	 * @param integer $byte
	 * @param boolean $required
     * @return $this->text
     */
    public function classXoopsFormText($caption, $name, $width = 50, $byte = 255, $required = false)
    {
        $req = $required == true ? ', true' : '';
		$this->text[] = '// '.ucfirst($name).'
			$this->addElement(new XoopsFormText('.$caption.', "'.$name.'", '.$width.', '.$byte.', $obj->getVar(\''.$name.'\'))'.$req.');'.PHP_EOL;		
		return $this->text;
    }
	
	/**
	 * @param string $caption
	 * @param string $name
	 * @param string $value
	 * @param integer $rows
	 * @param integer $cols
	 * @param boolean $required
     * @return $this->text
     */
    public function classXoopsFormTextArea($caption, $name, $value = '', $rows = 5, $cols = 50, $required = false)
    {
        $req = $required == true ? ', true' : '';
		$this->text[] = '// '.ucfirst($name).'
			$this->addElement(new XoopsFormTextArea('.$caption.', "'.$name.'", $obj->getVar(\''.$value.'\'), '.$rows.', '.$cols.')'.$req.');'.PHP_EOL;		
		return $this->text;
    }
	
	/**
	 * @param string $caption
	 * @param string $name
	 * @param string $value
	 * @param integer $rows
	 * @param integer $cols
	 * @param boolean $required
     * @return $this->text
     */
    public function classXoopsFormDhtmlTextArea($caption, $name, $value = '', $rows = 10, $cols = 80, $required = false)
    {
        $req = $required == true ? ', true' : '';
		$this->text[] = '// '.ucfirst($name).'
			$editor_configs = array();
			$editor_configs["name"] = \''.$name.'\';
			$editor_configs["value"] = $obj->getVar(\''.$value.'\', \'e\');
			$editor_configs["rows"] = '.$rows.';
			$editor_configs["cols"] = '.$cols.';
			$editor_configs["width"] = "100%";
			$editor_configs["height"] = "400px";
			$editor_configs["editor"] = $xoops->getModuleConfig(\'editor\');				
			$form->addElement( new XoopsFormEditor('.$caption.', \''.$name.'\', $editor_configs)'.$req.' );'.PHP_EOL;		
		return $this->text;
    }
	
	/**
	 * @param string $caption
	 * @param string $name
	 * @param string $value
	 * @param boolean $required
     * @return $this->text
     */
    public function classXoopsFormCheckBox($caption, $name, $value = '', $required = false)
    {
        $req = $required == true ? ', true' : '';
		$this->text[] = '// '.ucfirst($name).'
			$'.$name.' = $obj->isNew() ? 0 : $obj->getVar(\''.$value.'\');
			$check_'.$name.' = new XoopsFormCheckBox('.$caption.', \''.$name.'\', $'.$name.');
			$check_'.$name.'->addOption(1, \' \');
			$this->addElement($check_'.$name.$req.');'.PHP_EOL;		
		return $this->text;
    }
	
	/**
	 * @param string $caption
	 * @param string $name
	 * @param string $value
	 * @param boolean $required
     * @return $this->text
     */
    public function classXoopsFormRadioYN($caption, $name, $value = '', $required = false)
    {
        $req = $required == true ? ', true' : '';
		$this->text[] = '// '.ucfirst($name).'			
			$'.$name.' = $obj->isNew() ? 0 : $obj->getVar(\''.$value.'\');
			$form->addElement(new XoopsFormRadioYN('.$caption.', \''.$name.'\', $'.$name.')'.$req.');'.PHP_EOL;		
		return $this->text;
    }
	
	/**
	 * @param string $name
	 * @param string $value
     * @return $this->text
     */
    public function classXoopsFormHidden($name, $value = '')
    {        
		$this->text[] = '// '.ucfirst($name).'
			$this->addElement(new XoopsFormHidden(\''.$name.'\', $obj->getVar(\''.$value.'\')));'.PHP_EOL;		
		return $this->text;
    }
	
	/**
	 * @param string $caption
	 * @param string $module
	 * @param string $table
	 * @param string $name
	 * @param string $value
	 * @param boolean $required
     * @return $this->text
     */
    public function classXoopsFormUploadImage($caption, $module, $table, $name, $value = '', $required = false)
    {    
		$req = $required == true ? ', true' : '';
		$this->text[] = '// '.ucfirst($name).'
			$'.$name.' = $obj->getVar(\''.$value.'\') ? $obj->getVar(\''.$value.'\') : \'blank.gif\';		
			$uploadir = \'/uploads/'.$module.'/'.$table.'/'.$name.'\';
			$imgtray = new XoopsFormElementTray(XoopsLocale::A_, \'<br />\');
			$imgpath = sprintf('.$caption.'FORMIMAGE_PATH, $uploadir);
			$imageselect = new XoopsFormSelect($imgpath, \''.$name.'\', $'.$name.');
			$image_array = XoopsLists :: getImgListAsArray( XOOPS_ROOT_PATH.$uploadir );
			foreach( $image_array as $image ) {
				$imageselect->addOption("{$image}", $image);
			}
			$imageselect->setExtra( "onchange=\'showImgSelected(\"image_'.$name.'\", \"'.$name.'\", \"".$uploadir."\", \"\", \"".XOOPS_URL."\")\'" );
			$imgtray->addElement($imageselect);
			$imgtray->addElement( new XoopsFormLabel( \'\', "<br /><img src=\'".XOOPS_URL."/".$uploadir."/".$'.$name.'."\' name=\'image_'.$name.'\' id=\'image_'.$name.'\' alt=\'\' />" ) );		
			$fileseltray = new XoopsFormElementTray(\'\',\'<br />\');
			$fileseltray->addElement(new XoopsFormFile('.$caption.'FORMUPLOAD , "'.$name.'", $xoops->getModuleConfig(\'maxsize\')));
			$fileseltray->addElement(new XoopsFormLabel(\'\'));
			$imgtray->addElement($fileseltray);
			$this->addElement($imgtray);'.PHP_EOL;		
		return $this->text;
    }
	
	/**
	 * @param string $caption
	 * @param string $name
	 * @param boolean $required
     * @return $this->text
     */
    public function classXoopsFormUploadFile($caption, $name, $required = false)
    {
        $req = $required == true ? ', true' : '';
		$this->text[] = '// '.ucfirst($name).'			
			$this->addElement(new XoopsFormFile('.$caption.', \''.$name.'\', $xoops->getModuleConfig(\'maxsize\'))'.$req.');'.PHP_EOL;		
		return $this->text;
    }
	
	/**
	 * @param string $caption
	 * @param string $name
	 * @param string $value
	 * @param boolean $required
     * @return $this->text
     */
    public function classXoopsFormColorPicker($caption, $name, $value = '', $required = false)
    {
        $req = $required == true ? ', true' : '';
		$this->text[] = '// '.ucfirst($name).'			
			$this->addElement(new XoopsFormColorPicker('.$caption.', \''.$name.'\', $obj->getVar(\''.$value.'\'))'.$req.');'.PHP_EOL;		
		return $this->text;
    }
	
	/**
	 * @param string $caption
	 * @param string $name
	 * @param string $value
	 * @param boolean $required
     * @return $this->text
     */
    public function classXoopsFormSelectUser($caption, $name, $value = '', $required = false)
    {
        $req = $required == true ? ', true' : '';
		$this->text[] = '// '.ucfirst($name).'			
			$this->addElement(new XoopsFormSelectUser('.$caption.', \''.$name.'\', false, $obj->getVar(\''.$value.'\'), 1, false)'.$req.');'.PHP_EOL;		
		return $this->text;
    }
	
	/**
	 * @param string $caption
	 * @param string $name
	 * @param string $value
	 * @param boolean $required
     * @return $this->text
     */
    public function classXoopsFormTextDateSelect($caption, $name, $value = '', $required = false)
    {
        $req = $required == true ? ', true' : '';
		$this->text[] = '// '.ucfirst($name).'			
			$this->addElement(new XoopsFormTextDateSelect('.$caption.', \''.$name.'\', $obj->getVar(\''.$value.'\'))'.$req.');'.PHP_EOL;		
		return $this->text;
    }
}