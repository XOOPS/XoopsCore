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
 * DateTimeSelect - date and time form element
 *
 * @category  Xoops\Form\DateTimeSelect
 * @package   Xoops\Form
 * @author    Kazumi Ono <onokazu@xoops.org>
 * @copyright 2001-2016 XOOPS Project (http://xoops.org)
 * @license   GNU GPL 2 or later (http://www.gnu.org/licenses/gpl-2.0.html)
 * @link      http://xoops.org
 */
class DateTimeSelect extends ElementTray
{
    const SHOW_BOTH = 1;
    const SHOW_DATE = 0;
    const SHOW_TIME = 2;

    /**
     * __construct
     *
     * @param string|array      $caption  Caption or array of all attributes
     * @param string            $name     name
     * @param integer|\DateTime $value    unix timestamp or DateTime object
     * @param mixed             $showtime control display of date and time elements
     *                                     SHOW_BOTH, true  - show both date and time selectors
     *                                     SHOW_DATE, false - only show date selector
     *                                     SHOW_TIME        - only show time selector
     */
    public function __construct($caption, $name = null, $value = 0, $showtime = true)
    {
        // stash everything in the tray and sort out later
        if (is_array($caption)) {
            parent::__construct($caption);
            $this->set(':joiner', '');
        } else {
            parent::__construct($caption, '', $name);
            $this->set('value', $value);
            $this->set(':showtime', (int) $showtime);
        }

        $workingTime = \Xoops\Core\Locale\Time::cleanTime($this->get('value', 0));

        $displayDate = true;
        $displayTime = true;
        switch ((int) $this->get(':showtime', static::SHOW_BOTH)) {
            case static::SHOW_DATE:
                $displayTime = false;
                break;
            case static::SHOW_TIME:
                $displayDate = false;
                break;
        }

        $dateDefinition = [
            'caption' => '',
            'name' => $this->get('name') . '[date]',
            'id' => $this->get('name') . '-date',
            'size' => 15,
            'value' => $workingTime,
            ElementFactory::FORM_KEY => $this,
        ];
        if ($displayDate) {
            new DateSelect($dateDefinition);
        } else {
            unset($dateDefinition['size']);
            $dateDefinition['value'] = \Xoops\Core\Locale\Time::formatDate($workingTime);
            new Hidden($dateDefinition);
        }

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
            ElementFactory::FORM_KEY => $this
        ];

        if ($displayTime) {
            $timeDefinition['option'] = \Xoops\Core\Lists\Time::getList($minuteInterval);
            new Select($timeDefinition);
        } else {
            unset($timeDefinition['option'], $timeDefinition['size']);
            new Hidden($timeDefinition);
        }
    }
}
