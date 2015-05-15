<?php

use Xoops\Core\FixedGroups;

/**
 * Cache handlers
 *
 * @copyright       The XOOPS project http://sourceforge.net/projects/xoops/
 * @license         GNU GPL 2 or later (http://www.gnu.org/licenses/gpl-2.0.html)
 * @author          Taiwen Jiang <phppp@users.sourceforge.net>
 * @since           1.00
 * @version         $Id$
 * @package         Frameworks
 * @subpackage      art
 */

if (!defined("FRAMEWORKS_ART_FUNCTIONS_CACHE")):
    define("FRAMEWORKS_ART_FUNCTIONS_CACHE", true);

    /**
     * @param null|array $groups
     * @return string
     */
    function mod_generateCacheId_byGroup($groups = null)
    {
        $xoops = Xoops::getInstance();

        if (!empty($groups) && is_array($groups)) {
        } elseif ($xoops->isUser()) {
            $groups = $xoops->user->getGroups();
        }
        if (!empty($groups) && is_array($groups)) {
            sort($groups);
            $contentCacheId = substr(md5(implode(",", $groups) . \XoopsBaseConfig::get('db-pass') . \XoopsBaseConfig::get('db-name')), 0, strlen(\XoopsBaseConfig::get('db-user')) * 2);
        } else {
            $contentCacheId = FixedGroups::ANONYMOUS;
        }

        return $contentCacheId;
    }

    /**
     * @param null|array $groups
     * @return string
     */
    function mod_generateCacheId($groups = null)
    {
        return mod_generateCacheId_byGroup($groups);
    }

    /**
     * @param $data
     * @param null|string $name
     * @param null|string $dirname
     * @param null|string $cache_path
     * @return bool
     */
    function mod_createFile($data, $name = null, $dirname = null, $cache_path = null)
    {
        $xoops = Xoops::getInstance();

        $name = ($name) ? $name : strval(time());
        $dirname = ($dirname) ? $dirname : $xoops->moduleDirname;
        $cache_path = ($cache_path) ? $cache_path : \XoopsBaseConfig::get('caches-path');
		
        $key = "{$dirname}_{$name}";
        return Xoops_Cache::write($key, $data);
    }

    /**
     * @param $data
     * @param null|string $name
     * @param null|string $dirname
     * @return bool
     */
    function mod_createCacheFile($data, $name = null, $dirname = null)
    {
        return mod_createFile($data, $name, $dirname);
    }

    /**
     * @param $data
     * @param null|string $name
     * @param null|string $dirname
     * @param null|array $groups
     * @return bool
     */
    function mod_createCacheFile_byGroup($data, $name = null, $dirname = null, $groups = null)
    {
        $name .= mod_generateCacheId_byGroup();
        return mod_createCacheFile($data, $name, $dirname);
    }

    /**
     * @param $name
     * @param null|string $dirname
     * @param null|string $cache_path
     * @return mixed|null
     */
    function mod_loadFile($name, $dirname = null, $cache_path = null)
    {
        $xoops = Xoops::getInstance();

        $data = null;

        if (empty($name)) {
            return $data;
        }
        $dirname = ($dirname) ? $dirname : $xoops->moduleDirname;
        $cache_path = ($cache_path) ? $cache_path : $xoops->globalData->getVar('cache-path');
		
        $key = "{$dirname}_{$name}";
        return Xoops_Cache::read($key);
    }

    /**
     * @param $name
     * @param null|string $dirname
     * @return mixed|null
     */
    function mod_loadCacheFile($name, $dirname = null)
    {
        $data = mod_loadFile($name, $dirname);
        return $data;
    }

    /**
     * @param $name
     * @param null|string $dirname
     * @param null|array $groups
     * @return mixed|null
     */
    function mod_loadCacheFile_byGroup($name, $dirname = null, $groups = null)
    {
        $name .= mod_generateCacheId_byGroup();
        $data = mod_loadFile($name, $dirname);
        return $data;
    }

    /* Shall we use the function of glob for better performance ? */
    /**
     * @param string $name
     * @param null|string $dirname
     * @param null|string $cache_path
     * @return bool
     */
    function mod_clearFile($name = "", $dirname = null, $cache_path = null)
    {
		$xoops = \Xoops::getInstance();
        if (empty($dirname)) {
			$cache_path = ($cache_path) ? $cache_path : \XoopsBaseConfig::get('caches-path');
            $pattern = ($dirname) ? "{$dirname}_{$name}.*\.php" : "[^_]+_{$name}.*\.php";
            if ($handle = opendir($cache_path)) {
                while (false !== ($file = readdir($handle))) {
                    if (is_file($cache_path . '/' . $file) && preg_match("/{$pattern}$/", $file)) {
                        @unlink($cache_path . '/' . $file);
                    }
                }
                closedir($handle);
            }
        } else {
            $files = (array)glob($root_path . "/*{$dirname}_{$name}*.php");
            foreach ($files as $file) {
                @unlink($file);
            }
        }
        return true;
    }

    /**
     * @param string $name
     * @param null|string $dirname
     * @return bool
     */
    function mod_clearCacheFile($name = "", $dirname = null)
    {
        return mod_clearFile($name, $dirname);
    }

    /**
     * @param string $pattern
     * @return bool
     */
    function mod_clearSmartyCache($pattern = "")
    {
        $xoops = Xoops::getInstance();

        if (empty($pattern)) {
            $dirname = $xoops->moduleDirname;
            $pattern = "/(^{$dirname}\^.*\.html$|blk_{$dirname}_.*[^\.]*\.html$)/";
        }
		$cache_path = $xoops->globalData->getVar('cache-path');
        if ($handle = opendir($cache_path)) {
            while (false !== ($file = readdir($handle))) {
                if (is_file($cache_path . '/' . $file) && preg_match($pattern, $file)) {
                    @unlink($cache_path . '/' . $file);
                }
            }
            closedir($handle);
        }
        return true;
    }

endif;
?>
