<?php
/*
 You may not change or alter any portion of this comment or credits
 of supporting developers from this source code or any supporting source code
 which is considered copyrighted (c) material of the original comment or credit authors.

 This program is distributed in the hope that it will be useful,
 but WITHOUT ANY WARRANTY; without even the implied warranty of
 MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
 */

namespace Xmf\Module;

use Xmf\Loader;
use Xmf\Module\Helper;
use Xmf\Module\Helper\AbstractHelper;

/**
 * Methods to help manage permissions within a module
 *
 * @category  Xmf\Module\Helper\Permission
 * @package   Xmf
 * @author    trabis <lusopoemas@gmail.com>
 * @author    Richard Griffith <richard@geekwright.com>
 * @copyright 2011-2013 The XOOPS Project http://sourceforge.net/projects/xoops/
 * @license   GNU GPL 2 or later (http://www.gnu.org/licenses/gpl-2.0.html)
 * @version   Release: 1.0
 * @link      http://xoops.org
 * @since     1.0
 */
class Permission extends AbstractHelper
{
    /**
     * @var int
     */
    private $mid;

    /**
     * @var string
     */
    private $dirname;

    /**
     * @var XoopsDatabase
     */
    private $db;

    /**
     * @var XoopsGrouppermHandler
     */
    private $perm;

    /**
     * @var Xoops instance if available
     */
    private $xoops = null;

    /**
     * Initialize parent::__constuct calls this after verifying module object.
     *
     * @return void
     */
    public function init()
    {
        if (!class_exists('XoopsGroupPermHandler', true)) {
            Loader::loadFile(XOOPS_ROOT_PATH . '/kernel/groupperm.php');
        }
        $this->mid = $this->module->getVar('mid');
        $this->dirname = $this->module->getVar('dirname');
        $this->db = \XoopsDatabaseFactory::getDatabaseConnection();
        $this->perm = new \XoopsGroupPermHandler($this->db);
        if (class_exists('Xoops', false)) {
            $this->xoops = \Xoops::getInstance();
        }
    }

    /**
     * Check if the user has permission for an item
     *
     * @param string $gperm_name   name of the permission to test
     * @param int    $gperm_itemid id of the object to check
     *
     * @return bool   true if user has access, false if not
     **/
    public function checkPermission($gperm_name, $gperm_itemid)
    {
        $gperm_groupid = $this->getUserGroups();

        return $this->perm->checkRight(
            $gperm_name,
            $gperm_itemid,
            $gperm_groupid,
            $this->mid
        );
    }

    /**
     * Redirect to a url if user does not have permission for an item
     *
     * @param string $gperm_name   name of the permission to test
     * @param int    $gperm_itemid id of the object to check
     * @param string $url          module relative url to redirect to
     * @param int    $time         time in seconds to delay
     * @param string $message      message to display with redirect
     *
     * @return void
     **/
    public function checkPermissionRedirect(
        $gperm_name,
        $gperm_itemid,
        $url,
        $time = 3,
        $message = ''
    ) {
        $gperm_groupid = $this->getUserGroups();
        $permission = $this->perm->checkRight(
            $gperm_name,
            $gperm_itemid,
            $gperm_groupid,
            $this->mid
        );
        if (!$permission) {
            $helper = Helper::getHelper($this->dirname);
            $helper->redirect($url, $time, $message);
        }
    }

    /**
     * Get groups user belong to, even for annonymous user
     *
     * @return array of groups the current user is associted with
     */
    public function getUserGroups()
    {
        if ($this->xoops) {
            $groupids = $this->xoops->isUser() ?
                $this->xoops->user->getGroups() : array(XOOPS_GROUP_ANONYMOUS);
        } else {
            $groupids = is_object($GLOBALS['xoopsUser']) ?
                $GLOBALS['xoopsUser']->getGroups() : array(XOOPS_GROUP_ANONYMOUS);
        }

        return $groupids;
    }

    /**
     * Get array of groups with named permission to an item
     *
     * @param string $gperm_name   name of the permission to test
     * @param int    $gperm_itemid id of the object to check
     *
     * @return array  groups with permission for item
     **/
    public function getGroupsForItem($gperm_name, $gperm_itemid)
    {
        return $this->perm->getGroupIds($gperm_name, $gperm_itemid, $this->mid);
    }

    /**
     * Save group permissions for an item
     *
     * @param string $gperm_name   name of the permission to test
     * @param int    $gperm_itemid id of the object to check
     * @param array  $groups       group ids to grant permission to
     *
     * @return bool   true if no errors
     **/
    public function savePermissionForItem($gperm_name, $gperm_itemid, $groups)
    {
        $result = true;

        // First, delete any existing permissions for this name and id
        $this->deletePermissionForItem($gperm_name, $gperm_itemid);

        // Save the new permissions
        if (count($groups) > 0) {
            foreach ($groups as $group_id) {
                $this->perm->addRight(
                    $gperm_name,
                    $gperm_itemid,
                    $group_id,
                    $this->mid
                );
            }
        }

        return $result;
    }

    /**
     * Delete all permissions for a specific name and item
     *
     * @param string $gperm_name   name of the permission to test
     * @param int    $gperm_itemid id of the object to check
     *
     * @return bool   true if no errors
     */
    public function deletePermissionForItem($gperm_name, $gperm_itemid)
    {
        return $this->perm->deleteByModule($this->mid, $gperm_name, $gperm_itemid);
    }

    /**
     * Generate a XoopsFormElement to select groups to grant permission
     * to a specific gperm_name and gperm_item. Field will be preset
     * with existing permissions.
     *
     * @param string $gperm_name   name of the permission to test
     * @param int    $gperm_itemid id of the object to check
     * @param string $caption      caption for form field
     * @param string $name         name/id of form field
     * @param bool   $include_anon true to include annonymous group
     * @param int    $size         size of list
     * @param bool   $multiple     true to allow multiple selections
     *
     * @return object XoopsFormSelectGroup
     */
    public function getGroupSelectFormForItem(
        $gperm_name,
        $gperm_itemid,
        $caption,
        $name = null,
        $include_anon = false,
        $size = 5,
        $multiple = true
    ) {
        if (!class_exists('XoopsFormSelectGroup', true)) {
            Loader::loadFile(XOOPS_ROOT_PATH.'/class/xoopsformloader.php');
        }
        if (empty($name)) {
            $name = $this->defaultFieldName($gperm_name, $gperm_itemid);
        }
        $value = $this->getGroupsForItem($gperm_name, $gperm_itemid);
        $element = new \XoopsFormSelectGroup(
            $caption,
            $name,
            $include_anon,
            $value,
            $size,
            $multiple
        );

        return $element;

    }

    /**
     * Generate a default name for a XoopsFormElement based on
     * module, gperm_name and gperm_itemid
     *
     * @param string $gperm_name   name of the permission to test
     * @param int    $gperm_itemid id of the object to check
     *
     * @return object XoopsFormSelectGroup
     */
    public function defaultFieldName($gperm_name, $gperm_itemid)
    {
        $name = $this->module->getVar('dirname') . '_' .
            $gperm_name . '_' . $gperm_itemid;

        return $name;
    }
}
