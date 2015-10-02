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
use Xoops\Core\Handler\Factory;
use Xoops\Form\SelectGroup;

/**
 * Methods to help manage permissions within a module
 *
 * @category  Xmf\Module\Helper\Permission
 * @package   Xmf
 * @author    trabis <lusopoemas@gmail.com>
 * @author    Richard Griffith <richard@geekwright.com>
 * @copyright 2011-2015 XOOPS Project (http://xoops.org)
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
     * @var \Xoops\Core\Kernel\Handlers\XoopsGroupPermHandler
     */
    private $permissionHandler;

    /**
     * Initialize parent::__constuct calls this after verifying module object.
     *
     * @return void
     */
    public function init()
    {
        if (!class_exists('XoopsGroupPermHandler', true)) {
            Loader::loadFile(\XoopsBaseConfig::get('root-path') . '/kernel/groupperm.php');
        }
        $this->mid = $this->module->getVar('mid');
        $this->dirname = $this->module->getVar('dirname');
        $this->permissionHandler = Factory::newSpec()->scheme('kernel')->name('groupperm')->build();
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
        $gperm_groupid = \Xoops::getInstance()->getUserGroups();

        return $this->permissionHandler->checkRight(
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
        $gperm_groupid = \Xoops::getInstance()->getUserGroups();
        $permission = $this->permissionHandler->checkRight(
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
     * Get array of groups with named permission to an item
     *
     * @param string $gperm_name   name of the permission to test
     * @param int    $gperm_itemid id of the object to check
     *
     * @return array  groups with permission for item
     **/
    public function getGroupsForItem($gperm_name, $gperm_itemid)
    {
        return $this->permissionHandler->getGroupIds($gperm_name, $gperm_itemid, $this->mid);
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
                $this->permissionHandler->addRight(
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
        return $this->permissionHandler->deleteByModule($this->mid, $gperm_name, $gperm_itemid);
    }

    /**
     * Generate a \Xoops\Form\Element to select groups to grant permission
     * to a specific gperm_name and gperm_item. Field will be preset
     * with existing permissions.
     *
     * @param string $gperm_name   name of the permission to test
     * @param int    $gperm_itemid id of the object to check
     * @param string $caption      caption for form field
     * @param string $name         name/id of form field
     * @param bool   $include_anon true to include anonymous group
     * @param int    $size         size of list
     * @param bool   $multiple     true to allow multiple selections
     *
     * @return SelectGroup
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
        if (empty($name)) {
            $name = $this->defaultFieldName($gperm_name, $gperm_itemid);
        }
        $value = $this->getGroupsForItem($gperm_name, $gperm_itemid);
        $element = new SelectGroup(
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
     * Generate a default name for a Xoops\Form\SelectGroup based on
     * module, gperm_name and gperm_itemid
     *
     * @param string $gperm_name   name of the permission to test
     * @param int    $gperm_itemid id of the object to check
     *
     * @return string
     */
    public function defaultFieldName($gperm_name, $gperm_itemid)
    {
        $name = $this->module->getVar('dirname') . '_' .
            $gperm_name . '_' . $gperm_itemid;

        return $name;
    }
}
