<?php
$xoops_url = \XoopsBaseConfig::get('url');
?>
<link rel="stylesheet" type="text/css" media="all" href="<?php echo $xoops_url;?>/include/calendar-blue.css"/>
<script type="text/javascript" src="<?php echo $xoops_url . '/include/calendar.js';?>"></script>
<script type="text/javascript">
    <!--
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
        for (; el != null; el = el.parentNode) {
            if (el == calendar.element || el.tagName == "A") {
                break;
            }
        }
        if (el == null) {
            calendar.callCloseHandler();
            Calendar.stopEvent(ev);
        }
    }
    function showCalendar(id)
    {
        var el = xoopsGetElementById(id);
        if (calendar != null) {
            calendar.hide();
        } else {
            var cal = new Calendar(true, "<?php if (isset($jstime)) {
                echo $jstime;
            } else {
                echo 'null';
            }?>", selected, closeHandler);
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
        ("<?php echo XoopsLocale::L_DAY_SUNDAY;?>",
            "<?php echo XoopsLocale::L_DAY_MONDAY;?>",
            "<?php echo XoopsLocale::L_DAY_TUESDAY;?>",
            "<?php echo XoopsLocale::L_DAY_WEDNESDAY;?>",
            "<?php echo XoopsLocale::L_DAY_THURSDAY;?>",
            "<?php echo XoopsLocale::L_DAY_FRIDAY;?>",
            "<?php echo XoopsLocale::L_DAY_SATURDAY;?>",
            "<?php echo XoopsLocale::L_DAY_SUNDAY;?>");
    Calendar._MN = new Array
        ("<?php echo XoopsLocale::L_MONTH_JANUARY;?>",
            "<?php echo XoopsLocale::L_MONTH_FEBRUARY;?>",
            "<?php echo XoopsLocale::L_MONTH_MARCH;?>",
            "<?php echo XoopsLocale::L_MONTH_APRIL;?>",
            "<?php echo XoopsLocale::L_MONTH_MAY;?>",
            "<?php echo XoopsLocale::L_MONTH_JUNE;?>",
            "<?php echo XoopsLocale::L_MONTH_JULY;?>",
            "<?php echo XoopsLocale::L_MONTH_AUGUST;?>",
            "<?php echo XoopsLocale::L_MONTH_SEPTEMBER;?>",
            "<?php echo XoopsLocale::L_MONTH_OCTOBER;?>",
            "<?php echo XoopsLocale::L_MONTH_NOVEMBER;?>",
            "<?php echo XoopsLocale::L_MONTH_DECEMBER;?>");

    Calendar._TT = {};
    Calendar._TT["TOGGLE"] = "<?php echo XoopsLocale::TOGGLE_FIRST_DAY_OF_WEEK;?>";
    Calendar._TT["PREV_YEAR"] = "<?php echo XoopsLocale::PREVIOUS_YEAR;?>";
    Calendar._TT["PREV_MONTH"] = "<?php echo XoopsLocale::PREVIOUS_MONTH;?>";
    Calendar._TT["GO_TODAY"] = "<?php echo XoopsLocale::GO_TODAY;?>";
    Calendar._TT["NEXT_MONTH"] = "<?php echo XoopsLocale::NEXT_MONTH;?>";
    Calendar._TT["NEXT_YEAR"] = "<?php echo XoopsLocale::NEXT_YEAR;?>";
    Calendar._TT["SEL_DATE"] = "<?php echo XoopsLocale::SELECT_DATE;?>";
    Calendar._TT["DRAG_TO_MOVE"] = "<?php echo XoopsLocale::DRAG_TO_MOVE;?>";
    Calendar._TT["PART_TODAY"] = "(<?php echo XoopsLocale::TODAY;?>)";
    Calendar._TT["MON_FIRST"] = "<?php echo XoopsLocale::DISPLAY_MONDAY_FIRST;?>";
    Calendar._TT["SUN_FIRST"] = "<?php echo XoopsLocale::DISPLAY_SUNDAY_FIRST;?>";
    Calendar._TT["CLOSE"] = "<?php echo XoopsLocale::A_CLOSE;?>";
    Calendar._TT["TODAY"] = "<?php echo XoopsLocale::TODAY;?>";

    // date formats
    // todo, strings not defined bellow?
    Calendar._TT["DEF_DATE_FORMAT"] = "<?php echo _CAL_DEF_DATE_FORMAT;?>";
    Calendar._TT["TT_DATE_FORMAT"] = "<?php echo _CAL_TT_DATE_FORMAT;?>";

    Calendar._TT["WK"] = "";
    //-->
</script>