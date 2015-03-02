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
 * XOOPS Editor Abstract class
 *
 * @package         Xoopseditor
 * @author          Taiwen Jiang <phppp@users.sourceforge.net>
 * @copyright       The XOOPS Project http://sourceforge.net/projects/xoops/
 * @license         GNU GPL 2 or later (http://www.gnu.org/licenses/gpl-2.0.html)
 * @since           2.3.0
 */
class XoopsEditor extends Xoops\Form\TextArea
{
    /**
     *  make cache key available as XoopsEditor::CACHE_KEY_EDITOR_LIST
     */
    const CACHE_KEY_EDITOR_LIST = 'system/editorlist';

    /**
     * @var bool
     */
    public $isEnabled;

    /**
     * @var array
     */
    public $configs;

    /**
     * @var string
     */
    public $rootPath;

    /**
     * number of columns
     *
     * @var int
     */
    protected $cols;

    /**
     * number of rows
     *
     * @var int
     */
    protected $rows;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->rows = 5;
        $this->cols = 50;
        $this->setAttribute('rows', 5);
        $this->setAttribute('cols', 50);

        $args = func_get_args();
        $configs = array();
        // For backward compatibility
        if (!empty($args)) {
            if (!is_array($args[0])) {
                $i = 0;
                foreach (array('caption', 'name', 'value', 'rows', 'cols', 'hiddentext') as $key) {
                    if (isset($args[$i])) {
                        $configs[$key] = $args[$i];
                    }
                    ++$i;
                }
                $configs = (isset($args[$i]) && is_array($args[$i])) ? array_merge($configs, $args[$i]) : $configs;
            } else {
                $configs = $args[0];
            }
        }
        // TODO: switch to property_exists() as of PHP 5.1.0
        $vars = get_class_vars(__CLASS__);
        foreach ($configs as $key => $val) {
            $method = "set" . ucfirst($key);
            if (method_exists($this, $method)) {
                $this->$method($val);
            } else {
                if (array_key_exists("_{$key}", $vars)) {
                    $this->{"_{$key}"} = $val;
                } else {
                    if (array_key_exists($key, $vars)) {
                        $this->{$key} = $val;
                    } else {
                        $this->configs[$key] = $val;
                    }
                }
            }
        }
        $this->isActive();
    }

    /**
     * @return bool
     */
    public function isActive()
    {
        $this->isEnabled = true;
        return $this->isEnabled;
    }

    /**
     * @param array $options
     * @return void
     */
    public function setConfig($options)
    {
        foreach ($options as $key => $val) {
            $this->$key = $val;
        }
    }
}

/**
 * Editor handler
 *
 * @copyright The XOOPS project http://www.xoops.org/
 * @license GNU GPL 2 or later (http://www.gnu.org/licenses/gpl-2.0.html)
 * @package core
 * @since 2.3.0
 * @author Taiwen Jiang <phppp@users.sourceforge.net>
 */
class XoopsEditorHandler
{
    /**
     * @var string
     */
    public $root_path = "";

    /**
     * @var bool
     */
    public $nohtml = false;

    /**
     * @var array
     */
    public $allowed_editors = array();

    /**
     * Constructor
     */
    private function __construct()
    {
        $this->root_path = XOOPS_ROOT_PATH . '/class/xoopseditor';
    }

    /**
     * Access the only instance of this class
     *
     * @return XoopsEditorHandler
     * @static
     * @staticvar XoopsEditorHandler
     */
    static function getInstance()
    {
        static $instance;
        if (!isset($instance)) {
            $class = __CLASS__;
            $instance = new $class();
        }
        return $instance;
    }

    /**
     * @param string $name Editor name which is actually the folder name
     * @param array $options editor options: $key => $val
     * @param bool $noHtml
     * @param string $OnFailure a pre-validated editor that will be used if the required editor is failed to create
     * @param bool $noHtml dohtml disabled
     * @return null|XoopsEditor
     */
    public function get($name = '', $options = null, $noHtml = false, $OnFailure = '')
    {
        if ($editor = $this->_loadEditor($name, $options)) {
            return $editor;
        }
        $list = array_keys($this->getList($noHtml));
        if (empty($OnFailure) || !in_array($OnFailure, $list)) {
            $OnFailure = $list[0];
        }
        $editor = $this->_loadEditor($OnFailure, $options);
        return $editor;
    }


    /**
     * @param bool $noHtml
     * @return array
     */
    public function buildEditorList()
    {
        $list = array();
        $order = array();
        $fileList = XoopsLists::getDirListAsArray($this->root_path . '/');

        foreach ($fileList as $item) {
            if (XoopsLoad::fileExists($file = $this->root_path . '/' . $item . '/language/' . XoopsLocale::getLegacyLanguage() . '.php')) {
                include_once $file;
            } else {
                if (XoopsLoad::fileExists($file = $this->root_path . '/' . $item . '/language/english.php')) {
                    include_once $file;
                }
            }
            if (XoopsLoad::fileExists($file = $this->root_path . '/' . $item . '/editor_registry.php')) {
                include $file;
                if (empty($config['order'])) {
                    continue;
                }
                $order[] = $config['order'];
                $list[$item] = array('title' => $config['title'], 'nohtml' => $config['nohtml']);
            }
        }
        array_multisort($order, $list);
        return $list;
    }

    /**
     * @param bool $noHtml
     * @return array
     */
    public function getList($noHtml = false)
    {
        $xoops = Xoops::getInstance();
        $list = $xoops->cache()->cacheRead(
            XoopsEditor::CACHE_KEY_EDITOR_LIST,
            array($this, 'buildEditorList')
        );
        $editors = array_keys($list);
        if (!empty($this->allowed_editors)) {
            $editors = array_intersect($editors, $this->allowed_editors);
        }
        $returnList = array();
        foreach ($editors as $name) {
            if (!empty($noHtml) && empty($list[$name]['nohtml'])) {
                continue;
            }
            $returnList[$name] = $list[$name]['title'];
        }
        return $returnList;
    }

    /**
     * @param XoopsEditor $editor
     * @param array $options
     * @return void
     */
    function setConfig(XoopsEditor $editor, $options)
    {
        $editor->setConfig($options);
    }

    /**
     * XoopsEditorHandler::_loadEditor()
     *
     * @param string $name
     * @param mixed $options
     * @return XoopsEditor|null
     */
    private function _loadEditor($name, $options = null)
    {
        $xoops = Xoops::getInstance();
        $editor = null;
        if (empty($name) || !array_key_exists($name, $this->getList())) {
            return $editor;
        }
        $editor_path = $this->root_path . '/' . $name;
        if (XoopsLoad::fileExists($file = $editor_path . '/language/' . XoopsLocale::getLegacyLanguage() . '.php')) {
            include_once $file;
        } else {
            if (XoopsLoad::fileExists($file = $editor_path . '/language/english.php')) {
                include_once $file;
            }
        }
        if (XoopsLoad::fileExists($file = $editor_path . '/editor_registry.php')) {
            include $file;
        } else {
            return $editor;
        }
        if (empty($config['order'])) {
            return $editor;
        }
        include_once $config['file'];
        $editor = new $config['class']($options);
        return $editor;
    }
}
