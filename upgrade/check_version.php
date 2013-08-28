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
 * Upgrader check version file
 *
 * See the enclosed file license.txt for licensing information.
 * If you did not receive this file, get it at http://www.fsf.org/copyleft/gpl.html
 *
 * @copyright   The XOOPS project http://www.xoops.org/
 * @license     http://www.fsf.org/copyleft/gpl.html GNU General Public License (GPL)
 * @package     upgrader
 * @since       2.0.13
 * @author      Skalpa Keo <skalpa@xoops.org>
 * @author      Taiwen Jiang <phppp@users.sourceforge.net>
 * @version     $Id$
 */

defined( 'XOOPS_ROOT_PATH' ) or die();

$dirs = getDirList( "." );

$results = array();
$files = array();
$needUpgrade = false;

$_SESSION['xoops_upgrade'] = array();

foreach ($dirs as $dir) {
    if (strpos( $dir, "-to-")) {
        $upgrader = include_once "{$dir}/index.php";
        if (is_object($upgrader)) {
            if (!( $results[$dir] = $upgrader->isApplied())) {
                $_SESSION['xoops_upgrade']['steps'][] = $dir;
                $needUpgrade = true;
                if (!empty( $upgrader->usedFiles)) {
                    $files = array_merge( $files, $upgrader->usedFiles );
                }
            }
        }
    }
}

if ($needUpgrade && !empty($files)) {
    foreach ($files as $k => $file) {
        if (is_writable("../{$file}")) {
            unset($files[$k]);
        }
    }
}
?>
<h2><?php echo _CHECKING_APPLIED; ?></h2>

<table id="check_results">
<?php foreach ($results as $upd => $res) { ?>
    <tr>
        <td><?php echo $upd; ?></td>
        <td class="result-<?php echo $res?'y':'x'; ?>"><?php echo $res?'y':'x'; ?></td>
    </tr>
<?php } ?>
</table>
<?php
    if (!$needUpgrade) {
        $update_link = '<a href="' . XOOPS_URL . '/modules/system/admin.php?fct=modulesadmin&amp;op=update&amp;module=system">' . _UPDATE_SYSTEM_MODULE . '</a>';
        echo '<div class="x2-note">' . sprintf(_NO_NEED_UPGRADE, $update_link) . "</div>";
        return;
    } else {
        if (!empty($files)) {
            echo '<div class="x2-note"><p>' . _NEED_UPGRADE . "<br />" . _SET_FILES_WRITABLE . "</p><ul>";
            foreach ($files as $file) echo "<li>{$file}</li>\n";
            echo "</ul></div>";
            echo '<a id="link-next" href="index.php">' . _RELOAD . '</a>';
        } else {
            echo '<a id="link-next" href="index.php?action=next">' . _PROCEED_UPGRADE . '</a>';
        }
    }
?>