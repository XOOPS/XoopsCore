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
 * Protector
 *
 * @copyright       The XOOPS Project http://sourceforge.net/projects/xoops/
 * @license         http://www.fsf.org/copyleft/gpl.html GNU public license
 * @package         protector
 * @author          trabis <lusopoemas@gmail.com>
 * @version         $Id$
 */

// define it as you like :-)

define('PROTECTOR_BADIP_REDIRECTION_URI', 'http://yahoo.com/');

class protector_precommon_badip_redirection extends ProtectorFilterAbstract
{
    function execute()
    {
        header('Location: ' . PROTECTOR_BADIP_REDIRECTION_URI);
        exit;
    }
}