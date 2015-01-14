<?php
/*
 * You may not change or alter any portion of this comment or credits
 * of supporting developers from this source code or any supporting source code
 * which is considered copyrighted (c) material of the original comment or credit authors.
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
 */

/**
 * Class for tab navigation
 *
 * @category  Modules/system/class/form
 * @package   SystemMenuHandler
 * @author    John Neill (AKA Catzwolf)
 * @author    Andricq Nicolas (AKA MusS)
 * @copyright 2000-2014 The XOOPS Project http://sourceforge.net/projects/xoops/
 * @license   GNU GPL 2 (http://www.gnu.org/licenses/gpl-2.0.html)
 * @link      http://xoops.org
 */
class SystemMenuHandler
{
    /**
     *
     * @var string
     */
    private $_menutop = array();
    private $_menutabs = array();
    private $_obj;
    private $_header;
    private $_subheader;

    /**
     * Constructor
     */
    public function __construct()
    {
        $xoops = Xoops::getInstance();
        $this->_obj = $xoops->module;
    }

    public function getAddon($addon)
    {
        $this->_obj = $addon;
    }

    /**
     * @param string $link
     */
    public function addMenuTop($link, $name = "")
    {
        $this->_menutop[] = array('link' => $link, 'name' => $name);
    }

    public function addMenuTopArray($options, $multi = true)
    {
        if (is_array($options)) {
            if ($multi == true) {
                foreach ($options as $k => $v) {
                    $this->addOptionTop($k, $v);
                }
            } else {
                foreach ($options as $k) {
                    $this->addOptiontop($k, $k);
                }
            }
        }
    }

    /**
     * @param string $link
     */
    public function addMenuTabs($link, $name = "")
    {
        $this->_menutabs[] = array('link' => $link, 'name' => $name, 'current' => 0);
    }

    public function addMenuTabsArray($options, $multi = true)
    {
        if (is_array($options)) {
            if ($multi == true) {
                foreach ($options as $k => $v) {
                    $this->addMenuTabsTop($k, $v);
                }
            } else {
                foreach ($options as $k) {
                    $this->addMenuTabsTop($k, $k);
                }
            }
        }
    }

    public function addHeader($value)
    {
        $this->_header = $value;
    }

    public function addSubHeader($value)
    {
        $this->_subheader = $value;
    }

    public function breadcrumb_nav($basename = "Home")
    {
        global $bc_site, $bc_label;
        $site = $bc_site;
        $return_str = "<a href=\"/\">$basename</a>";
        $str = substr(dirname(Xoops::getInstance()->getEnv('PHP_SELF')), 1);

        $arr = split('/', $str);
        $num = count($arr);

        if ($num > 1) {
            foreach ($arr as $val) {
                $return_str .= ' &gt; <a href="' . $site . $val . '/">' . $bc_label[$val] . '</a>';
                $site .= $val . '/';
            }
        } else {
            if ($num == 1) {
                $arr = $str;
                $return_str .= ' &gt; <a href="' . $bc_site . $arr . '/">' . $bc_label[$arr] . '</a>';
            }
        }

        return $return_str;
    }

    public function render($currentoption = 1, $display = true)
    {
        $xoops = Xoops::getInstance();
        $xoops->tpl()->assign('xo_module_menu_top', $this->_menutop);
        $this->_menutabs[$currentoption]['current'] = 1;
        $xoops->tpl()->assign('xo_module_menu_tab', $this->_menutabs);
         //$xoops->tpl()->assign('xo_admin_help', $this->_help);
         //if ($xoops->tpl()_name == '') {
         //    $xoops->tpl()->display('admin:system/admin_tabs.tpl');
         //}
        return;

        global $modversion;
        $_dirname = $this->_obj->getVar('dirname');
        $i = 0;

        /**
         * Selects current menu tab
         */
        foreach ($this->_menutabs as $k => $menus) {
            $menuItems[] = $menus;
        }
        $breadcrumb = $menuItems[$currentoption];
        $menuItems[$currentoption] = 'current';
        //Not the best method of adding CSS but the only method available at the moment since xoops is shitty with the backend
        //$menu = "<style type=\"text/css\" media=\"screen\">@import \"" . XOOPS_URL . "/modules/" . $this->_obj->getVar('dirname') . "/css/menu.css\";</style>";
        $menu = "<div id='buttontop_mod'>";
        $menu .= "<table style='width: 100%; padding: 0;' cellspacing='0'>\n<tr>";
        $menu .= "<td style='font-size: 10px; text-align: left; color: #2F5376; padding: 0 6px; line-height: 18px;'>";
        foreach ($this->_menutop as $k => $v) {
            $menu .= " <a href=\"$k\">$v</a> |";
        }
        $menu = substr($menu, 0, -1);

        $menu .= "</td>";
        $menu .= "<td style='text-align: right;'><strong>" . $this->_obj->getVar('name') . "</strong> : " . $breadcrumb . "</td>";
        $menu .= "</tr>\n</table>\n";
        $menu .= "</div>\n";
        $menu .= "<div id='buttonbar_mod'><ul>";
        foreach ($this->_menutabs as $k => $v) {
            $menu .= "<li id='" . $menuItems[$i] . "'><a href='" . XOOPS_URL . "/modules/" . $this->_obj->getVar('dirname') . "/" . $k . "'><span>$v</span></a></li>\n";
            $i++;
        }
        $menu .= "</ul>\n</div>\n";
        if ($this->_header) {
            $menu .= "<h4 class='admin_header'>";
            if (isset($modversion['name'])) {
                if ($modversion['image'] && $this->_obj->getVar('mid') == 1) {
                    $system_image = XOOPS_URL . '/modules/system/images/system/' . $modversion['image'];
                } else {
                    $system_image = XOOPS_URL . '/modules/' . $_dirname . '/images/' . $modversion['image'];
                }
                $menu .= "<img src='$system_image' align='middle' height='32' width='32' alt='' />";
                $menu .= " " . $modversion['name'] . "</h4>\n";
            } else {
                $menu .= " " . $this->_header . "</h4>\n";
            }
        }
        if ($this->_subheader) {
            $menu .= "<div class='admin_subheader'>" . $this->_subheader . "</div>\n";
        }
        $menu .= '<div class="clear">&nbsp;</div>';
        unset($this->_obj);
        if ($display == true) {
            echo $menu;
        } else {
            return $menu;
        }
    }
}
