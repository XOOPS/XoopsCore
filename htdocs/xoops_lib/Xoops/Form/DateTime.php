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
 * DateTime - date and time form element
 *
 * @category  Xoops\Form\DateTime
 * @package   Xoops\Form
 * @author    Kazumi Ono (AKA onokazu) http://www.myweb.ne.jp/, http://jp.xoops.org/
 * @copyright 2001-2014 The XOOPS Project http://sourceforge.net/projects/xoops/
 * @license   GNU GPL 2 or later (http://www.gnu.org/licenses/gpl-2.0.html)
 * @link      http://xoops.org
 * @since     2.0.0
*/
class DateTime extends ElementTray
{
    /**
     * __construct
     *
     * @param string  $caption  caption
     * @param string  $name     name
     * @param integer $size     size
     * @param integer $value    value unix timestamp
     * @param boolean $showtime true to show time, false for date only
     */
    public function __construct($caption, $name, $size = 2, $value = 0, $showtime = true)
    {
        parent::__construct($caption, '');
        $value = intval($value);
        $value = ($value > 0) ? $value : time();
        $datetime = getdate($value);
        $date = new DateSelect('', $name . '[date]', $size, $value);
        $date->setAttribute('id', $name.'-date');
        $this->addElement($date);

        if ($showtime) {
            $timearray = array();
            for ($i = 0; $i < 24; ++$i) {
                for ($j = 0; $j < 60; $j = $j + 10) {
                    $key = ($i * 3600) + ($j * 60);
                    $timearray[$key] = ($j != 0) ? $i . ':' . $j : $i . ':0' . $j;
                }
            }
            ksort($timearray);

            $timeselect =
                new Select('', $name . '[time]', $datetime['hours'] * 3600 + 600 * ceil($datetime['minutes'] / 10));
            $timeselect->setAttribute('id', $name.'-time');
            $timeselect->addOptionArray($timearray);
            $timeselect->setClass('span2');
            $this->addElement($timeselect);
        } else {
            $this->addElement(new Hidden($name . '[time]', 0));
        }
    }
}
