<?php
/*
 You may not change or alter any portion of this comment or credits
 of supporting developers from this source code or any supporting source code
 which is considered copyrighted (c) material of the original comment or credit authors.

 This program is distributed in the hope that it will be useful,
 but WITHOUT ANY WARRANTY; without even the implied warranty of
 MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
 */

namespace Xmf\Template;

/**
 * Buttonbox
 *
 * @category  Xmf\Template\Buttonbox
 * @package   Xmf
 * @author    Grégory Mage (Aka Mage)
 * @author    trabis <lusopoemas@gmail.com>
 * @copyright 2011-2013 The XOOPS Project http://sourceforge.net/projects/xoops/
 * @license   GNU GPL 2 or later (http://www.gnu.org/licenses/gpl-2.0.html)
 * @version   Release: 1.0
 * @link      http://xoops.org
 * @since     1.0
 */
class Buttonbox extends AbstractTemplate
{
    /**
     * @var array
     */
    private $_items = array();

    /**
     * @var string
     */
    private $_delimiter = "&nbsp;";

    /**
     * @var string
     */
    private $_position = "right";

    /**
     * @var string
     */
    private $_path = '';

    /**
     * init
     * 
     * @return void
     */
    protected function init()
    {
    }

    /**
     * set position - alignment position
     * 
     * @param string $position left, right, center
     * 
     * @return void
     */
    public function setPosition($position)
    {
        $this->_position = $position;
    }

    /**
     * set path - path to image files. Do not set if icons are 
     * specified with absoulte URLs
     * 
     * @param string $position left, right, center
     * 
     * @return void
     */
    public function setImagePath($path)
    {
        $this->_path = $path;
    }

    /**
     * setDelimiter
     * 
     * @param string $delimiter delimiter put between buttons
     * 
     * @return void
     */
    public function setDelimiter($delimiter)
    {
        $this->_delimiter = $delimiter;
    }

    /**
     * addItem to button box
     * 
     * @param string $title title string for button
     * @param string $link  link for button
     * @param string $icon  icon for button
     * @param string $extra extra
     * 
     * @return void
     */
    public function addItem($title, $link, $icon, $extra = '')
    {
        $item['title'] = $title;
        $item['link'] = $link;
        $item['icon'] = $icon;
        $item['extra'] = $extra;
        $this->_items[] = $item;
    }

    /**
     * render the buttonbox
     * 
     * @return void
     */
    protected function render()
    {
        $ret = '';
        $path = $this->_path;
        switch ($this->_position) {
        default:
        case "right":
            $ret = "<div class=\"floatright\">\n";
            break;

        case "left":
            $ret = "<div class=\"floatleft\">\n";
            break;

        case "center":
            $ret = "<div class=\"aligncenter\">\n";
        }
        $ret .= "<div class=\"xo-buttons\">\n";
        foreach ($this->_items as $item) {
            $ret .= "<a class='ui-corner-all tooltip' href='" . $item['link'] . "' title='" . $item['title'] . "'>";
            $ret .= "<img src='" . $path . $item['icon'] . "' title='" . $item['title'] . "' />" . $item['title'] . ' ' . $item['extra'];
            $ret .= "</a>\n";
            $ret .= $this->_delimiter;
        }
        $ret .= "</div>\n</div>\n";
        $this->tpl->assign('dummy_content', $ret);
    }
}
