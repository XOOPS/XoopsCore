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
 * Installer mainfile creation page
 *
 * @copyright   The XOOPS project http://www.xoops.org/
 * @license     http://www.fsf.org/copyleft/gpl.html GNU General Public License (GPL)
 * @package     installer
 * @since       2.3.0
 * @author      Haruki Setoyama  <haruki@planewave.org>
 * @author      Kazumi Ono <webmaster@myweb.ne.jp>
 * @author      Skalpa Keo <skalpa@xoops.org>
 * @author      Taiwen Jiang <phppp@users.sourceforge.net>
 * @author      DuGris (aka L. JEN) <dugris@frxoops.org>
 * @version     $Id$
 */

require_once __DIR__ . '/include/common.inc.php';

/* @var $wizard XoopsInstallWizard */
$wizard = $_SESSION['wizard'];

$settings = $_SESSION['settings'];

if (empty($settings['ROOT_PATH'])) {
    $wizard->redirectToPage('pathsettings');
    exit();
} elseif (empty($settings['DB_PARAMETERS'])) {
    $wizard->redirectToPage('dbdriver');
    exit();
}
$error = '';

$rewrite = array(
    'GROUP_ADMIN' => 1, 'GROUP_USERS' => 2, 'GROUP_ANONYMOUS' => 3
);
$rewrite = array_merge($rewrite, $settings);

if (!@copy($settings['ROOT_PATH'] . '/mainfile.dist.php', $settings['ROOT_PATH'] . '/mainfile.php')) {
    $error = ERR_COPY_MAINFILE;
} else {
    clearstatcache();

    if (!$file = fopen($settings['ROOT_PATH'] . '/mainfile.php', "r")) {
        $error = ERR_READ_MAINFILE;
    } else {
        $content = fread($file, filesize($settings['ROOT_PATH'] . '/mainfile.php'));
        fclose($file);

        foreach ($rewrite as $key => $val) {
            if ($key == 'authorized') {
                continue;
            }
            if (is_int($val) && preg_match("/(define\()([\"'])(XOOPS_{$key})\\2,\s*([0-9]+)\s*\)/", $content)) {
                $content = preg_replace("/(define\()([\"'])(XOOPS_{$key})\\2,\s*([0-9]+)\s*\)/", "define('XOOPS_{$key}', {$val})", $content);
            } else {
                if (preg_match("/(define\()([\"'])(XOOPS_{$key})\\2,\s*([\"'])(.*?)\\4\s*\)/", $content)) {
                    $val = str_replace('$', '\$', addslashes($val));
                    $content = preg_replace("/(define\()([\"'])(XOOPS_{$key})\\2,\s*([\"'])(.*?)\\4\s*\)/", "define('XOOPS_{$key}', '{$val}')", $content);
                } else {
                    //$this->error = true;
                    //$this->report .= _NGIMG.sprintf( ERR_WRITING_CONSTANT, "<strong>$val</strong>")."<br />\n";
                }
            }
        }
        if (!$file = fopen($settings['ROOT_PATH'] . '/mainfile.php', "w")) {
            $error = ERR_WRITE_MAINFILE;
        } else {
            if (fwrite($file, $content) == -1) {
                $error = ERR_WRITE_MAINFILE;
            }
            fclose($file);
        }
    }
}

if (!@copy($rewrite['VAR_PATH'] . '/data/secure.dist.php', $rewrite['VAR_PATH'] . '/data/secure.php')) {
    $error = ERR_COPY_SECURE . $rewrite['VAR_PATH'] . '/data/secure.dist.php';
} else {
    clearstatcache();

    if (!$file = fopen($rewrite['VAR_PATH'] . '/data/secure.php', "r")) {
        $error = ERR_READ_SECURE;
    } else {
        $content = fread($file, filesize($rewrite['VAR_PATH'] . '/data/secure.php'));
        fclose($file);

        foreach ($rewrite as $key => $val) {
            if ($key == 'authorized') {
                continue;
            }
            if (is_int($val) && preg_match("/(define\()([\"'])(XOOPS_{$key})\\2,\s*([0-9]+)\s*\)/", $content)) {
                $content = preg_replace("/(define\()([\"'])(XOOPS_{$key})\\2,\s*([0-9]+)\s*\)/", "define('XOOPS_{$key}', {$val})", $content);
            } else {
                if (preg_match("/(define\()([\"'])(XOOPS_{$key})\\2,\s*([\"'])(.*?)\\4\s*\)/", $content)) {
                    $val = str_replace('$', '\$', addslashes($val));
                    $content = preg_replace("/(define\()([\"'])(XOOPS_{$key})\\2,\s*([\"'])(.*?)\\4\s*\)/", "define('XOOPS_{$key}', \"{$val}\")", $content);
                }
            }
        }
        if (!$file = fopen($rewrite['VAR_PATH'] . '/data/secure.php', "w")) {
            $error = ERR_WRITE_SECURE;
        } else {
            if (fwrite($file, $content) == -1) {
                $error = ERR_WRITE_SECURE;
            }
            fclose($file);
        }
    }
}

// update composer.json with our paths
$composer_path = $settings['PATH'] . '/composer.json';
$composer = json_decode(file_get_contents($composer_path));
$xoops_modules_path = $settings['ROOT_PATH'] . '/modules/';
if (isset($composer->extra) && is_object($composer->extra)) {
    $composer->extra->xoops_modules_path = $xoops_modules_path;
} else {
    $composer->extra['xoops_modules_path'] = $xoops_modules_path;
}
file_put_contents($composer_path, json_encode($composer, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES));

$settings['authorized'] = false;
if (empty($error)) {
    $_SESSION['UserLogin'] = true;
    $settings['authorized'] = true;
    $wizard->cleanCache($rewrite['VAR_PATH']);
    ob_start();
    ?>

<div class="caption"><?php echo SAVED_MAINFILE; ?></div>
<div class='x2-note confirmMsg'><?php echo SAVED_MAINFILE_MSG; ?></div>
<ul class='diags'>

    <?php
    foreach ($settings as $k => $v) {
    if ($k == 'authorized') {
        continue;
    }
    echo "<li><strong>XOOPS_{$k}</strong> " . IS_VALOR . " {$v}</li>";
}
    ?>
</ul>
    <?php
        $content = ob_get_contents();
    ob_end_clean();
} else {
    $content = '<div class="errorMsg">' . $error . '</div>';
}

$_SESSION['pageHasHelp'] = false;
$_SESSION['pageHasForm'] = false;
$_SESSION['content'] = $content;
$_SESSION['settings'] = $settings;
include XOOPS_INSTALL_PATH . '/include/install_tpl.php';
