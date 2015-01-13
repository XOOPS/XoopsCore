<?php
/*
 You may not change or alter any portion of this comment or credits
 of supporting developers from this source code or any supporting source code
 which is considered copyrighted (c) material of the original comment or credit authors.

 This program is distributed in the hope that it will be useful,
 but WITHOUT ANY WARRANTY; without even the implied warranty of
 MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
*/

use Xoops\Core\Service\Manager;

/**
 * Service Provider Manager
 *
 * @copyright       The XOOPS Project http://sourceforge.net/projects/xoops/
 * @license         GNU GPL 2 (http://www.gnu.org/licenses/gpl-2.0.html)
 * @author          Andricq Nicolas (AKA MusS)
 * @author          Richard Griffith <richard@geekwright.com>
 * @package         system
 * @version         $Id$
 */

// Get main instance
$xoops = Xoops::getInstance();
$security = $xoops->security();

// Check users rights
if (!$xoops->isUser() || !$xoops->isModule() || !$xoops->user->isAdmin($xoops->module->mid())) {
    http_response_code(401);
    exit(XoopsLocale::E_NO_ACCESS_PERMISSION);
}

// any ajax requests land here
if (isset($_SERVER['HTTP_X_REQUESTED_WITH']) && $_SERVER['HTTP_X_REQUESTED_WITH'] == 'XMLHttpRequest') {
    $xoops->logger()->quiet();
    // ajax post requests should have a valid token, but we don't clear it since the
    // token is set on page load and we may need to make multiple requests from it.
    // TODO - token has an expiration time, so eventually it will stop working, so
    // we need to decide how to handle that. Right now, some js will redirect us from
    // provider sort to service select just before the token should expire.
    if (isset($_POST['token']) && $security->validateToken($_POST['token'], false)) {
        if (isset($_POST['op']) && $_POST['op']=='order') {
            if (isset($_POST['service'])) {
                $service=$_POST['service'];
                if (isset($_POST[$service]) && is_array($_POST[$service])) {
                    $service_order = array_flip($_POST[$service]);
                    $sm = Manager::getInstance();
                    $sm->saveChoice($service, $service_order);
                    exit("OK");
                }
            }
        }
        http_response_code(400);
        exit("Parameter error");
    }
    http_response_code(403);
    exit("Token error");
}

$xoops->theme()->addBaseStylesheetAssets('@jqueryuicss');
$xoops->theme()->addStylesheet('modules/system/css/admin.css');
$xoops->theme()->addBaseScriptAssets('@jqueryui', '@jgrowl', 'modules/system/js/admin.js');

$xoops->header('admin:system/system_services.tpl');

$admin_page = new \Xoops\Module\Admin();
$admin_page->addBreadcrumbLink(SystemLocale::CONTROL_PANEL, XOOPS_URL . '/admin.php', true);
$admin_page->addBreadcrumbLink(
    SystemLocale::SERVICES_MANAGER,
    $system->adminVersion('services', 'adminpath')
);
$admin_page->addBreadcrumbLink(XoopsLocale::MAIN);
$admin_page->addTips(SystemLocale::SERVICES_TIPS);
$admin_page->renderBreadcrumb();
$admin_page->renderTips();

$selected_service='';
if (isset($_GET['service'])) {
    $selected_service = strtolower(XoopsFilterInput::clean($_GET['service'], 'WORD'));
}
$xoops->tpl()->assign('selected_service', $selected_service);

$sm = Manager::getInstance();
$filter = 'coreservicelocate';
$eventList = $xoops->events()->getEvents();
$l = strlen($filter);
$filteredList = array();
foreach ($eventList as $k => $v) {
    if (strncasecmp($filter, $k, $l) == 0) {
        $filteredList[] = strtolower(substr($k, $l));
    }
}


$service_list = array();
sort($filteredList);
foreach ($filteredList as $v) {
    $service_list[] = array(
        'name' => $v,
        'display' => ucfirst($v),
        'active' => ($v==$selected_service),
        );
}
$xoops->tpl()->assign('service_list', $service_list);
if (empty($filteredList)) {
    $xoops->tpl()->assign('message', $xoops->alert('error', 'No service providers are installed.', 'No Services'));
}

if (!empty($selected_service) && in_array($selected_service, $filteredList)) {
    $providers = $xoops->service($selected_service)->getRegistered();
    $service = ucfirst($selected_service);
    $mode = reset($providers)->getMode();
    switch ($mode) {
        case Manager::MODE_EXCLUSIVE:
            $modeDesc = 'This is an <em>Exclusive</em> mode service. Only the first provider on the list will be used.';
            break;
        case Manager::MODE_CHOICE:
            $modeDesc = 'This is an <em>Choice</em> mode service. The first provider on the list will be the default.';
            break;
        case Manager::MODE_PREFERENCE:
            $modeDesc = 'User Preference';
            break;
        case Manager::MODE_MULTIPLE:
            $modeDesc = 'This is an <em>Multiple</em> mode service. '
                . 'Each provider will be called in the sequence shown.';
            break;
    }
    $xoops->tpl()->assign('message', $xoops->alert('info', $modeDesc, 'Service Mode'));

    $provider_list = array();
    foreach ($providers as $p) {
        $provider_list[] = array(
            'name' => $p->getName(),
            'description' => $p->getDescription(),
            'priority' => $p->getPriority(),
        );
    }
    $xoops->tpl()->assign('provider_list', $provider_list);
    $xoops->tpl()->assign('token', $security->createToken(901));

}

$xoops->footer();
