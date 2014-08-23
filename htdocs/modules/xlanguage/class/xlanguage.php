<?php
/**
 * Xlanguage extension module
 * You may not change or alter any portion of this comment or credits
 * of supporting developers from this source code or any supporting source code
 * which is considered copyrighted (c) material of the original comment or credit authors.
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
 */

use Xoops\Core\Database\Connection;
use Xoops\Core\Kernel\XoopsObject;
use Xoops\Core\Kernel\XoopsPersistableObjectHandler;

/**
 * XlanguageLanguage
 *
 * @copyright       2010-2014 The XOOPS Project http://sourceforge.net/projects/xoops/
 * @license         GNU GPL 2 or later (http://www.gnu.org/licenses/gpl-2.0.html)
 * @package         xlanguage
 * @since           2.6.0
 * @author          Laurent JEN (Aka DuGris)
 * @version         $Id$
 */

include_once XOOPS_ROOT_PATH . '/modules/xlanguage/include/vars.php';

class XlanguageLanguage extends XoopsObject
{
    public function __construct()
    {
        $this->initVar('xlanguage_id', XOBJ_DTYPE_INT, 0, false, 10);
        $this->initVar('xlanguage_name', XOBJ_DTYPE_TXTBOX, '', false);
        $this->initVar('xlanguage_description', XOBJ_DTYPE_TXTBOX, '', false);
        $this->initVar('xlanguage_code', XOBJ_DTYPE_TXTBOX, '', false);
        $this->initVar('xlanguage_charset', XOBJ_DTYPE_TXTBOX, 'utf-8', false);
        $this->initVar('xlanguage_image', XOBJ_DTYPE_TXTBOX, '_unknown.png', false);
        $this->initVar('xlanguage_weight', XOBJ_DTYPE_INT, 1, false, 10);
    }

    public function getValues($keys = null, $format = 's', $maxDepth = 1)
    {
        $ret = parent::getValues();
        $ret['xlanguage_image'] = XOOPS_URL . '/media/xoops/images/flags/' . \Xoops\Module\Helper::getHelper('xlanguage')->getConfig('theme') . '/' . $this->getVar('xlanguage_image');
        return $ret;
    }

    public function CleanVarsForDB()
    {
        $system = System::getInstance();
        foreach (parent::getValues() as $k => $v) {
            if ($k != 'dohtml') {
                if ($this->vars[$k]['data_type'] == XOBJ_DTYPE_STIME || $this->vars[$k]['data_type'] == XOBJ_DTYPE_MTIME || $this->vars[$k]['data_type'] == XOBJ_DTYPE_LTIME) {
                    $value = $system->CleanVars($_POST[$k], 'date', date('Y-m-d'), 'date') + $system->CleanVars($_POST[$k], 'time', date('u'), 'int');
                    $this->setVar($k, isset($_POST[$k]) ? $value : $v);
                } elseif ($this->vars[$k]['data_type'] == XOBJ_DTYPE_INT) {
                    $value = $system->CleanVars($_POST, $k, $v, 'int');
                    $this->setVar($k, $value);
                } elseif ($this->vars[$k]['data_type'] == XOBJ_DTYPE_ARRAY) {
                    $value = $system->CleanVars($_POST, $k, $v, 'array');
                    $this->setVar($k, $value);
                } else {
                    $value = $system->CleanVars($_POST, $k, $v, 'string');
                    $this->setVar($k, $value);
                }
            }
        }
    }
}

class XlanguageXlanguageHandler extends XoopsPersistableObjectHandler
{
    public function __construct($db)
    {
        parent::__construct($db, 'xlanguage', 'XlanguageLanguage', 'xlanguage_id', 'xlanguage_name');
        $this->loadConfig();
    }

    public function loadConfig()
    {
        $xoops = Xoops::getInstance();
        $this->configPath = XOOPS_VAR_PATH . '/configs/';
        $this->configFile = $xoops->registry()->get('XLANGUAGE_CONFIG_FILE');
        $this->configFileExt = '.php';
        return $this->cached_config = $this->loadFileConfig();
    }

    public function loadFileConfig()
    {
        $cached_config = $this->readConfig();
        if (empty($cached_config)) {
            $cached_config = $this->createConfig();
        }
        return $cached_config;
    }

    public function readConfig()
    {
        $path_file = $this->configPath . $this->configFile . $this->configFileExt;
        XoopsLoad::load('XoopsFile');
        $file = XoopsFile::getHandler('file', $path_file);
        return eval(@$file->read());
    }

    public function createConfig()
    {
        $cached_config = array();
        foreach ($this->getAllLanguage(false) as $key => $language) {
            $cached_config[$language['xlanguage_name']] = $language;
        }
        $this->writeConfig($cached_config);
        return $cached_config;
    }

    public function writeConfig($data)
    {
        if ($this->CreatePath($this->configPath)) {
            $path_file = $this->configPath . $this->configFile . $this->configFileExt;
            XoopsLoad::load('XoopsFile');
            $file = XoopsFile::getHandler('file', $path_file);
            return $file->write('return ' . var_export($data, true) . ';');
        }
    }

    private function CreatePath($pathname, $pathout = XOOPS_ROOT_PATH)
    {
        $xoops = Xoops::getInstance();
        $pathname = substr($pathname, strlen(XOOPS_ROOT_PATH));
        $pathname = str_replace(DIRECTORY_SEPARATOR, '/', $pathname);

        $dest = $pathout;
        $paths = explode('/', $pathname);

        foreach ($paths as $path) {
            if (!empty($path)) {
                $dest = $dest . '/' . $path;
                if (!is_dir($dest)) {
                    if (!mkdir($dest, 0755)) {
                        return false;
                    } else {
                        $this->WriteIndex($xoops->path('uploads'), 'index.html', $dest);
                    }
                }
            }
        }
        return true;
    }

    private function WriteIndex($folder_in, $source_file, $folder_out)
    {
        if (!is_dir($folder_out)) {
            if (!$this->CreatePath($folder_out)) {
                return false;
            }
        }

        // Simple copy for a file
        if (is_file($folder_in . '/' . $source_file)) {
            return copy($folder_in . '/' . $source_file, $folder_out . '/' . basename($source_file));
        }
        return false;
    }

    public function getByName($name = null)
    {
        $xoops = Xoops::getInstance();
        $name = empty($name) ? $xoops->getConfig('locale') : strtolower($name);

        $file_config = $xoops->registry()->get('XLANGUAGE_CONFIG_FILE');
        if (!XoopsLoad::fileExists($file_config) || !isset($this->cached_config)) {
            $this->loadConfig();
        }

        if (isset($this->cached_config[$name])) {
            return $this->cached_config[$name];
        }
        return null;
    }

    public function getAllLanguage($asobject = true)
    {
        $criteria = new CriteriaCompo();
        $criteria->setSort('xlanguage_weight');
        $criteria->setOrder('asc');

        return parent::getAll($criteria, null, $asobject, true);
    }

    public function renderlist()
    {
        $xoops = Xoops::getInstance();
        $xoops->tpl()->assign('theme', \Xoops\Module\Helper::getHelper('xlanguage')->getConfig('theme'));
        $xoops->tpl()->assign('languages', $this->getAllLanguage(false));
        return $xoops->tpl()->fetch('admin:xlanguage|xlanguage_admin_list.html');
    }
}
