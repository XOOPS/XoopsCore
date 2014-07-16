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
 * XOOPS form element
 *
 * @copyright       The XOOPS Project http://sourceforge.net/projects/xoops/
 * @license         GNU GPL 2 (http://www.gnu.org/licenses/old-licenses/gpl-2.0.html)
 * @package         class
 * @subpackage      xoopsform
 * @since           2.0.0
 * @version         $Id$
 */

defined('XOOPS_ROOT_PATH') or die('Restricted access');

/**
 * Abstract base class for form elements
 */
abstract class XoopsFormElement
{
    /**
     * Javascript performing additional validation of this element data
     *
     * This property contains a list of Javascript snippets that will be sent to
     * XoopsForm::renderValidationJS().
     * NB: All elements are added to the output one after the other, so don't forget
     * to add a ";" after each to ensure no Javascript syntax error is generated.
     *
     * @var array ()
     */
    public $customValidationCode = array();

    /**
     * "name" attribute of the element
     *
     * @var string
     */
    private $_name = '';

    /**
     * caption of the element
     *
     * @var string
     */
    private $_caption = '';

    /**
     * Accesskey for this element
     *
     * @var string
     */
    private $_accesskey = '';

    /**
     * HTML classes for this element
     *
     * @var array
     */
    private $_class = array();

     /**
     * pattern for this element
     *
     * @var string
     * @access private
     */
    private $_pattern;

    /**
     * pattern_description for this element
     *
     * @var string
     * @access private
     */
    private $_pattern_description;

     /**
     * datalist for this element
     *
     * @var array
     * @access private
     */
    private $_datalist;

    /**
     * hidden?
     *
     * @var bool
     */
    private $_hidden = false;

    /**
     * extra attributes to go in the tag
     *
     * @var array
     */
    private $_extra = array();

    /**
     * required field?
     *
     * @var bool
     */
    private $_required = false;

    /**
     * description of the field
     *
     * @var string
     */
    private $_description = '';

    /**
     * value of the field
     *
     * Default  var type is string but extending classes can overide the type
     * Example: protected $_value = array();
     *
     * @var string|array
     */
    protected $_value = '';

    /**
     * maximum columns for a field
     *
     * @var integer
     * @access private
     */
    private $_maxcols = 12;

    /**
     * Generates output for the element.
     *
     * This method is abstract and must be overwritten by the child classes.
     *
     * @abstract
     */
    abstract function render();

    /**
     * Get an array of pre-selected values
     *
     * @param bool $encode To sanitizer the text?
     * @return mixed
     */
    public function getValue($encode = false)
    {
        if (is_array($this->_value)) {
            $ret = array();
            foreach ($this->_value as $value) {
                $ret[] = $encode ? htmlspecialchars($value, ENT_QUOTES) : $value;
            }
            return $ret;
        }
        return $encode ? htmlspecialchars($this->_value, ENT_QUOTES) : $this->_value;
    }

    /**
     * Set pre-selected values
     *
     * @param mixed $value
     */
    public function setValue($value)
    {
        if (is_array($value)) {
            foreach($value as $v) {
                $this->_value[] = $v;
            }
        } elseif (is_array($this->_value)) {
            $this->_value[] = $value;
        } else {
            $this->_value = $value;
        }
    }

    /**
     * set the "name" attribute for the element
     *
     * @param string $name "name" attribute for the element
     */
    public function setName($name)
    {
        $this->_name = trim($name);
    }

    /**
     * get the "name" attribute for the element
     *
     * @param bool $encode
     * @return string
     */
    public function getName($encode = true)
    {
        if (false != $encode) {
            return str_replace('&amp;', '&', htmlspecialchars($this->_name, ENT_QUOTES));
        }
        return $this->_name;
    }

    /**
     * set the "accesskey" attribute for the element
     *
     * @param string $key "accesskey" attribute for the element
     * @return void
     */
    public function setAccessKey($key)
    {
        $this->_accesskey = trim($key);
    }

    /**
     * get the "accesskey" attribute for the element
     *
     * @return string "accesskey" attribute value
     */
    public function getAccessKey()
    {
        return $this->_accesskey;
    }

    /**
     * If the accesskey is found in the specified string, underlines it
     *
     * @param string $str String where to search the accesskey occurence
     * @return string Enhanced string with the 1st occurence of accesskey underlined
     */
    public function getAccessString($str)
    {
        $access = $this->getAccessKey();
        if (!empty($access) && (false !== ($pos = strpos($str, $access)))) {
            return htmlspecialchars(substr($str, 0, $pos), ENT_QUOTES) . '<span style="text-decoration: underline;">' . htmlspecialchars(substr($str, $pos, 1), ENT_QUOTES) . '</span>' . htmlspecialchars(substr($str, $pos + 1), ENT_QUOTES);
        }
        return htmlspecialchars($str, ENT_QUOTES);
    }

    /**
     * set the "class" attribute for the element
     *
     * @param string $class "class" attribute for the element
     *
     * @return void
     */
    public function setClass($class)
    {
        if (is_array($class)) {
            if (!empty($class)) {
                $this->_class = $class;
            }
        }else{
            $this->_class[] = trim($class);
        }
    }

    /**
     * get the "class" attribute for the element
     *
     * @return string "class" attribute value
     */
    public function getClass()
    {
        if (empty($this->_class)) {
            return false;
        }
        $classes = array();
        foreach ($this->_class as $class) {
            $classes[] = htmlspecialchars($class, ENT_QUOTES);
        }
        return implode(' ', $classes);
    }

    /**
     * set the "pattern" attribute for the element
     *
     * @param string $pattern             "pattern" attribute for the element
     * @param string $pattern_description pattern description
     *
     * @return void
     */
    public function setPattern($pattern, $pattern_description = '')
    {
         $this->_pattern = trim($pattern);
         $this->_pattern_description = trim($pattern_description);
    }

    /**
     * get the "pattern" attribute for the element
     *
     * @return string "pattern"
     */
    public function getPattern()
    {
        if (empty($this->_pattern)) {
            return '';
        }
        return $this->_pattern;
    }

    /**
     * get the "pattern_description"
     *
     * @return string "pattern_description"
     */
    public function getPatternDescription()
    {
        if (empty($this->_pattern_description)) {
            return '';
        }
        return $this->_pattern_description;
    }

    /**
     * set the "datalist" attribute for the element
     *
     * @param $datalist "datalist" attribute for the element
     * @return void
     */

    public function setDatalist($datalist)
    {
        if (is_array($datalist)) {
            if (!empty($datalist)) {
                $this->_datalist = $datalist;
            }
        } else {
            $this->_datalist[] = trim($datalist);
        }
    }

    /**
     * get the "datalist" attribute for the element
     *
     * @return string "datalist" attribute value
     */
    public function getDatalist()
    {
        if (empty($this->_datalist)) {
            return '';
        }
        $ret = NWLINE . '<datalist id="list_' . $this->getName() . '">' . NWLINE;
        foreach ($this->_datalist as $datalist) {
            $ret .= '<option value="' . htmlspecialchars($datalist, ENT_QUOTES) . '">' . NWLINE;
        }
        $ret .= '</datalist>' . NWLINE;
        return $ret;
    }

    /**
     * get the "datalist" attribute for the element
     *
     * @return bool "list"
     */
    public function isDatalist()
    {
        if (empty($this->_datalist)) {
            return false;
        }
        return true;
    }

    /**
     * set the caption for the element
     *
     * @param string $caption caption for element
     *
     * @return void
     */
    public function setCaption($caption)
    {
        $this->_caption = trim($caption);
    }

    /**
     * get the caption for the element
     *
     * @param bool $encode To sanitizer the text?
     *
     * @return string
     */
    public function getCaption($encode = false)
    {
        return $encode ? htmlspecialchars($this->_caption, ENT_QUOTES) : $this->_caption;
    }

    /**
     * get the caption for the element
     *
     * @param bool $encode To sanitizer the text?
     * @return string
     */
    public function getTitle($encode = true)
    {
        if (strlen($this->_pattern_description) > 0) {
            return $encode ? htmlspecialchars(strip_tags($this->_caption . ' - ' . $this->_pattern_description), ENT_QUOTES)
                : strip_tags($this->_caption . ' - ' . $this->_pattern_description);
        } else {
            return $encode ? htmlspecialchars(strip_tags($this->_caption), ENT_QUOTES) : strip_tags($this->_caption);
        }
    }

    /**
     * set the element's description
     *
     * @param string $description
     * @return void
     */
    public function setDescription($description)
    {
        $this->_description = trim($description);
    }

    /**
     * get the element's description
     *
     * @param bool $encode To sanitizer the text?
     * @return string
     */
    public function getDescription($encode = false)
    {
        return $encode ? htmlspecialchars($this->_description, ENT_QUOTES) : $this->_description;
    }

    /**
     * flag the element as "hidden"
     *
     * @return void
     */
    public function setHidden()
    {
        $this->_hidden = true;
    }

    /**
     * Find out if an element is "hidden".
     *
     * @return bool
     */
    public function isHidden()
    {
        return $this->_hidden;
    }
    /**
     * @param bool $bool
     * @return void
     */
    public function setRequired($bool = true)
    {
        $this->_required = $bool;
    }

    /**
     * Find out if an element is required.
     *
     * @return bool
     */
    public function isRequired()
    {
        return $this->_required;
    }

    /**
     * Add extra attributes to the element.
     *
     * This string will be inserted verbatim and unvalidated in the
     * element's tag. Know what you are doing!
     *
     * @param string  $extra extra raw text to insert into form
     * @param boolean $replace If true, passed string will replace current
     *                         content, otherwise it will be appended to it
     *
     * @return string[] New content of the extra string
     */
    public function setExtra($extra, $replace = false)
    {
        if ($replace) {
            $this->_extra = array(trim($extra));
        } else {
            $this->_extra[] = trim($extra);
        }
        return $this->_extra;
    }

    /**
     * Get the extra attributes for the element
     *
     * @param boolean $encode To sanitizer the text?
     *
     * @return string
     */
    public function getExtra($encode = false)
    {
        if (!$encode) {
            return implode(' ', $this->_extra);
        }
        $value = array();
        foreach ($this->_extra as $val) {
            $value[] = str_replace('>', '&gt;', str_replace('<', '&lt;', $val));
        }
        return empty($value) ? '' : ' ' . implode(' ', $value);
    }

    /**
     * Render custom javascript validation code
     *
     * @seealso XoopsForm::renderValidationJS
     *
     * @return string|false
     */
    public function renderValidationJS()
    {
        // render custom validation code if any
        if (!empty($this->customValidationCode)) {
            return implode(NWLINE, $this->customValidationCode);
            // generate validation code if required
        } else {
            if ($this->isRequired() && $eltname = $this->getName()) {
                // $eltname    = $this->getName();
                $eltcaption = $this->getCaption();
                $eltmsg = empty($eltcaption) ? sprintf(XoopsLocale::F_ENTER, $eltname) : sprintf(XoopsLocale::F_ENTER, $eltcaption);
                $eltmsg = str_replace(array(':', '?', '%'), '', $eltmsg);
                $eltmsg = str_replace('"', '\"', stripslashes($eltmsg));
                $eltmsg = strip_tags($eltmsg);
                return NWLINE . "if ( myform.{$eltname}.value == \"\" ) { window.alert(\"{$eltmsg}\"); myform.{$eltname}.focus(); return false; }\n";
            }
        }
        return false;
    }

    /**
     * get the maximum columns for a field
     *
     * @param boolean $encode To sanitizer the text?
     * @return integer
     */
    public function getMaxcols($encode = false)
    {
        return $encode ? htmlspecialchars($this->_maxcols, ENT_QUOTES) : $this->_maxcols;
    }
}
