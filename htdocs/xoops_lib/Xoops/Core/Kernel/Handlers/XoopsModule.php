<?php
/**
 * XOOPS Kernel Class
 *
 * You may not change or alter any portion of this comment or credits
 * of supporting developers from this source code or any supporting source code
 * which is considered copyrighted (c) material of the original comment or credit authors.
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
 *
 * @copyright       XOOPS Project (http://xoops.org)
 * @license         GNU GPL 2 or later (http://www.gnu.org/licenses/gpl-2.0.html)
 * @package         kernel
 * @since           2.0.0
 * @version         $Id$
 */

namespace Xoops\Core\Kernel\Handlers;

use Xoops\Core\Kernel\XoopsObject;

/**
 * A Module
 *
 * @package kernel
 * @author Kazumi Ono <onokazu@xoops.org>
 */
class XoopsModule extends XoopsObject
{
    /**
     * @var string
     */
    public $modinfo;

    /**
     *
     * @var array
     */
    public $adminmenu;
    /**
    *
    * @var array
    */
    private $_msg = array();
	
	protected $xoops_url;
	protected $xoops_root_path;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->initVar('mid', XOBJ_DTYPE_INT, null, false);
        $this->initVar('name', XOBJ_DTYPE_TXTBOX, null, true, 150);
        $this->initVar('version', XOBJ_DTYPE_INT, 100, false);
        $this->initVar('last_update', XOBJ_DTYPE_INT, null, false);
        $this->initVar('weight', XOBJ_DTYPE_INT, 0, false);
        $this->initVar('isactive', XOBJ_DTYPE_INT, 1, false);
        $this->initVar('dirname', XOBJ_DTYPE_OTHER, null, true);
        $this->initVar('hasmain', XOBJ_DTYPE_INT, 0, false);
        $this->initVar('hasadmin', XOBJ_DTYPE_INT, 0, false);
        $this->initVar('hassearch', XOBJ_DTYPE_INT, 0, false);
        $this->initVar('hasconfig', XOBJ_DTYPE_INT, 0, false);
        $this->initVar('hascomments', XOBJ_DTYPE_INT, 0, false);
        // RMV-NOTIFY
        $this->initVar('hasnotification', XOBJ_DTYPE_INT, 0, false);
		
		$this->xoops_url = \XoopsBaseConfig::get('url');
		$this->xoops_root_path = \XoopsBaseConfig::get('root-path');
    }

    /**
     * Load module info
     *
     * @param string $dirname Directory Name
     * @param boolean $verbose
     */
    public function loadInfoAsVar($dirname, $verbose = true)
    {
        $dirname = basename($dirname);
        if (!isset($this->modinfo)) {
            $this->loadInfo($dirname, $verbose);
        }
        $this->setVar('name', $this->modinfo['name'], true);
        $this->setVar('version', (int)(100 * ($this->modinfo['version'] + 0.001)), true);
        $this->setVar('dirname', $this->modinfo['dirname'], true);
        $hasmain = (isset($this->modinfo['hasMain']) && $this->modinfo['hasMain'] == 1) ? 1 : 0;
        $hasadmin = (isset($this->modinfo['hasAdmin']) && $this->modinfo['hasAdmin'] == 1) ? 1 : 0;
        $hassearch = (isset($this->modinfo['hasSearch']) && $this->modinfo['hasSearch'] == 1) ? 1 : 0;
        $hasconfig = ((isset($this->modinfo['config']) && is_array($this->modinfo['config'])) || ! empty($this->modinfo['hasComments'])) ? 1 : 0;
        $hascomments = (isset($this->modinfo['hasComments']) && $this->modinfo['hasComments'] == 1) ? 1 : 0;
        // RMV-NOTIFY
        $hasnotification = (isset($this->modinfo['hasNotification']) && $this->modinfo['hasNotification'] == 1) ? 1 : 0;
        $this->setVar('hasmain', $hasmain);
        $this->setVar('hasadmin', $hasadmin);
        $this->setVar('hassearch', $hassearch);
        $this->setVar('hasconfig', $hasconfig);
        $this->setVar('hascomments', $hascomments);
        // RMV-NOTIFY
        $this->setVar('hasnotification', $hasnotification);
    }

    /**
     * add a message
     *
     * @param string $str message to add
     * @access public
     */
    public function setMessage($str)
    {
        $this->_msg[] = trim($str);
    }

    /**
     * return the messages for this object as an array
     *
     * @return array an array of messages
     * @access public
     */
    public function getMessages()
    {
        return $this->_msg;
    }

    /**
     * Set module info
     *
     * @param   string  $name
     * @param   mixed   $value
     * @return  bool
     **/
    public function setInfo($name, $value)
    {
        if (empty($name)) {
            $this->modinfo = $value;
        } else {
            $this->modinfo[$name] = $value;
        }
        return true;
    }

    /**
     * Get module info
     *
     * @param string $name
     * @return array |string    Array of module information.
     * If {@link $name} is set, returns a single module information item as string.
     */
    public function getInfo($name = null)
    {
        if (!isset($this->modinfo)) {
            $this->loadInfo($this->getVar('dirname'));
        }
        if (isset($name)) {
            if (isset($this->modinfo[$name])) {
                return $this->modinfo[$name];
            }
            $return = false;
            return $return;
        }
        return $this->modinfo;
    }

    /**
     * Get a link to the modules main page
     *
     * @return string FALSE on fail
     */
    public function mainLink()
    {
        if ($this->getVar('hasmain') == 1) {
            $ret = '<a href="' . $this->xoops_url . '/modules/' . $this->getVar('dirname') . '/">' . $this->getVar('name') . '</a>';
            return $ret;
        }
        return false;
    }

    /**
     * Get links to the subpages
     *
     * @return string
     */
    public function subLink()
    {
        $ret = array();
        if ($this->getInfo('sub') && is_array($this->getInfo('sub'))) {
            foreach ($this->getInfo('sub') as $submenu) {
                $ret[] = array(
                    'name' => $submenu['name'] ,
                    'url' => $submenu['url']);
            }
        }
        return $ret;
    }

    /**
     * Load the admin menu for the module
     */
    public function loadAdminMenu()
    {
		$file = $this->xoops_root_path . '/modules/' . $this->getInfo('dirname') . '/' . $this->getInfo('adminmenu');
        if ($this->getInfo('adminmenu') && $this->getInfo('adminmenu') != '' && \XoopsLoad::fileExists($file)) {
            $adminmenu = array();
            include $file;
            $this->adminmenu = $adminmenu;
        }
    }

    /**
     * Get the admin menu for the module
     *
     * @return string
     */
    public function getAdminMenu()
    {
        if (!isset($this->adminmenu)) {
            $this->loadAdminMenu();
        }
        return $this->adminmenu;
    }

    /**
     * Load the module info for this module
     *
     * @param string $dirname Module directory
     * @param bool   $verbose Give an error on fail?
     *
     * @return bool
     *
     * @todo the $modVersions array should be built once when modules are installed/updated and then cached
     */
    public function loadInfo($dirname, $verbose = true)
    {
        static $modVersions;
        if (empty($dirname)) {
            return false;
        }
        $dirname = basename($dirname);
        if (isset($modVersions[$dirname])) {
            $this->modinfo = $modVersions[$dirname];
            return true;
        }
        $xoops = \Xoops::getInstance();
        $dirname = basename($dirname);
        $xoops->loadLanguage('modinfo', $dirname);
        $xoops->loadLocale($dirname);

        if (!\XoopsLoad::fileExists($file = $xoops->path('modules/' . $dirname . '/xoops_version.php'))) {
            if (false != $verbose) {
                echo "Module File for $dirname Not Found!";
            }
            return false;
        }
        $modversion = array();
        include $file;
        $modVersions[$dirname] = $modversion;
        $this->modinfo = $modVersions[$dirname];
        return true;
    }

    /**
     * Search contents within a module
     *
     * @deprecated Use search module instead
     *
     * @param string $term
     * @param string $andor 'AND' or 'OR'
     * @param integer $limit
     * @param integer $offset
     * @param integer $userid
     * @return boolean Search result.
     */
    public function search($term = '', $andor = 'AND', $limit = 0, $offset = 0, $userid = 0)
    {
        return false;
    }

    /**
     * @param string $format
     * @return mixed
     */
    public function id($format = 'n')
    {
        return $this->getVar('mid', $format);
    }

    /**
     * @param string $format
     * @return mixed
     */
    public function mid($format = '')
    {
        return $this->getVar('mid', $format);
    }

    /**
     * @param string $format
     * @return mixed
     */
    public function name($format = '')
    {
        return $this->getVar('name', $format);
    }

    /**
     * @param string $format
     * @return mixed
     */
    public function version($format = '')
    {
        return $this->getVar('version', $format);
    }

    /**
     * @param string $format
     * @return mixed
     */
    public function last_update($format = '')
    {
        return $this->getVar('last_update', $format);
    }

    /**
     * @param string $format
     * @return mixed
     */
    public function weight($format = '')
    {
        return $this->getVar('weight', $format);
    }

    /**
     * @param string $format
     * @return mixed
     */
    public function isactive($format = '')
    {
        return $this->getVar('isactive', $format);
    }

    /**
     * @param string $format
     * @return mixed
     */
    public function dirname($format = '')
    {
        return $this->getVar('dirname', $format);
    }

    /**
     * @param string $format
     * @return mixed
     */
    public function hasmain($format = '')
    {
        return $this->getVar('hasmain', $format);
    }

    /**
     * @param string $format
     * @return mixed
     */
    public function hasadmin($format = '')
    {
        return $this->getVar('hasadmin', $format);
    }

    /**
     * @param string $format
     * @return mixed
     */
    public function hassearch($format = '')
    {
        return $this->getVar('hassearch', $format);
    }

    /**
     * @param string $format
     * @return mixed
     */
    public function hasconfig($format = '')
    {
        return $this->getVar('hasconfig', $format);
    }

    /**
     * @param string $format
     * @return mixed
     */
    public function hascomments($format = '')
    {
        return $this->getVar('hascomments', $format);
    }

    /**
     * @param string $format
     * @return mixed
     */
    public function hasnotification($format = '')
    {
        return $this->getVar('hasnotification', $format);
    }

    /**
     * @param $dirname
     * @return XoopsModule
     */
    public function getByDirName($dirname)
    {
        return \Xoops::getInstance()->getModuleByDirname($dirname);
    }
}
