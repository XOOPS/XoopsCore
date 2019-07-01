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
 * CAPTCHA configurations for Recaptcha mode
 *
 * @category  Xoops\Class\Captcha\config.recaptcha
 * @package   config.recaptcha
 * @author    trabis <lusopoemas@gmail.com>
 * @copyright 2013 XOOPS Project (http://xoops.org)
 * @license   GNU GPL 2 or later (http://www.gnu.org/licenses/gpl-2.0.html)
 * @version   $Id$
 * @link      http://xoops.org
 * @since     2.6.0
 */

return $config = [
    'private_key' => 'YourPrivateApiKey',
    'public_key' => 'YourPublicApiKey',
    'theme' => 'white', // 'red' | 'white' | 'blackglass' | 'clean' | 'custom'
    'lang' => XoopsLocale::getLangCode(),
];
