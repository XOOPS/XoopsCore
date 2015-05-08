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
 * @author          TDM Xoops (AKA Developers)
 * @version         $Id: tables.php 11387 2013-04-16 15:19:57Z txmodxoops $
 */	
defined('XOOPS_ROOT_PATH') or die('XOOPS root path not defined');

class TDMCreateTablesForm extends Xoops\Form\ThemeForm
{ 
	/**
     * @param TDMCreateTables|XoopsObject $obj
     */
	public function __construct(TDMCreateTables &$obj)
	{	    
		$xoops = Xoops::getInstance();
		$tdmcreate = TDMCreate::getInstance();
		
		$title = $obj->isNew() ? sprintf(TDMCreateLocale::TABLE_ADD) : sprintf(TDMCreateLocale::TABLE_EDIT);
		
		//parent::__construct($title, 'form', 'tables.php', 'post', true);
		parent::__construct($title, 'form', $xoops->getEnv('PHP_SELF'));
        $this->setExtra('enctype="multipart/form-data"');
		
		if (!$obj->isNew()) {		
		    $this->addElement(new Xoops\Form\Hidden('id', $obj->getVar('table_id')));
		}
		
		$tabtray = new Xoops\Form\TabTray('', 'uniqueid', $xoops->getModuleConfig('jquery_theme', 'system'));
		
		$tab1 = new Xoops\Form\Tab(TDMCreateLocale::IMPORTANT, 'important');

        $modules_Handler = $xoops->getModuleHandler('modules');
    	$mods_select = new Xoops\Form\Select(TDMCreateLocale::MODULES_LIST, 'table_mid', $obj->getVar('table_mid'));
    	$mods_select->addOptionArray($modules_Handler->getList());
    	$tab1->addElement($mods_select, true);		
		
		$table_name = new Xoops\Form\Text(TDMCreateLocale::TABLE_NAME, 'table_name', 50, 255, $obj->getVar('table_name'));
		$table_name->setDescription(TDMCreateLocale::TABLE_NAME_DESC);
		$tab1->addElement($table_name, true);
		$table_fieldname = new Xoops\Form\Text(TDMCreateLocale::TABLE_FIELD_NAME, 'table_fieldname', 3, 50, $obj->getVar('table_fieldname'));
		$table_fieldname->setDescription(TDMCreateLocale::TABLE_FIELD_NAME_DESC);
		$tab1->addElement($table_fieldname);
		$table_nbfield = new Xoops\Form\Text(TDMCreateLocale::TABLE_FIELDS_NUMBER, 'table_nbfields', 2, 50, $obj->getVar('table_nbfields'));
		$table_nbfield->setDescription(TDMCreateLocale::TABLE_FIELDS_NUMBER_DESC);
		$tab1->addElement($table_nbfield, true);		
		// table_image	
		$table_image = $obj->getVar('table_image') ? $obj->getVar('table_image') : 'blank.gif';	
		$uploadir = 'media/xoops/images/icons/32';
		$imgtray = new Xoops\Form\ElementTray(TDMCreateLocale::C_IMAGE,'<br />');
		$imgpath = sprintf(TDMCreateLocale::CF_IMAGE_PATH, './'.$uploadir.'/');
		$imageselect = new Xoops\Form\Select($imgpath, 'tables_image', $table_image, 5);
		$image_array = XoopsLists::getImgListAsArray( XOOPS_ROOT_PATH.'/'.$uploadir );
		foreach( $image_array as $image ) {
			$imageselect->addOption("{$image}", $image);
		}
		$imageselect->setExtra( "onchange='showImgSelected(\"image3\", \"tables_image\", \"".$uploadir."\", \"\", \"".XOOPS_URL."\")'" );
		$imgtray->addElement($imageselect);
		$imgtray->addElement( new Xoops\Form\Label( '', "<br /><img src='".XOOPS_URL."/".$uploadir."/".$table_image."' name='image3' id='image3' alt='' />" ) );		
		$fileseltray = new Xoops\Form\ElementTray('','<br />');
		$fileseltray->addElement(new Xoops\Form\File(XoopsLocale::A_UPLOAD, 'attachedfile', $xoops->getModuleConfig('maxuploadsize')));
		$fileseltray->addElement(new Xoops\Form\Label(''));
		$imgtray->addElement($fileseltray);
		$tab1->addElement($imgtray);
		
		$options_tray = new Xoops\Form\ElementTray(XoopsLocale::OPTIONS, '<br />');
			$table_checkbox_all = new Xoops\Form\CheckBox('', "tablebox", 1);
			$table_checkbox_all->addOption('allbox', TDMCreateLocale::C_CHECK_ALL);
			$table_checkbox_all->setExtra(" onclick='xoopsCheckAll(\"form\", \"tablebox\");' ");
			$table_checkbox_all->setClass('xo-checkall');
			$options_tray->addElement($table_checkbox_all);
			$table_blocks = $obj->isNew() ? 0 : $obj->getVar('table_blocks');
			$check_blocks = new Xoops\Form\CheckBox(' ', "table_blocks", $table_blocks);
			$check_blocks->addOption(1, TDMCreateLocale::TABLE_BLOCKS);
			$options_tray->addElement($check_blocks);
			$table_display_admin = $obj->isNew() ? 0 : $obj->getVar('table_display_admin');
			$check_display_admin = new Xoops\Form\CheckBox(' ', "table_admin", $table_display_admin);
			$check_display_admin->addOption(1, TDMCreateLocale::TABLE_ADMIN);
			$options_tray->addElement($check_display_admin);
			$table_display_user = $obj->isNew() ? 0 : $obj->getVar('table_display_user');
			$check_display_user = new Xoops\Form\CheckBox(' ', "table_user", $table_display_user);
			$check_display_user->addOption(1, TDMCreateLocale::TABLE_USER);
			$options_tray->addElement($check_display_user);
			$table_submenu = $obj->isNew() ? 0 : $obj->getVar('table_submenu');
			$check_submenu = new Xoops\Form\CheckBox(' ', "table_submenu", $table_submenu);
			$check_submenu->addOption(1, TDMCreateLocale::TABLE_SUBMENU);
			$options_tray->addElement($check_submenu);
			$table_search = $obj->isNew() ? 0 : $obj->getVar('table_search');
			$check_search = new Xoops\Form\CheckBox(' ', "table_search", $table_search);
			$check_search->addOption(1, TDMCreateLocale::TABLE_SEARCH);
			$options_tray->addElement($check_search);
			$table_comments = $obj->isNew() ? 0 : $obj->getVar('table_comments');
			$check_comments = new Xoops\Form\CheckBox(' ', "table_comments", $table_comments);
			$check_comments->addOption(1, TDMCreateLocale::TABLE_COMMENTS);
			$options_tray->addElement($check_comments);
			$table_notify = $obj->isNew() ? 0 : $obj->getVar('table_notifications');
			$check_notify = new Xoops\Form\CheckBox(' ', "table_notifications", $table_notify);
			$check_notify->addOption(1, TDMCreateLocale::TABLE_NOTIFICATIONS);
			$options_tray->addElement($check_notify);
		$tab1->addElement($options_tray);			
		
		$tab1->addElement(new Xoops\Form\Hidden('op', 'save'));
		$tab1->addElement(new Xoops\Form\Button('', 'submit', XoopsLocale::A_SUBMIT, 'submit'));
		$tabtray->addElement($tab1);
		$this->addElement($tabtray);
	}
}