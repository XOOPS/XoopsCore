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
     * @access protected
     * @var    array
     */
    protected $_param;

    /**
     * @access protected
     * @var    string
     */
    protected $_methodName;

    /**
     * @access protected
     * @var    array
     */
    protected $_tempName;

    /**
     * @access protected
     * @var    array
     */
    protected $_tempValue;

    /**
     * @access protected
     * @var    array
     */
    protected $_tempMember;

    /**
     * @access protected
     * @var    array
     */
    protected $_tempStruct;

    /**
     * @access protected
     * @var    array
     */
    protected $_tempArray;

    /**
     * @access protected
     * @var    array
     */
    protected $_workingLevel = array();


    /**
     * Constructor of the class
     *
     * @param $input
     * @return void
     */
    public function __construct(&$input)
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
    public function setTempName($name)
    {
        $this->_tempName[$this->getWorkingLevel()] = $name;
    }

    /**
     * @return string
     */
    public function getTempName()
    {
        return $this->_tempName[$this->getWorkingLevel()];
    }

    /**
     * @param mixed $value
     * @return void
     */
    public function setTempValue($value)
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
    public function getTempValue()
    {
        return $this->_tempValue;
    }

    /**
     * @return void
     */
    public function resetTempValue()
    {
        $this->_tempValue = null;
    }

    /**
     * @param string $name
     * @param mixed $value
     * @return void
     */
    public function setTempMember($name, $value)
    {
        $this->_tempMember[$this->getWorkingLevel()][$name] = $value;
    }

    /**
     * @return mixed
     */
    public function getTempMember()
    {
        return $this->_tempMember[$this->getWorkingLevel()];
    }

    /**
     * @return void
     */
    public function resetTempMember()
    {
        $this->_tempMember[$this->getWorkingLevel()] = array();
    }

    /**
     * @return void
     */
    public function setWorkingLevel()
    {
        array_push($this->_workingLevel, $this->getCurrentLevel());
    }

    /**
     * @return mixed
     */
    public function getWorkingLevel()
    {
        return (count($this->_workingLevel) > 0)
            ? $this->_workingLevel[count($this->_workingLevel) - 1]
            : null;
    }

    /**
     * @return void
     */
    public function releaseWorkingLevel()
    {
        array_pop($this->_workingLevel);
    }

    /**
     * @param array $member
     * @return void
     */
    public function setTempStruct(array $member)
    {
        $key = key($member);
        $this->_tempStruct[$this->getWorkingLevel()][$key] = $member[$key];
    }

    /**
     * @return
     */
    public function getTempStruct()
    {
        return $this->_tempStruct[$this->getWorkingLevel()];
    }

    /**
     * @return void
     */
    public function resetTempStruct()
    {
        $this->_tempStruct[$this->getWorkingLevel()] = array();
    }

    /**
     * @param mixed $value
     * @return void
     */
    public function setTempArray($value)
    {
        $this->_tempArray[$this->getWorkingLevel()][] = $value;
    }

    /**
     * @return
     */
    public function getTempArray()
    {
        return $this->_tempArray[$this->getWorkingLevel()];
    }

    /**
     * @return void
     */
    public function resetTempArray()
    {
        $this->_tempArray[$this->getWorkingLevel()] = array();
    }

    /**
     * @param string $methodName
     * @return void
     */
    public function setMethodName($methodName)
    {
        $this->_methodName = $methodName;
    }

    /**
     * @return string
     */
    public function getMethodName()
    {
        return $this->_methodName;
    }

    /**
     * @param mixed $value
     * @return void
     */
    public function setParam($value)
    {
        $this->_param[] = $value;
    }

    /**
     * @return array
     */
    public function getParam()
    {
        return $this->_param;
    }
}


class RpcMethodNameHandler extends XmlTagHandler
{

    /**
     * @return string
     */
    public function getName()
    {
        return 'methodName';
    }

    /**
     * @param SaxParser $parser
     * @param $data
     * @return void
     */
    public function handleCharacterData(SaxParser $parser, &$data)
    {
        if (!is_a($parser,'XoopsXmlRpcParser')) return;
        $parser->setMethodName($data);
    }
}

class RpcIntHandler extends XmlTagHandler
{

    /**
    * @return string[]
    */
    public function getName()
    {
        return array('int', 'i4');
    }

    /**
     * @param SaxParser $parser
     * @param $data
     * @return void
     */
    public function handleCharacterData(SaxParser $parser, &$data)
    {
        if (!is_a($parser,'XoopsXmlRpcParser')) return;
        $parser->setTempValue((int)($data));
    }
}

class RpcDoubleHandler extends XmlTagHandler
{

    /**
     * @return string
     */
    public function getName()
    {
        return 'double';
    }

    /**
     * @param SaxParser $parser
     * @param $data
     * @return void
     */
    public function handleCharacterData(SaxParser $parser, &$data)
    {
        if (!is_a($parser,'XoopsXmlRpcParser')) return;
        $data = (float)$data;
        $parser->setTempValue($data);
    }
}

class RpcBooleanHandler extends XmlTagHandler
{

    /**
     * @return string
     */
    public function getName()
    {
        return 'boolean';
    }

    /**
     * @param SaxParser $parser
     * @param $data
     * @return void
     */
    public function handleCharacterData(SaxParser $parser, &$data)
    {
        if (!is_a($parser,'XoopsXmlRpcParser')) return;
        $data = (boolean)$data;
        $parser->setTempValue($data);
    }
}

class RpcStringHandler extends XmlTagHandler
{

    /**
     * @return string
     */
    public function getName()
    {
        return 'string';
    }

    /**
     * @param SaxParser $parser
     * @param $data
     * @return void
     */
    public function handleCharacterData(SaxParser $parser, &$data)
    {
        if (!is_a($parser,'XoopsXmlRpcParser')) return;
        $parser->setTempValue((string)($data));
    }
}

class RpcDateTimeHandler extends XmlTagHandler
{

    /**
     * @return string
     */
    public function getName()
    {
        return 'dateTime.iso8601';
    }

    /**
     * @param SaxParser $parser
     * @param $data
     * @return void
     */
    public function handleCharacterData(SaxParser $parser, &$data)
    {
        if (!is_a($parser,'XoopsXmlRpcParser')) return;
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
    public function getName()
    {
        return 'base64';
    }

    /**
     * @param SaxParser $parser
     * @param $data
     * @return void
     */
    public function handleCharacterData(SaxParser $parser, &$data)
    {
        if (!is_a($parser,'XoopsXmlRpcParser')) return;
        $parser->setTempValue(base64_decode($data));
    }
}

class RpcNameHandler extends XmlTagHandler
{

    /**
     * @return string
     */
    public function getName()
    {
        return 'name';
    }

    /**
     * @param SaxParser $parser
     * @param $data
     * @return void
     */
    public function handleCharacterData(SaxParser $parser, &$data)
    {
        if (!is_a($parser,'XoopsXmlRpcParser')) return;
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
    public function getName()
    {
        return 'value';
    }

    /**
     * @param SaxParser $parser
     * @param $data
     * @return void
     */
    public function handleCharacterData(SaxParser $parser, &$data)
    {
        if (!is_a($parser,'XoopsXmlRpcParser')) return;
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
     * @param SaxParser $parser
     * @param $attributes
     * @return void
     */
    public function handleBeginElement(SaxParser $parser, &$attributes)
    {
        if (!is_a($parser,'XoopsXmlRpcParser')) return;
        //$parser->resetTempValue();
    }

    /**
     * @param SaxParser $parser
     * @return void
     */
    public function handleEndElement(SaxParser $parser)
    {
        if (!is_a($parser,'XoopsXmlRpcParser')) return;
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
    public function getName()
    {
        return 'member';
    }

    /**
     * @param SaxParser $parser
     * @param array $attributes
     * @return void
     */
    public function handleBeginElement(SaxParser $parser, &$attributes)
    {
        if (!is_a($parser,'XoopsXmlRpcParser')) return;
        $parser->setWorkingLevel();
        $parser->resetTempMember();
    }

    /**
     * @param SaxParser $parser
     * @return void
     */
    public function handleEndElement(SaxParser $parser)
    {
        if (!is_a($parser,'XoopsXmlRpcParser')) return;
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
    public function getName()
    {
        return 'array';
    }

    /**
    * @param SaxParser $parser
    * @param array $attributes
    * @return void
    */
    public function handleBeginElement(SaxParser $parser, &$attributes)
    {
        if (!is_a($parser,'XoopsXmlRpcParser')) return;
        $parser->setWorkingLevel();
        $parser->resetTempArray();
    }

    /**
     * @param SaxParser $parser
     * @return void
     */
    public function handleEndElement(SaxParser $parser)
    {
        if (!is_a($parser,'XoopsXmlRpcParser')) return;
        $parser->setTempValue($parser->getTempArray());
        $parser->releaseWorkingLevel();
    }
}

class RpcStructHandler extends XmlTagHandler
{

    /**
     * @return string
     */
    public function getName()
    {
        return 'struct';
    }

    /**
     * @param SaxParser $parser
     * @param array $attributes
     * @return void
     */
    public function handleBeginElement(SaxParser $parser, &$attributes)
    {
        if (!is_a($parser,'XoopsXmlRpcParser')) return;
        $parser->setWorkingLevel();
        $parser->resetTempStruct();
    }

    /**
     * @param SaxParser $parser
     * @return void
     */
    public function handleEndElement(SaxParser $parser)
    {
        if (!is_a($parser,'XoopsXmlRpcParser')) return;
        $parser->setTempValue($parser->getTempStruct());
        $parser->releaseWorkingLevel();
    }
}
