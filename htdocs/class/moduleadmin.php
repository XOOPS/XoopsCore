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
 * Xoops Moduleadmin Classes
 *
 * @copyright       The XOOPS Project http://sourceforge.net/projects/xoops/
 * @license         GNU GPL 2 (http://www.gnu.org/licenses/old-licenses/gpl-2.0.html)
 * @package         class
 * @since           2.6.0
 * @author          Mage Grégory (AKA Mage)
 * @version         $Id$
 */

class XoopsModuleAdmin
{
    /**
     * Set module directory
     *
     * @var string
     */
    public $_tplModule = 'system';

    /**
     * Template call for each render parts
     *
     * @var array
     */
    public $_tplFile = array(
        'index' => 'admin_index.html', 'about' => 'admin_about.html', 'infobox' => 'admin_infobox.html',
        'bread' => 'admin_breadcrumb.html', 'button' => 'admin_buttons.html', 'tips' => 'admin_tips.html',
        'nav'   => 'admin_navigation.html'
    );

    /**
     * Tips to display in admin page
     *
     * @var string
     */
    private $_tips = '';

    /**
     * List of button
     *
     * @var array
     */
    private $_itemButton = array();

    /**
     * List of Info Box
     *
     * @var array
     */
    private $_itemInfoBox = array();

    /**
     * List of line of an Info Box
     *
     * @var array
     */
    private $_itemConfigBoxLine = array();

    /**
     * Breadcrumb data
     *
     * @var array
     */
    private $_bread = array();

    /**
     * Current module object
     *
     * @var XoopsModule $_obj
     */
    private $_obj = null;

    /**
     * Constructor
     */
    public function __construct()
    {
        $xoops = Xoops::getInstance();
        $this->_obj = $xoops->module;
        $xoops->theme()->addStylesheet('media/xoops/css/moduladmin.css');
    }

    /**
     * Add breadcrumb menu
     *
     * @param string $title
     * @param string $link
     * @param bool   $home
     */
    public function addBreadcrumbLink($title = '', $link = '', $home = false)
    {
        if ($title != '') {
            $this->_bread[] = array(
                'link' => $link, 'title' => $title, 'home' => $home
            );
        }
    }

    /**
     * Add config line
     *
     * @param string $value
     * @param string $type
     *
     * @return bool
     */
    public function addConfigBoxLine($value = '', $type = 'default')
    {
        switch ($type) {
            default:
            case "default":
                $this->_itemConfigBoxLine[] = array('type' => $type, 'text' => $value);
                break;

            case "folder":
                if (!is_dir($value)) {
                    $this->_itemConfigBoxLine[] = array(
                        'type' => 'error', 'text' => sprintf(XoopsLocale::EF_FOLDER_DOES_NOT_EXIST, $value)
                    );
                } else {
                    $this->_itemConfigBoxLine[] = array(
                        'type' => 'accept', 'text' => sprintf(xoopsLocale::SF_FOLDER_EXISTS, $value)
                    );
                }
                break;

            case "chmod":
                if (is_dir($value[0])) {
                    if (substr(decoct(fileperms($value[0])), 2) != $value[1]) {
                        $this->_itemConfigBoxLine[] = array(
                            'type' => 'error',
                            'text' => sprintf(XoopsLocale::EF_FOLDER_MUST_BE_WITH_CHMOD, $value[0], $value[1], substr(decoct(fileperms($value[0])), 2))
                        );
                    } else {
                        $this->_itemConfigBoxLine[] = array(
                            'type' => 'accept',
                            'text' => sprintf(XoopsLocale::EF_FOLDER_MUST_BE_WITH_CHMOD, $value[0], $value[1], substr(decoct(fileperms($value[0])), 2))
                        );
                    }
                }
                break;

            case "extension":
                $xoops = Xoops::getInstance();
                if (is_array($value)) {
                    $text = $value[0];
                    $type = $value[1];
                } else {
                    $text = $value;
                    $type = 'error';
                }
                if ($xoops->isActiveModule($text) == false) {
                    $this->_itemConfigBoxLine[] = array(
                        'type' => $type, 'text' => sprintf(XoopsLocale::EF_EXTENSION_IS_NOT_INSTALLED, $text)
                    );
                } else {
                    $this->_itemConfigBoxLine[] = array(
                        'type' => 'accept', 'text' => sprintf(XoopsLocale::SF_EXTENSION_IS_INSTALLED, $text)
                    );
                }
                break;

            case "module":
                $xoops = Xoops::getInstance();
                if (is_array($value)) {
                    $text = $value[0];
                    $type = $value[1];
                } else {
                    $text = $value;
                    $type = 'error';
                }
                if ($xoops->isActiveModule($text) == false) {
                    $this->_itemConfigBoxLine[] = array(
                        'type' => $type, 'text' => sprintf(XoopsLocale::F_MODULE_IS_NOT_INSTALLED, $text)
                    );
                } else {
                    $this->_itemConfigBoxLine[] = array(
                        'type' => 'accept', 'text' => sprintf(XoopsLocale::F_MODULE_IS_INSTALLED, $text)
                    );
                }
                break;
        }
        return true;
    }

    /**
     * Add Info box
     *
     * @param        $title
     * @param string $type
     * @param string $extra
     *
     * @return bool
     */
    public function addInfoBox($title, $type = 'default', $extra = '')
    {
        $ret['title'] = $title;
        $ret['type'] = $type;
        $ret['extra'] = $extra;
        $this->_itemInfoBox[] = $ret;
        return true;
    }

    /**
     * Add line to the info box
     *
     * @param string $text
     * @param string $type
     * @param string $color
     *
     * @return bool
     */
    public function addInfoBoxLine($text = '', $type = 'default', $color = 'inherit')
    {
        $ret = array();
        $ret['text'] = $text;
        $ret['color'] = $color;

        foreach (array_keys($this->_itemInfoBox) as $i) {
            if ($this->_itemInfoBox[$i]['type'] == $type) {
                $this->_itemInfoBox[$i]['line'][] = $ret;
                unset($ret);
            }
        }
        return true;
    }

    /**
     * Add Item button
     *
     * @param        $title
     * @param        $link
     * @param string $icon
     * @param string $extra
     *
     * @return bool
     */
    public function addItemButton($title, $link, $icon = 'add', $extra = '')
    {
        $ret['title'] = $title;
        $ret['link'] = $link;
        $ret['icon'] = $icon;
        $ret['extra'] = $extra;
        $this->_itemButton[] = $ret;
        return true;
    }

    /**
     * Add a tips
     *
     * @param string $text
     */
    public function addTips($text = '')
    {
        $this->_tips = $text;
    }

    /**
     * Construct template path
     *
     * @param string $type
     *
     * @return string
     */
    private function getTplPath($type = '')
    {
        return 'admin:' . $this->_tplModule . '|' . $this->_tplFile[$type];
    }

    public function renderBreadcrumb()
    {
        $xoops = Xoops::getInstance();
        $xoops->tpl()->assign('xo_admin_breadcrumb', $this->_bread);
        return $xoops->tpl()->fetch($this->getTplPath('bread'));
    }

    public function displayBreadcrumb()
    {
        echo $this->renderBreadcrumb();
    }

    /**
     * Render all items buttons
     *
     * @param string $position
     * @param string $delimiter
     *
     * @return string
     */
    public function renderButton($position = "floatright", $delimiter = "&nbsp;")
    {
        $xoops = Xoops::getInstance();

        $xoops->tpl()->assign('xo_admin_buttons_position', $position);
        $xoops->tpl()->assign('xo_admin_buttons_delim', $delimiter);
        $xoops->tpl()->assign('xo_admin_buttons', $this->_itemButton);
        return $xoops->tpl()->fetch($this->getTplPath('button'));
    }

    /**
     * @param string $position
     * @param string $delimiter
     */
    public function displayButton($position = "floatright", $delimiter = "&nbsp;")
    {
        echo $this->renderButton($position, $delimiter);
    }

    /**
     * Render InfoBox
     */
    public function renderInfoBox()
    {
        $xoops = Xoops::getInstance();
        $xoops->tpl()->assign('xo_admin_box', $this->_itemInfoBox);
        return $xoops->tpl()->fetch($this->getTplPath('infobox'));
    }

    public function displayInfoBox()
    {
        echo $this->renderInfoBox();
    }

    /**
     * Render index page for admin
     */
    public function renderIndex()
    {
        $xoops = Xoops::getInstance();
        $this->_obj->loadAdminMenu();
        foreach (array_keys($this->_obj->adminmenu) as $i) {
            if (XoopsLoad::fileExists($xoops->path("/media/xoops/images/icons/32/" . $this->_obj->adminmenu[$i]['icon']))) {
                $this->_obj->adminmenu[$i]['icon'] = $xoops->url("/media/xoops/images/icons/32/" . $this->_obj->adminmenu[$i]['icon']);
            } else {
                $this->_obj->adminmenu[$i]['icon'] = $xoops->url("/modules/" . $xoops->module->dirname() . "/icons/32/" . $this->_obj->adminmenu[$i]['icon']);
            }
            $xoops->tpl()->append('xo_admin_index_menu', $this->_obj->adminmenu[$i]);
        }
        if ($this->_obj->getInfo('help')) {
            $help = array();
            $help['link'] = '../system/help.php?mid=' . $this->_obj->getVar('mid', 's') . "&amp;" . $this->_obj->getInfo('help');
            $help['icon'] = $xoops->url("/media/xoops/images/icons/32/help.png");
            $help['title'] = XoopsLocale::HELP;
            $xoops->tpl()->append('xo_admin_index_menu', $help);
        }
        $xoops->tpl()->assign('xo_admin_box', $this->_itemInfoBox);

        // If you use a config label
        if ($this->_obj->getInfo('min_php') || $this->_obj->getInfo('min_xoops') || !empty($this->_itemConfigBoxLine)) {
            // PHP version
            if ($this->_obj->getInfo('min_php')) {
                if (phpversion() < $this->_obj->getInfo('min_php')) {
                    $this->addConfigBoxLine(sprintf(XoopsLocale::F_MINIMUM_PHP_VERSION_REQUIRED, $this->_obj->getInfo('min_php'), phpversion()), 'error');
                } else {
                    $this->addConfigBoxLine(sprintf(XoopsLocale::F_MINIMUM_PHP_VERSION_REQUIRED, $this->_obj->getInfo('min_php'), phpversion()), 'accept');
                }
            }
            // Database version
            $dbarray = $this->_obj->getInfo('min_db');
            if ($dbarray[XOOPS_DB_TYPE]) {
                switch (XOOPS_DB_TYPE) {
                    case "mysql":
                        $dbCurrentVersion = mysql_get_server_info();
                        break;
                    case "mysqli":
                        $dbCurrentVersion = mysqli_get_server_info();
                        break;
                    case "pdo":
                        $dbCurrentVersion = $xoops->db()->getAttribute(PDO::ATTR_SERVER_VERSION);
                        break;
                    default:
                        $dbCurrentVersion = '0';
                        break;
                }
                $currentVerParts = explode('.', (string)$dbCurrentVersion);
                $iCurrentVerParts = array_map('intval', $currentVerParts);
                $dbRequiredVersion = $dbarray[XOOPS_DB_TYPE];
                $reqVerParts = explode('.', (string)$dbRequiredVersion);
                $iReqVerParts = array_map('intval', $reqVerParts);
                $icount = $j = count($iReqVerParts);
                $reqVer = $curVer = 0;
                for ($i = 0; $i < $icount; $i++) {
                    $j--;
                    $reqVer += $iReqVerParts[$i] * pow(10, $j);
                    if (isset($iCurrentVerParts[$i])) {
                        $curVer += $iCurrentVerParts[$i] * pow(10, $j);
                    } else {
                        $curVer = $curVer * pow(10, $j);
                    }
                }
                if ($reqVer > $curVer) {
                    $this->addConfigBoxLine(sprintf(strtoupper(XOOPS_DB_TYPE) . ' ' . XoopsLocale::F_MINIMUM_DATABASE_VERSION_REQUIRED, $dbRequiredVersion, $dbCurrentVersion), 'error');
                } else {
                    $this->addConfigBoxLine(sprintf(strtoupper(XOOPS_DB_TYPE) . ' ' . XoopsLocale::F_MINIMUM_DATABASE_VERSION_REQUIRED, $dbRequiredVersion, $dbCurrentVersion), 'accept');
                }
            }

            // xoops version
            if ($this->_obj->getInfo('min_xoops')) {
                if (substr(XOOPS_VERSION, 6, strlen(XOOPS_VERSION) - 6) < $this->_obj->getInfo('min_xoops')) {
                    $this->addConfigBoxLine(sprintf(XoopsLocale::F_MINIMUM_XOOPS_VERSION_REQUIRED, $this->_obj->getInfo('min_xoops'), substr(XOOPS_VERSION, 6, strlen(XOOPS_VERSION) - 6)), 'error');
                } else {
                    $this->addConfigBoxLine(sprintf(XoopsLocale::F_MINIMUM_XOOPS_VERSION_REQUIRED, $this->_obj->getInfo('min_xoops'), substr(XOOPS_VERSION, 6, strlen(XOOPS_VERSION) - 6)), 'accept');
                }
            }
            $xoops->tpl()->assign('xo_admin_index_config', $this->_itemConfigBoxLine);
        }
        return $xoops->tpl()->fetch($this->getTplPath('index'));
    }

    public function displayIndex()
    {
        echo $this->renderIndex();
    }

    /**
     * Render navigation to admin page
     *
     * @param string $menu
     *
     * @return array
     */
    public function renderNavigation($menu = '')
    {
        $xoops = Xoops::getInstance();
        $ret = array();

        $this->_obj->loadAdminMenu();
        foreach (array_keys($this->_obj->adminmenu) as $i) {
            if ($this->_obj->adminmenu[$i]['link'] == "admin/" . $menu) {
                if (XoopsLoad::fileExists($xoops->path("/media/xoops/images/icons/32/" . $this->_obj->adminmenu[$i]['icon']))) {
                    $this->_obj->adminmenu[$i]['icon'] = $xoops->url("/media/xoops/images/icons/32/" . $this->_obj->adminmenu[$i]['icon']);
                } else {
                    $this->_obj->adminmenu[$i]['icon'] = $xoops->url("/modules/" . $xoops->module->dirname() . "/icons/32/" . $this->_obj->adminmenu[$i]['icon']);
                }
                $xoops->tpl()->assign('xo_sys_navigation', $this->_obj->adminmenu[$i]);
                $ret[] = $xoops->tpl()->fetch($this->getTplPath('nav'));
            }
        }
        return $ret;
    }

    /**
     * @param string $menu
     */
    public function displayNavigation($menu = '')
    {
        $items = $this->renderNavigation($menu);
        foreach ($items as $item) {
            echo $item;
        }
    }

    /**
     * Render tips to admin page
     */
    public function renderTips()
    {
        $xoops = Xoops::getInstance();
        $xoops->tpl()->assign('xo_admin_tips', $this->_tips);
        return $xoops->tpl()->fetch($this->getTplPath('tips'));
    }

    public function displayTips()
    {
        echo $this->renderTips();
    }

    /**
     * Render about page
     *
     * @param bool $logo_xoops
     *
     * @return bool|mixed|string
     */
    public function renderAbout($logo_xoops = true)
    {
        $xoops = Xoops::getInstance();

        $date = explode('/', $this->_obj->getInfo('release_date'));
        $author = explode(',', $this->_obj->getInfo('author'));
        $nickname = explode(',', $this->_obj->getInfo('nickname'));
        $release_date = XoopsLocale::formatTimestamp(mktime(0, 0, 0, $date[1], $date[2], $date[0]), 's');

        $author_list = '';
        foreach (array_keys($author) as $i) {
            $author_list .= $author[$i];
            if (isset($nickname[$i]) && $nickname[$i] != '') {
                $author_list .= " (" . $nickname[$i] . "), ";
            } else {
                $author_list .= ", ";
            }
        }
        $changelog = '';
        $language = $xoops->getConfig('locale');
        if (!is_file(XOOPS_ROOT_PATH . "/modules/" . $this->_obj->getVar("dirname") . "/locale/" . $language . "/changelog.txt")) {
            $language = 'en_US';
        }
        $file = XOOPS_ROOT_PATH . "/modules/" . $this->_obj->getVar("dirname") . "/locale/" . $language . "/changelog.txt";
        if (is_readable($file)) {
            $changelog = utf8_encode(implode("<br />", file($file))) . "\n";
        } else {
            $file = XOOPS_ROOT_PATH . "/modules/" . $this->_obj->getVar("dirname") . "/docs/changelog.txt";
            if (is_readable($file)) {
                $changelog = utf8_encode(implode("<br />", file($file))) . "\n";
            }
        }
        $author_list = substr($author_list, 0, -2);

        $this->_obj->setInfo('release_date', $release_date);
        $this->_obj->setInfo('author_list', $author_list);
        if (is_array($this->_obj->getInfo('paypal'))) {
            $this->_obj->setInfo('paypal', $this->_obj->getInfo('paypal'));
        }
        $this->_obj->setInfo('changelog', $changelog);
        $xoops->tpl()->assign('module', $this->_obj);

        $this->addInfoBox(XoopsLocale::MODULE_INFORMATION, 'info', 'id="xo-about"');
        $this->addInfoBoxLine(XoopsLocale::C_DESCRIPTION . ' ' . $this->_obj->getInfo("description"), 'info');
        $this->addInfoBoxLine(XoopsLocale::C_UPDATE_DATE . ' <span class="bold">' . XoopsLocale::formatTimestamp($this->_obj->getVar("last_update"), "m") . '</span>', 'info');
        $this->addInfoBoxLine(XoopsLocale::C_WEBSITE . ' <a class="xo-tooltip" href="http://' . $this->_obj->getInfo("module_website_url") . '" rel="external" title="' . $this->_obj->getInfo("module_website_name") . ' - ' . $this->_obj->getInfo("module_website_url") . '">
                                ' . $this->_obj->getInfo("module_website_name") . '</a>', 'info');

        $xoops->tpl()->assign('xoops_logo', $logo_xoops);
        $xoops->tpl()->assign('xo_admin_box', $this->_itemInfoBox);
        return $xoops->tpl()->fetch($this->getTplPath('about'));
    }

    /**
     * @param bool $logo_xoops
     */
    public function displayAbout($logo_xoops = true)
    {
        echo $this->renderAbout($logo_xoops);
    }
}