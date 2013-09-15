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
 * Breadcrumb
 *
 * @category  Xmf\Template\Breadcrumb
 * @package   Xmf
 * @author    trabis <lusopoemas@gmail.com>
 * @author    The SmartFactory <www.smartfactory.ca>
 * @copyright 2011-2013 The XOOPS Project http://sourceforge.net/projects/xoops/
 * @license   GNU GPL 2 or later (http://www.gnu.org/licenses/gpl-2.0.html)
 * @version   Release: 1.0
 * @link      http://xoops.org
 * @since     1.0
 */
class Breadcrumb extends AbstractTemplate
{
    /**
     * @var array
     */
    private $_items = array();

    /**
     * initialization run by parent::__construct
     *
     * @return void
     */
    protected function init()
    {
        $this->setTemplate(XMF_ROOT_PATH . '/templates/xmf_breadcrumb.html');
    }

    /**
     * Set the items to be shown. Items are specified as an array of
     * breadcrumb items. Each breadcrumb item is an array of:
     *  - 'caption' => ready to display string item,
     *  - 'link' => url (omit to disable link on this item)
     *
     * @param array $items array of breadcrumb items
     *
     * @return void
     */
    public function setItems($items)
    {
        $this->_items = $items;
    }

    /**
     * Assigning content to template
     *
     * @return void
     */
    protected function render()
    {
        $this->tpl->assign('xmf_breadcrumb_items', $this->_items);
    }
}
