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
 * @license     GNU GPL 2 or later (http://www.gnu.org/licenses/gpl-2.0.html)
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
// get list of parameters the selected drive accepts
$driver_info = $wizard->configs['db_types'][$settings['DB_DRIVER']];
$driver_params=explode(',', $driver_info['params']);
$settings['DB_TYPE'] = $driver_info['type'];

// get settings name and value (post, session or default) for each parameter
foreach ($driver_params as $param) {
    $name=false;
    if (!empty($wizard->configs['db_param_names'][$param])) {
        $name=$wizard->configs['db_param_names'][$param];
        $default = null;
        switch ($param) {
            case 'host':
                $default = empty($settings[$name]) ? 'localhost' : $settings[$name];
                break;
            case 'user':
                $default = empty($settings[$name]) ? '' : $settings[$name];
                break;
            case 'password':
                $default = empty($settings[$name]) ? '' : $settings[$name];
                break;
            case 'port':
                $default = empty($settings[$name]) ? '' : $settings[$name];
                break;
            case 'unix_socket':
                $default = empty($settings[$name]) ? '' : $settings[$name];
                break;
            case 'path':
                $default = empty($settings[$name]) ? '' : $settings[$name];
                break;
            case 'service':
                $default = empty($settings[$name]) ? false : $settings[$name];
                break;
            case 'pooled':
                $default = empty($settings[$name]) ? false : $settings[$name];
                break;
            case 'protocol':
                $default = empty($settings[$name]) ? '' : $settings[$name];
                break;
            case 'dbname':
                $default = empty($settings[$name]) ? '' : $settings[$name];
                break;
        }
        $value = $default;
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $value = empty($_POST[$name]) ? $default : $_POST[$name];
        }
        $settings[$name]=$value;
    }
}

$_SESSION['settings'] = $settings;

// if a POST, try to connect to the database using the parameters
$error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $instance = getDbConnection($error);
    if ($instance && empty($error)) {
        $_SESSION['settings'] = $settings;
        $wizard->redirectToPage('+1');
        exit();
    }
}

ob_start();
?>
<?php if (!empty($error)) {
    echo '<div class="x2-note errorMsg">' . $error . "</div>\n";
} ?>
<fieldset>
    <legend><?php echo LEGEND_CONNECTION; ?>
        <?php echo $wizard->configs['db_types'][$settings['DB_DRIVER']]['desc']; ?>
    </legend>
<?php
foreach ($driver_params as $param) {
    $name = $wizard->configs['db_param_names'][$param];
    if ($wizard->configs['db_param_types'][$param]==='string') {
        echo xoFormField(
            $name,
            $settings[$name],
            constant($name . '_LABEL'),
            constant($name . '_HELP')
        );
    } elseif ($wizard->configs['db_param_types'][$param]==='boolean') {
        echo xoBoolField(
            $name,
            $settings[$name],
            constant($name . '_LABEL'),
            constant($name . '_HELP')
        );
    } elseif ($wizard->configs['db_param_types'][$param]==='password') {
        echo xoPassField(
            $name,
            $settings[$name],
            constant($name . '_LABEL'),
            constant($name . '_HELP')
        );
    }
}
?>
</fieldset>

<?php
/*
    <label class="xolabel" for="DB_PCONNECT" class="center">
        <?php echo DB_PCONNECT_LABEL; ?>
        <input class="checkbox" type="checkbox" name="DB_PCONNECT"
               value="1" <?php echo $settings['DB_PCONNECT'] ? "'checked'" : ""; ?>/>

        <div class="xoform-help"><?php echo DB_PCONNECT_HELP; ?></div>
    </label>
*/
$content = ob_get_contents();
ob_end_clean();

$_SESSION['pageHasHelp'] = true;
$_SESSION['pageHasForm'] = true;
$_SESSION['content'] = $content;
$_SESSION['settings'] = $settings;
include XOOPS_INSTALL_PATH . '/include/install_tpl.php';
