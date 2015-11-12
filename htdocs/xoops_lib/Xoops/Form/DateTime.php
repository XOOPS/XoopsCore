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
 * @author    Kazumi Ono <onokazu@xoops.org>
 * @copyright 2001-2015 XOOPS Project (http://xoops.org)
 * @license   GNU GPL 2 or later (http://www.gnu.org/licenses/gpl-2.0.html)
 * @link      http://xoops.org
 */
class DateTime extends ElementTray
{
    /**
     * __construct
     *
     * @param string|array      $caption Caption or array of all attributes
     * @param string            $name    name
     * @param integer|\DateTime $value   unix timestamp or DateTime object
     */
    public function __construct($caption, $name = null, $value = 0)
    {
        // stash everything in the tray and sort out later
        if (is_array($caption)) {
            parent::__construct($caption);
            $this->set(':joiner', '');
        } else {
            parent::__construct($caption, '', $name);
            $this->set('value', $value);
        }

        $workingTime = \Xoops\Core\Locale\Time::cleanTime($this->get('value', 0));

        $dateDefinition = [
            'caption' => '',
            'name' => $this->get('name') . '[date]',
            'id' => $this->get('name') . '-date',
            'size' => 15,
            'value' => $workingTime,
            ElementFactory::FORM_KEY => $this,
        ];
        new DateSelect($dateDefinition);

        $minuteInterval = $this->get(':minuteinterval', 15);
        $hours    = (int) ltrim($workingTime->format('H'), '0');
        $minutes  = (int) ltrim($workingTime->format('i'), '0');

        $timeDefinition = [
            'caption' => '',
            'name' => $this->get('name') . '[time]',
            'id' => $this->get('name') . '-time',
            'size' => 1,
            'value' => \Xoops\Core\Locale\Time::formatTime(
                $hours * 3600 + 60*$minuteInterval * ceil($minutes / $minuteInterval),
                'short',
                new \DateTimeZone('UTC')
            ),
            ElementFactory::FORM_KEY => $this,
            'option' => \Xoops\Core\Lists\Time::getList($minuteInterval),
        ];
        new Select($timeDefinition);
    }
}
