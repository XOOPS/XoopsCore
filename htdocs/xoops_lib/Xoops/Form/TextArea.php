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
 * @copyright 2001-2014 The XOOPS Project http://sourceforge.net/projects/xoops/
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
    private $cols;

    /**
     * number of rows
     *
     * @var int
     */
    private $rows;

     /**
     * placeholder for this element
     *
     * @var string
     */
    private $placeholder;


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
        $this->setName($name);
        $this->rows = intval($rows);
        $this->cols = intval($cols);
        $this->setValue($value);
        $this->placeholder = $placeholder;
    }

    /**
     * get number of rows
     *
     * @return int
     */
    public function getRows()
    {
        return $this->rows;
    }

    /**
     * Get number of columns
     *
     * @return int
     */
    public function getCols()
    {
        return $this->cols;
    }

    /**
     * Get placeholder for this element
     *
     * @return string
     */
    public function getPlaceholder()
    {
        if (empty($this->placeholder)) {
            return '';
        }
        return $this->placeholder;
    }

    /**
     * prepare HTML for output
     *
     * @return string HTML
     */
    public function render()
    {
        $name = $this->getName();
        $class = ($this->getClass() != '' ? " class='" . $this->getClass() . "'" : '');
        if ($this->getCols() > $this->getMaxcols()) {
            $maxcols = 5;
        } else {
            $maxcols = $this->getCols();
        }
        $class = ($this->getClass() != ''
            ? " class='span" . $maxcols . " " . $this->getClass() . "'"
            : " class='span" . $maxcols . "'");
        $placeholder = ($this->getPlaceholder() != '' ? " placeholder='" . $this->getPlaceholder() . "'" : '');
        $extra = ($this->getExtra() != '' ? " " . $this->getExtra() : '');
        $required = ($this->isRequired() ? ' required' : '');
        return "<textarea name='" . $name . "' title='" . $this->getTitle() . "' id='"
            . $name . "'" . $class ." rows='" . $this->getRows() . "'" . $placeholder
            . $extra . $required . ">" . $this->getValue() . "</textarea>";
    }
}
