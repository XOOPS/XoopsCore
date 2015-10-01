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
 * Installer configuration check page
 *
 * @copyright   XOOPS Project (http://xoops.org)
 * @license     GNU GPL 2 or later (http://www.gnu.org/licenses/gpl-2.0.html)
 * @package     installer
 * @since       2.3.0
 * @author      Haruki Setoyama  <haruki@planewave.org>
 * @author      Kazumi Ono <webmaster@myweb.ne.jp>
 * @author      Skalpa Keo <skalpa@xoops.org>
 * @author      Taiwen Jiang <phppp@users.sourceforge.net>
 * @author      DuGris (aka L. JEN) <dugris@frxoops.org>
 * @version     $Id$
**/

require_once __DIR__ . '/include/common.inc.php';

/* @var $wizard XoopsInstallWizard */
$wizard = $_SESSION['wizard'];

foreach ($wizard->configs['extensions'] as $ext => $value) {
    if (extension_loaded($ext)) {
        if (is_array($value[0])) {
            $wizard->configs['extensions'][$ext][] = xoDiag(1, implode(',', $value[0]));
        } else {
            $wizard->configs['extensions'][$ext][] = xoDiag(1, $value[0]);
        }
    } else {
        $wizard->configs['extensions'][$ext][] = xoDiag(0, NONE);
    }
}
ob_start();
?>
<div class="caption"><?php echo REQUIREMENTS; ?></div>
<table class="diags">
<tbody>
<tr>
    <th><?php echo SERVER_API; ?></th>
    <td><?php echo php_sapi_name(); ?><br />
        <?php echo $_SERVER["SERVER_SOFTWARE"]; ?></td>
</tr>

<tr>
    <th><?php echo _PHP_VERSION; ?></th>
    <td><?php echo xoPhpVersion(); ?></td>
</tr>

<tr>
    <th><?php echo COMPOSER; ?></th>
    <td><?php echo xoDiag(class_exists('\Composer\Autoload\ClassLoader') ? 1 : -1, COMPOSER_ENVIRONMENT); ?></td>
</tr>

<tr>
    <th><?php printf(PHP_EXTENSION, 'PDO'); ?></th>
    <td><?php echo xoDiag(extension_loaded('PDO') ? 1 : -1, 'PDO::getAvailableDrivers() = ' . @implode(', ', PDO::getAvailableDrivers())); ?></td>
</tr>

<tr>
    <th><?php printf(PHP_EXTENSION, 'Session'); ?></th>
    <td><?php echo xoDiag(extension_loaded('session') ? 1 : -1); ?></td>
</tr>

<tr>
    <th><?php printf(PHP_EXTENSION, 'PCRE'); ?></th>
    <td><?php echo xoDiag(extension_loaded('pcre') ? 1 : -1); ?></td>
</tr>

<tr>
    <th><?php printf(PHP_EXTENSION, 'OpenSSL'); ?></th>
    <td><?php echo xoDiag(extension_loaded('openssl') ? 1 : -1); ?></td>
</tr>

<tr>
    <th><?php printf(PHP_EXTENSION, 'JSON'); ?></th>
    <td><?php echo xoDiag(extension_loaded('json') ? 1 : -1); ?></td>
</tr>

<tr>
    <th scope="row">file_uploads</th>
    <td><?php echo xoDiagBoolSetting('file_uploads', true); ?></td>
</tr>
</tbody>
</table>

<div class="caption"><?php echo RECOMMENDED_EXTENSIONS; ?></div>
<div class='confirmMsg'><?php echo RECOMMENDED_EXTENSIONS_MSG; ?></div>
<table class="diags">
<tbody>
<?php
foreach ($wizard->configs['extensions'] as $key => $value) {
    echo "<tr><th>" . $value[1] . "</th><td>" . $value[2] . "</td></tr>";
}
?>

</tbody>
</table>
<?php
$content = ob_get_contents();
ob_end_clean();

$_SESSION['pageHasHelp'] = false;
$_SESSION['pageHasForm'] = false;
$_SESSION['content'] = $content;
include XOOPS_INSTALL_PATH . '/include/install_tpl.php';
