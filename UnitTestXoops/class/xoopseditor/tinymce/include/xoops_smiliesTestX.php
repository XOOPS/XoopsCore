<?php
/**
 *  TinyMCE adapter for XOOPS
 *
 * @copyright       The XOOPS Project http://sourceforge.net/projects/xoops/
 * @license         http://www.fsf.org/copyleft/gpl.html GNU public license
 * @package         class
 * @subpackage      editor
 * @since           2.6.0
 * @author          Laurent JEN (aka DuGris)
 * @version         $Id$
 */

if (!defined("XOOPS_ROOT_PATH")) { die("XOOPS root path not defined"); }

/*
$xoops = Xoops::getInstance();
if ($xoops->isActiveModule('smilies')) {*/
    return true;
/*
}
*/
return false;
?>