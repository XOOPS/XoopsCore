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
 * BreadCrumb Class
 *
 * @copyright   The XOOPS Project http://sourceforge.net/projects/xoops/
 * @license     GNU GPL 2 or later (http://www.gnu.org/licenses/gpl-2.0.html)
 * @author      Andricq Nicolas (AKA MusS)
 * @package     system
 * @version     $Id$
 */

class SystemBreadcrumb
{
    /**
     * @var string
     */
    private $_directory = '';

    /**
     * @var array
     */
    private $_bread = array();

    var $_help;

    var $_tips;

    /**
     * Actual System BreadCrumb Object
     *
     * @param string $fct
     */
    private function __construct($fct)
    {
        if ($fct != '') {
            $this->_directory = $fct;
        }
    }

    /**
     * Access the only instance of this class
     *
     * @param string $fct
     *
     * @staticvar SystemBreadcrumb
     * @return SystemBreadcrumb
     */
    static public function getInstance($fct = '')
    {
        static $instance;
        if (!isset($instance)) {
            $class = __CLASS__;
            $instance = new $class($fct);
        }
        return $instance;
    }

    public function setDirectory($fct)
    {
        $this->_directory = $fct;
    }

    /**
     * Add link to breadcrumb

     */
    function addLink($title = '', $link = '', $home = false)
    {
        $this->_bread[] = array(
            'link' => $link, 'title' => $title, 'home' => $home
        );
    }

    /**
     * Add Help link

     */
    function addHelp($link = '')
    {
        $this->_help = $link;
    }

    /**
     * Add Tips

     */
    function addTips($value)
    {
        $this->_tips = $value;
    }

    /**
     * Render System BreadCrumb

     */
    function render()
    {
        $xoops = Xoops::getInstance();
        if ($xoops->tpl()) {
            $xoops->tpl()->assign('xo_sys_breadcrumb', $this->_bread);
            $xoops->tpl()->assign('xo_sys_help', $this->_help);
            if ($this->_tips) {
                if ($xoops->getModuleConfig('usetips', 'system')) {
                    $xoops->tpl()->assign('xo_sys_tips', $this->_tips);
                }
            }
            // Call template
            if (XoopsLoad::fileExists(XOOPS_ROOT_PATH . '/modules/system/language/' . $xoops->getConfig('language') . '/help/' . $this->_directory . '.html')) {
                $xoops->tpl()
                        ->assign('help_content', XOOPS_ROOT_PATH . '/modules/system/language/' . $xoops->getConfig('language') . '/help/' . $this->_directory . '.html');
            } else {
                if (XoopsLoad::fileExists(XOOPS_ROOT_PATH . '/modules/system/language/english/help/' . $this->_directory . '.html')) {
                    $xoops->tpl()
                            ->assign('help_content', XOOPS_ROOT_PATH . '/modules/system/language/english/help/' . $this->_directory . '.html');
                } else {
                    $xoops->tpl()->assign('load_error', 1);
                }
            }
        } else {
            $out = $menu = '<style type="text/css" media="screen">@import ' . XOOPS_URL . '/modules/system/css/menu.css;</style>';
            $out .= '<ul id="xo-breadcrumb">';
            foreach ($this->_bread as $menu) {
                if ($menu['home']) {
                    $out .= '<li><a href="' . $menu['link'] . '" title="' . $menu['title'] . '"><img src="images/home.png" alt="' . $menu['title'] . '" class="home" /></a></li>';
                } else {
                    if ($menu['link'] != '') {
                        $out .= '<li><a href="' . $menu['link'] . '" title="' . $menu['title'] . '">' . $menu['title'] . '</a></li>';
                    } else {
                        $out .= '<li>' . $menu['title'] . '</li>';
                    }
                }
            }
            $out .= '</ul>';
            if ($this->_tips) {
                $out .= '<div class="tips">' . $this->_tips . '</div>';
            }
            echo $out;
        }
    }
}