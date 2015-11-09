<?php
/*
 You may not change or alter any portion of this comment or credits
 of supporting developers from this source code or any supporting source code
 which is considered copyrighted (c) material of the original comment or credit authors.

 This program is distributed in the hope that it will be useful,
 but WITHOUT ANY WARRANTY; without even the implied warranty of
 MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
*/

namespace Xoops\Form;

/**
 * TabTray - a form tray for tabs
 *
 * @category  Xoops\Form\TabTray
 * @package   Xoops\Form
 * @author    trabis <lusopoemas@gmail.com>
 * @copyright 2012-2015 XOOPS Project (http://xoops.org)
 * @license   GNU GPL 2 or later (http://www.gnu.org/licenses/gpl-2.0.html)
 * @link      http://xoops.org
 */
class TabTray extends ElementTray
{
    /**
     * Theme to use for jquery UI
     *
     * @var string
     */
    private $uiTheme = '';

    /**
     * __construct
     *
     * @param string $caption   tray caption
     * @param string $name      Unique identifier for this tray
     * @param string $uiTheme   Theme to use for jquery UI (remove? now set by theme)
     * @param string $delimiter delimiter
     */
    public function __construct($caption, $name, $uiTheme = 'base', $delimiter = "&nbsp;")
    {
        $this->setName($name);
        $this->setCaption($caption);
        $this->delimiter = $delimiter;
        $this->uiTheme = $uiTheme;
    }

    /**
     * create HTML to output the form as a table
     *
     * @return string
     */
    public function render()
    {
        $xoops = \Xoops::getInstance();
        $xoops->theme()->addBaseScriptAssets('@jquery');
        $xoops->theme()->addBaseScriptAssets('@jqueryui');
        $xoops->theme()->addBaseStylesheetAssets('@jqueryuicss');
        $xoops->theme()->addScript('', '', '$(function() { $("#tabs_' . $this->getName() . '").tabs(); });');

        $ret = '<div id="tabs_' . $this->getName() . '">' . "\n";
        $ret .= '<ul>' . "\n";
        foreach ($this->getElements() as $ele) {
            if ($ele instanceof Tab) {
                $ret .= '<li><a href="#tab_' . $ele->getName() . '"><span>'
                    . $ele->getCaption() . '</span></a></li>' . "\n";
            }
        }
        $ret .= '</ul>' . "\n";

        $hidden = '';
        $extras = array();

        foreach ($this->getElements() as $ele) {
            /* @var $ele Element */
            if (!$ele->isHidden()) {
                if (!$ele instanceof Raw) {
                    if ($ele instanceof Tab) {
                        $ret .= '<div id="tab_' . $ele->getName() . '">' . "\n";
                        $ret .= '<table class="outer" cellspacing="1">' . "\n";
                        $ret .= $ele->render();
                        $ret .= '</table>' . "\n";
                        $ret .= '</div>' . "\n";
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
            $tray = new ElementTray('', $this->getJoiner());
            foreach ($extras as $extra) {
                $tray->addElement($extra);
            }
            $ret .= $tray->render();
            $ret .= "\n";
        }

        $ret .= $hidden . "\n";
        $ret .= '</div>' . "\n";
        return $ret;
    }
}
