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
 * @version         $Id: forms.php 10665 2012-12-27 10:14:15Z timgno $
 */

defined('XOOPS_ROOT_PATH') or die("XOOPS root path not defined");

/**
 * Get {@link XoopsThemeForm} for editing a user
 *
 * @param bool $action
 * @return XoopsThemeForm
 */
function tdmcreate_getBuildingForm( $action = false )
{
	$xoops = Xoops::getInstance();

    if ($action === false) {
        $action = $_SERVER['REQUEST_URI'];
    }
	
	$modules_Handler = $xoops->getModuleHandler('modules');
    $extensions_Handler = $xoops->getModuleHandler('extensions');	
	$form = new XoopsSimpleForm(_AM_TDMCREATE_BUILDING_TITLE, 'building', $action, 'post', true);

	$mods_select = new XoopsFormSelect(_AM_TDMCREATE_MODULES, 'mod_name', 'mod_name');
	$mods_select->addOption(0, _AM_TDMCREATE_SELMODDEF);
	$mods_select->addOptionArray($modules_Handler->getList());
	$form->addElement($mods_select, false);	

    $exts_select = new XoopsFormSelect(_AM_TDMCREATE_EXTENSIONS, 'ext_name', 'ext_name');
	$exts_select->addOption(0, _AM_TDMCREATE_SELEXTDEF);
	$exts_select->addOptionArray($extensions_Handler->getList());
	$form->addElement($exts_select, false);		
	
	$form->addElement(new XoopsFormHidden('op', 'build'));
	$form->addElement(new XoopsFormButton('', 'submit', _SUBMIT, 'submit'));
	return $form;
}