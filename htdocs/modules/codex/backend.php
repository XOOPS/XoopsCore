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
 * @author          Laurent JEN (aka DuGris)
 * @version         $Id$
 */

include dirname(dirname(dirname(__FILE__))) . DIRECTORY_SEPARATOR . 'mainfile.php';

$xoops = Xoops::getInstance();
$xoops->header();
echo "
<strong>backend for your module</strong><br /><br />
Since 2.6.0, the backend functionality use a 'Plugin' interface.<br />
The new class \\Xoops\\Module\\Plugin is the class that makes using plugins simple and effective!<br />
<br />
<ul>
    <li>Copy the file <strong><i>root_path/backend.php</i></strong> in your module folder</li>
    <li>Copy the file <strong><i>root_path/modules/system/templates/system_rss.html</i></strong> in your module templates folder</li>
    <li>Create backend plugin <i>see the <a href='system-plugin.php' title='System plugin sample'><strong>System plugin sample</strong></a></li>
</ul>
";

Xoops_Utils::dumpFile(dirname(__FILE__) . '/class/plugin/system.php');
$xoops->footer();
