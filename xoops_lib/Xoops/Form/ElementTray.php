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
 * ElementTray - a group of form elements
 *
 * @category  Xoops\Form\ElementTray
 * @package   Xoops\Form
 * @author    Kazumi Ono <onokazu@xoops.org>
 * @copyright 2001-2016 XOOPS Project (http://xoops.org)
 * @license   GNU GPL 2 or later (http://www.gnu.org/licenses/gpl-2.0.html)
 * @link      http://xoops.org
 */
class ElementTray extends Element implements ContainerInterface
{
    /**
     * array of form element objects
     *
     * @var Element[]
     */
    protected $elements = array();

    /**
     * __construct
     *
     * @param string|array $caption caption or array of all attributes
     *                               Control attributes:
     *                                   :joiner joiner for elements in tray
     * @param string       $joiner  joiner for elements in tray
     * @param string       $name    name
     */
    public function __construct($caption, $joiner = '&nbsp;', $name = '')
    {
        if (is_array($caption)) {
            parent::__construct($caption);
            $this->setIfNotSet(':joiner', '&nbsp;');
        } else {
            parent::__construct();
            $this->setName($name);
            $this->setCaption($caption);
            $this->set(':joiner', $joiner);
        }
    }

    /**
     * Are there are required elements?
     *
     * @return bool
     */
    public function isRequired()
    {
        foreach ($this->elements as $el) {
            if ($el->isRequired()) {
                return true;
            }
        }
        return false;
    }

    /**
     * Add an element to the tray
     *
     * @param Element $formElement Element to add
     * @param boolean $required    true = entry required
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

    // ContainerInterface
    /**
     * get an array of "required" form elements
     *
     * @return array array of Element objects
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
     * Get an array of the elements in this group
     *
     * @param bool $recurse get elements recursively?
     *
     * @return array Array of Element objects.
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
     * Get the delimiter of this group
     *
     * @param boolean $encode True to encode special characters
     *
     * @return string The delimiter
     */
    protected function getJoiner($encode = false)
    {
        $joiner = $this->get(':joiner');
        return $encode ? htmlspecialchars(str_replace('&nbsp;', ' ', $joiner)) : $joiner;
    }

    /**
     * prepare HTML to output this group
     *
     * @return string HTML output
     */
    public function render()
    {
        $count = 0;
        $ret = "<div class=\"form-inline\">";
        foreach ($this->getElements() as $ele) {
            /* @var Element $ele */
            if ($count > 0) {
                $ret .= $this->getJoiner();
            }
            if ($ele->getCaption() != '') {
                $ret .= '<div class="form-group">';
                $ret .= '<label class="control-label">' . $ele->getCaption() . "</label>&nbsp;";
                $ret .= '</div>';
            }
            $ret .= $ele->render() . "\n";
            if (!$ele->isHidden()) {
                ++$count;
            }
        }
        $ret .= '</div>';
        return $ret;
    }
}
