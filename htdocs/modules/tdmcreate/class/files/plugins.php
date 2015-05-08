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
 * @version         $Id: plugins.php 10665 2012-12-27 10:14:15Z timgno $
 */
defined('XOOPS_ROOT_PATH') or die("Restricted access");

class TDMCreatePlugins extends TDMCreateFile
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
    public function pluginSubMenu($module = null, $module_name = null)
    {
        $this->text[] = '<?php';
		$this->text[] = TDMCreateCommon::getCommonHeader($module);    
		$this->text[] = 'defined("XOOPS_ROOT_PATH") or die("XOOPS root path not defined");

class '.ucfirst($module_name).'MenusPlugin extends Xoops_Module_Plugin_Abstract implements MenusPluginInterface
{
	/**
	 * expects an array of array containing:
	 * name,      Name of the submenu
	 * url,       Url of the submenu relative to the module
	 * ex: return array(0 => array(
	 *      \'name\' => _MI_PUBLISHER_SUB_SMNAME3;
	 *      \'url\' => "search.php";
	 *    ));
	 *
	 * @return array
	 */
	public function subMenus()
	{
		$ret = array();
		$files = XoopsLists::getFileListAsArray(dirname(dirname(dirname(__FILE__))));
		$i = 0;
		foreach ($files as $file) {
			if (!in_array($file, array(\'xoops_version.php\', \'index.php\'))) {
				$fileName = ucfirst(str_replace(\'.php\', '', $file));
				$ret[$i][\'name\'] = $fileName;
				$ret[$i][\'url\'] = $file;
				$i++;
			}
		}
		return $ret;
	}
}';
		return $this->text;
	}
	
	/**
     * @param string $module
	 * @param string $module_name
     * @return $this->text
     */
    public function pluginSearch($module = null, $module_name = null)
    {
        $this->text[] = '<?php';
		$this->text[] = TDMCreateCommon::getCommonHeader($module);    
		$this->text[] = 'defined("XOOPS_ROOT_PATH") or die("XOOPS root path not defined");

class '.ucfirst($module_name).'SearchPlugin extends Xoops_Module_Plugin_Abstract implements SearchPluginInterface
{
	public function search($queries, $andor, $limit, $start, $uid)
	{
		$queries = implode(' ', (array) $queries);

		$files = XoopsLists::getFileListAsArray(dirname(dirname(dirname(__FILE__))));
		$res = array();
		$i = 0;
		foreach ($files as $file) {
			if (!in_array($file, array(\'xoops_version.php\', \'index.php\'))) {
				$fileName = ucfirst(str_replace(\'.php\', '', $file));
				if (stripos($fileName, $queries) !== false) {
					$res[$i][\'link\'] = $file;
					$res[$i][\'title\'] = $fileName;
					$i++;
				}
			}
		}
		return $res;
	}
}';
		return $this->text;
	}
	
	/**
     * @param string $module
	 * @param string $module_name
     * @return $this->text
     */
    public function pluginSystem($module = null, $module_name = null)
    {
		$mod_name = strtolower($module_name);
        $this->text[] = '<?php';
		$this->text[] = TDMCreateCommon::getCommonHeader($module);    
		$this->text[] = 'defined("XOOPS_ROOT_PATH") or die("XOOPS root path not defined");

class '.ucfirst($module_name).'SystemPlugin extends Xoops_Module_Plugin_Abstract implements SystemPluginInterface
{
    /**
     * Used to synchronize a user number of posts
     * Please return the number of posts the user as made in your module
     *
     * @param int $uid The uid of the user
     *
     * @return int Number of posts
     */
    public function userPosts($uid)
    {
        $xoops = Xoops::getInstance();
        $count = count(XoopsLists::getFileListAsArray($xoops->path(\'modules/'.ucfirst($module_name).'/\')))-2;
        return $count;
    }

    /**
     * Used to populate the Waiting Block
     *
     * Expects an array containing:
     *    count : Number of waiting items,    ex: 3
     *    name  : Name for the waiting items, ex: Pending approval
     *    link  : Link for the waiting items, ex: Xoops::getInstance()->url(\'modules/comments/admin/main.php\');
     *
     * @return array
     */
    public function waiting()
    {
        $xoops = Xoops::getInstance();
        $ret[\'count\'] = count(XoopsLists::getFileListAsArray($xoops->path(\'modules/'.$mod_name.'/\')))-2;
        $ret[\'name\'] = $xoops->getHandlerModule()->getBydirname(\''.$mod_name.'\')->getVar(\'name\');
        $ret[\'link\'] = $xoops->url(\'modules/'.$mod_name.'/\');
        return array();
    }

    /**
     * Used to populate backend
     *
     * @param int $limit : Number of item for backend
     *
     * Expects an array containing:
     *    title   : Title for the backend items
     *    link    : Link for the backend items
     *    content : content for the backend items
     *    date    : Date of the backend items
     *
     * @return array
     */
    public function backend($limit)
    {
        $xoops = Xoops::getInstance();
        $i=0;
        $ret=array();

        $files = XoopsLists::getFileListAsArray($xoops->path(\'modules/'.$mod_name.'/\'));
        foreach ($files as $file) {
            if (!in_array($file, array(\'xoops_version.php\', \'index.php\'))) {
                $ret[$i][\'title\']   = ucfirst(str_replace(\'.php\', \'\', $file));
                $ret[$i][\'link\']    = $xoops->url(\'modules/'.$mod_name.'/\' . $file);
                $ret[$i][\'content\'] = \'Codex module : \' . ucfirst(str_replace(\'.php\', \'\', $file));
                $ret[$i][\'date\']    = filemtime($xoops->path(\'modules/'.$mod_name.'/\' . $file));
                $i++;
            }
        }
        return $ret;
    }

    /**
     * Used to populate the User Block
     *
     * Expects an array containing:
     *    name  : Name for the Link
     *    link  : Link relative to module
     *    image : Url of image to display, please use 16px*16px image
     *
     * @return array
     */
    public function userMenus()
    {
        /*$xoops = Xoops::getInstance();
        $ret[\'name\'] = Xoops::getInstance()->getHandlerModule()->getBydirname(\''.$mod_name.'\')->getVar(\'name\');
        $ret[\'link\'] = \'index.php\';
        $ret[\'image\'] = $xoops->url(\'modules/'.$mod_name.'/icons/logo_small.png\');
        return $ret;*/
    }
}';
		return $this->text;
	}
	
	/**
     * @param string $module
	 * @param string $module_name
     * @return $this->text
     */
    public function pluginUserconfigs($module = null, $module_name = null)
    {
		$mod_name = strtoupper($module_name);
        $this->text[] = '<?php';
		$this->text[] = TDMCreateCommon::getCommonHeader($module);    
		$this->text[] = 'defined("XOOPS_ROOT_PATH") or die("XOOPS root path not defined");

class '.ucfirst($module_name).'UserconfigsPlugin extends Xoops_Module_Plugin_Abstract implements UserconfigsPluginInterface
{
    /**
     * Expects an array of arrays containing:
     * name,        Name of the category
     * description, Description for the category, use constant
     * The keys must be unique identifiers
     */
    public function categories()
    {
        $categories[\'cat_1\'][\'name\'] = _MI_'.$mod_name.'_UCONF_CAT1;
        $categories[\'cat_1\'][\'title\'] = _MI_'.$mod_name.'_UCONF_CAT1_DSC;
        $categories[\'cat_2\'][\'name\'] = _MI_'.$mod_name.'_UCONF_CAT2;
        $categories[\'cat_2\'][\'title\'] = _MI_'.$mod_name.'_UCONF_CAT2_DSC;
        return $categories;
    }

    /**
     * Expects an array of arrays containing:
     * name,        Name of the config
     * title,       Display name for the config, use constant
     * description, Description for the config, use constant
     * formtype,    Form to use for the config
     * default,     Default value for the config
     * options,     Options available for the config
     * category,    Category for this config, use the unique identifier set on categories()
     */
    public function configs()
    {
        $i = 0;
        $config[$i][\'name\'] = \'config_1\';
        $config[$i][\'title\'] = \'_MI_'.$mod_name.'_UCONF_ITEM1\';
        $config[$i][\'description\'] = \'_MI_'.$mod_name.'_UCONF_ITEM1_DSC\';
        $config[$i][\'formtype\'] = \'select\';
        $config[$i][\'valuetype\'] = \'int\';
        $config[$i][\'default\'] = 1;
        $config[$i][\'options\'] = array_flip(array(\'Option 1\', \'Option 2\'));
        $config[$i][\'category\'] = \'cat_1\';
        $i++;
        $config[$i][\'name\'] = \'config_2\';
        $config[$i][\'title\'] = \'_MI_'.$mod_name.'_UCONF_ITEM2\';
        $config[$i][\'description\'] = \'_MI_'.$mod_name.'_UCONF_ITEM2_DSC\';
        $config[$i][\'formtype\'] = \'text\';
        $config[$i][\'valuetype\'] = \'text\';
        $config[$i][\'default\'] = \'Type Something here\';
        $config[$i][\'category\'] = \'cat_2\';
        return $config;
    }
}';
		return $this->text;
	}
}