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
 * BlockForm - Form that will output formatted as a HTML table
 *
 * No styles and no JavaScript to check for required fields.
 *
 * @category  Xoops\Form\BlockForm
 * @package   Xoops\Form
 * @author    trabis <lusopoemas@gmail.com>
 * @copyright 2012-2016 XOOPS Project (http://xoops.org)
 * @license   GNU GPL 2 or later (http://www.gnu.org/licenses/gpl-2.0.html)
 * @link      http://xoops.org
 */
class BlockForm extends Form
{
    /**
     * __construct
     */
    public function __construct()
    {
        parent::__construct('', '', '');
    }

    /**
     * render
     *
     * @return string
     */
    public function render()
    {
        $ret = '<div>';
        /* @var $ele Element */
        foreach ($this->getElements() as $ele) {
            if ($ele->has('datalist')) {
                $ret .= $ele->renderDatalist();
            }
            if (!$ele->isHidden()) {
                $ret .= '<div class="form-group">';
                $ret .= '<label>' . $ele->getCaption();
                $ret .= ($ele->isRequired() ? '<span class="caption-required">*</span>' : '') . '</label>';
                $ret .= $ele->render();
                $ret .= '<small class="text-muted">' . $ele->getDescription() . '</small>';
                $ret .= '<p class="dsc_pattern_vertical">' . $ele->getPatternDescription() . '</p>';
                $ret .= '</div>' . "\n";
            } else {
                $ret .= $ele->render(). "\n";
            }
        }
        $ret .= '</div>';
        return $ret;
    }
}
