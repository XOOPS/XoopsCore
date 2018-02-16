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
 * @copyright       XOOPS Project (http://xoops.org)
 * @license         GNU GPL 2 or later (http://www.gnu.org/licenses/gpl-2.0.html)
 * @author          Laurent JEN - aka DuGris
 */

define('_MI_QRCODE_NAME', 'QRCode');
define('_MI_QRCODE_DSC', 'Support for QRCode creation');

// Preferences
define("_MI_QRCODE_ECL", "Error Correction Level");
define("_MI_QRCODE_ECLDSC", "Allows correction for damaged or obscured pixels.");
define("_MI_QRCODE_ECL_L", "Low (7%)");
define("_MI_QRCODE_ECL_M", "Medium (15%)");
define("_MI_QRCODE_ECL_Q", "Quartile (25%)");
define("_MI_QRCODE_ECL_H", "High (30%)");
define("_MI_QRCODE_MPS", "QR size");
define("_MI_QRCODE_MPSDSC", "Pixel size of QR code");
define("_MI_QRCODE_MARGIN", "Image margin");
define("_MI_QRCODE_MARGINDSC", "Quiet zone size in pixels");
define("_MI_QRCODE_BGCOLOR", "Background Color");
define("_MI_QRCODE_BGCOLORDSC", "");
define("_MI_QRCODE_FGCOLOR", "Foreground Color");
define("_MI_QRCODE_FGCOLORDSC", "");
define("_MI_QRCODE_CONTRAST_OK", "QR code color contrast OK");
define("_MI_QRCODE_CONTRAST_ERROR", "QR code color contrast may be too low to be readable");
define("_MI_QRCODE_CONTRAST_INVERSE", "QR code foreground color must be darker than background to be readable");
