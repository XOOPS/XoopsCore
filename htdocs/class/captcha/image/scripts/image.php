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
 * CAPTCHA class For XOOPS
 *
 * PHP 5.3
 *
 * @category  Xoops\Class\Captcha\CaptchaImage
 * @package   CaptchaImage
 * @author    Taiwen Jiang <phppp@users.sourceforge.net>
 * @copyright 2013 XOOPS Project (http://xoops.org)
 * @license   GNU GPL 2 or later (http://www.gnu.org/licenses/gpl-2.0.html)
 * @version   $Id$
 * @link      http://xoops.org
 * @since     2.6.0
 */

require dirname(dirname(dirname(dirname(__DIR__)))) . '/mainfile.php';

require_once 'imageclass.php';

Xoops::getInstance()->disableErrorReporting();
$image_handler = new XoopsCaptchaImageHandler();
$image_handler->loadImage();
