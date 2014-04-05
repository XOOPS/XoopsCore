<?php
/** *  TinyMCE adapter for XOOPS * * @copyright       The XOOPS Project http://sourceforge.net/projects/xoops/ * @license         http://www.fsf.org/copyleft/gpl.html GNU public license * @package         class * @subpackage      editor * @since           2.3.0 * @author          Laurent JEN <dugris@frxoops.org> * @version         $Id: xoopsmlcontent.php 8066 2011-11-06 05:09:33Z beckmi $ */
if (!defined("XOOPS_ROOT_PATH")) { die("XOOPS root path not defined"); }
// Xlanguageif ( $GLOBALS["module_handler"]->getByDirname("xlanguage") && defined("XLANGUAGE_LANG_TAG") ) {    return true;}
// Easiest Multi-Language Hack (EMLH)if ( defined('EASIESTML_LANGS') && defined('EASIESTML_LANGNAMES') ) {    return true;}return false;
?>