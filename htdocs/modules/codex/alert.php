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
 * @license         GNU GPL 2 or later (http://www.gnu.org/licenses/gpl-2.0.html)
 * @author          Mage Grégory (AKA Mage)
 * @version         $Id$
 */

include dirname(dirname(__DIR__)) . DIRECTORY_SEPARATOR . 'mainfile.php';

$xoops = Xoops::getInstance();
$xoops->header();
echo "
<strong>alert</strong><br /><br />
since 2.6.0 you can use the alert function to display alerts<br />
You have four types of alerts:<br /><br /><br />";

echo $xoops->alert('info', 'Your information message' , 'Title information');
echo $xoops->alert('warning', 'Your warning message' , 'Title warning');
echo $xoops->alert('error', array('error 1', 'error 2', '...') , 'Title error');
echo $xoops->alert('success', 'Your success message' , 'Title success');
Xoops_Utils::dumpFile(__FILE__ );
$xoops->footer();

