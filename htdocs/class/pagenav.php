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
 * XOOPS page navigation
 *
 * @copyright   The XOOPS Project http://sourceforge.net/projects/xoops/
 * @license     GNU GPL 2 (http://www.gnu.org/licenses/old-licenses/gpl-2.0.html)
 * @package     class
 * @since       2.0.0
 * @author      Kazumi Ono (http://www.myweb.ne.jp/, http://jp.xoops.org/)
 * @version     $Id$
 */

defined('XOOPS_ROOT_PATH') or die('Restricted access');

class XoopsPageNav
{
    /**
     * *#@+
     *
     * @access private
     */
    /**
     * @var int
     */
    private $total;

    /**
     * @var int
     */
    private $perpage;

    /**
     * @var int
     */
    private $current;

    /**
     * @var string
     */
    private $extra;

    /**
     * @var string
     */
    private $url;

    /**
     * Constructor
     *
     * @param int    $total_items   Total number of items
     * @param int    $items_perpage Number of items per page
     * @param int    $current_start First item on the current page
     * @param string $start_name    Name for "start" or "offset"
     * @param string $extra_arg     Additional arguments to pass in the URL
     */
    public function __construct($total_items, $items_perpage, $current_start, $start_name = "start", $extra_arg = "")
    {
        $this->total = intval($total_items);
        $this->perpage = intval($items_perpage);
        $this->current = intval($current_start);
        $this->extra = $extra_arg;
        if ($extra_arg != '' && (substr($extra_arg, - 5) != '&amp;' || substr($extra_arg, - 1) != '&')) {
            $this->extra = '&amp;' . $extra_arg;
        }
        $this->url = $_SERVER['PHP_SELF'] . '?' . trim($start_name) . '=';
    }

    /**
     * Create text navigation
     *
     * @param integer $offset    offset
     * @param string  $size      of pagination (Value: 'large', '', 'small', 'mini')
     * @param string  $align     of pagination (Value: 'right', 'centered')
     * @param string  $prev_text text for previous
     * @param string  $next_text text for next
     *
     * @return string
     */
    public function renderNav($offset = 4, $size = "", $align = "right", $prev_text = "&laquo;", $next_text = "&raquo;")
    {
        $xoops = Xoops::getInstance();
        $ret = '';
        $nav = array();
        if ($this->total <= $this->perpage) {
            return $ret;
        }
        if (($this->total != 0) && ($this->perpage != 0)) {
            $total_pages = ceil($this->total / $this->perpage);
            if ($total_pages > 1) {
                $prev = $this->current - $this->perpage;
                if ($prev >= 0) {
                    $xoops->tpl()->assign('prev_text', $prev_text);
                    $xoops->tpl()->assign('prev_url', $this->url . $prev . $this->extra);
                }
                $last = 0;
                $last_text = '';
                $last_url = '';
                $first = 0;
                $first_text = '';
                $first_url = '';
                $counter = 1;
                $current_page = intval(floor(($this->current + $this->perpage) / $this->perpage));
                while ($counter <= $total_pages) {
                    if ($counter == $current_page) {
                        $nav['text'] = $counter;
                        $nav['url'] = '';
                        $nav['active'] = 0;
                    } elseif (($counter > $current_page - $offset && $counter < $current_page + $offset) || $counter == 1 || $counter == $total_pages) {
                        if ($counter == $total_pages && $current_page < $total_pages - $offset) {
                            $nav['text'] = '...';
                            $nav['url'] = '';
                            $nav['active'] = 0;
                            $last = 1;
                            $last_text = $counter;
                            $last_url = $this->url . (($counter - 1) * $this->perpage) . $this->extra;
                        } else {
                            $nav['text'] = $counter;
                            $nav['url'] = $this->url . (($counter - 1) * $this->perpage) . $this->extra;
                            $nav['active'] = 1;
                        }
                        if ($counter == 1 && $current_page > 1 + $offset) {
                            $nav['text'] = '...';
                            $nav['url'] = '';
                            $nav['active'] = 0;
                            $first = 1;
                            $first_text = $counter;
                            $first_url = $this->url . (($counter - 1) * $this->perpage) . $this->extra;
                        }
                    }
                    $xoops->tpl()->append_by_ref('xo_nav', $nav);
                    unset($nav);
                    $counter ++;
                }
                $xoops->tpl()->assign('last', $last);
                $xoops->tpl()->assign('last_text', $last_text);
                $xoops->tpl()->assign('last_url', $last_url);
                $xoops->tpl()->assign('first', $first);
                $xoops->tpl()->assign('first_text', $first_text);
                $xoops->tpl()->assign('first_url', $first_url);

                $next = $this->current + $this->perpage;
                if ($this->total > $next) {
                    $xoops->tpl()->assign('next_text', $next_text);
                    $xoops->tpl()->assign('next_url', $this->url . $next . $this->extra);
                }
            }
        }
        if ($size != '') {
            $size = ' pagination-' . $size;
        }
        $xoops->tpl()->assign('size', $size);
        $xoops->tpl()->assign('align', ' pagination-' . $align);
        $xoops->tpl()->assign('pagination_nav', true);
        $ret = $xoops->tpl()->fetch('module:system|system_pagenav.html');
        $xoops->tpl()->clear_assign('xo_nav');
        return $ret;
    }

    /**
     * Create a navigational dropdown list
     *
     * @param boolean $showbutton Show the "Go" button?
     *
     * @return string|false
     */
    public function renderSelect($align = "right", $showbutton = false)
    {
        $xoops = Xoops::getInstance();
        $ret = '';
        if ($this->total < $this->perpage) {
            return $ret;
        }
        $total_pages = ceil($this->total / $this->perpage);
        if ($total_pages > 1) {
            $counter = 1;
            $current_page = intval(floor(($this->current + $this->perpage) / $this->perpage));
            while ($counter <= $total_pages) {
                $select['text'] = $counter;
                $select['value'] = $this->url . (($counter - 1) * $this->perpage) . $this->extra;
                if ($counter == $current_page) {
                    $select['selected'] = 1;
                } else {
                    $select['selected'] = 0;
                }
                $xoops->tpl()->append_by_ref('xo_select', $select);
                unset($select);
                $counter ++;
            }
        }
        $xoops->tpl()->assign('onchange', "location=this.options[this.options.selectedIndex].value;");
        $xoops->tpl()->assign('pagination_select', true);
        $xoops->tpl()->assign('showbutton', $showbutton);
        $xoops->tpl()->assign('align', ' pagination-' . $align);
        $ret = $xoops->tpl()->fetch('module:system|system_pagenav.html');
        $xoops->tpl()->clear_assign('xo_select');
        return $ret;
    }

    /**
     * Create navigation with images
     *
     * @param integer $offset
     *
     * @return string
     */
    public function renderImageNav($offset = 4)
    {
        $xoops = Xoops::getInstance();
        $xoops->deprecated('renderImageNav() is deprecated since 2.6.0. Please use renderNav()');
        return $this->renderNav($offset);
    }
}
