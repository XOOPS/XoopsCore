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
 * Database character set configuration page
 *
 * See the enclosed file license.txt for licensing information.
 * If you did not receive this file, get it at http://www.fsf.org/copyleft/gpl.html
 *
 * @copyright   The XOOPS project http://www.xoops.org/
 * @license     http://www.fsf.org/copyleft/gpl.html GNU General Public License (GPL)
 * @package     upgrader
 * @since       2.3.0
 * @author      Skalpa Keo <skalpa@xoops.org>
 * @author      Taiwen Jiang <phppp@users.sourceforge.net>
 * @version     $Id$
 */

if (!defined('XOOPS_ROOT_PATH')) {
    die('Bad installation: please add this folder to the XOOPS install you want to upgrade');
}

$vars = $_SESSION['settings'];

function getDbCharsets()
{
    $xoops = Xoops::getInstance();
    $db = $xoops->db();
    $charsets = array();

    $charsets["utf8"] = array();
    $ut8_available = false;
    if ($result = $db->queryF("SHOW CHARSET")) {
        while ($row = $db->fetchArray($result)) {
            $charsets[$row["Charset"]]["desc"] = $row["Description"];
            if ($row["Charset"] == "utf8") {
                $ut8_available = true;
            }
        }
    }
    if (!$ut8_available) {
        unset($charsets["utf8"]);
    }

    return $charsets;
}

function getDbCollations()
{
    $xoops = Xoops::getInstance();
    $db = $xoops->db();
    $collations = array();
    $charsets = getDbCharsets();

    if ($result = $db->queryF("SHOW COLLATION")) {
        while ($row = $db->fetchArray($result)) {
            $charsets[$row["Charset"]]["collation"][] = $row["Collation"];
        }
    }

    return $charsets;
}

function xoFormFieldCollation($name, $value, $label, $help = '')
{
    $collations = getDbCollations();

    $myts = MyTextSanitizer::getInstance();
    $label = $myts->htmlspecialchars($label, ENT_QUOTES, _UPGRADE_CHARSET, false);
    $name = $myts->htmlspecialchars($name, ENT_QUOTES, _UPGRADE_CHARSET, false);
    $value = $myts->htmlspecialchars($value, ENT_QUOTES);

    $field = "<label for='$name'>$label</label>\n";
    if ($help) {
        $field .= '<div class="xoform-help">' . $help . "</div>\n";
    }
    $field .= "<select name='$name' id='$name'\">";
    $field .= "<option value=''>" . DB_COLLATION_NOCHANGE . "</option>";

    $collation_default = "";
    $options = "";
    foreach ($collations as $key => $charset) {
        $field .= "<optgroup label='{$key} - ({$charset['desc']})'>";
        foreach ($charset['collation'] as $collation) {
            $field .= "<option value='{$collation}'" . (($value == $collation) ? " selected='selected'" : "") . ">{$collation}</option>";
        }
        $field .= "</optgroup>";
    }
    $field .= "</select>";

    return $field;
}

if ($_SERVER['REQUEST_METHOD'] == 'POST' && @$_POST['task'] == 'db') {
    $params = array( 'DB_COLLATION' );
    foreach ($params as $name) {
        $vars[$name] = isset($_POST[$name]) ? $_POST[$name] : "";
    }
    return $vars;
}

if (!isset($vars['DB_COLLATION'])) {
    $vars['DB_COLLATION'] = '';
}


?>
<?php if (!empty($error)) {
    echo '<div class="x2-note error">' . $error . "</div>\n";
} ?>

<form action="<?php echo $_SERVER['PHP_SELF']; ?>" method='post'>
<fieldset>
    <legend><?php echo LEGEND_DATABASE; ?></legend>
    <?php echo xoFormFieldCollation('DB_COLLATION', $vars['DB_COLLATION'], DB_COLLATION_LABEL, DB_COLLATION_HELP); ?>

</fieldset>
<input type="hidden" name="action" value="next" />
<input type="hidden" name="task" value="db" />

<div class="xo-formbuttons">
    <button type="submit"><?php echo XoopsLocale::A_SUBMIT; ?></button>
</div>