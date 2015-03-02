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
 * @copyright       The XOOPS Project http://sourceforge.net/projects/xoops/
 * @license         GNU GPL 2 or later (http://www.gnu.org/licenses/gpl-2.0.html)
 * @package         class
 * @subpackage      xml
 * @since           1.0.0
 * @author          Kazumi Ono (AKA onokazu)
 * @version         $Id $
 */

defined('XOOPS_ROOT_PATH') or die("XOOPS root path not defined");

class BloggerApi extends XoopsXmlRpcApi
{

    function BloggerApi(&$params, &$response, &$module)
    {
        $this->XoopsXmlRpcApi($params, $response, $module);
        $this->_setXoopsTagMap('storyid', 'postid');
        $this->_setXoopsTagMap('published', 'dateCreated');
        $this->_setXoopsTagMap('uid', 'userid');
    }

    function newPost()
    {
        if (!$this->_checkUser($this->params[2], $this->params[3])) {
            $this->response->add(new XoopsXmlRpcFault(104));
        } else {
            if (!$fields = $this->_getPostFields(null, $this->params[1])) {
                $this->response->add(new XoopsXmlRpcFault(106));
            } else {
                $missing = array();
                $post = array();
                foreach ($fields as $tag => $detail) {
                    $maptag = $this->_getXoopsTagMap($tag);
                    $data = $this->_getTagCdata($this->params[4], $maptag, true);
                    if (trim($data) == ''){
                        if ($detail['required']) {
                            $missing[] = $maptag;
                        }
                    } else {
                        $post[$tag] = $data;
                    }
                }
                if (count($missing) > 0) {
                    $msg = '';
                    foreach ($missing as $m) {
                        $msg .= '<'.$m.'> ';
                    }
                    $this->response->add(new XoopsXmlRpcFault(109, $msg));
                } else {
                    $newparams = array();
                    // Xoops Api ignores App key
                    $newparams[0] = $this->params[1];
                    $newparams[1] = $this->params[2];
                    $newparams[2] = $this->params[3];
                    foreach ($post as $key => $value) {
                        $newparams[3][$key] = $value;
                        unset($value);
                    }
                    $newparams[3]['xoops_text'] = $this->params[4];
                    $newparams[4] = $this->params[5];
                    $xoopsapi = $this->_getXoopsApi($newparams);
                    $xoopsapi->_setUser($this->user, $this->isadmin);
                    $xoopsapi->newPost();
                }
            }
        }
    }

    function editPost()
    {
        if (!$this->_checkUser($this->params[2], $this->params[3])) {
            $this->response->add(new XoopsXmlRpcFault(104));
        } else {
            if (!$fields = $this->_getPostFields($this->params[1])) {
            } else {
                $missing = array();
                $post = array();
                foreach ($fields as $tag => $detail) {
                    $data = $this->_getTagCdata($this->params[4], $tag, true);
                    if (trim($data) == ''){
                        if ($detail['required']) {
                            $missing[] = $tag;
                        }
                    } else {
                        $post[$tag] = $data;
                    }
                }
                if (count($missing) > 0) {
                    $msg = '';
                    foreach ($missing as $m) {
                        $msg .= '<'.$m.'> ';
                    }
                    $this->response->add(new XoopsXmlRpcFault(109, $msg));
                } else {
                    $newparams = array();
                    // XOOPS API ignores App key (index 0 of params)
                    $newparams[0] = $this->params[1];
                    $newparams[1] = $this->params[2];
                    $newparams[2] = $this->params[3];
                    foreach ($post as $key => $value) {
                        $newparams[3][$key] = $value;
                        unset($value);
                    }
                    $newparams[3]['xoops_text'] = $this->params[4];
                    $newparams[4] = $this->params[5];
                    $xoopsapi = $this->_getXoopsApi($newparams);
                    $xoopsapi->_setUser($this->user, $this->isadmin);
                    $xoopsapi->editPost();
                }
            }
        }
    }

    function deletePost()
    {
        if (!$this->_checkUser($this->params[2], $this->params[3])) {
            $this->response->add(new XoopsXmlRpcFault(104));
        } else {
            // XOOPS API ignores App key (index 0 of params)
            array_shift($this->params);
            $xoopsapi = $this->_getXoopsApi($this->params);
            $xoopsapi->_setUser($this->user, $this->isadmin);
            $xoopsapi->deletePost();
        }
    }

    function getPost()
    {
        if (!$this->_checkUser($this->params[2], $this->params[3])) {
            $this->response->add(new XoopsXmlRpcFault(104));
        } else {
            // XOOPS API ignores App key (index 0 of params)
            array_shift($this->params);
            $xoopsapi = $this->_getXoopsApi($this->params);
            $xoopsapi->_setUser($this->user, $this->isadmin);
            $ret = $xoopsapi->getPost(false);
            if (is_array($ret)) {
                $struct = new XoopsXmlRpcStruct();
                $content = '';
                foreach ($ret as $key => $value) {
                    $maptag = $this->_getXoopsTagMap($key);
                    switch($maptag) {
                    case 'userid':
                        $struct->add('userid', new XoopsXmlRpcString($value));
                        break;
                    case 'dateCreated':
                        $struct->add('dateCreated', new XoopsXmlRpcDatetime($value));
                        break;
                    case 'postid':
                        $struct->add('postid', new XoopsXmlRpcString($value));
                        break;
                    default :
                        $content .= '<'.$key.'>'.trim($value).'</'.$key.'>';
                        break;
                    }
                }
                $struct->add('content', new XoopsXmlRpcString($content));
                $this->response->add($struct);
            } else {
                $this->response->add(new XoopsXmlRpcFault(106));
            }
        }
    }

    function getRecentPosts()
    {
        if (!$this->_checkUser($this->params[2], $this->params[3])) {
            $this->response->add(new XoopsXmlRpcFault(104));
        } else {
            // XOOPS API ignores App key (index 0 of params)
            array_shift($this->params);
            $xoopsapi = $this->_getXoopsApi($this->params);
            $xoopsapi->_setUser($this->user, $this->isadmin);
            $ret = $xoopsapi->getRecentPosts(false);
            if (is_array($ret)) {
                $arr = new XoopsXmlRpcArray();
                $count = count($ret);
                if ($count == 0) {
                    $this->response->add(new XoopsXmlRpcFault(106, 'Found 0 Entries'));
                } else {
                    for ($i = 0; $i < $count; ++$i) {
                        $struct = new XoopsXmlRpcStruct();
                        $content = '';
                        foreach($ret[$i] as $key => $value) {
                            $maptag = $this->_getXoopsTagMap($key);
                            switch($maptag) {
                            case 'userid':
                                $struct->add('userid', new XoopsXmlRpcString($value));
                                break;
                            case 'dateCreated':
                                $struct->add('dateCreated', new XoopsXmlRpcDatetime($value));
                                break;
                            case 'postid':
                                $struct->add('postid', new XoopsXmlRpcString($value));
                                break;
                            default :
                                $content .= '<'.$key.'>'.trim($value).'</'.$key.'>';
                                break;
                            }
                        }
                        $struct->add('content', new XoopsXmlRpcString($content));
                        $arr->add($struct);
                        unset($struct);
                    }
                    $this->response->add($arr);
                }
            } else {
                $this->response->add(new XoopsXmlRpcFault(106));
            }
        }
    }

    function getUsersBlogs()
    {
        if (!$this->_checkUser($this->params[1], $this->params[2])) {
            $this->response->add(new XoopsXmlRpcFault(104));
        } else {
            $arr = new XoopsXmlRpcArray();
            $struct = new XoopsXmlRpcStruct();
            $struct->add('url', new XoopsXmlRpcString(XOOPS_URL.'/modules/'.$this->module->getVar('dirname').'/'));
            $struct->add('blogid', new XoopsXmlRpcString($this->module->getVar('mid')));
            $struct->add('blogName', new XoopsXmlRpcString('XOOPS Blog'));
            $arr->add($struct);
            $this->response->add($arr);
        }
    }

    function getUserInfo()
    {
        if (!$this->_checkUser($this->params[1], $this->params[2])) {
            $this->response->add(new XoopsXmlRpcFault(104));
        } else {
            $struct = new XoopsXmlRpcStruct();
            $struct->add('nickname', new XoopsXmlRpcString($this->user->getVar('uname')));
            $struct->add('userid', new XoopsXmlRpcString($this->user->getVar('uid')));
            $struct->add('url', new XoopsXmlRpcString($this->user->getVar('url')));
            $struct->add('email', new XoopsXmlRpcString($this->user->getVar('email')));
            $struct->add('lastname', new XoopsXmlRpcString(''));
            $struct->add('firstname', new XoopsXmlRpcString($this->user->getVar('name')));
            $this->response->add($struct);
        }
    }

    function getTemplate()
    {
        if (!$this->_checkUser($this->params[2], $this->params[3])) {
            $this->response->add(new XoopsXmlRpcFault(104));
        } else {
            switch ($this->params[5]) {
            case 'main':
                $this->response->add(new XoopsXmlRpcFault(107));
                break;
            case 'archiveIndex':
                $this->response->add(new XoopsXmlRpcFault(107));
                break;
            default:
                $this->response->add(new XoopsXmlRpcFault(107));
                break;
            }
        }
    }

    function setTemplate()
    {
        if (!$this->_checkUser($this->params[2], $this->params[3])) {
            $this->response->add(new XoopsXmlRpcFault(104));
        } else {
            $this->response->add(new XoopsXmlRpcFault(107));
        }
    }
}
?>
