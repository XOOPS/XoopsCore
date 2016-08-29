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
 * Backward compatibility stub - use real class, as shown below for all new development.
 */
class XoopsFormDateTime extends Xoops\Form\DateTimeSelect
{
    /**
     * Note change in arguments, removed size
     *
     * @param string  $caption  form field caption
     * @param string  $name     form variable name
     * @param integer $size     size of date select
     * @param integer $value    unix timestamp, defaults to now
     * @param mixed   $showtime control display of date and time elements
     *                           SHOW_BOTH, true  - show both date and time selectors
     *                           SHOW_DATE, false - only show date selector
     *                           SHOW_TIME        - only show time selector
     */
    public function __construct($caption, $name, $size = 12, $value = 0, $showtime = true)
    {
        parent::__construct($caption, $name, $value, $showtime);
    }
}
