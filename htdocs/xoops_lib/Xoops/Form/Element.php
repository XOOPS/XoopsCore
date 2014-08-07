<?php
/*
 You may not change or alter any portion of this comment or credits
 of supporting developers from this source code or any supporting source code
 which is considered copyrighted (c) material of the original comment or credit authors.

 This program is distributed in the hope that it will be useful,
 but WITHOUT ANY WARRANTY; without even the implied warranty of
 MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
*/


namespace Xoops\Form;

/**
 * Element - Abstract base class for form elements
 *
 * @category  Xoops\Form\Element
 * @package   Xoops\Form
 * @author    trabis <lusopoemas@gmail.com>
 * @copyright 2012-2014 The XOOPS Project http://sourceforge.net/projects/xoops/
 * @license   GNU GPL 2 or later (http://www.gnu.org/licenses/gpl-2.0.html)
 * @link      http://xoops.org
 * @since     2.6.0
*/
abstract class Element
{
    /**
     * Javascript performing additional validation of this element data
     *
     * This property contains a list of Javascript snippets that will be sent to
     * \Xoops\Form\Form::renderValidationJS().
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
    private $name = '';

    /**
     * caption of the element
     *
     * @var string
     */
    private $caption = '';

    /**
     * Accesskey for this element
     *
     * @var string
     */
    private $accesskey = '';

    /**
     * HTML classes for this element
     *
     * @var array
     */
    private $class = array();

     /**
     * pattern for this element
     *
     * @var string
     * @access private
     */
    private $pattern;

    /**
     * pattern_description for this element
     *
     * @var string
     * @access private
     */
    private $pattern_description;

     /**
     * datalist for this element
     *
     * @var array
     * @access private
     */
    private $datalist;

    /**
     * hidden?
     *
     * @var bool
     */
    private $hidden = false;

    /**
     * extra attributes to go in the tag
     *
     * @var array
     */
    private $extra = array();

    /**
     * required field?
     *
     * @var bool
     */
    private $required = false;

    /**
     * description of the field
     *
     * @var string
     */
    private $description = '';

    /**
     * value of the field
     *
     * Default  var type is string but extending classes can overide the type
     * Example: protected $value = array();
     *
     * @var string|array
     */
    protected $value = '';

    /**
     * maximum columns for a field
     *
     * @var integer
     * @access private
     */
    private $maxcols = 12;

    /**
     * render - Generates output for the element.
     *
     * This method is abstract and must be overwritten by the child classes.
     *
     * @return    string
     */
    abstract public function render();

    /**
     * getValue - Get an array of pre-selected values
     *
     * @param boolean $encode True to encode special characters
     *
     * @return mixed
     */
    public function getValue($encode = false)
    {
        if (is_array($this->value)) {
            $ret = array();
            foreach ($this->value as $value) {
                $ret[] = $encode ? htmlspecialchars($value, ENT_QUOTES) : $value;
            }
            return $ret;
        }
        return $encode ? htmlspecialchars($this->value, ENT_QUOTES) : $this->value;
    }

    /**
     * setValue - Set pre-selected values
     *
     * @param mixed $value value to assign to this element
     *
     * @return void
     */
    public function setValue($value)
    {
        if (is_array($value)) {
            foreach ($value as $v) {
                $this->value[] = $v;
            }
        } elseif (is_array($this->value)) {
            $this->value[] = $value;
        } else {
            $this->value = $value;
        }
    }

    /**
     * setName - set the "name" attribute for the element
     *
     * @param string $name "name" attribute for the element
     *
     * @return void
     */
    public function setName($name)
    {
        $this->name = trim($name);
    }

    /**
     * getName - get the "name" attribute for the element
     *
     * @param boolean $encode True to encode special characters
     *
     * @return string
     */
    public function getName($encode = true)
    {
        if (false != $encode) {
            return str_replace('&amp;', '&', htmlspecialchars($this->name, ENT_QUOTES));
        }
        return $this->name;
    }

    /**
     * setAccessKey - set the accesskey attribute for the element
     *
     * @param string $key "accesskey" attribute for the element
     *
     * @return void
     */
    public function setAccessKey($key)
    {
        $this->accesskey = trim($key);
    }

    /**
     * getAccessKey - get the "accesskey" attribute for the element
     *
     * @return string "accesskey" attribute value
     */
    public function getAccessKey()
    {
        return $this->accesskey;
    }

    /**
     * getAccessString - If the accesskey is found in the specified string, underlines it
     *
     * @param string $str string to add accesskey highlight to
     *
     * @return string Enhanced string with the 1st occurence of accesskey underlined
     */
    public function getAccessString($str)
    {
        $access = $this->getAccessKey();
        if (!empty($access) && (false !== ($pos = strpos($str, $access)))) {
            return htmlspecialchars(substr($str, 0, $pos), ENT_QUOTES)
                . '<span style="text-decoration: underline;">'
                . htmlspecialchars(substr($str, $pos, 1), ENT_QUOTES) . '</span>'
                . htmlspecialchars(substr($str, $pos + 1), ENT_QUOTES);
        }
        return htmlspecialchars($str, ENT_QUOTES);
    }

    /**
     * setClass - set the "class" attribute for the element
     *
     * @param string $class "class" attribute for the element
     *
     * @return void
     */
    public function setClass($class)
    {
        if (is_array($class)) {
            if (!empty($class)) {
                $this->class = $class;
            }
        } else {
            $this->class[] = trim($class);
        }
    }

    /**
     * getClass - get the "class" attribute for the element
     *
     * @return string "class" attribute value
     */
    public function getClass()
    {
        if (empty($this->class)) {
            return false;
        }
        $classes = array();
        foreach ($this->class as $class) {
            $classes[] = htmlspecialchars($class, ENT_QUOTES);
        }
        return implode(' ', $classes);
    }

    /**
     * setPattern - set the "pattern" attribute for the element
     *
     * @param string $pattern             pattern attribute for the element
     * @param string $pattern_description pattern description
     *
     * @return void
     */
    public function setPattern($pattern, $pattern_description = '')
    {
         $this->pattern = trim($pattern);
         $this->pattern_description = trim($pattern_description);
    }

    /**
     * getPattern - get the "pattern" attribute for the element
     *
     * @return string "pattern"
     */
    public function getPattern()
    {
        if (empty($this->pattern)) {
            return '';
        }
        return $this->pattern;
    }

    /**
     * getPatternDescription - get the "pattern_description"
     *
     * @return string "pattern_description"
     */
    public function getPatternDescription()
    {
        if (empty($this->pattern_description)) {
            return '';
        }
        return $this->pattern_description;
    }

    /**
     * setDatalist - set the datalist attribute for the element
     *
     * @param string $datalist datalist attribute for the element
     *
     * @return void
     */

    public function setDatalist($datalist)
    {
        if (is_array($datalist)) {
            if (!empty($datalist)) {
                $this->datalist = $datalist;
            }
        } else {
            $this->datalist[] = trim($datalist);
        }
    }

    /**
     * getDatalist - get the datalist attribute for the element
     *
     * @return string "datalist" attribute value
     */
    public function getDatalist()
    {
        if (empty($this->datalist)) {
            return '';
        }
        $ret = NWLINE . '<datalist id="list_' . $this->getName() . '">' . NWLINE;
        foreach ($this->datalist as $datalist) {
            $ret .= '<option value="' . htmlspecialchars($datalist, ENT_QUOTES) . '">' . NWLINE;
        }
        $ret .= '</datalist>' . NWLINE;
        return $ret;
    }

    /**
     * isDatalist - is there a datalist for the element?
     *
     * @return boolean true if element has a non-empty datalist
     */
    public function isDatalist()
    {
        if (empty($this->datalist)) {
            return false;
        }
        return true;
    }

    /**
     * setCaption - set the caption for the element
     *
     * @param string $caption caption for element
     *
     * @return void
     */
    public function setCaption($caption)
    {
        $this->caption = trim($caption);
    }

    /**
     * getCaption - get the caption for the element
     *
     * @param boolean $encode True to encode special characters
     *
     * @return string
     */
    public function getCaption($encode = false)
    {
        return $encode ? htmlspecialchars($this->caption, ENT_QUOTES) : $this->caption;
    }

    /**
     * getTitle - get the title for the element
     *
     * @param boolean $encode True to encode special characters
     *
     * @return string
     */
    public function getTitle($encode = true)
    {
        if (strlen($this->pattern_description) > 0) {
            return $encode
                ? htmlspecialchars(strip_tags($this->caption . ' - ' . $this->pattern_description), ENT_QUOTES)
                : strip_tags($this->caption . ' - ' . $this->pattern_description);
        } else {
            return $encode ? htmlspecialchars(strip_tags($this->caption), ENT_QUOTES) : strip_tags($this->caption);
        }
    }

    /**
     * setDescription - set the element's description
     *
     * @param string $description description
     *
     * @return void
     */
    public function setDescription($description)
    {
        $this->description = trim($description);
    }

    /**
     * getDescription - get the element's description
     *
     * @param boolean $encode True to encode special characters
     *
     * @return string
     */
    public function getDescription($encode = false)
    {
        return $encode ? htmlspecialchars($this->description, ENT_QUOTES) : $this->description;
    }

    /**
     * setHidden - flag the element as "hidden"
     *
     * @return void
     */
    public function setHidden()
    {
        $this->hidden = true;
    }

    /**
     * isHidden - is this a hidden element?
     *
     * @return boolean true if hidden
     */
    public function isHidden()
    {
        return $this->hidden;
    }

    /**
     * setRequired - set entry required
     *
     * @param boolean $bool true to set required entry for this element
     *
     * @return void
     */
    public function setRequired($bool = true)
    {
        $this->required = $bool;
    }

    /**
     * isRequired - is entry required for this element?
     *
     * @return boolean true if entry is required
     */
    public function isRequired()
    {
        return $this->required;
    }

    /**
     * setExtra - Add extra attributes to the element.
     *
     * This string will be inserted verbatim and unvalidated in the
     * element's tag. Know what you are doing!
     *
     * @param string  $extra   extra raw text to insert into form
     * @param boolean $replace If true, passed string will replace current
     *                         content, otherwise it will be appended to it
     *
     * @return string[] New content of the extra string
     */
    public function setExtra($extra, $replace = false)
    {
        if ($replace) {
            $this->extra = array(trim($extra));
        } else {
            $this->extra[] = trim($extra);
        }
        return $this->extra;
    }

    /**
     * getExtra - Get the extra attributes for the element
     *
     * @param boolean $encode True to encode special characters
     *
     * @return string
     */
    public function getExtra($encode = false)
    {
        if (!$encode) {
            return implode(' ', $this->extra);
        }
        $value = array();
        foreach ($this->extra as $val) {
            $value[] = str_replace('>', '&gt;', str_replace('<', '&lt;', $val));
        }
        return empty($value) ? '' : ' ' . implode(' ', $value);
    }

    /**
     * renderValidationJS - Render custom javascript validation code
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
                $eltmsg = empty($eltcaption)
                    ? sprintf(\XoopsLocale::F_ENTER, $eltname)
                    : sprintf(\XoopsLocale::F_ENTER, $eltcaption);
                $eltmsg = str_replace(array(':', '?', '%'), '', $eltmsg);
                $eltmsg = str_replace('"', '\"', stripslashes($eltmsg));
                $eltmsg = strip_tags($eltmsg);
                return NWLINE
                    . "if ( myform.{$eltname}.value == \"\" ) { window.alert(\"{$eltmsg}\");"
                    . " myform.{$eltname}.focus(); return false; }\n";
            }
        }
        return false;
    }

    /**
     * getMaxcols - get the maximum columns for a field
     *
     * @param boolean $encode True to encode special characters
     *
     * @return integer
     */
    public function getMaxcols($encode = false)
    {
        return $encode ? htmlspecialchars($this->maxcols, ENT_QUOTES) : $this->maxcols;
    }
}
