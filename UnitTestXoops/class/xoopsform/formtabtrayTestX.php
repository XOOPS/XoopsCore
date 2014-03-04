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
 * XOOPS Form element of tab tray
 *
 * @copyright       The XOOPS Project http://sourceforge.net/projects/xoops/
 * @license         GNU GPL 2 (http://www.gnu.org/licenses/old-licenses/gpl-2.0.html)
 * @package         class
 * @subpackage      xoopsform
 * @since           2.6.0
 * @author          trabis <lusopoemas@gmail.com>
 * @version         $Id$
 */

defined('XOOPS_ROOT_PATH') or die('Restricted access');

class XoopsFormTabTray extends XoopsFormElementTray
{
    /**
     * Theme to use for jquery UI
     *
     * @var string
     */
    private $_uiTheme = '';

    /**
     * @param string $caption
     * @param string $name Unique identifier for this tray
     * @param string $uiTheme Theme to use for jquery UI
     * @param string $delimiter
     */
    public function __construct($caption, $name, $uiTheme = 'base', $delimiter = "&nbsp;")
    {
        $this->setName($name);
        $this->setCaption($caption);
        $this->_delimiter = $delimiter;
        $this->_uiTheme = $uiTheme;
    }

    /**
     * create HTML to output the form as a table
     *
     * @return string
     */
    public function render()
    {
        $xoops = Xoops::getInstance();
        $xoops->theme()->addScript('media/jquery/jquery.js');
        $xoops->theme()->addScript('media/jquery/ui/jquery.ui.js');
        $xoops->theme()->addStylesheet('media/jquery/ui/' . $this->_uiTheme . '/ui.all.css');
        $xoops->theme()->addScript('', '', '
            $(function() {
                $("#tabs_' . $this->getName() . '").tabs();
            });
        ');

        $ret = '<div id="tabs_' . $this->getName() . '">' . NWLINE;
        $ret .= '<ul>' . NWLINE;
        foreach ($this->getElements() as $ele) {
            if ($ele instanceof XoopsFormTab) {
                $ret .= '<li><a href="#tab_' . $ele->getName() . '"><span>' . $ele->getCaption() . '</span></a></li>' . NWLINE;
            }
        }
        $ret .= '</ul>' . NWLINE;

        $hidden = '';
        $extras = array();

        foreach ($this->getElements() as $ele) {
            /* @var $ele XoopsFormElement */
            if (!$ele->isHidden()) {
                if (!$ele instanceof XoopsFormRaw) {
                    if ($ele instanceof XoopsFormTab) {
                        $ret .= '<div id="tab_' . $ele->getName() . '">' . NWLINE;
                        $ret .= '<table class="outer" cellspacing="1">' . NWLINE;
                        $ret .= $ele->render();
                        $ret .= '</table>' . NWLINE;
                        $ret .= '</div>' . NWLINE;
                    } else {
                        $extras[] = $ele;
                    }
                } else {
                    $ret .= $ele->render();
                }
            } else {
                $hidden .= $ele->render();
            }
        }
        if (!empty($extras)) {
            $tray = new XoopsFormElementTray('', $this->getDelimiter());
            foreach ($extras as $extra) {
                $tray->addElement($extra);
            }
            $ret .= $tray->render();
            $ret .= NWLINE;
        }

        $ret .= $hidden . NWLINE;
        $ret .= '</div>' . NWLINE;
        return $ret;
    }
}