<?php
/*
 You may not change or alter any portion of this comment or credits
 of supporting developers from this source code or any supporting source code
 which is considered copyrighted (c) material of the original comment or credit authors.

 This program is distributed in the hope that it will be useful,
 but WITHOUT ANY WARRANTY; without even the implied warranty of
 MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
 */

use Endroid\QrCode\ErrorCorrectionLevel;
use Endroid\QrCode\QrCode;
use Xmf\Request;

/**
 * @author    Richard Griffith <richard@geekwright.com>
 * @copyright 2000-2020 XOOPS Project (https://xoops.org)
 * @license   GNU GPL 2 or later (http://www.gnu.org/licenses/gpl-2.0.html)
 * @link      https://xoops.org
 */

// this is located in include, otherwise normal/anon users do not have authority to run
include dirname(dirname(dirname(__DIR__))) . '/mainfile.php';

$xoops = Xoops::getInstance();
$xoops->logger()->quiet();

$text = Request::getString('text', 'error');

$configs = $xoops->getModuleConfigs('qrcode');

$qrCode = new QrCode($text);

$ecChar = $configs['qrcode_ecl'];
switch (mb_strtoupper($ecChar)) {
    case 'H':
        $ec = new ErrorCorrectionLevel(ErrorCorrectionLevel::HIGH);
        break;
    case 'Q':
        $ec = new ErrorCorrectionLevel(ErrorCorrectionLevel::QUARTILE);
        break;
    case 'M':
        $ec = new ErrorCorrectionLevel(ErrorCorrectionLevel::MEDIUM);
        break;
    case 'L':
    default:
    $ec = new ErrorCorrectionLevel(ErrorCorrectionLevel::LOW);
        break;
}
$qrCode->setWriterByName('png');
$qrCode->setMargin($configs['qrcode_margin']);
$qrCode->setEncoding('UTF-8');

$qrCode->setErrorCorrectionLevel($ec);
$qrCode->setSize((int)($configs['qrcode_mps']));

$qrCode->setBackgroundColor(normalizeColor($configs['qrcode_bgcolor']));
$qrCode->setForegroundColor(normalizeColor($configs['qrcode_fgcolor']));

try {
    $qrData = $qrCode->writeString();
} catch (\Exception $e) {
    $xoops->events()->triggerEvent('core.exception', $e);
    $qrData = '';
}

$mimetype = \Xoops\Core\MimeTypes::findType('png');
$expires = 60 * 60 * 24 * 15; // seconds, minutes, hours, days
header('Cache-Control: public, max-age=' . $expires);
header('Expires: ' . gmdate('D, d M Y H:i:s', time() + $expires) . ' GMT');
//header('Last-Modified: ' . gmdate('D, d M Y H:i:s T', $mtime));
header('Content-type: ' . $mimetype);
echo $qrData;
exit;

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
    $color = mb_substr('000000' . $color, -6); // only 6 digits, pad with leading zeros
    $rgb = [
        'r' => hexdec(mb_substr($color, 0, 2)),
        'g' => hexdec(mb_substr($color, 2, 2)),
        'b' => hexdec(mb_substr($color, 4, 2)),
        'a' => 0,
    ];

    return $rgb;
}
