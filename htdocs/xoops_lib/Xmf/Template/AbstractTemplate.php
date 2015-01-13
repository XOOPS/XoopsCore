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
 * AbstractTemplate
 *
 * @category  Xmf\Template\AbstractTemplate
 * @package   Xmf
 * @author    trabis <lusopoemas@gmail.com>
 * @author    The SmartFactory <www.smartfactory.ca>
 * @copyright 2011-2013 The XOOPS Project http://sourceforge.net/projects/xoops/
 * @license   GNU GPL 2 or later (http://www.gnu.org/licenses/gpl-2.0.html)
 * @version   Release: 1.0
 * @link      http://xoops.org
 * @since     1.0
 */
abstract class AbstractTemplate
{
    /**
     * @var XoopsTpl
     */
    protected $tpl;

    /**
     * @var string
     */
    private $template;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->tpl = new \XoopsTpl();
        $this->template = "module:system/system_dummy.tpl";
        $this->init();
    }

    /**
     * Classes must implement this method instead of using constructors
     *
     * @return void
     */
    abstract protected function init();

    /**
     * Classes must implement this method for assigning content to $_tpl
     *
     * @return void
     */
    abstract protected function render();

    /**
     * Used in init methods to set the template used by $_tpl
     *
     * @param string $template Path to the template file
     *
     * @return void
     */
    protected function setTemplate($template = '')
    {
        $this->template = $template;
    }

    /**
     * Use this method to disable XoopsLogger
     *
     * @return void
     */
    protected function disableLogger()
    {
        \Xoops::getInstance()->logger()->quiet();
    }

    /**
     * Returns the rendered template
     *
     * @return bool|mixed|string
     */
    public function fetch()
    {
        $this->render();

        return $this->tpl->fetch($this->template);
    }

    /**
     * Echo/Display the rendered template
     *
     * @return void
     */
    public function display()
    {
        echo $this->fetch();
    }
}
