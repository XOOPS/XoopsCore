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

use Xoops\Core\XoopsTpl;

/**
 * Form - Abstract Form
 *
 * @category  Xoops\Form\Form
 * @package   Xoops\Form
 * @author    Kazumi Ono <onokazu@xoops.org>
 * @author    Taiwen Jiang <phppp@users.sourceforge.net>
 * @copyright 2001-2015 XOOPS Project (http://xoops.org)
 * @license   GNU GPL 2 or later (http://www.gnu.org/licenses/gpl-2.0.html)
 * @link      http://xoops.org
 */
abstract class Form implements ContainerInterface
{
    /**
     * "action" attribute for the html form
     *
     * @var string
     */
    protected $action;

    /**
     * "method" attribute for the form.
     *
     * @var string
     */
    protected $method;

    /**
     * "name" attribute of the form
     *
     * @var string
     */
    protected $name;

    /**
     * title for the form
     *
     * @var string
     */
    protected $title;

    /**
     * display class for the form, i.e. horizontal, vertical, inline
     *
     * @var string
     */
    protected $display = '';

    /**
     * array of Element objects
     *
     * @var Element[]
     */
    protected $elements = array();

    /**
     * extra information for the <form> tag
     *
     * @var string[]
     */
    protected $extra = array();

    /**
     * required elements
     *
     * @var string[]
     */
    protected $required = array();

    /**
     * constructor
     *
     * @param string  $title    title of the form
     * @param string  $name     name attribute for the <form> tag
     * @param string  $action   action attribute for the <form> tag
     * @param string  $method   method attribute for the <form> tag
     * @param boolean $addtoken whether to add a security token to the form
     * @param string  $display  class for the form, i.e. horizontal, vertical, inline
     */
    public function __construct($title, $name, $action, $method = 'post', $addtoken = false, $display = '')
    {
        $this->title = $title;
        $this->name = $name;
        $this->action = $action;
        $this->method = $method;
        $this->display = $display;
        if ($addtoken != false) {
            $this->addElement(new Token());
        }
    }

    /**
     * getDisplay - return the summary of the form
     *
     * @param boolean $encode True to encode special characters
     *
     * @return string
     */
    public function getDisplay($encode = false)
    {
        return $encode ? htmlspecialchars($this->display, ENT_QUOTES) : $this->display;
    }

    /**
     * getTitle - return the title of the form
     *
     * @param bool $encode To sanitizer the text?
     *
     * @return string
     */
    public function getTitle($encode = false)
    {
        return $encode ? htmlspecialchars($this->title, ENT_QUOTES) : $this->title;
    }

    /**
     * setTitle
     *
     * @param string $title form title
     *
     * @return string
     */
    public function setTitle($title)
    {
        $this->title = $title;
    }

    /**
     * get the "name" attribute for the <form> tag
     *
     * Deprecated, to be refactored
     *
     * @param boolean $encode True to encode special characters
     *
     * @return string
     */
    public function getName($encode = true)
    {
        return $encode ? htmlspecialchars($this->name, ENT_QUOTES) : $this->name;
    }

    /**
     * setAction
     *
     * @param string $value URL of form action
     *
     * @return void
     */
    public function setAction($value = '')
    {
        $this->action = $value;
    }

    /**
     * getAction - get the "action" attribute for the <form> tag
     *
     * @param boolean $encode True to encode special characters
     *
     * @return string
     */
    public function getAction($encode = true)
    {
        // Convert &amp; to & for backward compatibility
        return $encode ? htmlspecialchars(str_replace('&amp;', '&', $this->action), ENT_QUOTES) : $this->action;
    }

    /**
     * getMethod - get the "method" attribute for the <form> tag
     *
     * @return string
     */
    public function getMethod()
    {
        return (strtolower($this->method) === 'get') ? 'get' : 'post';
    }

    /**
     * addElement - Add an element to the form
     *
     * @param Element $formElement Xoops\Form\Element to add
     * @param boolean $required    true if this is a required element
     *
     * @return void
     */
    public function addElement(Element $formElement, $required = false)
    {
        $this->elements[] = $formElement;
        if ($required) {
            $formElement->setRequired();
        }
    }

    /**
     * getElements - get an array of forms elements
     *
     * @param boolean $recurse true to get elements recursively
     *
     * @return Element[]
     */
    public function getElements($recurse = false)
    {
        if (!$recurse) {
            return $this->elements;
        } else {
            $ret = array();
            foreach ($this->elements as $ele) {
                if ($ele instanceof ContainerInterface) {
                    /* @var ContainerInterface $ele */
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
     * getElementNames - get an array of "name" attributes of form elements
     *
     * @return string[] of form element names
     */
    public function getElementNames()
    {
        $ret = array();
        $elements = $this->getElements(true);
        foreach ($elements as $ele) {
            /* @var Element $ele */
            $ret[] = $ele->getName();
            unset($ele);
        }
        return $ret;
    }

    /**
     * getElementByName - get a reference to a Xoops\Form\Element by its name
     *
     * @param string $name name attribute assigned to a Xoops\Form\Element
     *
     * @return null|Element
     */
    public function getElementByName($name)
    {
        $elements = $this->getElements(true);
        foreach ($elements as $ele) {
            /* @var Element $ele */
            if ($name == $ele->getName()) {
                return $ele;
            }
        }
        $ele = null;
        return $ele;
    }

    /**
     * setElementValue - Sets the "value" attribute of a form element
     *
     * @param string $name  the "name" attribute of a form element
     * @param string $value the "value" attribute of a form element
     *
     * @return void
     */
    public function setElementValue($name, $value)
    {
        $ele = $this->getElementByName($name);
        if (is_object($ele)) {
            $ele->setValue($value);
        }
    }

    /**
     * setElementValues - Sets the "value" attribute of form elements in a batch
     *
     * @param array $values array of name/value pairs to be assigned to form elements
     *
     * @return void
     */
    public function setElementValues($values)
    {
        if (is_array($values) && !empty($values)) {
            // will not use getElementByName() for performance..
            $elements = $this->getElements(true);
            foreach ($elements as $ele) {
                /* @var $ele Element */
                $name = $ele->getName();
                if ($name && isset($values[$name])) {
                    $ele->setValue($values[$name]);
                }
            }
        }
    }

    /**
     * getElementValue - Gets the value attribute of a form element
     *
     * @param string  $name   the name attribute of a form element
     * @param boolean $encode True to encode special characters
     *
     * @return string|null the value attribute assigned to a form element, null if not set
     */
    public function getElementValue($name, $encode = false)
    {
        $ele = $this->getElementByName($name);
        return $ele->getValue($encode);
    }

    /**
     * getElementValues - gets the value attribute of all form elements
     *
     * @param boolean $encode True to encode special characters
     *
     * @return array array of name/value pairs assigned to form elements
     */
    public function getElementValues($encode = false)
    {
        // will not use getElementByName() for performance..
        $elements = $this->getElements(true);
        $values = array();
        foreach ($elements as $ele) {
            /* @var Element $ele */
            $name = $ele->getName();
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
     *
     * @return void
     */
    public function setExtra($extra)
    {
        if (!empty($extra)) {
            $this->extra[] = $extra;
        }
    }

    /**
     * getExtra - get the extra attributes for the <form> tag
     *
     * @return string
     */
    public function getExtra()
    {
        $extra = empty($this->extra) ? '' : ' ' . implode(' ', $this->extra);
        return $extra;
    }

    /**
     * setRequired - mark an element as required entry
     *
     * @param Element $formElement Xoops\Form\Element to set as required entry
     *
     * @return void
     *
     * @deprecated set required attribute on element directly or when calling addElement
     */
    public function setRequired(Element $formElement)
    {
        $formElement->setRequired();
    }

    /**
     * getRequired - get an array of required form elements
     *
     * @return array array of Xoops\Form\Element
     */
    public function getRequired()
    {
        $required = [];
        foreach ($this->elements as $el) {
            if ($el->isRequired()) {
                $required[] = $el;
            }
        }
        return $required;
    }

    /**
     * render - returns rendered form
     *
     * This method is abstract. It must be overwritten in the child classes.
     *
     * @return string the rendered form
     */
    abstract public function render();

    /**
     * display - displays rendered form
     *
     * @return void
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
            /* @var Element $ele */
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
     * assign - assign to smarty form template instead of displaying directly
     *
     * @param \XoopsTpl $tpl template
     *
     * @return void
     */
    public function assign(XoopsTpl $tpl)
    {
        $i = -1;
        $elements = array();
        if (count($this->getRequired()) > 0) {
            $this->elements[] =
                new Raw("<tr class='foot'><td colspan='2'>* = " . \XoopsLocale::REQUIRED . "</td></tr>");
        }
        foreach ($this->getElements() as $ele) {
            ++$i;
            /* @var Element $ele */
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
