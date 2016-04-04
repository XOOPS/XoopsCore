<?php
/*
 You may not change or alter any portion of this comment or credits
 of supporting developers from this source code or any supporting source code
 which is considered copyrighted (c) material of the original comment or credit authors.

 This program is distributed in the hope that it will be useful,
 but WITHOUT ANY WARRANTY; without even the implied warranty of
 MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
*/

namespace Xoops\Core\Service\Contract;

use Xoops\Core\Service\Response;
use Xoops\Core\Kernel\CriteriaElement;

/**
 * Notifications service contract
 *
 * @category  Xoops\Core\Service\Contract\NotificationsInterface
 * @package   Xoops\Core
 * @author    Alain91 <alain091@gmail.com>
 * @copyright XOOPS Project (http://xoops.org)
 * @license   GNU GPL 2 or later (http://www.gnu.org/licenses/gpl-2.0.html)
 * @link      http://xoops.org
 * @since     2.6.0
 */
interface NotificationsInterface
{
    const MODE = \Xoops\Core\Service\Manager::MODE_EXCLUSIVE;
    
    /**
     * Send a notification message to the user
     *
     * @param string $template_dir Template directory
     * @param string $template     Template name
     * @param string $subject      Subject line for notification message
     * @param array  $tags         Array of substitutions for template variables
     *
     * @return bool true if success, false if error
     **/
    public function notifyUser(Response $response, $template_dir, $template, $subject, $tags);
    
    /**
     * Get some {@link NotificationsNotification}s
     *
     * @param CriteriaElement|null $criteria  criteria object
     * @param bool                 $id_as_key Use IDs as keys into the array?
     *
     * @return  array   Array of {@link NotificationsNotification} objects
     */
    public function getObjectsArray(Response $response, CriteriaElement $criteria = null, $id_as_key = false);
    
    /**
     * getNotification
     *
     * @param int    $module_id module
     * @param string $category  category
     * @param int    $item_id   item
     * @param string $event     event
     * @param int    $user_id   user
     *
     * @return NotificationsNotification|bool
     *
     * @todo rename this
     * @todo Also, should we have get by module, get by category, etc...??
     */
    public function getNotification(Response $response, $module_id, $category, $item_id, $event, $user_id);
    
    /**
     * Determine if a user is subscribed to a particular event in
     * a particular module.
     *
     * @param string $category  Category of notification event
     * @param int    $item_id   Item ID of notification event
     * @param string $event     Event
     * @param int    $module_id ID of module (default current module)
     * @param int    $user_id   ID of user (default current user)
     *
     * @return int  0 if not subscribe; non-zero if subscribed
     */
    public function isSubscribed(Response $response, $category, $item_id, $event, $module_id, $user_id);
    
    /**
     * Subscribe for notification for an event(s)
     *
     * @param string $category  category of notification
     * @param int    $item_id   ID of the item
     * @param mixed  $events    event string or array of events
     * @param int    $mode      force a particular notification mode
     *                          (e.g. once_only) (default to current user preference)
     * @param int    $module_id ID of the module (default to current module)
     * @param int    $user_id   ID of the user (default to current user)
     *
     * @return bool
     *
     * @todo: how about a function to subscribe a whole group of users???
     *      e.g. if we want to add all moderators to be notified of subscription
     *      of new threads...
     */
    public function subscribe(Response $response, $category, $item_id, $events, $mode = null, $module_id = null, $user_id = null);
    
    /**
     * Get a list of notifications by user ID
     *
     * @param int $user_id ID of the user
     *
     * @return array Array of {@link NotificationsNotification} objects
     *
     * @todo this will be to provide a list of everything a particular
     *       user has subscribed to... e.g. for on the 'Profile' page, similar
     *       to how we see the various posts etc. that the user has made.
     *       We may also want to have a function where we can specify module id
     **/
    public function getByUser(Response $response, $user_id);
    
    /**
     * Get a list of notification events for the current item/mod/user
     *
     * @param string $category  caategory
     * @param int    $item_id   id
     * @param int    $module_id module
     * @param int    $user_id   user
     *
     * @return array
     *
     * @todo rename this?
     */
    public function getSubscribedEvents(Response $response, $category, $item_id, $module_id, $user_id);
    
    /**
     * Retrieve items by their ID
     *
     * @param int    $module_id Module ID
     * @param int    $item_id   Item ID
     * @param string $order     Sort order
     * @param int    $status    status
     *
     * @return  array   Array of {@link NotificationsNotification} objects
     *
     * @todo is this a useful function?? (Copied from comment_handler)
     */
    public function getByItemId(Response $response, $module_id, $item_id, $order = null, $status = null);
    
    /**
     * Send notifications to users
     *
     * @param string $category     notification category
     * @param int    $item_id      ID of the item
     * @param string $events       notification event
     * @param array  $extra_tags   array of substitutions for template to be
     *                             merged with the one from function..
     * @param array  $user_list    only notify the selected users
     * @param int    $module_id    ID of the module
     * @param int    $omit_user_id ID of the user to omit from notifications. (default to
     *                              current user).  set to 0 for all users to receive notification.
     *
     * @return void
     *
     * @todo(?) - pass in an event LIST.  This will help to avoid
     *      problem of sending people multiple emails for similar events.
     *      BUT, then we need an array of mail templates, etc...  Unless
     *      mail templates can include logic in the future, then we can
     *      tailor the mail so it makes sense for any of the possible
     *      (or combination of) events.
     */
    public function triggerEvents(
        Response $response,
        $category,
        $item_id,
        $events,
        $extra_tags = array(),
        $user_list = array(),
        $module_id = null,
        $omit_user_id = null
    );
    
    /**
     * triggerEvent
     *
     * @param int   $category     notification category
     * @param int   $item_id      ID of the item
     * @param int   $event        notification event
     * @param array $extra_tags   array of substitutions for template
     * @param array $user_list    users to notify
     * @param int   $module_id    module
     * @param int   $omit_user_id users to not notify
     *
     * @return bool
     */
    public function triggerEvent(
        Response $response,
        $category,
        $item_id,
        $event,
        $extra_tags = array(),
        $user_list = array(),
        $module_id = null,
        $omit_user_id = null
    );
    
    /**
     * Delete all notifications for one user
     *
     * @param int $user_id ID of the user
     *
     * @return  bool
     **/
    public function unsubscribeByUser(Response $response, $user_id);
    
    /**
     * Unsubscribe notifications for an event(s).
     *
     * @param string $category  category of the events
     * @param int    $item_id   ID of the item
     * @param mixed  $events    event string or array of events
     * @param int    $module_id ID of the module (default current module)
     * @param int    $user_id   UID of the user (default current user)
     *
     * @return bool
     *
     * @todo allow these to use current module, etc...
     */
    public function unsubscribe(Response $response, $category, $item_id, $events, $module_id = null, $user_id = null);
    
    /**
     * Delete all notifications for a particular module
     *
     * @param int $module_id ID of the module
     *
     * @return  bool
     *
     * @todo When 'update' a module, may need to switch around some
     **/
    public function unsubscribeByModule(Response $response, $module_id);
    
    /**
     * Delete all subscriptions for a particular item.
     *
     * @param int    $module_id ID of the module to which item belongs
     * @param string $category  Notification category of the item
     * @param int    $item_id   ID of the item
     *
     * @return bool
     **/
    public function unsubscribeByItem(Response $response, $module_id, $category, $item_id);
    
    /**
     * Perform notification maintenance activites at login time.
     * In particular, any notifications for the newly logged-in
     * user with mode NOTIFICATIONS_MODE_WAITFORLOGIN are
     * switched to mode NOTIFICATIONS_MODE_SENDONCETHENWAIT.
     *
     * @param int $user_id ID of the user being logged in
     *
     * @return void
     **/
    public function doLoginMaintenance(Response $response, $user_id);

}
