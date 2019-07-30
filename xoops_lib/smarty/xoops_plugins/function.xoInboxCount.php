<?php

use Xoops\Core\Kernel\Criteria;
use Xoops\Core\Kernel\CriteriaCompo;

/**
 * xoInboxCount lets templates access private message inbox statistics for the current user
 *
 * Example: {xoInboxCount assign='unread_count' total='inbox_total'}
 *
 * Both assign and total parameters are optional. If neither is specified the unread count is displayed.
 * - assign = variable name to assign with the current unread message count
 * - total  = variable name to assign with the current inbox total
 *
 * @param array                    $params parameters
 * @param Smarty_Internal_Template $smarty template
 * @return null
 */
function smarty_function_xoInboxCount($params, Smarty_Internal_Template $smarty)
{
    $assign = 'assign';
    $total = 'total';
    $inbox_count = 'xoops_inbox_count';
    $inbox_total = 'xoops_inbox_total';
    $inbox_expire = 'xoops_inbox_count_expire';

    $xoops = Xoops::getInstance();
    $session = $xoops->session();

    if (!$xoops->isUser()) {
        return;
    }
    $time = time();
    if ($session->has($inbox_count)
        && $session->has($inbox_expire)
        && $session->get($inbox_expire) > $time
    ) {
        $totals[$assign] = (int)$session->get($inbox_count);
        $totals[$total] = (int)$session->get($inbox_total);
    } else {
        $pmHandler = $xoops->getHandlerPrivateMessage();
        $eventArgs = [$pmHandler];

        $xoops->events()->triggerEvent('core.class.smarty.xoops_plugins.xoinboxcount', $eventArgs);
        $pmHandler = $eventArgs[0];

        $criteria = new CriteriaCompo(new Criteria('to_userid', $xoops->user->getVar('uid')));
        $totals[$total] = $pmHandler->getCount($criteria);

        $criteria->add(new Criteria('read_msg', 0));
        $totals[$assign] = $pmHandler->getCount($criteria);

        $session->set($inbox_count, $totals[$assign]);
        $session->set($inbox_total, $totals[$total]);
        $session->set($inbox_expire, $time + 60);
    }

    $printCount = true;
    foreach ($totals as $key => $count) {
        if (!empty($params[$key])) {
            $smarty->assign($params[$key], $count);
            $printCount = false;
        }
    }
    if ($printCount) {
        echo $totals['assign'];
    }
}
