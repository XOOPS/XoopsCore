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

abstract class XoopsXmlRpcDocument
{
    /**
     * @var array of XoopsXmlRpcTag
     */
    protected $_tags = array();

    /**
     * @param XoopsXmlRpcTag $tagobj
     * @return void
     */
    public function add(XoopsXmlRpcTag $tagobj)
    {
        $this->_tags[] = $tagobj;
    }

    abstract public function render();
}

class XoopsXmlRpcResponse extends XoopsXmlRpcDocument
{
    /**
     * @return string
     */
    public function render()
    {
        $payload = '';
        foreach ($this->_tags as $tag) {
            /* @var $tag XoopsXmlRpcTag */
            if (!$tag->isFault()) {
                $payload .= $tag->render();
            } else {
                return '<?xml version="1.0"?><methodResponse>' . $tag->render() . '</methodResponse>';
            }
        }
        return '<?xml version="1.0"?><methodResponse><params><param>' . $payload . '</param></params></methodResponse>';
    }
}

class XoopsXmlRpcRequest extends XoopsXmlRpcDocument
{

    /**
     * @var string
     */
    public $methodName;

    /**
     * @param string $methodName
     */
    public function __construct($methodName)
    {
        $this->methodName = trim($methodName);
    }

    public function render()
    {
        $payload = '';
        foreach ($this->_tags as $tag) {
            /* @var $tag XoopsXmlRpcTag */
            $payload .= '<param>' . $tag->render() . '</param>';
        }
        return '<?xml version="1.0"?><methodCall><methodName>' . $this->methodName . '</methodName><params>' . $payload . '</params></methodCall>';
    }
}

abstract class XoopsXmlRpcTag
{
    /**
     * @var bool
     */
    protected $_fault = false;

    /**
     * encode - make string HTML safe
     *
     * @param string $text string to encode
     *
     * @return string
     */
    public function encode($text)
    {
        return htmlspecialchars($text, ENT_XML1, 'UTF-8');
    }

    /**
     * @param bool $fault
     * @return void
     */
    public function setFault($fault = true)
    {
        $this->_fault = (bool)$fault;
    }

    /**
     * @return bool
     */
    public function isFault()
    {
        return $this->_fault;
    }

    /**
     * @abstract
     * @return void
     */
    abstract public function render();
}

class XoopsXmlRpcFault extends XoopsXmlRpcTag
{
    /**
     * @var int
     */
    protected $_code;

    /**
     * @var string
     */
    protected $_extra;

    /**
     * @param int $code
     * @param string $extra
     */
    public function __construct($code, $extra = null)
    {
        $this->setFault(true);
        $this->_code = (int)($code);
        $this->_extra = isset($extra) ? trim($extra) : '';
    }

    /**
     * @return string
     */
    public function render()
    {
        switch ($this->_code) {
            case 101:
                $string = 'Invalid server URI';
                break;
            case 102:
                $string = 'Parser parse error';
                break;
            case 103:
                $string = 'Module not found';
                break;
            case 104:
                $string = 'User authentication failed';
                break;
            case 105:
                $string = 'Module API not found';
                break;
            case 106:
                $string = 'Method response error';
                break;
            case 107:
                $string = 'Method not supported';
                break;
            case 108:
                $string = 'Invalid parameter';
                break;
            case 109:
                $string = 'Missing parameters';
                break;
            case 110:
                $string = 'Selected blog application does not exist';
                break;
            case 111:
                $string = 'Method permission denied';
                break;
            default:
                $string = 'Method response error';
                break;
        }
        $string .= "\n" . $this->_extra;
        return '<fault><value><struct><member><name>faultCode</name><value>' . $this->_code . '</value></member><member><name>faultString</name><value>' . $this->encode($string) . '</value></member></struct></value></fault>';
    }
}

class XoopsXmlRpcInt extends XoopsXmlRpcTag
{
    /**
     * @var int
     */
    protected $_value;

    /**
     * @param $value
     */
    public function __construct($value)
    {
        $this->_value = (int)($value);
    }

    /**
     * @return string
     */
    public function render()
    {
        return '<value><int>' . $this->_value . '</int></value>';
    }
}

class XoopsXmlRpcDouble extends XoopsXmlRpcTag
{
    /**
     * @var float
     */
    protected $_value;

    /**
     * @param float $value
     */
    public function __construct($value)
    {
        $this->_value = (float)$value;
    }

    /**
     * @return string
     */
    public function render()
    {
        return '<value><double>' . $this->_value . '</double></value>';
    }
}

class XoopsXmlRpcBoolean extends XoopsXmlRpcTag
{
    /**
     * @var int
     */
    protected $_value;

    /**
     * @param boolean $value
     */
    public function __construct($value)
    {
        $this->_value = (!empty($value) && $value != false) ? 1 : 0;
    }

    /**
     * @return string
     */
    public function render()
    {
        return '<value><boolean>' . $this->_value . '</boolean></value>';
    }
}

class XoopsXmlRpcString extends XoopsXmlRpcTag
{
    /**
     * @var string
     */
    protected $_value;

    /**
     * @param string $value
     */
    public function __construct($value)
    {
        $this->_value = (string)($value);
    }

    /**
     * @return string
     */
    public function render()
    {
        return '<value><string>' . $this->encode($this->_value) . '</string></value>';
    }
}

class XoopsXmlRpcDatetime extends XoopsXmlRpcTag
{
    /**
     * @var int
     */
    protected $_value;

    /**
     * @param int|string $value
     */
    public function __construct($value)
    {
        if (!is_numeric($value)) {
            $this->_value = strtotime($value);
        } else {
            $this->_value = (int)($value);
        }
    }

    /**
     * @return string
     */
    public function render()
    {
        return '<value><dateTime.iso8601>' . gmstrftime("%Y%m%dT%H:%M:%S", $this->_value) . '</dateTime.iso8601></value>';
    }
}

class XoopsXmlRpcBase64 extends XoopsXmlRpcTag
{
    /**
     * @var string
     */
    protected $_value;

    /**
     * @param string $value
     */
    public function __construct($value)
    {
        $this->_value = base64_encode($value);
    }

    /**
     * @return string
     */
    public function render()
    {
        return '<value><base64>' . $this->_value . '</base64></value>';
    }
}

class XoopsXmlRpcArray extends XoopsXmlRpcTag
{
    /**
     * @var array of XoopsXmlRpcTag
     */
    protected $_tags = array();

    /**
     * @param XoopsXmlRpcTag $tagobj
     * @return void
     */
    public function add(XoopsXmlRpcTag $tagobj)
    {
        $this->_tags[] = $tagobj;
    }

    /**
     * @return string
     */
    public function render()
    {
        $ret = '<value><array><data>';
        foreach ($this->_tags as $tag) {
            /* @var $tag XoopsXmlRpcTag */
            $ret .= $tag->render();
        }
        $ret .= '</data></array></value>';
        return $ret;
    }
}

class XoopsXmlRpcStruct extends XoopsXmlRpcTag
{
    /**
     * @var array of array containing XoopsXmlRpcTag
     */
    protected $_tags = array();

    /**
     * @param $name
     * @param XoopsXmlRpcTag $tagobj
     * @return void
     */
    public function add($name, XoopsXmlRpcTag $tagobj)
    {
        $this->_tags[] = array('name' => $name, 'value' => $tagobj);
    }

    public function render()
    {
        $ret = '<value><struct>';
        foreach ($this->_tags as $tag) {
            /* @var $tag['value'] XoopsXmlRplTag */
            $ret .= '<member><name>' . $this->encode($tag['name']) . '</name>' . $tag['value']->render() . '</member>';
        }
        $ret .= '</struct></value>';
        return $ret;
    }
}
