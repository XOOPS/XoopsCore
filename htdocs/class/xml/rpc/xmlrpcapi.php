<?php
/*
 You may not change or alter any portion of this comment or credits
 of supporting developers from this source code or any supporting source code
 which is considered copyrighted (c) material of the original comment or credit authors.

 This program is distributed in the hope that it will be useful,
 but WITHOUT ANY WARRANTY; without even the implied warranty of
 MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
*/

use Xoops\Core\Kernel\Handlers\XoopsModule;

/**
 * @copyright      2000-2020 XOOPS Project (https://xoops.org)
 * @license         GNU GPL 2 or later (http://www.gnu.org/licenses/gpl-2.0.html)
 * @package         class
 * @subpackage      xml
 * @since           1.0.0
 * @author          Kazumi Ono (AKA onokazu)
 * @version         $Id $
 */
class XoopsXmlRpcApi
{
    // reference to method parameters
    protected $params;

    // reference to xmlrpc document class object
    /**
     * @var XoopsXmlRpcResponse
     */
    protected $response;

    // reference to module class object
    /**
     * @var XoopsModule
     */
    protected $module;

    // map between xoops tags and blogger specific tags
    protected $xoopsTagMap = [];

    // user class object
    protected $user;

    protected $isadmin = false;

    /**
     * @param $params
     * @param $response
     * @param $module
     */
    public function __construct(&$params, &$response, &$module)
    {
        $this->params = $params;
        $this->response = $response;
        $this->module = $module;
    }

    /**
     * @param      $user
     * @param bool $isadmin
     */
    public function _setUser(&$user, $isadmin = false)
    {
        if (is_object($user)) {
            $this->user = $user;
            $this->isadmin = $isadmin;
        }
    }

    /**
     * @param $username
     * @param $password
     *
     * @return bool
     */
    public function _checkUser($username, $password)
    {
        $xoops = Xoops::getInstance();

        $member_handler = $xoops->getHandlerMember();
        $this->user = $member_handler->loginUser(addslashes($username), addslashes($password));
        if (!is_object($this->user)) {
            $this->user = null;

            return false;
        }
        $moduleperm_handler = $xoops->getHandlerGroupPermission();
        if (!$moduleperm_handler->checkRight('module_read', $this->module->getVar('mid'), $this->user->getGroups())) {
            $this->user = null;

            return false;
        }

        return true;
    }

    /**
     * @return bool
     */
    public function _checkAdmin()
    {
        if ($this->isadmin) {
            return true;
        }
        if (!is_object($this->user)) {
            return false;
        }
        if (!$this->user->isAdmin($this->module->getVar('mid'))) {
            return false;
        }
        $this->isadmin = true;

        return true;
    }

    /**
     * @param null $post_id
     * @param null $blog_id
     *
     * @return array
     */
    public function &_getPostFields($post_id = null, $blog_id = null)
    {
        $ret = [];
        $ret['title'] = ['required' => true, 'form_type' => 'textbox', 'value_type' => 'text'];
        $ret['hometext'] = ['required' => false, 'form_type' => 'textarea', 'data_type' => 'textarea'];
        $ret['moretext'] = ['required' => false, 'form_type' => 'textarea', 'data_type' => 'textarea'];
        $ret['categories'] = ['required' => false, 'form_type' => 'select_multi', 'data_type' => 'array'];

        /*
        if (!isset($blog_id)) {
            if (!isset($post_id)) {
                return false;
            }
            $itemman = $this->mf->get(MANAGER_ITEM);
            $item = $itemman->get($post_id);
            $blog_id = $item->getVar('sect_id');
        }
        $sectman = $this->mf->get(MANAGER_SECTION);
        $this->section = $sectman->get($blog_id);
        $ret = $this->section->getVar('sect_fields');
        */

        return $ret;
    }

    /**
     * @param string $xoopstag
     * @param string $blogtag
     */
    public function _setXoopsTagMap($xoopstag, $blogtag)
    {
        if ('' != trim($blogtag)) {
            $this->xoopsTagMap[$xoopstag] = $blogtag;
        }
    }

    /**
     * @param $xoopstag
     *
     * @return mixed
     */
    public function _getXoopsTagMap($xoopstag)
    {
        if (isset($this->xoopsTagMap[$xoopstag])) {
            return $this->xoopsTagMap[$xoopstag];
        }

        return $xoopstag;
    }

    /**
     * @param      $text
     * @param      $tag
     * @param bool $remove
     *
     * @return string
     */
    public function _getTagCdata(&$text, $tag, $remove = true)
    {
        $ret = '';
        $match = [];
        if (preg_match("/\<" . $tag . "\>(.*)\<\/" . $tag . "\>/is", $text, $match)) {
            if ($remove) {
                $text = str_replace($match[0], '', $text);
            }
            $ret = $match[1];
        }

        return $ret;
    }

    // kind of dirty method to load XOOPS API and create a new object thereof
    // returns itself if the calling object is XOOPS API

    /**
     * @param $params
     *
     * @return XoopsApi
     */
    public function _getXoopsApi(&$params)
    {
        if ('xoopsapi' !== mb_strtolower(get_class($this))) {
            $xoops_root_path = \XoopsBaseConfig::get('root-path');
            require_once($xoops_root_path . '/class/xml/rpc/xoopsapi.php');

            return new XoopsApi($params, $this->response, $this->module);
        }

        return $this;
    }
}
