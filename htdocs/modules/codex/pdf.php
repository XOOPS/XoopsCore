<?php
/*
 You may not change or alter any portion of this comment or credits
 of supporting developers from this source code or any supporting source code
 which is considered copyrighted (c) material of the original comment or credit authors.

 This program is distributed in the hope that it will be useful,
 but WITHOUT ANY WARRANTY; without even the implied warranty of
 MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
 */

use Xmf\Request;
use Xoops\Core\XoopsTpl;

/**
 * @copyright 2012-2016 XOOPS Project (http://xoops.org)
 * @license   GNU GPL 2 or later (http://www.gnu.org/licenses/gpl-2.0.html)
 * @author    trabis <lusopoemas@gmail.com>
 */

include dirname(dirname(__DIR__)) . '/mainfile.php';

$xoops = Xoops::getInstance();

if (Request::getBool('pdf', false)) {
    $content = \Xoops\Utils::dumpFile(__FILE__, false);

    $tpl = new XoopsTpl();
    $tpl->assign('dummy_content', $content);
    $content2 = $tpl->fetch('module:system/system_dummy.tpl');

    if ($xoops->service('htmltopdf')->isAvailable()) {
        $xoops->service('htmltopdf')->addHtml($content2);
        $xoops->service('htmltopdf')->outputPdfInline('codex_example.pdf');
    } else {
        $xoops->header();
        echo 'Please install an HtmlToPdf provider!';
        \Xoops\Utils::dumpFile(__FILE__);
        $xoops->footer();
    }
} else {
    $xoops->header();
    echo '<a href="?pdf=1">Make Pdf</a>';
    \Xoops\Utils::dumpFile(__FILE__);
    $xoops->footer();
}
