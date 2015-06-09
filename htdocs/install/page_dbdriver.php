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
 * Installer database configuration page
 *
 * @copyright   XOOPS Project (http://xoops.org)
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

// clear any old list
if (array_key_exists('DB_PARAMETERS', $settings)) {
    unset($settings['DB_PARAMETERS']);
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $params = array('DB_DRIVER', 'DB_PREFIX');
    foreach ($params as $name) {
        $settings[$name] = $_POST[$name];
    }
    //$settings['DB_PCONNECT'] = @$_POST['DB_PCONNECT'] ? 1 : 0;
}

$error = '';
if ($_SERVER['REQUEST_METHOD'] == 'POST' && !empty($settings['DB_DRIVER'])) {
    $_SESSION['settings'] = $settings;
    $wizard->redirectToPage('+1');
    exit();
}

if (@empty($settings['DB_DRIVER'])) {
    // Fill with default values
    $settings = array_merge(
        $settings,
        array(
            'DB_DRIVER' => 'pdo_mysql',
            'DB_PREFIX' => 'x' . substr(md5(time()), 0, 3),
        )
    );
}
ob_start();
?>
<?php
if (!empty($error)) {
    echo '<div class="x2-note errorMsg">' . $error . "</div>\n";
}
?>
<fieldset>
    <div class="xoform-help"><?php echo DB_DRIVER_HELP; ?></div>
    <legend><?php echo LEGEND_DRIVER; ?></legend>
    <label class="xolabel" for="DB_DATABASE_LABEL" class="center">
        <?php echo DB_DRIVER_LABEL; ?>
        <select size="1" name="DB_DRIVER">
            <?php
            foreach ($wizard->configs['db_types'] as $db_driver => $db_info) {
                $selected = ($settings['DB_DRIVER'] == $db_driver) ? 'selected' : '';
                echo "<option value=\"{$db_driver}\" {$selected}>{$db_info['desc']}</option>";
            }
            ?>
        </select>
    </label>
    <?php echo xoFormField('DB_PREFIX', $settings['DB_PREFIX'], DB_PREFIX_LABEL, DB_PREFIX_HELP); ?>
</fieldset>

<?php
$content = ob_get_contents();
ob_end_clean();

$_SESSION['pageHasHelp'] = true;
$_SESSION['pageHasForm'] = true;
$_SESSION['content'] = $content;
$_SESSION['settings'] = $settings;
include XOOPS_INSTALL_PATH . '/include/install_tpl.php';
