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
 * XOOPS Form element of tab
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

class XoopsFormTab extends XoopsFormElementTray
{
    /**
     * @param string $caption
     * @param string $name Unique identifier for this tsb
     */
    public function __construct($caption, $name)
    {
        $this->setName($name);
        $this->setCaption($caption);
    }

    /**
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