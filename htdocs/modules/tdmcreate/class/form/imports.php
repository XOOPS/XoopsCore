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
 * @version         $Id: imports.php 10607 2012-12-30 00:36:57Z timgno $
 */

class TDMCreateImportsForm extends Xoops\Form\ThemeForm
{ 
	/**
     * @param TDMCreateImports|XoopsObject $obj
     */
	public function __construct(TDMCreateImports &$obj)
	{
		$xoops = Xoops::getInstance();
		
		parent::__construct(TDMCreateLocale::IMPORT_TITLE, 'form', false, 'post', true);
        $this->setExtra('enctype="multipart/form-data"');
		
		$this->addElement(new Xoops\Form\Text(XoopsLocale::NAME, 'import_name', 50, 255, $obj->getVar('import_name')), true);
		
		$filetray = new Xoops\Form\ElementTray('','<br />');
		$filetray->addElement(new Xoops\Form\File(XoopsLocale::A_UPLOAD , 'importfile', $xoops->getModuleConfig('maxuploadsize')));
		$filetray->addElement(new Xoops\Form\Label(''));		
		$this->addElement($filetray);
		
		$this->addElement(new Xoops\Form\Hidden('op', 'save' ) );
        $this->addElement(new Xoops\Form\Button('', 'upload', XoopsLocale::A_SUBMIT, 'submit' ) );
	}	
}