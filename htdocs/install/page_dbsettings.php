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

require_once dirname(__FILE__) . '/include/common.inc.php';

/* @var $wizard XoopsInstallWizard */
$wizard = $_SESSION['wizard'];

$settings = $_SESSION['settings'];

$func_connect = empty($settings['DB_PCONNECT']) ? "mysql_connect" : "mysql_pconnect";
if (!($link = @$func_connect($settings['DB_HOST'], $settings['DB_USER'], $settings['DB_PASS'], true))) {
    $error = ERR_NO_DBCONNECTION;
    $wizard->redirectToPage('-1', $error);
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'GET' && isset($_GET['charset']) && @$_GET['action'] == 'updateCollation') {
    echo xoFormFieldCollation('DB_COLLATION', $settings['DB_COLLATION'], DB_COLLATION_LABEL, DB_COLLATION_HELP, $link, $_GET['charset']);
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $params = array('DB_NAME', 'DB_CHARSET', 'DB_COLLATION', 'DB_PREFIX');
    foreach ($params as $name) {
        $settings[$name] = isset($_POST[$name]) ? $_POST[$name] : "";
    }
}

$error = '';
if ($_SERVER['REQUEST_METHOD'] == 'POST' && !empty($settings['DB_NAME'])) {
    $error = validateDbCharset($link, $settings['DB_CHARSET'], $settings['DB_COLLATION']);
    $db_exist = true;
    if (empty($error)) {
        if (!@mysql_select_db($settings['DB_NAME'], $link)) {
            // Database not here: try to create it
            $result = mysql_query("CREATE DATABASE `" . $settings['DB_NAME'] . '`');
            if (!$result) {
                $error = ERR_NO_DATABASE;
                $db_exist = false;
            }
        }
        if ($db_exist && $settings['DB_CHARSET']) {
            $sql = "ALTER DATABASE `" . $settings['DB_NAME'] . "` DEFAULT CHARACTER SET " . mysql_real_escape_string($settings['DB_CHARSET']) . ($settings['DB_COLLATION']
                    ? " COLLATE " . mysql_real_escape_string($settings['DB_COLLATION']) : "");
            if (!mysql_query($sql)) {
                $error = ERR_CHARSET_NOT_SET . $sql;
            }
        }
    }
    if (empty($error)) {
        $_SESSION['settings'] = $settings;
        $wizard->redirectToPage('+1');
        exit();
    }
}

if (@empty($settings['DB_NAME'])) {
    // Fill with default values
    $settings = array_merge($settings, array(
                                    'DB_NAME' => 'xoops', 'DB_CHARSET' => 'utf8', 'DB_COLLATION' => '',
                                    'DB_PREFIX' => 'x' . substr(md5(time()), 0, 3),
                               ));
}

ob_start();
?>
<?php if (!empty($error)) {
    echo '<div class="x2-note errorMsg">' . $error . "</div>\n";
} ?>

<script type="text/javascript">
    function setFormFieldCollation(id, val)
    {
        var display = (val == '') ? 'none' : '';
        $(id).style.display = display;
        new Ajax.Updater(
                id, '<?php echo $_SERVER['PHP_SELF']; ?>',
                { method:'get',parameters:'action=updateCollation&charset=' + val }
        );
    }
</script>

<fieldset>
    <legend><?php echo LEGEND_DATABASE; ?></legend>
    <?php echo xoFormField('DB_NAME', $settings['DB_NAME'], DB_NAME_LABEL, DB_NAME_HELP); ?>
    <?php echo xoFormField('DB_PREFIX', $settings['DB_PREFIX'], DB_PREFIX_LABEL, DB_PREFIX_HELP); ?>
    <?php echo xoFormFieldCharset('DB_CHARSET', $settings['DB_CHARSET'], DB_CHARSET_LABEL, DB_CHARSET_HELP, $link); ?>
    <?php echo xoFormBlockCollation('DB_COLLATION', $settings['DB_COLLATION'], DB_COLLATION_LABEL, DB_COLLATION_HELP, $link, $settings['DB_CHARSET']); ?>
</fieldset>

<?php
$content = ob_get_contents();
ob_end_clean();

$_SESSION['pageHasHelp'] = true;
$_SESSION['pageHasForm'] = true;
$_SESSION['content'] = $content;
$_SESSION['settings'] = $settings;
include XOOPS_INSTALL_PATH . '/include/install_tpl.php';