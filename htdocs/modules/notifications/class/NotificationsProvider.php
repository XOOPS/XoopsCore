<?php
/*
 You may not change or alter any portion of this comment or credits
 of supporting developers from this source code or any supporting source code
 which is considered copyrighted (c) material of the original comment or credit authors.

 This program is distributed in the hope that it will be useful,
 but WITHOUT ANY WARRANTY; without even the implied warranty of
 MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
*/

use Xoops\Core\Kernel\CriteriaElement;
use Xoops\Core\Service\AbstractContract;
use Xoops\Core\Service\Response;
use Xoops\Core\Service\Contract\NotificationsInterface;

/**
 * Notifications provider for service manager
 *
 * @category  Module
 * @package   Notifications
 * @author    Alain91 <alain091@gmail.com>
 * @copyright XOOPS Project (http://xoops.org)
 * @license   GNU GPL 2 or later (http://www.gnu.org/licenses/gpl-2.0.html)
 * @link      http://xoops.org
 * @since     2.6.0
 */
class NotificationsProvider extends AbstractContract implements NotificationsInterface
{
	protected $xoops_url;
	protected $xoops_upload_url;

    public function __construct()
    {
		$this->xoops_url = \XoopsBaseConfig::get('url');
		$this->xoops_upload_url = \XoopsBaseConfig::get('uploads-url');
    }

    /**
     * getName - get a short name for this service provider. This should be unique within the
     * scope of the named service, so using module dirname is suggested.
     *
     * @return string - a unique name for the service provider
     */
    public function getName()
    {
        return 'notifications';
    }

    /**
     * getDescription - get human readable description of the service provider
     *
     * @return string
     */
    public function getDescription()
    {
        return 'Traditional XOOPS notifications.';
    }

    public function notifyUser(Response $response, $template_dir, $template, $subject, $tags)
    {
        $notification = new NotificationsNotification();
        $ret = $notification->notifyUser($template_dir, $template, $subject, $tags);
        if (!$ret)
            $response->setSuccess(false)->addErrorMessage('Unable to notify user');           
    }

    public function getObjectsArray(Response $response, CriteriaElement $criteria = null, $id_as_key = false)
    {
        $handler = Notifications::getInstance()->getHandlerNotification();
        $ret = $handler->getObjectsArray($criteria, $id_as_key);
        if ($ret)
            $response->setValue($ret);
        else
            $response->setSuccess(false)->addErrorMessage('Unable to getObjectsArray');
    }
    
    public function getNotification(Response $response, $module_id, $category, $item_id, $event, $user_id)
    {
        $handler = Notifications::getInstance()->getHandlerNotification();
        $ret = $handler->getNotification($module_id, $category, $item_id, $event, $user_id);
        if ($ret)
            $response->setValue($ret);
        else
            $response->setSuccess(false)->addErrorMessage('Unable to getNotification');
    }
    
    public function isSubscribed(Response $response, $category, $item_id, $event, $module_id, $user_id)
    {
        $handler = Notifications::getInstance()->getHandlerNotification();
        $ret = $handler->isSubscribed($module_id, $category, $item_id, $event, $user_id);
        $response->setValue((int)$ret);
    }
    
    public function subscribe(Response $response, $category, $item_id, $events, $mode = null, $module_id = null, $user_id = null)
    {
        $handler = Notifications::getInstance()->getHandlerNotification();
        $ret = $handler->suscribe($category, $item_id, $events, $mode, $module_id, $user_id);
        if (!$ret)
            $response->setSuccess(false)->addErrorMessage('Unable to suscribe');
    }

    public function getByUser(Response $response, $user_id)
    {
        $handler = Notifications::getInstance()->getHandlerNotification();
        $ret = $handler->getByUser($user_id);
        $response->setValue($ret);
    }
    
    public function getSubscribedEvents(Response $response, $category, $item_id, $module_id, $user_id)
    {
        $handler = Notifications::getInstance()->getHandlerNotification();
        $ret = $handler->getSubscribedEvents($category, $item_id, $module_id, $user_id);
        $response->setValue($ret);
    }

    public function getByItemId(Response $response, $module_id, $item_id, $order = null, $status = null)
    {
        $handler = Notifications::getInstance()->getHandlerNotification();
        $ret = $handler->getByItemId($module_id, $item_id, $order, $status);
        $response->setValue($ret);
    }
    
    public function triggerEvents(
        Response $response,
        $category,
        $item_id,
        $events,
        $extra_tags = array(),
        $user_list = array(),
        $module_id = null,
        $omit_user_id = null
    ) {
        $handler = Notifications::getInstance()->getHandlerNotification();
        $ret = $handler->triggerEvents($category, $item_id, $events, $extra_tags, $user_list, $module_id, $omit_user_id);
        if (!ret)
            $response->setSuccess(false)->addErrorMessage('Unable to triggerEvents');
    }
    
    public function triggerEvent(
        Response $response,
        $category,
        $item_id,
        $event,
        $extra_tags = array(),
        $user_list = array(),
        $module_id = null,
        $omit_user_id = null
    ) {
        $helper = Notifications::getInstance();
        $handler = $helper->getHandlerNotification();
        if (empty($category) AND empty($module_id)) {
            $response->setSuccess(false)->addErrorMessage('Unable to triggerEvent');
            return;
        }
        if (empty($category)) {
            $xoops = \Xoops::getInstance();
            $module = $xoops->getModuleById($module_id);
            $catinfo = $helper->getCommentsCategory($module->getVar('dirname'));
            $category = $catinfo['name'];
        }
        $ret = $handler->triggerEvent($category, $item_id, $event, $extra_tags, $user_list, $module_id, $omit_user_id);
        if (!ret)
            $response->setSuccess(false)->addErrorMessage('Unable to triggerEvent');
    }

    public function unsubscribeByUser(Response $response, $user_id)
    {
        $handler = Notifications::getInstance()->getHandlerNotification();
        $ret = $handler->unsubscribeByUser($user_id);
        if (!ret)
            $response->setSuccess(false)->addErrorMessage('Unable to unsubscribeByUser');
    }
    
    public function unsubscribe(Response $response, $category, $item_id, $events, $module_id = null, $user_id = null)
    {
        $handler = Notifications::getInstance()->getHandlerNotification();
        $ret = $handler->unsubscribe($category, $item_id, $events, $module_id, $user_id);
        if (!ret)
            $response->setSuccess(false)->addErrorMessage('Unable to unsubscribe');
    }

    public function unsubscribeByModule(Response $response, $module_id)
    {
        $handler = Notifications::getInstance()->getHandlerNotification();
        $ret = $handler->unsubscribeByModule($module_id);
        if (!ret)
            $response->setSuccess(false)->addErrorMessage('Unable to unsubscribeByModule');
    }

    public function unsubscribeByItem(Response $response, $module_id, $category, $item_id)
    {
        $handler = Notifications::getInstance()->getHandlerNotification();
        $ret = $handler->unsubscribeByItem($module_id, $category, $item_id);
        if (!ret)
            $response->setSuccess(false)->addErrorMessage('Unable to unsubscribeByItem');
    }

    public function doLoginMaintenance(Response $response, $user_id)
    {
        $handler = Notifications::getInstance()->getHandlerNotification();
        $handler->doLoginMaintenance($user_id);
    }
    
}
