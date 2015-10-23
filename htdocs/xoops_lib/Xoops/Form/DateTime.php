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
 * @copyright 2001-2014 XOOPS Project (http://xoops.org)
 * @license   GNU GPL 2 or later (http://www.gnu.org/licenses/gpl-2.0.html)
 * @link      http://xoops.org
 * @since     2.0.0
*/
class DateTime extends ElementTray
{
    /**
     * __construct
     *
     * @param string            $caption  caption
     * @param string            $name     name
     * @param integer           $size     size
     * @param integer|\DateTime $value    unix timestamp or DateTime object
     * @param boolean           $showtime true to show time, false for date only
     */
    public function __construct($caption, $name, $size = 12, $value = 0, $showtime = true)
    {
        parent::__construct($caption, '');
        $value = \Xoops\Core\Locale\Time::cleanTime($value);
        $date = new DateSelect('', $name . '[date]', $size, $value);
        $date->setAttribute('id', $name.'-date');
        $this->addElement($date);

        if ($showtime) {
            $minuteInterval = 15;
            $hours    = (int) ltrim($value->format('H'), '0');
            $minutes  = (int) ltrim($value->format('i'), '0');
            $timeSelect = new Select(
                '',
                $name . '[time]',
                \Xoops\Core\Locale\Time::formatTime($hours * 3600 + 60*$minuteInterval * ceil($minutes / $minuteInterval), 'short', new \DateTimeZone('UTC'))
            );
            \Xoops\Core\Lists\Time::setOptionsArray($timeSelect, $minuteInterval);
            $timeSelect->setAttribute('id', $name.'-time');
            $timeSelect->setClass('span2');
            $this->addElement($timeSelect);
        } else {
            $this->addElement(new Hidden($name . '[time]', 0));
        }
    }
}
