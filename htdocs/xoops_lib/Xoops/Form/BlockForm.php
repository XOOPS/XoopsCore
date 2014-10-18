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
 * @copyright 2012-2014 The XOOPS Project http://sourceforge.net/projects/xoops/
 * @license   GNU GPL 2 or later (http://www.gnu.org/licenses/gpl-2.0.html)
 * @link      http://xoops.org
 * @since     2.6.0
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
            if (!$ele->isHidden()) {
                $ret .= '<div class="row"><div class="span2"><strong>' . $ele->getCaption().'</strong></div>';
                $ret .= '<div class="span4">' . $ele->render() . '<br />';
                $ret .= '<em>' . $ele->getDescription() . '</em><br /></div></div>';
            } else {
                $ret .= $ele->render();
            }
        }
        $ret .= '</div>';
        return $ret;
    }
}
