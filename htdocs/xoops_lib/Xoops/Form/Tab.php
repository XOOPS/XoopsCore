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
 * @copyright 2012-2014 The XOOPS Project http://sourceforge.net/projects/xoops/
 * @license   GNU GPL 2 or later (http://www.gnu.org/licenses/gpl-2.0.html)
 * @link      http://xoops.org
 * @since     2.0.0
*/
class Tab extends ElementTray
{
    /**
     * __construct
     *
     * @param string $caption tab caption
     * @param string $name    unique identifier for this tab
     */
    public function __construct($caption, $name)
    {
        $this->setName($name);
        $this->setCaption($caption);
    }

    /**
     * render
     *
     * @return string
     */
    public function render()
    {
        $ret = '';
        /* @var $ele XoopsFormElement*/
        foreach ($this->getElements() as $ele) {
            $ret .= NWLINE;
            $ret .= '<tr>' . NWLINE;
            $ret .= '<td class="head" width="30%">' . NWLINE;
            $required = $ele->isRequired() ? '-required' : '';
            $ret .= '<div class="xoops-form-element-caption' . $required . '">' . NWLINE;
            $ret .= '<span class="caption-text">' . $ele->getCaption() . '</span>' . NWLINE;
            $ret .= '<span class="caption-marker">*</span>' . NWLINE;
            $ret .= '</div>' . NWLINE;
            $description = $ele->getDescription();
            if ($description) {
                $ret .= '<div style="font-weight: normal">' . NWLINE;
                $ret .= $description . NWLINE;
                $ret .= '</div>' . NWLINE;
            }
            $ret .= '</td>' . NWLINE;
            $ret .= '<td class="even">' . NWLINE;
            $ret .= $ele->render() . NWLINE;
            $ret .= '<span class="dsc_pattern_horizontal">'. $ele->getPatternDescription() . '</span>';
            $ret .= '</td>' . NWLINE;
            $ret .= '</tr>' . NWLINE;
        }
        return $ret;
    }
}
