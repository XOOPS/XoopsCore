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
 * XOOPS PDA for news
 *
 * @copyright       The XOOPS Project http://sourceforge.net/projects/xoops/
 * @license         GNU GPL 2 or later (http://www.gnu.org/licenses/gpl-2.0.html)
 * @package         core
 * @since           2.0.0
 * @version         $Id$
 */

include __DIR__ . DIRECTORY_SEPARATOR . 'mainfile.php';
$xoops_url = \XoopsBaseConfig::get('url');

header("Content-Type: text/html");
echo "<html><head><title>" . htmlspecialchars($xoops->getConfig('sitename')) . "</title>
      <meta name='HandheldFriendly' content='True' />
      <meta name='PalmComputingPlatform' content='True' />
      </head>
      <body>";

$sql = "SELECT storyid, title FROM " . $xoopsDB->prefix("stories") . " WHERE published>0 AND published<" . time() . " ORDER BY published DESC";
$result = $xoopsDB->query($sql, 10, 0);
//TODO Remove this hardcoded string
if (!$result) {
    echo "An error occured";
} else {
    echo "<img src='images/logo.gif' alt='" . htmlspecialchars($xoops->getConfig('sitename'), ENT_QUOTES) . "' border='0' /><br />";
    echo "<h2>" . htmlspecialchars($xoops->getConfig('slogan')) . "</h2>";
    echo "<div>";
    while (list ($storyid, $title) = $xoopsDB->fetchRow($result)) {
        echo "<a href='" . $xoops_url . "/modules/news/print.php?storyid={$storyid}'>" . htmlspecialchars($title) . "</a><br />";
    }
    echo "</div>";
}
echo "</body></html>";
