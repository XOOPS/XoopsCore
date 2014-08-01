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
 * @license         GNU GPL 2 (http://www.gnu.org/licenses/old-licenses/gpl-2.0.html)
 * @package         class
 * @subpackage      xml
 * @since           1.0.0
 * @author          Kazumi Ono (AKA onokazu)
 * @version         $Id $
 */

defined('XOOPS_ROOT_PATH') or die("XOOPS root path not defined");

/**
 * Class RSS Parser
 *
 * This class offers methods to parse RSS Files
 *
 * @link      http://www.xoops.org/ Latest release of this class
 * @package   class
 * @copyright Copyright (c) 2001 xoops.org. All rights reserved.
 * @author    Kazumi Ono <onokazu@xoops.org>
 * @version   $Id$
 * @access    public
 */
class XoopsXmlRpcParser extends SaxParser
{

    /**
     * @access private
     * @var    array
     */
    var $_param;

    /**
     * @access private
     * @var    string
     */
    var $_methodName;

    /**
     * @access private
     * @var    array
     */
    var $_tempName;

    /**
     * @access private
     * @var    array
     */
    var $_tempValue;

    /**
     * @access private
     * @var    array
     */
    var $_tempMember;

    /**
     * @access private
     * @var    array
     */
    var $_tempStruct;

    /**
     * @access private
     * @var    array
     */
    var $_tempArray;

    /**
     * @access private
     * @var    array
     */
    var $_workingLevel = array();


    /**
     * Constructor of the class
     *
     * @param $input
     * @return void
     */
    function XoopsXmlRpcParser(&$input)
    {
        parent::__construct($input);
        $this->addTagHandler(new RpcMethodNameHandler());
        $this->addTagHandler(new RpcIntHandler());
        $this->addTagHandler(new RpcDoubleHandler());
        $this->addTagHandler(new RpcBooleanHandler());
        $this->addTagHandler(new RpcStringHandler());
        $this->addTagHandler(new RpcDateTimeHandler());
        $this->addTagHandler(new RpcBase64Handler());
        $this->addTagHandler(new RpcNameHandler());
        $this->addTagHandler(new RpcValueHandler());
        $this->addTagHandler(new RpcMemberHandler());
        $this->addTagHandler(new RpcStructHandler());
        $this->addTagHandler(new RpcArrayHandler());
    }

    /**
     * @param string $name
     * @return void
     */
    function setTempName($name)
    {
        $this->_tempName[$this->getWorkingLevel()] = $name;
    }

    /**
     * @return string
     */
    function getTempName()
    {
        return $this->_tempName[$this->getWorkingLevel()];
    }

    /**
     * @param mixed $value
     * @return void
     */
    function setTempValue($value)
    {
        if (is_array($value)) {
            settype($this->_tempValue, 'array');
            foreach ($value as $k => $v) {
                $this->_tempValue[$k] = $v;
            }
        } elseif (is_string($value)) {
            if (isset($this->_tempValue)) {
                if (is_string($this->_tempValue)) {
                    $this->_tempValue .= $value;
                }
            } else {
                $this->_tempValue = $value;
            }
        } else {
            $this->_tempValue = $value;
        }
    }

    /**
     * @return array
     */
    function getTempValue()
    {
        return $this->_tempValue;
    }

    /**
     * @return void
     */
    function resetTempValue()
    {
        $this->_tempValue = null;
    }

    /**
     * @param string $name
     * @param mixed $value
     * @return void
     */
    function setTempMember($name, $value)
    {
        $this->_tempMember[$this->getWorkingLevel()][$name] = $value;
    }

    /**
     * @return mixed
     */
    function getTempMember()
    {
        return $this->_tempMember[$this->getWorkingLevel()];
    }

    /**
     * @return void
     */
    function resetTempMember()
    {
        $this->_tempMember[$this->getWorkingLevel()] = array();
    }

    /**
     * @return void
     */
    function setWorkingLevel()
    {
        array_push($this->_workingLevel, $this->getCurrentLevel());
    }

    /**
     * @return mixed
     */
    function getWorkingLevel()
    {
        return $this->_workingLevel[count($this->_workingLevel) - 1];
    }

    /**
     * @return void
     */
    function releaseWorkingLevel()
    {
        array_pop($this->_workingLevel);
    }

    /**
     * @param array $member
     * @return void
     */
    function setTempStruct(array $member)
    {
        $key = key($member);
        $this->_tempStruct[$this->getWorkingLevel()][$key] = $member[$key];
    }

    /**
     * @return
     */
    function getTempStruct()
    {
        return $this->_tempStruct[$this->getWorkingLevel()];
    }

    /**
     * @return void
     */
    function resetTempStruct()
    {
        $this->_tempStruct[$this->getWorkingLevel()] = array();
    }

    /**
     * @param $value
     * @return void
     */
    function setTempArray($value)
    {
        $this->_tempArray[$this->getWorkingLevel()][] = $value;
    }

    /**
     * @return
     */
    function getTempArray()
    {
        return $this->_tempArray[$this->getWorkingLevel()];
    }

    /**
     * @return void
     */
    function resetTempArray()
    {
        $this->_tempArray[$this->getWorkingLevel()] = array();
    }

    /**
     * @param $methodName
     * @return void
     */
    function setMethodName($methodName)
    {
        $this->_methodName = $methodName;
    }

    /**
     * @return string
     */
    function getMethodName()
    {
        return $this->_methodName;
    }

    /**
     * @param $value
     * @return void
     */
    function setParam($value)
    {
        $this->_param[] = $value;
    }

    /**
     * @return array
     */
    function getParam()
    {
        return $this->_param;
    }
}


class RpcMethodNameHandler extends XmlTagHandler
{

    /**
     * @return string
     */
    function getName()
    {
        return 'methodName';
    }

    /**
     * @param XoopsXmlRpcParser $parser
     * @param $data
     * @return void
     */
    function handleCharacterData(XoopsXmlRpcParser &$parser, &$data)
    {
        if (!$parser) return;
        $parser->setMethodName($data);
    }
}

class RpcIntHandler extends XmlTagHandler
{

    /**
    * @return string[]
    */
    function getName()
    {
        return array('int', 'i4');
    }

    /**
     * @param XoopsXmlRpcParser $parser
     * @param $data
     * @return void
     */
    function handleCharacterData(XoopsXmlRpcParser &$parser, &$data)
    {
        if (!$parser) return;
        $parser->setTempValue(intval($data));
    }
}

class RpcDoubleHandler extends XmlTagHandler
{

    /**
     * @return string
     */
    function getName()
    {
        return 'double';
    }

    /**
     * @param XoopsXmlRpcParser $parser
     * @param $data
     * @return void
     */
    function handleCharacterData(XoopsXmlRpcParser &$parser, &$data)
    {
        if (!$parser) return;
        $data = (float)$data;
        $parser->setTempValue($data);
    }
}

class RpcBooleanHandler extends XmlTagHandler
{

    /**
     * @return string
     */
    function getName()
    {
        return 'boolean';
    }

    /**
     * @param XoopsXmlRpcParser $parser
     * @param $data
     * @return void
     */
    function handleCharacterData(XoopsXmlRpcParser &$parser, &$data)
    {
        if (!$parser) return;
        $data = (boolean)$data;
        $parser->setTempValue($data);
    }
}

class RpcStringHandler extends XmlTagHandler
{

    /**
     * @return string
     */
    function getName()
    {
        return 'string';
    }

    /**
     * @param XoopsXmlRpcParser $parser
     * @param $data
     * @return void
     */
    function handleCharacterData(XoopsXmlRpcParser &$parser, &$data)
    {
        if (!$parser) return;
        $parser->setTempValue(strval($data));
    }
}

class RpcDateTimeHandler extends XmlTagHandler
{

    /**
     * @return string
     */
    function getName()
    {
        return 'dateTime.iso8601';
    }

    /**
     * @param XoopsXmlRpcParser $parser
     * @param $data
     * @return void
     */
    function handleCharacterData(XoopsXmlRpcParser &$parser, &$data)
    {
        if (!$parser) return;
        $matches = array();
        if (!preg_match("/^([0-9]{4})([0-9]{2})([0-9]{2})T([0-9]{2}):([0-9]{2}):([0-9]{2})$/", $data, $matches)) {
            $parser->setTempValue(time());
        } else {
            $parser->setTempValue(gmmktime($matches[4], $matches[5], $matches[6], $matches[2], $matches[3], $matches[1]));
        }
    }
}

class RpcBase64Handler extends XmlTagHandler
{

    /**
     * @return string
     */
    function getName()
    {
        return 'base64';
    }

    /**
     * @param XoopsXmlRpcParser $parser
     * @param $data
     * @return void
     */
    function handleCharacterData(XoopsXmlRpcParser &$parser, &$data)
    {
        if (!$parser) return;
        $parser->setTempValue(base64_decode($data));
    }
}

class RpcNameHandler extends XmlTagHandler
{

    /**
     * @return string
     */
    function getName()
    {
        return 'name';
    }

    /**
     * @param XoopsXmlRpcParser $parser
     * @param $data
     * @return void
     */
    function handleCharacterData(XoopsXmlRpcParser &$parser, &$data)
    {
        if (!$parser) return;
        switch ($parser->getParentTag()) {
            case 'member':
                $parser->setTempName($data);
                break;
            default:
                break;
        }
    }
}


class RpcValueHandler extends XmlTagHandler
{

    /**
     * @return string
     */
    function getName()
    {
        return 'value';
    }

    /**
     * @param XoopsXmlRpcParser $parser
     * @param $data
     * @return void
     */
    function handleCharacterData(XoopsXmlRpcParser &$parser, &$data)
    {
        if (!$parser) return;
        switch ($parser->getParentTag()) {
            case 'member':
                $parser->setTempValue($data);
                break;
            case 'data':
            case 'array':
                $parser->setTempValue($data);
                break;
            default:
                break;
        }
    }

    /**
     * @param XoopsXmlRpcParser $parser
     * @param $attributes
     * @return void
     */
    function handleBeginElement(XoopsXmlRpcParser &$parser, &$attributes)
    {
        if (!$parser) return;
        //$parser->resetTempValue();
    }

    /**
     * @param XoopsXmlRpcParser $parser
     * @return void
     */
    function handleEndElement(XoopsXmlRpcParser &$parser)
    {
        if (!$parser) return;
        switch ($parser->getCurrentTag()) {
            case 'member':
                $parser->setTempMember($parser->getTempName(), $parser->getTempValue());
                break;
            case 'array':
            case 'data':
                $parser->setTempArray($parser->getTempValue());
                break;
            default:
                $parser->setParam($parser->getTempValue());
                break;
        }
        $parser->resetTempValue();
    }
}

class RpcMemberHandler extends XmlTagHandler
{

    /**
     * @return string
     */
    function getName()
    {
        return 'member';
    }

    /**
     * @param XoopsXmlRpcParser $parser
     * @param $attributes
     * @return void
     */
    function handleBeginElement(XoopsXmlRpcParser &$parser, &$attributes)
    {
        $parser->setWorkingLevel();
        $parser->resetTempMember();
    }

    /**
     * @param XoopsXmlRpcParser $parser
     * @return void
     */
    function handleEndElement(XoopsXmlRpcParser &$parser)
    {
        $member = $parser->getTempMember();
        $parser->releaseWorkingLevel();
        $parser->setTempStruct($member);
    }
}

class RpcArrayHandler extends XmlTagHandler
{

    /**
     * @return string
     */
    function getName()
    {
        return 'array';
    }

    /**
    * @param XoopsXmlRpcParser $parser
    * @param $attributes
    * @return void
    */
    function handleBeginElement(XoopsXmlRpcParser &$parser, &$attributes)
    {
        $parser->setWorkingLevel();
        $parser->resetTempArray();
    }

    /**
     * @param XoopsXmlRpcParser $parser
     * @return void
     */
    function handleEndElement(XoopsXmlRpcParser &$parser)
    {
        $parser->setTempValue($parser->getTempArray());
        $parser->releaseWorkingLevel();
    }
}

class RpcStructHandler extends XmlTagHandler
{

    /**
     * @return string
     */
    function getName()
    {
        return 'struct';
    }

    /**
     * @param XoopsXmlRpcParser $parser
     * @param $attributes
     * @return void
     */
    function handleBeginElement(XoopsXmlRpcParser &$parser, &$attributes)
    {
        $parser->setWorkingLevel();
        $parser->resetTempStruct();
    }

    /**
     * @param XoopsXmlRpcParser $parser
     * @return void
     */
    function handleEndElement(XoopsXmlRpcParser &$parser)
    {
        $parser->setTempValue($parser->getTempStruct());
        $parser->releaseWorkingLevel();
    }
}