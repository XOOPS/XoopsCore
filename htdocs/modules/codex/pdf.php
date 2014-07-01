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
 * @copyright       The XOOPS Project http://sourceforge.net/projects/xoops/
 * @license         http://www.fsf.org/copyleft/gpl.html GNU public license
 * @author          trabis <lusopoemas@gmail.com>
 * @version         $Id$
 */

include dirname(dirname(__DIR__)) . DIRECTORY_SEPARATOR . 'mainfile.php';

$xoops = Xoops::getInstance();

if (isset($_GET['pdf'])) {
    $content = Xoops_Utils::dumpVar($xoops->getConfigs(), false);

    $tpl = new XoopsTpl();
    $tpl->assign('dummy_content' , $content);
    $content2 = $tpl->fetch('module:system|system_dummy.html');

    if ($xoops->isActiveModule('pdf')) {
        $pdf = new Pdf();
        $pdf->writeHtml($content2, false);
        $pdf->Output('example.pdf');
    } else {
        $xoops->header();
        echo 'Oops, Please install pdf module!';
        Xoops_Utils::dumpFile(__FILE__ );
        $xoops->footer();
    }
} else {
    $xoops->header();
    echo '<a href="?pdf">Make Pdf</a>';
    Xoops_Utils::dumpFile(__FILE__ );
    $xoops->footer();
}

