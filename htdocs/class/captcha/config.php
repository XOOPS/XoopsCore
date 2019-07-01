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
 * CAPTCHA configurations for All modes
 *
 * PHP 5.3
 *
 * @category  Xoops\Class\Captcha\CaptchaMethod
 * @package   CaptchaMethod
 * @author    Taiwen Jiang <phppp@users.sourceforge.net>
 * @copyright 2013 XOOPS Project (http://xoops.org)
 * @license   GNU GPL 2 or later (http://www.gnu.org/licenses/gpl-2.0.html)
 * @version   $Id$
 * @link      http://xoops.org
 * @since     2.6.0
 */

/**
 * This keeping config in files has really got to stop. If we can't actually put these into
 * the actual XOOPS config then we should do this. (Who said this? You are right!)
 */
return $config = [
    'disabled' => false, // Disable CAPTCHA
    'mode' => 'image', // default mode, you can choose 'text', 'image', 'recaptcha'(requires api key)
    'name' => 'xoopscaptcha', // captcha name
    'skipmember' => true, // Skip CAPTCHA check for members
    'maxattempts' => 10, // Maximum attempts for each session
];
