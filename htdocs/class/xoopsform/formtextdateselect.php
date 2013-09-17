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
 * XOOPS form element of text date select
 *
 * @copyright       The XOOPS Project http://sourceforge.net/projects/xoops/
 * @license         GNU GPL 2 (http://www.gnu.org/licenses/old-licenses/gpl-2.0.html)
 * @package         class
 * @subpackage      xoopsform
 * @since           2.0.0
 * @author          Kazumi Ono (AKA onokazu) http://www.myweb.ne.jp/, http://jp.xoops.org/
 * @version         $Id$
 */

defined('XOOPS_ROOT_PATH') or die("XOOPS root path not defined");

/**
 * A text field with calendar popup
 */
class XoopsFormTextDateSelect extends XoopsFormText
{

    /**
     * @param string $caption
     * @param string $name
     * @param int    $size
     * @param int    $value
     */
    public function __construct($caption, $name, $size = 2, $value = 0)
    {
        $value = !is_numeric($value) ? time() : intval($value);
        $value = ($value == 0) ? time() : $value;
        parent::__construct($caption, $name, $size, 2, $value);
    }

    public function render()
    {
        static $included = false;
        $xoops = Xoops::getInstance();

        $ele_name = $this->getName();
        $ele_value = $this->getValue(false);
        if (is_string($ele_value)) {
            $display_value = $ele_value;
            $ele_value = time();
        } else {
            $display_value = date(XoopsLocale::getFormatShortDate(), $ele_value);
        }

        $jstime = XoopsLocale::formatTimestamp($ele_value, XoopsLocale::getFormatShortDate());
        $xoops->theme()->addScript('include/calendar.js');
        $xoops->theme()->addStylesheet('include/calendar-blue.css');
        if (!$included) {
            $included = true;
            $xoops->theme()->addScript('','', '
                var calendar = null;

                function selected(cal, date)
                {
                cal.sel.value = date;
                }

                function closeHandler(cal)
                {
                cal.hide();
                Calendar.removeEvent(document, "mousedown", checkCalendar);
                }

                function checkCalendar(ev)
                {
                var el = Calendar.is_ie ? Calendar.getElement(ev) : Calendar.getTargetElement(ev);
                for (; el != null; el = el.parentNode)
                if (el == calendar.element || el.tagName == "A") break;
                if (el == null) {
                calendar.callCloseHandler(); Calendar.stopEvent(ev);
                }
                }
                function showCalendar(id)
                {
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
                ("' . XoopsLocale::L_DAY_SUNDAY . '",
                "' . XoopsLocale::L_DAY_MONDAY . '",
                "' . XoopsLocale::L_DAY_TUESDAY . '",
                "' . XoopsLocale::L_DAY_WEDNESDAY . '",
                "' . XoopsLocale::L_DAY_THURSDAY . '",
                "' . XoopsLocale::L_DAY_FRIDAY . '",
                "' . XoopsLocale::L_DAY_SATURDAY . '",
                "' . XoopsLocale::L_DAY_SUNDAY . '");
                Calendar._MN = new Array
                ("' . XoopsLocale::L_MONTH_JANUARY . '",
                "' . XoopsLocale::L_MONTH_FEBRUARY . '",
                "' . XoopsLocale::L_MONTH_MARCH . '",
                "' . XoopsLocale::L_MONTH_APRIL . '",
                "' . XoopsLocale::L_MONTH_MAY . '",
                "' . XoopsLocale::L_MONTH_JUNE . '",
                "' . XoopsLocale::L_MONTH_JULY . '",
                "' . XoopsLocale::L_MONTH_AUGUST . '",
                "' . XoopsLocale::L_MONTH_SEPTEMBER . '",
                "' . XoopsLocale::L_MONTH_OCTOBER . '",
                "' . XoopsLocale::L_MONTH_NOVEMBER . '",
                "' . XoopsLocale::L_MONTH_DECEMBER . '");

                Calendar._TT = {};
                Calendar._TT["TOGGLE"] = "' . XoopsLocale::TOGGLE_FIRST_DAY_OF_WEEK . '";
                Calendar._TT["PREV_YEAR"] = "' . XoopsLocale::PREVIOUS_YEAR . '";
                Calendar._TT["PREV_MONTH"] = "' . XoopsLocale::PREVIOUS_MONTH . '";
                Calendar._TT["GO_TODAY"] = "' . XoopsLocale::GO_TODAY . '";
                Calendar._TT["NEXT_MONTH"] = "' . XoopsLocale::NEXT_MONTH . '";
                Calendar._TT["NEXT_YEAR"] = "' . XoopsLocale::NEXT_YEAR . '";
                Calendar._TT["SEL_DATE"] = "' . XoopsLocale::SELECT_DATE . '";
                Calendar._TT["DRAG_TO_MOVE"] = "' . XoopsLocale::DRAG_TO_MOVE . '";
                Calendar._TT["PART_TODAY"] = "(' . XoopsLocale::TODAY . ')";
                Calendar._TT["MON_FIRST"] = "' . XoopsLocale::DISPLAY_MONDAY_FIRST . '";
                Calendar._TT["SUN_FIRST"] = "' . XoopsLocale::DISPLAY_SUNDAY_FIRST . '";
                Calendar._TT["CLOSE"] = "' . XoopsLocale::A_CLOSE . '";
                Calendar._TT["TODAY"] = "' . XoopsLocale::TODAY . '";

                // date formats
                Calendar._TT["DEF_DATE_FORMAT"] = "' . XoopsLocale::getFormatShortDate() . '";
                Calendar._TT["TT_DATE_FORMAT"] = "' . XoopsLocale::getFormatShortDate() . '";

                Calendar._TT["WK"] = "";
            ');
        }
        if ($this->getSize() > $this->getMaxcols()) {
            $maxcols = 4;
        } else {
            $maxcols = $this->getSize();
        }
        $class = ($this->getClass() != '' ? " class='span" . $maxcols . " " . $this->getClass() . "'" : " class='span" . $maxcols . "'");
        $extra = ($this->getExtra() != '' ? " " . $this->getExtra() : '');
        $required = ($this->isRequired() ? ' required' : '');

        return "<input type='text' name='" . $ele_name . "' id='" . $ele_name . "'" . $class ."' maxlength='" . $this->getMaxlength() . "' value='" . $display_value . "'" . $extra . $required . " /><button class='btn' type='button' onclick='return showCalendar(\"" . $this->getName() . "\");'> ... </button>" ;
    }
}
