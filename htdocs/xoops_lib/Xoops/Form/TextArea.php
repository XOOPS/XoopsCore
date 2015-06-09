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
 * TextArea - a text area element
 *
 * @category  Xoops\Form\TextArea
 * @package   Xoops\Form
 * @author    Kazumi Ono (AKA onokazu) http://www.myweb.ne.jp/, http://jp.xoops.org/
 * @copyright 2001-2014 XOOPS Project (http://xoops.org)
 * @license   GNU GPL 2 or later (http://www.gnu.org/licenses/gpl-2.0.html)
 * @link      http://xoops.org
 * @since     2.0.0
 */
class TextArea extends Element
{
    /**
     * number of columns
     *
     * @var int
     */
    //protected $cols;

    /**
     * number of rows
     *
     * @var int
     */
    //protected $rows;

     /**
     * placeholder for this element
     *
     * @var string
     */
    //private $placeholder;


    /**
     * Constructor
     *
     * @param string  $caption     caption
     * @param string  $name        name
     * @param string  $value       initial content
     * @param integer $rows        number of rows
     * @param integer $cols        number of columns
     * @param string  $placeholder placeholder for this element.
     */
    public function __construct($caption, $name, $value = "", $rows = 5, $cols = 5, $placeholder = '')
    {
        $this->setCaption($caption);
        $this->setAttribute('name', $name);
        $this->setAttribute('rows', intval($rows));
        $this->setAttribute('cols', intval($cols));
        $this->setValue($value);
        $this->setAttribute('placeholder', $placeholder);

    }

    /**
     * get number of rows
     *
     * @return int
     */
    public function getRows()
    {
        return (int) $this->getAttribute('rows');
    }

    /**
     * Get number of columns
     *
     * @return int
     */
    public function getCols()
    {
        return (int) $this->getAttribute('cols');
    }

    /**
     * Get placeholder for this element
     *
     * @return string
     */
    public function getPlaceholder()
    {
        return (string) $this->getAttribute('placeholder');
    }

    /**
     * prepare HTML for output
     *
     * @return string HTML
     */
    public function render()
    {
        if ($this->getCols() > $this->getMaxcols()) {
            $maxcols = $this->getMaxcols();
        } else {
            $maxcols = $this->getCols();
        }
        $this->addAttribute('class', 'span' . $maxcols);

        $attributes = $this->renderAttributeString();
        return '<textarea ' . $attributes . ' ' . $this->getExtra() .' >'
            . $this->getValue() . '</textarea>';
    }
}
