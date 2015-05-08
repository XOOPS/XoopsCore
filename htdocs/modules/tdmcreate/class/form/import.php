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
 * @version         $Id: import.php 10607 2012-12-30 00:36:57Z timgno $
 */
defined('XOOPS_ROOT_PATH') or die('XOOPS root path not defined');

class TDMCreateImportForm extends XoopsThemeForm
{ 
	/**
     * @param TDMCreateImport|XoopsObject $obj
     */
	public function __construct(TDMCreateImport &$obj)
	{
		$xoops = Xoops::getInstance();
		
		parent::__construct(TDMCreateLocale::IMPORT_TITLE, 'form', false, 'post', true);
        $this->setExtra('enctype="multipart/form-data"');
		
		$this->addElement(new XoopsFormText(XoopsLocale::NAME, 'import_name', 50, 255, $obj->getVar('import_name')), true);
		
		$filetray = new XoopsFormElementTray('','<br />');
		$filetray->addElement(new XoopsFormFile(XoopsLocale::A_UPLOAD , 'importfile', $xoops->getModuleConfig('maxuploadsize')));
		$filetray->addElement(new XoopsFormLabel(''));		
		$this->addElement($filetray);
		
		$this->addElement(new XoopsFormHidden('op', 'save' ) );
        $this->addElement(new XoopsFormButton('', 'upload', XoopsLocale::A_SUBMIT, 'submit' ) );
	}	
}