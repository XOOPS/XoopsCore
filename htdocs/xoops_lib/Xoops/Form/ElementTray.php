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
 * @author    Kazumi Ono (AKA onokazu) http://www.myweb.ne.jp/, http://jp.xoops.org/
 * @copyright 2001-2014 The XOOPS Project http://sourceforge.net/projects/xoops/
 * @license   GNU GPL 2 or later (http://www.gnu.org/licenses/gpl-2.0.html)
 * @link      http://xoops.org
 * @since     2.0.0
*/
class ElementTray extends Element implements ContainerInterface
{
    /**
     * array of form element objects
     *
     * @var array
     */
    protected $elements = array();

    /**
     * required elements
     *
     * @var array
     */
    protected $required = array();

    /**
     * HTML to separate the elements
     *
     * @var string
     */
    protected $delimiter;

    /**
     * __construct
     *
     * @param string $caption   caption
     * @param string $delimiter delimiter
     * @param string $name      name
     */
    public function __construct($caption, $delimiter = "&nbsp;", $name = "")
    {
        $this->setName($name);
        $this->setCaption($caption);
        $this->delimiter = $delimiter;
    }

    /**
     * Find out if there are required elements.
     *
     * @return bool
     */
    public function isRequired()
    {
        return !empty($this->required);
    }

    /**
     * Add an element to the group
     *
     * @param Element $formElement Element to add
     * @param boolean $required    true = entry required
     *
     * @return void
     */
    public function addElement(Element $formElement, $required = false)
    {
        $this->elements[] = $formElement;
        if ($formElement instanceof ContainerInterface) {
            /* @var $formElement ContainerInterface */
            $required_elements = $formElement->getRequired();
            $count = count($required_elements);
            for ($i = 0; $i < $count; $i++) {
                $this->required[] = $required_elements[$i];
            }
        } else {
            if ($required) {
                $formElement->setRequired();
                $this->required[] = $formElement;
            }
        }
    }

    /**
     * get an array of "required" form elements
     *
     * @return array array of Element objects
     */
    public function getRequired()
    {
        return $this->required;
    }

    /**
     * Get an array of the elements in this group
     *
     * @param bool $recursively get elements recursively?
     *
     * @return array Array of Element objects.
     */
    public function getElements($recursively = false)
    {
        if (!$recursively) {
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
    public function getDelimiter($encode = false)
    {
        return $encode ? htmlspecialchars(str_replace('&nbsp;', ' ', $this->delimiter)) : $this->delimiter;
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
            /* @var Element $ele */
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
