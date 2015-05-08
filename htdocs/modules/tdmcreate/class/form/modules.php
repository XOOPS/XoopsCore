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
 * @version         $Id: modules.php 11387 2013-04-16 15:19:57Z txmodxoops $
 */
defined('XOOPS_ROOT_PATH') or die("XOOPS root path not defined");

class TDMCreateModulesForm extends Xoops\Form\ThemeForm
{	
	/**
     * @param TDMCreateModules|XoopsObject $obj
     */
	public function __construct(TDMCreateModules &$obj)
	{
		$xoops = Xoops::getInstance();
		$tdmcreate = TDMCreate::getInstance();
	
		$title = $obj->isNew() ? sprintf(TDMCreateLocale::ADD_MODULE) : sprintf(TDMCreateLocale::EDIT_MODULE);
		//parent::__construct($title, 'form', 'modules.php', 'post', true);
		parent::__construct($title, 'form', $xoops->getEnv('PHP_SELF'));
        $this->setExtra('enctype="multipart/form-data"');
        //$this->setExtra('enctype="multipart/form-data"');
		$tabtray = new Xoops\Form\TabTray('', 'uniqueid', $xoops->getModuleConfig('jquery_theme', 'system'));
		// First Break
		//$this->insertBreak('<div class="center"><b>'.TDMCreateLocale::IMPORTANT.'</b></div>', 'head');
		$tab1 = new Xoops\Form\Tab(TDMCreateLocale::IMPORTANT, 'important');
		// Name of Module 
		$mod_tray = new Xoops\Form\ElementTray(TDMCreateLocale::C_MODULE_OR_EXTENSION, '<br />');
		$mod_tray->setDescription(TDMCreateLocale::C_MODULE_OR_EXTENSION_DESC);
		$mod_tray->addElement( new Xoops\Form\Text(TDMCreateLocale::C_NAME, 'mod_name', 50, 255, $obj->getVar('mod_name')), true);
		$is_extension = $obj->isNew() ? 0 : $obj->getVar('mod_isextension');
		$check_is_extension = new Xoops\Form\CheckBox(' ', 'isextension', $is_extension);
		$check_is_extension->addOption(1, TDMCreateLocale::QC_ISEXTENSION);
		$mod_tray->addElement($check_is_extension);
		$tab1->addElement($mod_tray);
		// Version module
		$tab1->addElement(new Xoops\Form\Text(TDMCreateLocale::C_VERSION, 'mod_version', 2, 4, $obj->getVar('mod_version')), true);
		// Editor
		$editor_configs=array();
		$editor_configs['name'] = 'mod_description';
		$editor_configs['value'] = $obj->getVar('mod_description', 'e');
		$editor_configs['rows'] = 4;
		$editor_configs['cols'] = 80;
		$editor_configs['editor'] = $tdmcreate->getConfig('editor');				
		$tab1->addElement( new Xoops\Form\Editor(XoopsLocale::DESCRIPTION, 'mod_description', $editor_configs), true );
		// Author module
		$tab1->addElement(new Xoops\Form\Text(XoopsLocale::C_AUTHOR, 'mod_author', 50, 255, $obj->getVar('mod_author')), true);
		$tab1->addElement(new Xoops\Form\Text(TDMCreateLocale::C_LICENSE, 'mod_license', 50, 255, $obj->getVar('mod_license')), true);
		$option_tray = new Xoops\Form\ElementTray(TDMCreateLocale::C_OPTIONS, '<br />');
			$mod_checkbox_all = new Xoops\Form\CheckBox('', "modulebox", 1);
			$mod_checkbox_all->addOption('allbox', TDMCreateLocale::C_CHECK_ALL);
			$mod_checkbox_all->setExtra(" onclick='xoopsCheckAll(\"form\", \"modulebox\");' ");
			$mod_checkbox_all->setClass('xo-checkall');
			$option_tray->addElement($mod_checkbox_all);
			$display_admin = $obj->isNew() ? 0 : $obj->getVar('mod_admin');
			$check_display_admin = new Xoops\Form\CheckBox(' ', 'mod_admin', $display_admin);
			$check_display_admin->addOption(1, TDMCreateLocale::C_ADMIN);
			$option_tray->addElement($check_display_admin);
			$display_user = $obj->isNew() ? 0 : $obj->getVar('mod_user');
			$check_display_user = new Xoops\Form\CheckBox(' ', 'mod_user', $display_user);
			$check_display_user->addOption(1, TDMCreateLocale::C_USER);
			$option_tray->addElement($check_display_user);
			$display_submenu = $obj->isNew() ? 0 : $obj->getVar('mod_submenu');
			$check_display_submenu = new Xoops\Form\CheckBox(' ', 'mod_submenu', $display_submenu);
			$check_display_submenu->addOption(1, TDMCreateLocale::C_SUBMENU);
			$option_tray->addElement($check_display_submenu);
			$display_blocks = $obj->isNew() ? 0 : $obj->getVar('mod_blocks');
			$check_display_blocks = new Xoops\Form\CheckBox(' ', 'mod_blocks', $display_blocks);
			$check_display_blocks->addOption(1, TDMCreateLocale::C_BLOCKS);
			$option_tray->addElement($check_display_blocks);
			$active_search = $obj->isNew() ? 0 : $obj->getVar('mod_search');
			$check_active_search = new Xoops\Form\CheckBox(' ', 'mod_search', $active_search);
			$check_active_search->addOption(1, TDMCreateLocale::C_SEARCH);
			$option_tray->addElement($check_active_search);
			$active_comments = $obj->isNew() ? 0 : $obj->getVar('mod_comments');
			$check_active_comments = new Xoops\Form\CheckBox(' ', 'mod_comments', $active_comments);
			$check_active_comments->addOption(1, TDMCreateLocale::C_COMMENTS);
			$option_tray->addElement($check_active_comments);
			$active_permissions = $obj->isNew() ? 0 : $obj->getVar('mod_permissions');
			$check_active_permissions = new Xoops\Form\CheckBox(' ', 'mod_permissions', $active_permissions);
			$check_active_permissions->addOption(1, TDMCreateLocale::C_PERMISSIONS);
			$option_tray->addElement($check_active_permissions);
			$active_notifications = $obj->isNew() ? 0 : $obj->getVar('mod_notifications');
			$check_active_notifications = new Xoops\Form\CheckBox(' ', 'mod_notifications', $active_notifications);
			$check_active_notifications->addOption(1, TDMCreateLocale::C_NOTIFICATIONS);
			$option_tray->addElement($check_active_notifications);			
			$active_inroot = $obj->isNew() ? 0 : $obj->getVar('mod_inroot_copy');
			$check_active_inroot = new Xoops\Form\CheckBox(' ', 'mod_inroot_copy', $active_inroot);
			$check_active_inroot->addOption(1, TDMCreateLocale::C_IN_ROOT);
			$option_tray->addElement($check_active_inroot);
		$tab1->addElement($option_tray);			    
		
		$module_image = $obj->getVar('mod_image') ? $obj->getVar('mod_image') : 'default_slogo.png';	
		$uploadir = 'uploads/tdmcreate/images/modules';
		$imgtray = new Xoops\Form\ElementTray(TDMCreateLocale::C_IMAGE,'<br /><br />');
		$imgpath = sprintf(TDMCreateLocale::CF_IMAGE_PATH, './'.$uploadir.'/');
		$imageselect = new Xoops\Form\Select($imgpath, 'modules_image', $module_image);
		$image_array = XoopsLists::getImgListAsArray( XOOPS_ROOT_PATH.'/'.$uploadir );
		foreach( $image_array as $image ) {
			$imageselect->addOption("$image", $image);
		}
		$imageselect->setExtra( "onchange='showImgSelected(\"image3\", \"modules_image\", \"".$uploadir."\", \"\", \"".XOOPS_URL."\")'" );
		$imgtray->addElement($imageselect);
		$imgtray->addElement( new XoopsFormLabel( '', "<br /><img src='".XOOPS_URL."/".$uploadir."/".$module_image."' name='image3' id='image3' alt='' />" ) );		
		$fileseltray = new Xoops\Form\ElementTray('','<br />');
		$fileseltray->addElement(new XoopsFormFile(XoopsLocale::A_UPLOAD , 'modules_image', $xoops->getModuleConfig('maxuploadsize')));
		$fileseltray->addElement(new XoopsFormLabel(''));
		$imgtray->addElement($fileseltray);
		$tab1->addElement($imgtray, true);
		
		$tabtray->addElement($tab1);

        /**
         * Not important
         */
        $tab2 = new Xoops\Form\Tab(TDMCreateLocale::NOT_IMPORTANT, 'not_important');
        // Second Break
		//$this->insertBreak('<div class="center"><b>'.TDMCreateLocale::NOT_IMPORTANT.'</b></div>','head');
		$tab2->addElement(new Xoops\Form\Text(TDMCreateLocale::C_AUTHOR_MAIL, 'mod_author_mail', 50, 255, $obj->getVar('mod_author_mail')));
		$tab2->addElement(new Xoops\Form\Text(TDMCreateLocale::C_AUTHOR_WEBSITE_URL, 'mod_author_website_url', 50, 255, $obj->getVar('mod_author_website_url')));
		$tab2->addElement(new Xoops\Form\Text(TDMCreateLocale::C_AUTHOR_WEBSITE_NAME, 'mod_author_website_name', 50, 255, $obj->getVar('mod_author_website_name')));
		$tab2->addElement(new Xoops\Form\Text(TDMCreateLocale::C_CREDITS, 'mod_credits', 50, 255, $obj->getVar('mod_credits')));	
		$tab2->addElement(new Xoops\Form\Text(TDMCreateLocale::C_RELEASE_INFO, 'mod_release_info', 50, 255, $obj->getVar('mod_release_info')));
		$tab2->addElement(new Xoops\Form\Text(TDMCreateLocale::C_RELEASE_FILE, 'mod_release_file', 50, 255, $obj->getVar('mod_release_file')));
		$tab2->addElement(new Xoops\Form\Text(TDMCreateLocale::C_MANUAL, 'mod_manual', 50, 255, $obj->getVar('mod_manual')));
		$tab2->addElement(new Xoops\Form\Text(TDMCreateLocale::C_MANUAL_FILE, 'mod_manual_file', 50, 255, $obj->getVar('mod_manual_file')));
		$tab2->addElement(new Xoops\Form\Text(TDMCreateLocale::C_DEMO_SITE_URL, 'mod_demo_site_url', 50, 255, $obj->getVar('mod_demo_site_url')));
		$tab2->addElement(new Xoops\Form\Text(TDMCreateLocale::C_DEMO_SITE_NAME, 'mod_demo_site_name', 50, 255, $obj->getVar('mod_demo_site_name')));
		$tab2->addElement(new Xoops\Form\Text(TDMCreateLocale::C_SUPPORT_URL, 'mod_support_url', 50, 255, $obj->getVar('mod_support_url')));
		$tab2->addElement(new Xoops\Form\Text(TDMCreateLocale::C_SUPPORT_NAME, 'mod_support_name', 50, 255, $obj->getVar('mod_support_name')));
		$tab2->addElement(new Xoops\Form\Text(TDMCreateLocale::C_WEBSITE_URL, 'mod_website_url', 50, 255, $obj->getVar('mod_website_url')));
		$tab2->addElement(new Xoops\Form\Text(TDMCreateLocale::C_WEBSITE_NAME, 'mod_website_name', 50, 255, $obj->getVar('mod_website_name')));
		$tab2->addElement(new Xoops\Form\Text(TDMCreateLocale::C_RELEASE, 'mod_release', 50, 255, $obj->getVar('mod_release')));
		$tab2->addElement(new Xoops\Form\Text(TDMCreateLocale::C_STATUS, 'mod_status', 50, 255, $obj->getVar('mod_status')));	
		
        $tab2->addElement(new Xoops\Form\Text(TDMCreateLocale::C_DONATIONS, 'mod_donations', 50, 255, $obj->getVar('mod_donations')));
		$tab2->addElement(new Xoops\Form\Text(TDMCreateLocale::C_SUBVERSION, 'mod_subversion', 50, 255, $obj->getVar('mod_subversion')));	         
        
		/**
         * Button submit
         */
        $buttontray = new Xoops\Form\ElementTray('', '');
        $buttontray->addElement(new Xoops\Form\Hidden('op', 'save'));
			
        $button = new Xoops\Form\Button('', 'submit', XoopsLocale::A_SUBMIT, 'submit' );
        $button->setClass('btn');
		$buttontray->addElement($button);
		$tab2->addElement($buttontray);
		$tabtray->addElement($tab2);
		
		if (!$obj->isNew()) {
            $this->addElement(new Xoops\Form\Hidden( 'id', $obj->getVar('mod_id') ) );
        }
		
		$this->addElement($tabtray);
	}
}