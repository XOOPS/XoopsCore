<?php   
/*
 You may not change or alter any portion of this comment or credits
 of supporting developers from this source code or any supporting source code
 which is considered copyrighted (c) material of the original comment or credit authors.

 This program is distributed in the hope that it will be useful,
 but WITHOUT ANY WARRANTY; without even the implied warranty of
 MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
*/
use Xoops\Core\Database\Connection;
/**
 * tdmcreate module
 *
 * @copyright       The XOOPS Project http://sourceforge.net/projects/xoops/
 * @license         GNU GPL 2 (http://www.gnu.org/licenses/old-licenses/gpl-2.0.html)
 * @package         tdmcreate
 * @since           2.6.0
 * @author          TDM Xoops (AKA Developers)
 * @version         $Id: modules.php 10665 2012-12-27 10:14:15Z timgno $
 */
/**
 * Class TDMCreateModules
 */
class TDMCreateModules extends XoopsObject
{ 
	/**
     * Constructor
     */
	public function __construct()
	{	
	    $helper = Xoops\Module\Helper::getHelper('tdmcreate');	
		
		$this->initVar('mod_id', XOBJ_DTYPE_INT);
        $this->initVar('mod_name', XOBJ_DTYPE_TXTBOX, $helper->getConfig('name'));
		$this->initVar('mod_isextension', XOBJ_DTYPE_INT, $helper->getConfig('isextension'));
        $this->initVar('mod_dirname', XOBJ_DTYPE_TXTBOX, $helper->getConfig('dirname'));
        $this->initVar('mod_version', XOBJ_DTYPE_TXTBOX, $helper->getConfig('version'));
        $this->initVar('mod_since', XOBJ_DTYPE_TXTBOX, $helper->getConfig('since'));
        $this->initVar('mod_min_php', XOBJ_DTYPE_TXTBOX, $helper->getConfig('min_php'));
        $this->initVar('mod_min_xoops', XOBJ_DTYPE_TXTBOX, $helper->getConfig('min_xoops'));
        $this->initVar('mod_min_admin', XOBJ_DTYPE_TXTBOX, $helper->getConfig('min_admin'));
        $this->initVar('mod_min_mysql', XOBJ_DTYPE_TXTBOX, $helper->getConfig('min_mysql'));
        $this->initVar('mod_description', XOBJ_DTYPE_TXTAREA, $helper->getConfig('description'));
        $this->initVar('mod_author', XOBJ_DTYPE_TXTBOX, $helper->getConfig('author'));
        $this->initVar('mod_author_mail', XOBJ_DTYPE_TXTBOX, $helper->getConfig('author_email'));
        $this->initVar('mod_author_website_url', XOBJ_DTYPE_TXTBOX, $helper->getConfig('author_website_url'));
        $this->initVar('mod_author_website_name', XOBJ_DTYPE_TXTBOX, $helper->getConfig('author_website_name'));
        $this->initVar('mod_credits', XOBJ_DTYPE_TXTBOX, $helper->getConfig('credits'));
        $this->initVar('mod_license', XOBJ_DTYPE_TXTBOX, $helper->getConfig('license'));
        $this->initVar('mod_release_info', XOBJ_DTYPE_TXTBOX, $helper->getConfig('release_info'));
        $this->initVar('mod_release_file', XOBJ_DTYPE_TXTBOX, $helper->getConfig('release_file'));
        $this->initVar('mod_manual', XOBJ_DTYPE_TXTBOX, $helper->getConfig('manual'));
        $this->initVar('mod_manual_file', XOBJ_DTYPE_TXTBOX, $helper->getConfig('manual_file'));
        $this->initVar('mod_image', XOBJ_DTYPE_TXTBOX, null);
        $this->initVar('mod_demo_site_url', XOBJ_DTYPE_TXTBOX, $helper->getConfig('demo_site_url'));
        $this->initVar('mod_demo_site_name', XOBJ_DTYPE_TXTBOX, $helper->getConfig('demo_site_name'));
        $this->initVar('mod_support_url', XOBJ_DTYPE_TXTBOX, $helper->getConfig('support_url'));
        $this->initVar('mod_support_name', XOBJ_DTYPE_TXTBOX, $helper->getConfig('support_name'));
        $this->initVar('mod_website_url', XOBJ_DTYPE_TXTBOX, $helper->getConfig('website_url'));
        $this->initVar('mod_website_name', XOBJ_DTYPE_TXTBOX, $helper->getConfig('website_name'));
        $this->initVar('mod_release', XOBJ_DTYPE_TXTBOX, $helper->getConfig('release_date'));
        $this->initVar('mod_status', XOBJ_DTYPE_TXTBOX, $helper->getConfig('status'));
        $this->initVar('mod_admin', XOBJ_DTYPE_INT, $helper->getConfig('display_admin'));
        $this->initVar('mod_user', XOBJ_DTYPE_INT, $helper->getConfig('display_user'));
        $this->initVar('mod_blocks', XOBJ_DTYPE_INT, $helper->getConfig('active_blocks'));
        $this->initVar('mod_search', XOBJ_DTYPE_INT, $helper->getConfig('active_search'));
        $this->initVar('mod_comments', XOBJ_DTYPE_INT, $helper->getConfig('active_comments'));
        $this->initVar('mod_notifications', XOBJ_DTYPE_INT, $helper->getConfig('active_notifications'));
        $this->initVar('mod_permissions', XOBJ_DTYPE_INT, $helper->getConfig('active_permissions'));
        $this->initVar('mod_inroot_copy', XOBJ_DTYPE_INT, $helper->getConfig('inroot_copy'));
        $this->initVar('mod_donations', XOBJ_DTYPE_TXTBOX, $helper->getConfig('donations'));
        $this->initVar('mod_subversion', XOBJ_DTYPE_TXTBOX, $helper->getConfig('subversion'));
	}	
}
/**
 * Class TDMCreateModulesHandler
 */
class TDMCreateModulesHandler extends XoopsPersistableObjectHandler 
{
    /**
     * @param null|Connection $db
     */
	public function __construct(Connection $db = null)
	{
		parent::__construct($db, 'tdmcreate_modules', 'tdmcreatemodules', 'mod_id', 'mod_name');
	}
}