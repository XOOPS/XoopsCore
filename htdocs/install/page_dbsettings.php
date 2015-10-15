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

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $params = array('DB_NAME');
    foreach ($params as $name) {
        $settings[$name] = isset($_POST[$name]) ? $_POST[$name] : "";
    }
    $settings['DB_PARAMETERS'] = serialize(getDbConnectionParams());
    $_SESSION['settings'] = $settings;
}

$platform=false;
$error = '';
$availableDatabases = array();

$tried_create = false;
$connection = null;
$connection = getDbConnection($error);
// if we specified the dbname and failed, try again without it
// we will try and create it later
if (!$connection && !empty($settings['DB_NAME'])) {
    $hold_name=$settings['DB_NAME'];
    unset($settings['DB_NAME']);
    $_SESSION['settings'] = $settings;
    $hold_error = $error;
    $error='';
    $connection = getDbConnection($error);
    $settings['DB_NAME'] = $hold_name;
    $_SESSION['settings'] = $settings;
    if ($connection) {
        // we have a database name and did not connect
        if (!empty($settings['DB_NAME']) && !$tried_create) {
            $platform = $connection->getDatabasePlatform();
            $canCreate = $platform->supportsCreateDropDatabase();
            if ($canCreate) {
                $tried_create = true;
                try {
                    $sql = $platform->getCreateDatabaseSQL($connection->quoteIdentifier($settings['DB_NAME']));
                    $result = $connection->exec($sql);
                    if ($result) {
                        if ('mysql' === $platform->getName()) {
                            $sql = sprintf(
                                'ALTER DATABASE %s CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;',
                                $connection->quoteIdentifier($settings['DB_NAME'])
                            );
                            $connection->exec($sql);
                        }
                        // try to reconnect with the database specified
                        $connection = null;
                        $connection = getDbConnection($error);
                    } else {
                        $error = ERR_NO_DATABASE;
                    }
                } catch (Exception $e) {
                    $error = $e->getMessage();
                }
            } else {
                $error = ERR_NO_CREATEDB;
            }
        }
    } else { // keep original error
        $error = $hold_error;
    }
}

// leave if we are already connected to a database from earlier input
if ($connection && empty($error)) {
    $currentDb = $connection->getDatabase();
    if (!empty($currentDb)) {
        $settings['DB_PARAMETERS'] = serialize(getDbConnectionParams());
        $_SESSION['settings'] = $settings;
        $wizard->redirectToPage('+1');
        exit();
    }
}

if ($connection) {
    $platform = $connection->getDatabasePlatform();
    try {
        $sql = $platform->getListDatabasesSQL();
        $dbResults = $connection->fetchAll($sql);
    } catch (Exception $e) {
        $dbResults = false;
    }

    $dbIgnored = $wizard->configs['db_types'][$settings['DB_DRIVER']]['ignoredb'];
    if ($dbResults) {
        foreach ($dbResults as $dbrow) {
            if (is_array($dbrow)) {
                $dbase = reset($dbrow); // get first value in array
            } else {
                $dbase = $dbrow;
            }
            if (!in_array($dbase, $dbIgnored)) {
                $availableDatabases[] = $dbase;
            }
        }
    }
}

if (is_array($availableDatabases) && count($availableDatabases)==1) {
    if (empty($settings['DB_NAME'])) {
        $settings['DB_NAME'] = $availableDatabases[0];
    }
}

$_SESSION['settings'] = $settings;

ob_start();
?>
<?php
if (!empty($error)) {
    echo '<div class="x2-note errorMsg">' . $error . "</div>\n";
}
?>
<script type="text/javascript">
function updateDbName(){
var e = document.getElementById("DB_AVAILABLE");
var dbSelected = e.options[e.selectedIndex].text;

document.getElementById("DB_NAME").value=dbSelected;
}
</script>
<fieldset>
<?php
if (!empty($availableDatabases)) {
    echo '<legend>' . LEGEND_DATABASE . '</legend>';
    echo '<div class="xoform-help">' . DB_AVAILABLE_HELP . '</div>';
    echo '<label class="xolabel" for="DB_DATABASE_LABEL" class="center">';
    echo DB_AVAILABLE_LABEL;
    echo ' <select size="1" name="DB_AVAILABLE" id="DB_AVAILABLE" onchange="updateDbName();">';
    $selected = ($settings['DB_NAME'] == '') ? 'selected' : '';
    echo '<option value="" {$selected}>-----------</option>';
    foreach ($availableDatabases as $dbase) {
        $selected = ($settings['DB_NAME'] == $dbase) ? 'selected' : '';
        echo "<option value=\"{$dbase}\" {$selected}>{$dbase}</option>";
    }
}
?>
        </select>
    </label>

    <?php echo xoFormField('DB_NAME', $settings['DB_NAME'], DB_NAME_LABEL, DB_NAME_HELP); ?>
</fieldset>

<?php
$content = ob_get_contents();
ob_end_clean();

$_SESSION['pageHasHelp'] = true;
$_SESSION['pageHasForm'] = true;
$_SESSION['content'] = $content;
$_SESSION['settings'] = $settings;
include XOOPS_INSTALL_PATH . '/include/install_tpl.php';
