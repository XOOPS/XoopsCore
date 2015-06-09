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

$xoopsOption['checkadmin'] = true;
$xoopsOption['hascommon'] = true;

require_once __DIR__ . '/include/common.inc.php';

$xoops = Xoops::getInstance();

/* @var $wizard XoopsInstallWizard */
$wizard = $_SESSION['wizard'];
$config_handler = $xoops->getHandlerConfig();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (array_key_exists('conf_ids', $_REQUEST)) {
        foreach ($_REQUEST['conf_ids'] as $key => $conf_id) {
            $config =& $config_handler->getConfig($conf_id);
            $new_value = $_REQUEST[$config->getVar('conf_name')];
            $config->setConfValueForInput($new_value);
            $config_handler->insertConfig($config);
        }
    }

    $member_handler = $xoops->getHandlerMember();
    $member_handler->updateUsersByField('theme', $new_value);

    $wizard->redirectToPage('+1');
}

$xoops->loadLocale('system');

$criteria = new CriteriaCompo();
$criteria->add(new Criteria('conf_modid', 1));
$criteria->add(new Criteria('conf_name', 'theme_set'));

$tempvar = $config_handler->getConfigs($criteria);
$config = array_pop($tempvar);

include XOOPS_INSTALL_PATH . '/include/createconfigform.php';
$wizard->form = createThemeform($config);
$content = $wizard->CreateForm();

$_SESSION['pageHasHelp'] = false;
$_SESSION['pageHasForm'] = false;
$_SESSION['content'] = $content;
include XOOPS_INSTALL_PATH . '/include/install_tpl.php';
