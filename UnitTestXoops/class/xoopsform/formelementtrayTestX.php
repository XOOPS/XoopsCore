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
 * XOOPS form element of tray
 *
 * @copyright       The XOOPS Project http://sourceforge.net/projects/xoops/
 * @license         GNU GPL 2 (http://www.gnu.org/licenses/old-licenses/gpl-2.0.html)
 * @package         class
 * @subpackage      xoopsform
 * @since           2.0.0
 * @author          Kazumi Ono (AKA onokazu) http://www.myweb.ne.jp/, http://jp.xoops.org/
 * @version         $Id$
 */

defined('XOOPS_ROOT_PATH') or die('Restricted access');

/**
 * A group of form elements
 */
class XoopsFormElementTray extends XoopsFormElement implements XoopsFormContainer
{
    /**
     * array of form element objects
     *
     * @var array
     */
    protected $_elements = array();

    /**
     * required elements
     *
     * @var array
     */
    protected $_required = array();

    /**
     * HTML to separate the elements
     *
     * @var string
     */
    protected $_delimiter;

    /**
     * constructor
     *
     * @param $caption
     * @param string $delimiter
     * @param string $name
     */
    public function __construct($caption, $delimiter = "&nbsp;", $name = "")
    {
        $this->setName($name);
        $this->setCaption($caption);
        $this->_delimiter = $delimiter;
    }

    /**
     * Find out if there are required elements.
     *
     * @return bool
     */
    public function isRequired()
    {
        return !empty($this->_required);
    }

    /**
     * Add an element to the group
     *
     * @param XoopsFormElement $formElement
     * @param bool $required
     * @return void
     */
    function addElement(XoopsFormElement &$formElement, $required = false)
    {
        $this->_elements[] = $formElement;
        if ($formElement instanceof XoopsFormContainer) {
            /* @var $formElement XoopsFormContainer */
            $required_elements = $formElement->getRequired();
            $count = count($required_elements);
            for ($i = 0; $i < $count; $i++) {
                $this->_required[] = $required_elements[$i];
            }
        } else {
            if ($required) {
                $formElement->setRequired();
                $this->_required[] = $formElement;
            }
        }
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
     * Get an array of the elements in this group
     *
     * @param bool $recursively get elements recursively?
     * @return array Array of {@link XoopsFormElement} objects.
     */
    public function getElements($recursively = false)
    {
        if (!$recursively) {
            return $this->_elements;
        } else {
            $ret = array();
            foreach ($this->_elements as $ele) {
                if ($ele instanceof XoopsFormContainer) {
                    /* @var XoopsFormContainer $ele */
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
     * Get the delimiter of this group
     *
     * @param bool $encode To sanitizer the text?
     * @return string The delimiter
     */
    public function getDelimiter($encode = false)
    {
        return $encode ? htmlspecialchars(str_replace('&nbsp;', ' ', $this->_delimiter)) : $this->_delimiter;
    }

    /**
     * prepare HTML to output this group
     *
     * @return string HTML output
     */
    public function render()
    {
        $count = 0;
        $ret = "";
        foreach ($this->getElements() as $ele) {
            /* @var XoopsFormElement $ele */
            if ($count > 0) {
                $ret .= $this->getDelimiter();
            }
            if ($ele->getCaption() != '') {
                $ret .= $ele->getCaption() . "&nbsp;";
            }
            $ret .= $ele->render() . NWLINE;
            if (!$ele->isHidden()) {
                $count++;
            }
        }
        return $ret;
    }
}