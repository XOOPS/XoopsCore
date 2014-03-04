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
 * XOOPS Form Class
 *
 * @copyright       The XOOPS Project http://sourceforge.net/projects/xoops/
 * @license         GNU GPL 2 (http://www.gnu.org/licenses/old-licenses/gpl-2.0.html)
 * @package         class
 * @subpackage      xoopsform
 * @since           2.0.0
 * @author          Kazumi Ono (AKA onokazu) http://www.myweb.ne.jp/, http://jp.xoops.org/
 * @author          Taiwen Jiang <phppp@users.sourceforge.net>
 * @version         $Id$
 */

defined('XOOPS_ROOT_PATH') or die('Restricted access');

abstract class XoopsForm implements XoopsFormContainer
{
    /**
     * "action" attribute for the html form
     *
     * @var string
     */
    private $_action;

    /**
     * "method" attribute for the form.
     *
     * @var string
     */
    private $_method;

    /**
     * "name" attribute of the form
     *
     * @var string
     */
    private $_name;

    /**
     * title for the form
     *
     * @var string
     */
    private $_title;

    /**
     * display for the form
     *
     * @var string
     */
    private $_display = '';

    /**
     * array of {@link XoopsFormElement} objects
     *
     * @var array
     */
    private $_elements = array();

    /**
     * extra information for the <form> tag
     *
     * @var array
     */
    private $_extra = array();

    /**
     * required elements
     *
     * @var array
     */
    private $_required = array();

    /**
     * @var string
     */
    private $_summary = '';

    /**
     * constructor
     *
     * @param string $title title of the form
     * @param string $name "name" attribute for the <form> tag
     * @param string $action "action" attribute for the <form> tag
     * @param string $method "method" attribute for the <form> tag
     * @param bool $addtoken whether to add a security token to the form
     * @param string $display
     */
    public function __construct($title, $name, $action, $method = 'post', $addtoken = false, $display = '')
    {
        $this->_title = $title;
        $this->_name = $name;
        $this->_action = $action;
        $this->_method = $method;
        $this->_display = $display;
        if ($addtoken != false) {
            $this->addElement(new XoopsFormHiddenToken());
        }
    }

    /**
     * return the summary of the form
     *
     * @param bool $encode To sanitizer the text?
     * @return string
     */
    public function getDisplay($encode = false)
    {
        return $encode ? htmlspecialchars($this->_display, ENT_QUOTES) : $this->_display;
    }

    /**
     * return the title of the form
     *
     * @param bool $encode To sanitizer the text?
     * @return string
     */
    public function getTitle($encode = false)
    {
        return $encode ? htmlspecialchars($this->_title, ENT_QUOTES) : $this->_title;
    }

    /**
     * @param string $title
     *
     * @return string
     */
    public function setTitle($title)
    {
       $this->_title = $title;
    }

    /**
     * get the "name" attribute for the <form> tag
     *
     * Deprecated, to be refactored
     *
     * @param bool $encode To sanitizer the text?
     * @return string
     */
    public function getName($encode = true)
    {
        return $encode ? htmlspecialchars($this->_name, ENT_QUOTES) : $this->_name;
    }

    /**
     * @param string $value
     * @return void
     */
    public function setAction($value = '')
    {
        $this->_action = $value;
    }

    /**
     * get the "action" attribute for the <form> tag
     *
     * @param bool $encode To sanitizer the text?
     * @return string
     */
    public function getAction($encode = true)
    {
        // Convert &amp; to & for backward compatibility
        return $encode ? htmlspecialchars(str_replace('&amp;', '&', $this->_action), ENT_QUOTES) : $this->_action;
    }

    /**
     * get the "method" attribute for the <form> tag
     *
     * @return string
     */
    public function getMethod()
    {
        return (strtolower($this->_method) == 'get') ? 'get' : 'post';
    }

    /**
     * Add an element to the form
     *
     * @param XoopsFormElement $formElement
     * @param bool $required is this a "required" element?
     * @return void
     */
    public function addElement(XoopsFormElement &$formElement, $required = false)
    {
        /* @var XoopsFormElement $formElement */
        $this->_elements[] = $formElement;
        if ($formElement instanceof XoopsFormContainer) {
            /* @var $formElement XoopsFormContainer */
            $required_elements = $formElement->getRequired();
            $count = count($required_elements);
            for ($i = 0; $i < $count; $i++) {
                $this->_required[] = $required_elements[$i];
            }
        } else {
            if ($required && !$formElement instanceof XoopsFormRaw) {
                $formElement->setRequired();
                $this->_required[] = $formElement;
            }
        }
    }

    /**
     * get an array of forms elements
     *
     * @param bool $recurse get elements recursively?
     * @return array array of {@link XoopsFormElement}s
     */
    public function getElements($recurse = false)
    {
        if (!$recurse) {
            return $this->_elements;
        } else {
            $ret = array();
            foreach ($this->_elements as $ele) {
                if ($ele instanceof XoopsFormContainer) {
                    /* @var $ele XoopsFormContainer */
                    $elements = $ele->getElements(true);
                    foreach ($elements as $ele2) {
                        $ret[] = $ele2;
                    }
                    unset($elements);
                    unset($ele2);
                } else {
                    $ret[] = $ele;
                }
                unset($ele);
            }
            return $ret;
        }
    }

    /**
     * get an array of "name" attributes of form elements
     *
     * @return array array of form element names
     */
    public function getElementNames()
    {
        $ret = array();
        $elements = $this->getElements(true);
        foreach ($elements as $ele) {
            /* @var $ele XoopsFormElement */
            $ret[] = $ele->getName();
            unset($ele);
        }
        return $ret;
    }

    /**
     * get a reference to a {@link XoopsFormElement} object by its "name"
     *
     * @param string $name "name" attribute assigned to a {@link XoopsFormElement}
     *
     * @return null|XoopsFormElement
     */
    public function getElementByName($name)
    {
        $elements = $this->getElements(true);
        foreach ($elements as $ele) {
            /* @var XoopsFormElement $ele */
            if ($name == $ele->getName(false)) {
                return $ele;
            }
        }
        $ele = null;
        return $ele;
    }

    /**
     * Sets the "value" attribute of a form element
     *
     * @param string $name the "name" attribute of a form element
     * @param string $value the "value" attribute of a form element
     */
    public function setElementValue($name, $value)
    {
        $ele = $this->getElementByName($name);
        $ele->setValue($value);
    }

    /**
     * Sets the "value" attribute of form elements in a batch
     *
     * @param array $values array of name/value pairs to be assigned to form elements
     */
    public function setElementValues($values)
    {
        if (is_array($values) && !empty($values)) {
            // will not use getElementByName() for performance..
            $elements = $this->getElements(true);
            foreach ($elements as $ele) {
                /* @var $ele XoopsFormElement */
                $name = $ele->getName(false);
                if ($name && isset($values[$name])) {
                    $ele->setValue($values[$name]);
                }
            }
        }
    }

    /**
     * Gets the "value" attribute of a form element
     *
     * @param string $name the "name" attribute of a form element
     * @param bool $encode To sanitizer the text?
     * @return string|null the "value" attribute assigned to a form element, null if not set
     */
    public function getElementValue($name, $encode = false)
    {
        $ele = $this->getElementByName($name);
        return $ele->getValue($encode);
    }

    /**
     * gets the "value" attribute of all form elements
     *
     * @param bool $encode To sanitizer the text?
     * @return array array of name/value pairs assigned to form elements
     */
    public function getElementValues($encode = false)
    {
        // will not use getElementByName() for performance..
        $elements = $this->getElements(true);
        $values = array();
        foreach ($elements as $ele) {
            /* @var XoopsFormElement $ele */
            $name = $ele->getName(false);
            if ($name) {
                $values[$name] = $ele->getValue($encode);
            }
        }
        return $values;
    }

    /**
     * set the extra attributes for the <form> tag
     *
     * @param string $extra extra attributes for the <form> tag
     */
    public function setExtra($extra)
    {
        if (!empty($extra)) {
            $this->_extra[] = $extra;
        }
    }

    /**
     * set the summary tag for the <form> tag
     *
     * @param $summary
     * @return void
     */
    public function setSummary($summary)
    {
        if (!empty($summary)) {
            $this->_summary = strip_tags($summary);
        }
    }

    /**
     * get the extra attributes for the <form> tag
     *
     * @return string
     */
    public function getExtra()
    {
        $extra = empty($this->_extra) ? '' : ' ' . implode(' ', $this->_extra);
        return $extra;
    }

    /**
     * make an element "required"
     *
     * @param XoopsFormElement $formElement
     * @return void
     */
    public function setRequired(XoopsFormElement &$formElement)
    {
        $this->_required[] = $formElement;
    }

    /**
     * get an array of "required" form elements
     *
     * @return array array of {@link XoopsFormElement}s
     */
    public function getRequired()
    {
        return $this->_required;
    }

    /**
     * returns renderered form
     *
     * This method is abstract. It must be overwritten in the child classes.
     *
     * @abstract
     */
    abstract function render();

    /**
     * displays rendered form
     */
    public function display()
    {
        echo $this->render();
    }

    /**
     * Renders the Javascript function needed for client-side for validation
     *
     * Form elements that have been declared "required" and not set will prevent the form from being
     * submitted. Additionally, each element class may provide its own "renderValidationJS" method
     * that is supposed to return custom validation code for the element.
     *
     * The element validation code can assume that the JS "myform" variable points to the form, and must
     * execute <i>return false</i> if validation fails.
     *
     * A basic element validation method may contain something like this:
     * <code>
     * function renderValidationJS() {
     *            $name = $this->getName();
     *            return "if ( myform.{$name}.value != 'valid' ) { " .
     *              "myform.{$name}.focus(); window.alert( '$name is invalid' ); return false;" .
     *              " }";
     * }
     * </code>
     *
     * @param boolean $withtags Include the < javascript > tags in the returned string
     *
     * @return string
     */
    public function renderValidationJS($withtags = true)
    {
        $js = '';
        if ($withtags) {
            $js .= "\n<!-- Start Form Validation JavaScript //-->\n<script type='text/javascript'>\n<!--//\n";
        }
        $formname = $this->getName();
        $js .= "function xoopsFormValidate_{$formname}() { var myform = window.document.{$formname}; ";
        $elements = $this->getElements(true);
        foreach ($elements as $ele) {
            /* @var XoopsFormElement $ele */
            $js .= $ele->renderValidationJS();
        }
        $js .= "return true;\n}\n";
        if ($withtags) {
            $js .= "//--></script>\n";
            $js .= "<!-- End Form Validation JavaScript //-->\n";
        }
        return $js;
    }

    /**
     * assign to smarty form template instead of displaying directly
     *
     * @param XoopsTpl $tpl
     * @return void
     */
    public function assign(XoopsTpl $tpl)
    {
        $i = -1;
        $elements = array();
        if (count($this->getRequired()) > 0) {
            $this->_elements[] = new XoopsFormRaw("<tr class='foot'><td colspan='2'>* = " . XoopsLocale::REQUIRED . "</td></tr>");
        }
        foreach ($this->getElements() as $ele) {
            ++$i;
            /* @var XoopsFormElement $ele */
            $ele_name = $ele->getName();
            $ele_description = $ele->getDescription();
            $n = $ele_name ? $ele_name : $i;
            $elements[$n]['name'] = $ele_name;
            $elements[$n]['caption'] = $ele->getCaption();
            $elements[$n]['body'] = $ele->render();
            $elements[$n]['hidden'] = $ele->isHidden();
            $elements[$n]['required'] = $ele->isRequired();
            if ($ele_description != '') {
                $elements[$n]['description'] = $ele_description;
            }
        }
        $js = $this->renderValidationJS();
        $tpl->assign($this->getName(), array(
                'title' => $this->getTitle(), 'name' => $this->getName(), 'action' => $this->getAction(),
                'method' => $this->getMethod(),
                'extra' => 'onsubmit="return xoopsFormValidate_' . $this->getName() . '();"' . $this->getExtra(),
                'javascript' => $js, 'elements' => $elements
            ));
    }
}