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
 * PrintContent implements a simple page layout suited to printing
 *
 * @category  Xmf\Template\Breadcrumb
 * @package   Xmf
 * @author    trabis <lusopoemas@gmail.com>
 * @author    The SmartFactory <www.smartfactory.ca>
 * @copyright 2011-2013 The XOOPS Project http://sourceforge.net/projects/xoops/
 * @license   GNU GPL 2 or later (http://www.gnu.org/licenses/old-licenses/gpl-2.0.html)
 * @version   Release: 1.0
 * @link      http://xoops.org
 * @since     1.0
 */
class PrintContent extends AbstractTemplate
{
    /**
     * @var string
     */
    private $_title = '';

    /**
     * @var string
     */
    private $_description = '';

    /**
     * @var string
     */
    private $_content = '';

    /**
     * @var bool
     */
    private $_pagetitle = false;

    /**
     * @var int
     */
    private $_width = 680;

    /**
     * init - called by parent::_construct
     * 
     * @return void
     */
    protected function init()
    {
        $this->setTemplate(XMF_ROOT_PATH . '/templates/xmf_print.html');
    }

    /**
     * Render the print template
     * 
     * @return void
     */
    protected function render()
    {
        $this->tpl->assign('xmf_print_pageTitle', $this->_pagetitle ? $this->_pagetitle : $this->_title);
        $this->tpl->assign('xmf_print_title', $this->_title);
        $this->tpl->assign('xmf_print_description', $this->_description);
        $this->tpl->assign('xmf_print_content', $this->_content);
        $this->tpl->assign('xmf_print_width', $this->_width);
    }

    /**
     * setContent
     * 
     * @param string $content page content
     * 
     * @return void
     */
    public function setContent($content)
    {
        $this->_content = $content;
    }

    /**
     * getContent
     * 
     * @return string page content
     */
    public function getContent()
    {
        return $this->_content;
    }

    /**
     * setDescription
     * 
     * @param string $description page description
     * 
     * @return void
     */
    public function setDescription($description)
    {
        $this->_description = $description;
    }

    /**
     * getDescription
     * 
     * @return string page description
     */
    public function getDescription()
    {
        return $this->_description;
    }

    /**
     * setPagetitle
     * 
     * @param boolean $pagetitle use page title
     * 
     * @return void
     */
    public function setPagetitle($pagetitle)
    {
        $this->_pagetitle = $pagetitle;
    }

    /**
     * getPagetitle
     * 
     * @return boolean use page title
     */
    public function getPagetitle()
    {
        return $this->_pagetitle;
    }

    /**
     * setTitle
     * 
     * @param string $title page title
     * 
     * @return void
     */
    public function setTitle($title)
    {
        $this->_title = $title;
    }

    /**
     * getTitle
     * 
     * @return string page title
     */
    public function getTitle()
    {
        return $this->_title;
    }

    /**
     * setWidth
     * 
     * @param int $width page width in pixels
     * 
     * @return void
     */
    public function setWidth($width)
    {
        $this->_width = $width;
    }

    /**
     * getWidth
     * 
     * @return int page width in pixels
     */
    public function getWidth()
    {
        return $this->_width;
    }

}
