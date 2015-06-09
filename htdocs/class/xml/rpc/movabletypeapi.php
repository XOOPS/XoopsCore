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
 * @copyright       XOOPS Project (http://xoops.org)
 * @license         GNU GPL 2 or later (http://www.gnu.org/licenses/gpl-2.0.html)
 * @package         class
 * @subpackage      xml
 * @since           1.0.0
 * @author          Kazumi Ono (AKA onokazu)
 * @version         $Id $
 */

class MovableTypeApi extends XoopsXmlRpcApi
{
    function MovableTypeApi(&$params, &$response, &$module)
    {
        $this->XoopsXmlRpcApi($params, $response, $module);
    }

    function getCategoryList()
    {
        if (!$this->_checkUser($this->params[1], $this->params[2])) {
            $this->response->add(new XoopsXmlRpcFault(104));
        } else {
            $xoopsapi = $this->_getXoopsApi($this->params);
            $xoopsapi->_setUser($this->user, $this->isadmin);
            $ret = $xoopsapi->getCategories(false);
            if (is_array($ret)) {
                $arr = new XoopsXmlRpcArray();
                foreach ($ret as $id => $name) {
                    $struct = new XoopsXmlRpcStruct();
                    $struct->add('categoryId', new XoopsXmlRpcString($id));
                    $struct->add('categoryName', new XoopsXmlRpcString($name['title']));
                    $arr->add($struct);
                    unset($struct);
                }
                $this->response->add($arr);
            } else {
                $this->response->add(new XoopsXmlRpcFault(106));
            }
        }
    }

    function getPostCategories()
    {
        $this->response->add(new XoopsXmlRpcFault(107));
    }

    function setPostCategories()
    {
        $this->response->add(new XoopsXmlRpcFault(107));
    }

    function supportedMethods()
    {
        $this->response->add(new XoopsXmlRpcFault(107));
    }
}
?>
