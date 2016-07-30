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
class XoopsFormTextDateSelect extends Xoops\Form\DateSelect
{
    /**
     * Note change in arguments, removed size
     *
     * @param string $caption
     * @param string $name
     * @param int    $size
     * @param int    $value
     */
    public function __construct($caption, $name, $size = 15, $value = 0)
    {
        parent::__construct($caption, $name, $value);
    }
}
