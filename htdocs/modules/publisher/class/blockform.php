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
 *  Publisher class
 *
 * @copyright       The XUUPS Project http://sourceforge.net/projects/xuups/
 * @license         GNU GPL V2 or later (http://www.gnu.org/licenses/gpl-2.0.html)
 * @package         Publisher
 * @since           1.0
 * @author          trabis <lusopoemas@gmail.com>
 * @version         $Id$
 */

/**
 * Form that will output formatted as a HTML table
 *
 * No styles and no JavaScript to check for required fields.
 */
class PublisherBlockForm extends Xoops\Form\Form
{
    public function __construct()
    {
        parent::__construct('', '', '');
    }

    /**
     * @return string
     */
    public function render()
    {
        $ret = '<table border="0" width="100%">' . NWLINE;
        /* @var $ele Xoops\Form\Element */
        foreach ($this->getElements() as $ele) {
            if (!$ele->isHidden()) {
                $ret .= '<tr><td colspan="2">';
                $ret .= '<span style="font-weight: bold;">' . $ele->getCaption() . '</span>';
                $ret .= '</td></tr><tr><td>' . $ele->render() . '</td></tr>';
            }
        }
        $ret .= '</table>';
        return $ret;
    }
}
