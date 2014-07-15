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
 * XOOPS form element of datetime
 *
 * @copyright       The XOOPS Project http://sourceforge.net/projects/xoops/
 * @license         GNU GPL 2 (http://www.gnu.org/licenses/old-licenses/gpl-2.0.html)
 * @package         class
 * @subpackage      xoopsform
 * @since           2.0.0
 * @author          Kazumi Ono (AKA onokazu) http://www.myweb.ne.jp/, http://jp.xoops.org/
 * @version         $Id$
 */

defined('XOOPS_ROOT_PATH') or die('Restricted access');

class XoopsFormDateTime extends XoopsFormElementTray
{
    /**
     * @param string  $caption
     * @param string  $name
     * @param integer $size
     * @param integer $value
     * @param boolean $showtime
     */
    public function __construct($caption, $name, $size = 2, $value = 0, $showtime = true)
    {
        parent::__construct($caption, '');
        $value = intval($value);
        $value = ($value > 0) ? $value : time();
        $datetime = getDate($value);
        $date = new XoopsFormTextDateSelect('', $name . '[date]', $size, $value);
        $this->addElement($date);

        if ($showtime) {
            $timearray = array();
            for ($i = 0; $i < 24; $i++) {
                for ($j = 0; $j < 60; $j = $j + 10) {
                    $key = ($i * 3600) + ($j * 60);
                    $timearray[$key] = ($j != 0) ? $i . ':' . $j : $i . ':0' . $j;
                }
            }
            ksort($timearray);

            $timeselect = new XoopsFormSelect('', $name . '[time]', $datetime['hours'] * 3600 + 600 * ceil($datetime['minutes'] / 10));
            $timeselect->addOptionArray($timearray);
            $timeselect->setClass('span2');
            $this->addElement($timeselect);
        } else {
            $this->addElement(new XoopsFormHidden($name . '[time]', 0));
        }
    }
}