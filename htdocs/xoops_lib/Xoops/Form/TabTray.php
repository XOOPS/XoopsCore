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
 * @copyright 2012-2016 XOOPS Project (http://xoops.org)
 * @license   GNU GPL 2 or later (http://www.gnu.org/licenses/gpl-2.0.html)
 * @link      http://xoops.org
 */
class TabTray extends ElementTray
{
    /**
     * __construct
     *
     * @param string|array $caption Caption or array of all attributes
     * @param string       $name    Unique identifier for this tray
     */
    public function __construct($caption, $name = null)
    {
        if (is_array($caption)) {
            parent::__construct($caption);
        } else {
            parent::__construct([]);
            $this->setName($name);
            $this->setCaption($caption);
        }
    }

    /**
     * create HTML to output the form as a table
     *
     * @return string
     */
    public function render()
    {
        $xoops = \Xoops::getInstance();

        $ret = '<div id="tabs_' . $this->getName() . '">' . "\n";
        $ret .= '<ul class="nav nav-tabs">' . "\n";
        $active = ' active';
        foreach ($this->getElements() as $ele) {
            if ($ele instanceof Tab) {
                $ret .= '<li class="nav' . $active . '"><a href="#tab_' . $ele->getName()
                    . '" data-toggle="tab">' . $ele->getCaption() . '</a></li>' . "\n";
                $active = '';
            }
        }
        $ret .= '</ul><br>' . "\n";

        $hidden = '';
        $extras = array();

        $ret .= '<div class="tab-content">';
        $active = ' in active';

        foreach ($this->getElements() as $ele) {
            /* @var $ele Element */
            if (!$ele->isHidden()) {
                if (!$ele instanceof Raw) {
                    if ($ele instanceof Tab) {
                        $ret .= '<div class="tab-pane fade' . $active .'" id="tab_'. $ele->getName() . '">';
                        $ret .= $ele->render();
                        $ret .= '</div>' . "\n";
                        $active = '';
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
        $ret .= '</div>' . "\n";
        return $ret;
    }
}
