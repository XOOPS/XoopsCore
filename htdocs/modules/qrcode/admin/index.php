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
 * qrcode module
 *
 * @copyright XOOPS Project (http://xoops.org)
 * @license   GNU GPL 2 or later (http://www.gnu.org/licenses/gpl-2.0.html)
 * @package   qrcode
 * @since     2.6.0
 * @author    Mage Grégory (AKA Mage)
 */
include __DIR__ . '/header.php';

$xoops = Xoops::getInstance();

$xoops->header();

$admin_page = new \Xoops\Module\Admin();
$admin_page->displayNavigation('index.php');

$bg = $xoops->getModuleConfig('qrcode_bgcolor', 'qrcode');
$bg = getBrightness($bg);

$fg = $xoops->getModuleConfig('qrcode_fgcolor', 'qrcode');
$fg = getBrightness($fg);

$contrastMessage = _MI_QRCODE_CONTRAST_OK;
$contrastStatus = 'accept';

if ($bg < $fg) { // inverse (light cells on dark backgound) does not work on many readers
    $contrastMessage = _MI_QRCODE_CONTRAST_INVERSE;
    $contrastStatus = 'error';
} elseif (($bg-$fg) < 100) {
    $contrastMessage = _MI_QRCODE_CONTRAST_ERROR;
    $contrastStatus = 'error';
}
$admin_page->addConfigBoxLine($contrastMessage, $contrastStatus);
$admin_page->displayIndex();

$xoops->footer();

/**
 * getBrightness get brightness of a color
 *
 * @param string $color 24 bit RGB color as hex digit (i.e. 'FFFFFF')
 *
 * @return int relative brightness of color 1-255001
 */
function getBrightness($color)
{
    $rgb = normalizeColor($color);
    //$brightness = ($rgb['r']*299 + $rgb['g']*587 + $rgb['b']*114) / 1000;
    // luminosity is L = 0.2126 * R + 0.7152 * G + 0.0722.
    $brightness = ($rgb['r']*0.2126 + $rgb['g']*0.7152 + $rgb['b']*0.0722);
    return $brightness + 0.00001; // no zero
}

/**
 * normalizeColor
 *
 * @param string $color 24 bit RGB color as hex digit (i.e. 'FFFFFF')
 *
 * @return array of ints, RGB color values keyed as 'red', 'green' and 'blue'
 */
function normalizeColor($color)
{
    $color = preg_replace('/[^a-fA-F0-9]+/', '', $color); // only hex digits
    $color = substr('000000'.$color, -6); // only 6 digits, pad with leading zeros
    $rgb = array(
        'r' => hexdec(substr($color, 0, 2)),
        'g' => hexdec(substr($color, 2, 2)),
        'b' => hexdec(substr($color, 4, 2)),
    );
    return $rgb;
}
