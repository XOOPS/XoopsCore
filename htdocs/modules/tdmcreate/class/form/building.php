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
 * @version         $Id: building.php 10607 2012-12-30 00:36:57Z timgno $
 */

class TDMCreateBuildingForm extends Xoops\Form\ThemeForm
{ 
	/**
     * @param TDMCreateBuilding|XoopsObject $obj
     */
	public function __construct(TDMCreateBuilding &$obj)
	{
		parent::__construct(TDMCreateLocale::BUILDING_TITLE, 'form', 'building.php', 'post', true, 'raw');
		
		$moduleSelect = new Xoops\Form\Select(TDMCreateLocale::BUILDING_MODULES, 'mod_name', 'mod_name');
		$moduleSelect->addOption(0, TDMCreateLocale::BUILDING_SELECT_DEFAULT_MODULES);
		$moduleSelect->addOptionArray($modulesHandler->getList());
		$form->addElement($moduleSelect);
		
		$this->addElement(new XoopsFormHidden('op', 'build' ) );
        $this->addElement(new XoopsFormButton('', 'submit', XoopsLocale::A_SUBMIT, 'submit' ) );
	}	
}