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
 * Tab - a form tab
 *
 * @category  Xoops\Form\Tab
 * @package   Xoops\Form
 * @author    trabis <lusopoemas@gmail.com>
 * @copyright 2012-2015 XOOPS Project (http://xoops.org)
 * @license   GNU GPL 2 or later (http://www.gnu.org/licenses/gpl-2.0.html)
 * @link      http://xoops.org
 */
class Tab extends ElementTray
{
    /**
     * __construct
     *
     * @param string|array $caption Caption or array of all attributes
     * @param string       $name    unique identifier for this tab
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
     * render
     *
     * @return string
     */
    public function render()
    {
        $ret = '';
        /* @var $ele Element */
        foreach ($this->getElements() as $ele) {
            $ret .= "\n";
            $ret .= '<tr>' . "\n";
            $ret .= '<td class="head" width="30%">' . "\n";
            $required = $ele->isRequired() ? '-required' : '';
            $ret .= '<div class="xoops-form-element-caption' . $required . '">' . "\n";
            $ret .= '<span class="caption-text">' . $ele->getCaption() . '</span>' . "\n";
            $ret .= '<span class="caption-marker">*</span>' . "\n";
            $ret .= '</div>' . "\n";
            $description = $ele->getDescription();
            if ($description) {
                $ret .= '<div style="font-weight: normal">' . "\n";
                $ret .= $description . "\n";
                $ret .= '</div>' . "\n";
            }
            $ret .= '</td>' . "\n";
            $ret .= '<td class="even">' . "\n";
            $ret .= $ele->render() . "\n";
            $ret .= '<span class="dsc_pattern_horizontal">'. $ele->getPatternDescription() . '</span>';
            $ret .= '</td>' . "\n";
            $ret .= '</tr>' . "\n";
        }
        return $ret;
    }
}
