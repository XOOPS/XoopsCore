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
 * DateSelect - date entry element with calendar popup
 *
 * @category  Xoops\Form\DateSelect
 * @package   Xoops\Form
 * @author    Kazumi Ono (AKA onokazu) http://www.myweb.ne.jp/, http://jp.xoops.org/
 * @copyright 2001-2014 The XOOPS Project http://sourceforge.net/projects/xoops/
 * @license   GNU GPL 2 or later (http://www.gnu.org/licenses/gpl-2.0.html)
 * @link      http://xoops.org
 * @since     2.0.0
*/
class DateSelect extends Text
{

    /**
     * __construct
     *
     * @param string  $caption caption
     * @param string  $name    name
     * @param integer $size    field size
     * @param integer $value   date as unix timestamp
     */
    public function __construct($caption, $name, $size = 2, $value = 0)
    {
        if ($value !== '') {
            $value = ($value === 0) ? time() : intval($value);
        }
        parent::__construct($caption, $name, $size, 2, $value);
    }

    /**
     * render
     *
     * @return string rendered form element
     */
    public function render()
    {
        static $included = false;
        $xoops = \Xoops::getInstance();

        $ele_value = (string) $this->getValue(false);
        $display_value = $ele_value;
        if (0 < intval($ele_value)) {
            $display_value = date(\XoopsLocale::getFormatShortDate(), $ele_value);
        }

        $jstime = \XoopsLocale::formatTimestamp($ele_value, \XoopsLocale::getFormatShortDate());
        $xoops->theme()->addScript('include/calendar.js');
        $xoops->theme()->addStylesheet('include/calendar-blue.css');
        if (!$included) {
            $included = true;
            $xoops->theme()->addScript(
                '',
                '',
                '
                var calendar = null;

                function selected(cal, date) {
                cal.sel.value = date;
                }

                function closeHandler(cal) {
                cal.hide();
                Calendar.removeEvent(document, "mousedown", checkCalendar);
                }

                function checkCalendar(ev) {
                var el = Calendar.is_ie ? Calendar.getElement(ev) : Calendar.getTargetElement(ev);
                for (; el != null; el = el.parentNode)
                if (el == calendar.element || el.tagName == "A") break;
                if (el == null) {
                calendar.callCloseHandler(); Calendar.stopEvent(ev);
                }
                }
                function showCalendar(id) {
                var el = xoopsGetElementById(id);
                if (calendar != null) {
                calendar.hide();
                } else {
                var cal = new Calendar(true, "' . $jstime . '", selected, closeHandler);
                calendar = cal;
                cal.setRange(1900, 2100);
                calendar.create();
                }
                calendar.sel = el;
                calendar.parseDate(el.value);
                calendar.showAtElement(el);
                Calendar.addEvent(document, "mousedown", checkCalendar);
                return false;
                }

                Calendar._DN = new Array
                ("' . \XoopsLocale::L_DAY_SUNDAY . '",
                "' . \XoopsLocale::L_DAY_MONDAY . '",
                "' . \XoopsLocale::L_DAY_TUESDAY . '",
                "' . \XoopsLocale::L_DAY_WEDNESDAY . '",
                "' . \XoopsLocale::L_DAY_THURSDAY . '",
                "' . \XoopsLocale::L_DAY_FRIDAY . '",
                "' . \XoopsLocale::L_DAY_SATURDAY . '",
                "' . \XoopsLocale::L_DAY_SUNDAY . '");
                Calendar._MN = new Array
                ("' . \XoopsLocale::L_MONTH_JANUARY . '",
                "' . \XoopsLocale::L_MONTH_FEBRUARY . '",
                "' . \XoopsLocale::L_MONTH_MARCH . '",
                "' . \XoopsLocale::L_MONTH_APRIL . '",
                "' . \XoopsLocale::L_MONTH_MAY . '",
                "' . \XoopsLocale::L_MONTH_JUNE . '",
                "' . \XoopsLocale::L_MONTH_JULY . '",
                "' . \XoopsLocale::L_MONTH_AUGUST . '",
                "' . \XoopsLocale::L_MONTH_SEPTEMBER . '",
                "' . \XoopsLocale::L_MONTH_OCTOBER . '",
                "' . \XoopsLocale::L_MONTH_NOVEMBER . '",
                "' . \XoopsLocale::L_MONTH_DECEMBER . '");

                Calendar._TT = {};
                Calendar._TT["TOGGLE"] = "' . \XoopsLocale::TOGGLE_FIRST_DAY_OF_WEEK . '";
                Calendar._TT["PREV_YEAR"] = "' . \XoopsLocale::PREVIOUS_YEAR . '";
                Calendar._TT["PREV_MONTH"] = "' . \XoopsLocale::PREVIOUS_MONTH . '";
                Calendar._TT["GO_TODAY"] = "' . \XoopsLocale::GO_TODAY . '";
                Calendar._TT["NEXT_MONTH"] = "' . \XoopsLocale::NEXT_MONTH . '";
                Calendar._TT["NEXT_YEAR"] = "' . \XoopsLocale::NEXT_YEAR . '";
                Calendar._TT["SEL_DATE"] = "' . \XoopsLocale::SELECT_DATE . '";
                Calendar._TT["DRAG_TO_MOVE"] = "' . \XoopsLocale::DRAG_TO_MOVE . '";
                Calendar._TT["PART_TODAY"] = "(' . \XoopsLocale::TODAY . ')";
                Calendar._TT["MON_FIRST"] = "' . \XoopsLocale::DISPLAY_MONDAY_FIRST . '";
                Calendar._TT["SUN_FIRST"] = "' . \XoopsLocale::DISPLAY_SUNDAY_FIRST . '";
                Calendar._TT["CLOSE"] = "' . \XoopsLocale::A_CLOSE . '";
                Calendar._TT["TODAY"] = "' . \XoopsLocale::TODAY . '";

                // date formats
                Calendar._TT["DEF_DATE_FORMAT"] = "' . \XoopsLocale::getFormatShortDate() . '";
                Calendar._TT["TT_DATE_FORMAT"] = "' . \XoopsLocale::getFormatShortDate() . '";

                Calendar._TT["WK"] = "";
                '
            );
        }
        if ($this->getSize() > $this->getMaxcols()) {
            $maxcols = $this->getMaxcols();
        } else {
            $maxcols = $this->getSize();
        }
        $this->addAttribute('class', 'span' . $maxcols);
        $dlist = $this->isDatalist();
        if (!empty($dlist)) {
            $this->addAttribute('list', 'list_' . $this->getName());
        }

        $attributes = $this->renderAttributeString();
        return '<input ' . $attributes . 'value="' . $display_value . '" '
            . $this->getExtra() .' >'
            . '<button class="btn" type="button" onclick="return showCalendar(\''
            . $this->getName() . '\');"> ... </button>';
    }
}
