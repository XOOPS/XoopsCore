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
 * @version         $Id: locales.php 10607 2012-12-30 00:36:57Z timgno $
 */

class TDMCreateLocalesForm extends Xoops\Form\ThemeForm
{ 
	/**
     * @param TDMCreateLocale|XoopsObject $obj
     */
	public function __construct(TDMCreateLocales &$obj)
	{
		$xoops = Xoops::getInstance();
				
		$title = $obj->isNew() ? TDMCreateLocale::A_ADD_LOCALE : PageLocale::A_EDIT_LOCALE;
        parent::__construct($title, 'form', 'locales.php', 'post', true);
		
		$this->addElement(new XoopsFormText(XoopsLocale::LOCALE_FILE_NAME, 'loc_file', 50, 255, $obj->getVar('loc_file')), true);
		$this->addElement(new XoopsFormText(XoopsLocale::LOCALE_DEFINE, 'loc_define', 50, 255, $obj->getVar('loc_define')), true);
		$this->addElement(new XoopsFormText(XoopsLocale::LOCALE_DESCRIPTION, 'loc_description', 50, 255, $obj->getVar('loc_description')), true);
		
		$this->addElement(new XoopsFormHidden('op', 'save' ) );
        $this->addElement(new XoopsFormButton('', 'submit', XoopsLocale::A_SUBMIT, 'submit' ) );
	}	
}