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
use Xoops\Core\Kernel\Handlers\XoopsUser;

/**
 * @copyright       XOOPS Project (http://xoops.org)
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
    protected $xoopsTagMap = array();

    // user class object
    protected $user;

    protected $isadmin = false;


    function XoopsXmlRpcApi(array &$params, XoopsXmlRpcResponse &$response, XoopsModule &$module)
    {
        $this->params = $params;
        $this->response = $response;
        $this->module = $module;
    }

    function _setUser(XoopsUser &$user, $isadmin = false)
    {
        if (is_object($user)) {
            $this->user = $user;
            $this->isadmin = $isadmin;
        }
    }

    function _checkUser($username, $password)
    {
        $xoops = Xoops::getInstance();

        $member_handler = $xoops->getHandlerMember();
        $this->user = $member_handler->loginUser(addslashes($username), addslashes($password));
        if (!is_object($this->user)) {
            $this->user = null;
            return false;
        }
        $moduleperm_handler = $xoops->getHandlerGroupperm();
        if (!$moduleperm_handler->checkRight('module_read', $this->module->getVar('mid'), $this->user->getGroups())) {
            $this->user = null;
            return false;
        }
        return true;
    }

    function _checkAdmin()
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

    function _getPostFields($post_id = null, $blog_id = null)
    {
        $ret = array();
        $ret['title'] = array('required' => true, 'form_type' => 'textbox', 'value_type' => 'text');
        $ret['hometext'] = array('required' => false, 'form_type' => 'textarea', 'data_type' => 'textarea');
        $ret['moretext'] = array('required' => false, 'form_type' => 'textarea', 'data_type' => 'textarea');
        $ret['categories'] = array('required' => false, 'form_type' => 'select_multi', 'data_type' => 'array');
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
    function _setXoopsTagMap($xoopstag, $blogtag)
    {
        if (trim($blogtag) != '') {
            $this->xoopsTagMap[$xoopstag] = $blogtag;
        }
    }

    function _getXoopsTagMap($xoopstag)
    {
        if (isset($this->xoopsTagMap[$xoopstag])) {
            return $this->xoopsTagMap[$xoopstag];
        }
        return $xoopstag;
    }

    function _getTagCdata(&$text, $tag, $remove = true)
    {
        $ret = '';
        $match = array();
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
    function _getXoopsApi(&$params)
    {
        if (strtolower(get_class($this)) != 'xoopsapi') {
			$xoops_root_path = \XoopsBaseConfig::get('root-path');
            require_once($xoops_root_path . '/class/xml/rpc/xoopsapi.php');
            return new XoopsApi($params, $this->response, $this->module);
        } else {
            return $this;
        }
    }
}
