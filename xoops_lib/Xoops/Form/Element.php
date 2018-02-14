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

use Xoops\Html\Attributes;

/**
 * Element - Abstract base class for form elements
 *
 * @category  Xoops\Form\Element
 * @package   Xoops\Form
 * @author    trabis <lusopoemas@gmail.com>
 * @copyright 2012-2016 XOOPS Project (http://xoops.org)
 * @license   GNU GPL 2 or later (http://www.gnu.org/licenses/gpl-2.0.html)
 * @link      http://xoops.org
 */
abstract class Element extends Attributes
{
    /**
     * @var string[] list of attributes to NOT render
     */
    protected $suppressList = ['caption', 'datalist', 'description', 'option'];

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
     * extra attributes to go in the tag
     *
     * @var array
     */
    protected $extra = array();

    /**
     * __construct
     *
     * @param array $attributes array of attribute name => value pairs
     *                           Control attributes:
     *                               ElementFactory::FORM_KEY optional form or tray to hold this element
     */
    public function __construct($attributes = array())
    {
        parent::__construct($attributes);
        if ($this->has(ElementFactory::FORM_KEY)
            && $this->get(ElementFactory::FORM_KEY) instanceof ContainerInterface) {
            $this->get(ElementFactory::FORM_KEY)->addElement($this);
        }
    }

    /**
     * render - Generates output for the element.
     *
     * This method is abstract and must be overwritten by the child classes.
     *
     * @return    string
     */
    abstract public function render();

    /**
     * render attributes as a string to include in HTML output
     *
     * @return string
     */
    public function renderAttributeString()
    {
        $this->suppressRender($this->suppressList);

        // title attribute needs to be generated if not already set
        if (!$this->has('title')) {
            $this->set('title', $this->getTitle());
        }
        // generate id from name if not already set
        if (!$this->has('id')) {
            $id = $this->get('name');
            if (substr($id, -2) === '[]') {
                $id = substr($id, 0, strlen($id)-2);
            }
            $this->set('id', $id);
        }
        return parent::renderAttributeString();
    }

    /**
     * getValue - Get an array of pre-selected values
     *
     * @param boolean $encode True to encode special characters
     *
     * @return mixed
     */
    public function getValue($encode = false)
    {
        $values = $this->get('value', '');
        if (is_array($values)) {
            $ret = array();
            foreach ($values as $value) {
                $ret[] = $encode ? htmlspecialchars($value, ENT_QUOTES) : $value;
            }
            return $ret;
        }
        return $encode ? htmlspecialchars($values, ENT_QUOTES) : $values;
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
        $this->set('value', $value);
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
        $this->set('name', $name);
    }

    /**
     * getName - get the "name" attribute for the element
     *
     * @return string
     */
    public function getName()
    {
        return (string) $this->get('name');
    }

    /**
     * setAccessKey - set the access key attribute for the element
     *
     * @param string $key "accesskey" attribute for the element
     *
     * @return void
     */
    public function setAccessKey($key)
    {
        $this->set('accesskey', $key);
    }

    /**
     * getAccessKey - get the access key attribute for the element
     *
     * @return string "accesskey" attribute value
     */
    public function getAccessKey()
    {
        return (string) $this->get('accesskey');
    }

    /**
     * getAccessString - If the access key is found in the specified string, underline it
     *
     * @param string $str string to add access key highlight to
     *
     * @return string Enhanced string with the 1st occurrence of "accesskey underlined
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
     * @param string $class class attribute for the element
     *
     * @return void
     */
    public function setClass($class)
    {
        $this->add('class', (string) $class);
    }

    /**
     * getClass - get the "class" attribute for the element
     *
     * @return string "class" attribute value
     */
    public function getClass()
    {
        $class = $this->get('class', false);
        if ($class === false) {
            return false;
        }
        return htmlspecialchars(implode(' ', $class), ENT_QUOTES);
    }

    /**
     * setPattern - set the "pattern" attribute for the element
     *
     * @param string $pattern            pattern attribute for the element
     * @param string $patternDescription pattern description
     *
     * @return void
     */
    public function setPattern($pattern, $patternDescription = '')
    {
        $this->set('pattern', $pattern);
        $this->set(':pattern_description', $patternDescription);
    }

    /**
     * getPattern - get the "pattern" attribute for the element
     *
     * @return string "pattern"
     */
    public function getPattern()
    {
        return (string) $this->get('pattern', '');
    }

    /**
     * getPatternDescription - get the "pattern_description"
     *
     * @return string "pattern_description"
     */
    public function getPatternDescription()
    {
        return (string) $this->get(':pattern_description', '');
    }

    /**
     * setDatalist - set the datalist attribute for the element
     *
     * @param string[]|string $datalist datalist attribute for the element
     *
     * @return void
     */
    public function setDatalist($datalist)
    {
        $this->add('datalist', $datalist);
    }

    /**
     * renderDatalist - get the datalist attribute for the element
     *
     * @return string "datalist" attribute value
     */
    public function renderDatalist()
    {
        if (!$this->isDatalist()) {
            return '';
        }
        $ret = "\n" . '<datalist id="list_' . $this->getName() . '">' . "\n";
        foreach ($this->get('datalist') as $datalist) {
            $ret .= '<option value="' . htmlspecialchars($datalist, ENT_QUOTES) . '">' . "\n";
        }
        $ret .= '</datalist>' . "\n";
        return $ret;
    }

    /**
     * isDatalist - is there a datalist for the element?
     *
     * @return boolean true if element has a non-empty datalist
     */
    public function isDatalist()
    {
        return $this->has('datalist');
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
        $this->set('caption', $caption);
    }

    /**
     * getCaption - get the caption for the element
     *
     * @return string
     */
    public function getCaption()
    {
        return $this->get('caption', '');
        //return htmlspecialchars($this->caption, ENT_QUOTES);
    }

    /**
     * setTitle - set the title for the element
     *
     * @param string $title title for element
     *
     * @return void
     */
    public function setTitle($title)
    {
        $this->set('title', $title);
    }

    /**
     * getTitle - get the title for the element
     *
     * @return string
     */
    public function getTitle()
    {
        if ($this->has('title')) {
            return $this->get('title');
        } else {
            if ($this->has(':pattern_description')) {
                return htmlspecialchars(
                    strip_tags($this->get('caption') . ' - ' . $this->get(':pattern_description')),
                    ENT_QUOTES
                );
            } else {
                return htmlspecialchars(strip_tags($this->get('caption')), ENT_QUOTES);
            }
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
        $this->set('description', $description);
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
        $description = $this->get('description', '');
        return $encode ? htmlspecialchars($description, ENT_QUOTES) : $description;
    }

    /**
     * setHidden - flag the element as "hidden"
     *
     * @return void
     */
    public function setHidden()
    {
        $this->set('hidden');
    }

    /**
     * isHidden - is this a hidden element?
     *
     * @return boolean true if hidden
     */
    public function isHidden()
    {
        return $this->has('hidden');
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
        if ($bool) {
            $this->set('required');
        }
    }

    /**
     * isRequired - is entry required for this element?
     *
     * @return boolean true if entry is required
     */
    public function isRequired()
    {
        return $this->has('required');
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
     *
     * @deprecated please use attributes for event scripting
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
     *
     * @see setExtra() this is going to disappear
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
        return empty($value) ? '' : implode(' ', $value);
    }

    /**
     * addCustomValidationCode - Add custom validation javascript
     *
     * This string will be inserted verbatim and unvalidated in the page.
     * Know what you are doing!
     *
     * @param string  $code    javascript code  to insert into form
     * @param boolean $replace If true, passed string will replace current code,
     *                          otherwise it will be appended to it
     *
     * @return void
     */
    public function addCustomValidationCode($code, $replace = false)
    {
        if ($replace) {
            $this->customValidationCode = [$code];
        } else {
            $this->customValidationCode[] = $code;
        }
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
            return implode("\n", $this->customValidationCode);
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
                return "\n"
                    . "if ( myform.{$eltname}.value == \"\" ) { window.alert(\"{$eltmsg}\");"
                    . " myform.{$eltname}.focus(); return false; }\n";
            }
        }
        return false;
    }

    /**
     * Test if a class that starts with the pattern string is set
     *
     * @param string $pattern 'starts with' to match
     *
     * @return integer|false false if no match, or index of matching class
     */
    public function hasClassLike($pattern)
    {
        $class = $this->get('class');
        if ($class) {
            $length = strlen($pattern);
            foreach ((array) $class as $i => $value) {
                if (0 === strncmp($value, $pattern, $length)) {
                    return $i;
                }
            }
        }
        return false;
    }

    /**
     * themeDecorateElement - add theme decoration to element
     *
     * @return void
     *
     * @todo this should ask the theme
     */
    public function themeDecorateElement()
    {
        $class = 'form-control';
        if ($this instanceof Button) {
            if (false !== $this->hasClassLike('btn')) {
                return;
            }
            $class = 'btn btn-default';
        } elseif (false !== $this->hasClassLike('form-') && false !== $this->hasClassLike('col-')) {
            return;
        } elseif ($this instanceof TextArea) {
            $class = 'form-control';
        } /**
        } elseif ($this instanceof OptionElement) {
            $class = 'col-md-3';
            $options = $this->get('option', []);
            foreach ($options as $value) {
                if (is_array($value)) { // optgroup
                    foreach ($value as $subvalue) {
                        if (strlen($subvalue) > 20) {
                            $class = 'col-md-4';
                            break 2;
                        }
                    }
                } elseif (strlen($value) > 20) {
                    $class = 'col-md-4';
                    break;
                }
            }
        } else {
            $size = $this->get('size', 0);
            if ($size < 20) {
//                $class = 'col-md-2';
//            } elseif ($size < 30) {
                $class = 'col-md-3';
            } else {
                $class = 'form-control';
            }
        }
     */
        $this->add('class', $class);
    }

    /**
     * Convenience method to assist with setting attributes when using BC Element syntax
     *
     * Set attribute $name to $value, replacing $value with $default if $value is empty, or if the
     * value is not one of the values specified in the (optional) $enum array
     *
     * @param string $name    attribute name
     * @param mixed  $value   attribute value
     * @param mixed  $default default value
     * @param array  $enum    optional list of valid values
     *
     * @return void
     */
    public function setWithDefaults($name, $value, $default = null, $enum = null)
    {
        if (empty($value)) {
            $value = $default;
        } elseif (null !== $enum && !in_array($value, $enum)) {
            $value = $default;
        }
        $this->set($name, $value);
    }

    /**
     * Convenience method to assist with setting attributes when using BC Element syntax
     *
     * Set attribute $name to $value, replacing $value with $default if $value is empty, or if the
     * value is not one of the values specified in the (optional) $enum array
     *
     * @param string $name  attribute name
     * @param mixed  $value attribute value
     *
     * @return void
     */
    public function setIfNotEmpty($name, $value)
    {
        // don't overwrite
        if (!$this->has($name) && !empty($value)) {
            $this->set($name, $value);
        }
    }

    /**
     * Convenience method to assist with setting attributes
     *
     * Set attribute $name to $value, replacing $value with $default if $value is empty, or if the
     * value is not one of the values specified in the (optional) $enum array
     *
     * @param string $name  attribute name
     * @param mixed  $value attribute value
     *
     * @return void
     */
    public function setIfNotSet($name, $value)
    {
        // don't overwrite
        if (!$this->has($name)) {
            $this->set($name, $value);
        }
    }
}
