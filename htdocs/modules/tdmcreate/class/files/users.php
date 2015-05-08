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
 * @version         $Id: user.php 10665 2012-12-27 10:14:15Z timgno $
 */
defined('XOOPS_ROOT_PATH') or die("Restricted access");

class TDMCreateUser extends TDMCreateFile
{
    /**
     * File
     *
     * @var array of {@link TDMCreateFile} objects
     */
    protected $userFile = array();

    /**
     * Modules
     *
     * @var array
     */
    protected $module = array();
	
	/**
     * Text
     *
     * @var array
     */
    public $text = array();	
		
	/**
     * Userpager
     *
     * @var boolean
     */
    private $userpager = false;
	
	/**
     * Adminpager
     *
     * @var boolean
     */
    private $adminpager = false;
	
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
     * @param TDMCreateFile $adminFile
     * @param string $module
     * @return TDMCreateFile
     */
    public function create(TDMCreateFile $userFile, $module = '')
    {
        $this->userFile[] = $userFile;
        $this->module[] = $module;
        return $this;
    }
	
	/**
     * @param string $module
	 * @param string $module_name
     * @return $this->text
     */
    public function userHeader($module = null, $module_name = null, $tables_arr = array())
    {
        $this->text[] = '<?php';
		$this->text[] = TDMCreateCommon::getCommonHeader($module);
		$this->text[] = 'require_once dirname(dirname(dirname(__FILE__))) . \'/mainfile.php\';';
		$this->text[] = 'include_once dirname(dirname(__FILE__)) . \'/include/common.php\';';
		$this->text[] = 'include_once dirname(dirname(__FILE__)) . \'/include/functions.php\';';
		$this->text[] = '// Get main instance';
		$this->text[] = 'XoopsLoad::load(\'system\', \'system\');';
		$this->text[] = '$system = System::getInstance();';
		$this->text[] = '// Get main locale instance';
		$this->text[] = '$xoops = Xoops::getInstance();';
		$this->text[] = '$helper = '.ucfirst($module_name).'::getInstance();';
		$this->text[] = '$request = $xoops->request();';				
		foreach (array_keys($tables_arr) as $i)
		{	
			$table_name = $tables_arr[$i]->getVar("table_name");	
			$this->text[] = '// Get handler '.ucfirst($table_name);					
			$this->text[] = '$'.strtolower($table_name).'_Handler = $helper->getHandler'.ucfirst($table_name).'();';
		}
		$this->text[] = '// Get $_POST, $_GET, $_REQUEST';		
		$this->text[] = '$start = $request->asInt(\'start\', 0);';		
		if ($this->getUserPager()) 
		{
			$this->text[] = '// Parameters';
			$this->text[] = '$nb_pager = $helper->getConfig(\'adminpager\');';
		}		
		return $this->text;
    }
	
	/**
     * @param string $module
	 * @param string $module_name
     * @return $this->text
     */
    public function userIndex($module = null, $module_name = null)
    {
        $this->text[] = '<?php';
		$this->text[] = TDMCreateCommon::getCommonHeader($module);
		return $this->text;
    }
	
	/**
     * @param string $module
	 * @param string $module_name
     * @return $this->text
     */
    public function userFooter($module = null, $module_name = null)
    {
        $this->text[] = '<?php';
		$this->text[] = TDMCreateCommon::getCommonHeader($module);
		$this->text[] = '$xoops->footer();';
		return $this->text;
    }
	
	/**
     * @param string $module
	 * @param string $module_name
     * @return $this->text
     */
    public function userPages($module = null, $module_name = null)
    {
        $this->text[] = '<?php';
		$this->text[] = TDMCreateCommon::getCommonHeader($module);
		return $this->text;
    }
	
	/**
     * @param string $module
	 * @param string $module_name
     * @return $this->text
     */
    public function userXoopsVersion($module = null, $module_name = null, $tables_arr = array())
    {
		$menu = 1;
        $this->text[] = '<?php';
		$this->text[] = TDMCreateCommon::getCommonHeader($module);	
		$this->text[] = '$modversion = array();';
		$this->text[] = '$modversion[\'name\'] = '.ucfirst($module_name).'Locale::MODULE_NAME;';
		$this->text[] = '$modversion[\'description\'] = '.ucfirst($module_name).'Locale::MODULE_DESC;';
		$this->text[] = '$modversion[\'version\'] = 1;';
		$this->text[] = '$modversion[\'author\'] = \'Xoops Core Development Team\';';
		$this->text[] = '$modversion[\'nickname\'] = \'TXMod Xoops\';';
		$this->text[] = '$modversion[\'credits\'] = \'The XOOPS Project\';';
		$this->text[] = '$modversion[\'license\'] = \'GNU GPL 2.0\';';
		$this->text[] = '$modversion[\'license_url\'] = \'www.gnu.org/licenses/gpl-2.0.html/\';';
		$this->text[] = '$modversion[\'official\'] = 1;';
		$this->text[] = '$modversion[\'help\'] = \'page=help\';';
		$this->text[] = '$modversion[\'image\'] = \'images/logo.png\';';
		$this->text[] = '$modversion[\'dirname\'] = \''.strtolower($module_name).'\';';

		$this->text[] = '//about';
		$this->text[] = '$modversion[\'release_date\'] = \'2013/01/01\';';
		$this->text[] = '$modversion[\'module_website_url\'] = \'http://www.xoops.org/\';';
		$this->text[] = '$modversion[\'module_website_name\'] = \'XOOPS\';';
		$this->text[] = '$modversion[\'module_status\'] = \'Alpha\';';
		$this->text[] = '$modversion[\'min_php\'] = \'5.3\';';
		$this->text[] = '$modversion[\'min_xoops\'] = \'2.6.0\';';
		$this->text[] = '$modversion[\'min_db\'] = array(\'mysql\'=>\'5.0.7\', \'mysqli\'=>\'5.0.7\');';

		$this->text[] = '// paypal';
		$this->text[] = '$modversion[\'paypal\'] = array();';
		$this->text[] = '$modversion[\'paypal\'][\'business\'] = \'xoopsfoundation@gmail.com\';';
		$this->text[] = '$modversion[\'paypal\'][\'item_name\'] = \'Donation : \' . '.ucfirst($module_name).'Locale::MODULE_DESC;';
		$this->text[] = '$modversion[\'paypal\'][\'amount\'] = 0;';
		$this->text[] = '$modversion[\'paypal\'][\'currency_code\'] = \'USD\';';
        
        $this->text[] = '// Admin menu';
		$this->text[] = '// Set to 1 if you want to display menu generated by system module';
		$this->text[] = '$modversion[\'system_menu\'] = 1;';
		$this->text[] = '// Admin things';
		$this->text[] = '$modversion[\'hasAdmin\'] = 1;';
		$this->text[] = '$modversion[\'adminindex\'] = \'admin/index.php\';';
		$this->text[] = '$modversion[\'adminmenu\'] = \'admin/menu.php\';';
		$this->text[] = '// Scripts to run upon installation or update';
		$this->text[] = '$modversion[\'onInstall\'] = \'include/install.php\';';
		$this->text[] = '// JQuery';
		$this->text[] = '$modversion[\'jquery\'] = 1;';
		$this->text[] = '// Menu';
		$this->text[] = '$modversion[\'hasMain\'] = 1;';
		$this->text[] = '// Mysql file';
		$this->text[] = '$modversion[\'sqlfile\'][\'mysql\'] = \'sql/mysql.sql\';';

		$this->text[] = '// Tables created by sql file (without prefix!)';
		foreach (array_keys($tables_arr) as $i)
		{
			$table_name = $tables_arr[$i]->getVar("table_name");
			$this->text[] = '$modversion[\'tables\'][1] = \''.strtolower($module_name).'_'.strtolower($table_name).'\';';	
		}
		$this->text[] = '// blocks';
		$this->text[] = '$i = 0;';
		$this->text[] = '$modversion[\'blocks\'][$i][\'file\']        = \''.strtolower($module_name).'_blocks.php\';';
		$this->text[] = '$modversion[\'blocks\'][$i][\'name\']        = '.ucfirst($module_name).'Locale::BLOCKS_'.strtoupper($module_name).';';
		$this->text[] = '$modversion[\'blocks\'][$i][\'description\'] = '.ucfirst($module_name).'Locale::BLOCKS_'.strtoupper($module_name).'_DESC;';
		$this->text[] = '$modversion[\'blocks\'][$i][\'show_func\']   = \''.strtolower($module_name).'_blocks_show\';';
		$this->text[] = '$modversion[\'blocks\'][$i][\'edit_func\']   = \''.strtolower($module_name).'_blocks_edit\';';
		$this->text[] = '$modversion[\'blocks\'][$i][\'options\']     = \'1|2|3|4|5\';';
		$this->text[] = '$modversion[\'blocks\'][$i][\'template\']    = \''.strtolower($module_name).'_blocks.html\';';
		$this->text[] = '$i++;';
		
		$this->text[] = '// Preferences';
		$this->text[] = '$i = 0;';
		$this->text[] = '$editors = XoopsLists::getDirListAsArray(XOOPS_ROOT_PATH . \'/class/xoopseditor\');';
		$this->text[] = '$modversion[\'config\'][$i][\'name\']        = \'editor\';';
		$this->text[] = '$modversion[\'config\'][$i][\'title\']       = '.ucfirst($module_name).'Locale::CONF_EDITOR;';
		$this->text[] = '$modversion[\'config\'][$i][\'description\'] = \'\';';
		$this->text[] = '$modversion[\'config\'][$i][\'formtype\']    = \'select\';';
		$this->text[] = '$modversion[\'config\'][$i][\'valuetype\']   = \'text\';';
		$this->text[] = '$modversion[\'config\'][$i][\'default\']     = \'dhtmltextarea\';';
		$this->text[] = '$modversion[\'config\'][$i][\'options\']     = $editors;';
		if ($this->getAdminPager())	
		{
			$this->text[] = '$i++;';
			$this->text[] = '$modversion[\'config\'][$i][\'name\']        = \''.strtolower($module_name).'_adminpager\';';
			$this->text[] = '$modversion[\'config\'][$i][\'title\']       = '.ucfirst($module_name).'Locale::CONF_ADMINPAGER;';
			$this->text[] = '$modversion[\'config\'][$i][\'description\'] = \'\';';
			$this->text[] = '$modversion[\'config\'][$i][\'formtype\']    = \'textbox\';';
			$this->text[] = '$modversion[\'config\'][$i][\'valuetype\']   = \'int\';';
			$this->text[] = '$modversion[\'config\'][$i][\'default\']     = 20;';
		}
		if ($this->getUserPager())	
		{
			$this->text[] = '$i++;';
			$this->text[] = '$modversion[\'config\'][$i][\'name\']        = \''.strtolower($module_name).'_userpager\';';
			$this->text[] = '$modversion[\'config\'][$i][\'title\']       = '.ucfirst($module_name).'Locale::CONF_USERPAGER;';
			$this->text[] = '$modversion[\'config\'][$i][\'description\'] = \'\';';
			$this->text[] = '$modversion[\'config\'][$i][\'formtype\']    = \'textbox\';';
			$this->text[] = '$modversion[\'config\'][$i][\'valuetype\']   = \'int\';';
			$this->text[] = '$modversion[\'config\'][$i][\'default\']     = 20;';			
		}
		$this->text[] = 'unset($i);';
        return $this->text;
    }	
	
	/**
	 * @param null
     */
    private function getUserPager() 
	{
	    return $this->userpager;
	}
	
	/**
	 * @param null
     */
    private function getAdminPager() 
	{
	    return $this->adminpager;
	}
}