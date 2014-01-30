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
 * @license   GNU GPL 2 or later (http://www.gnu.org/licenses/old-licenses/gpl-2.0.html)
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
    private $_template;

    /**
     * Constructor
     */
    public function __construct()
    {
        \Xmf\Loader::loadFile(XOOPS_ROOT_PATH . '/class/template.php');
        $this->tpl = new \XoopsTpl();
        $this->_template = "db:system_dummy.html";
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
        $this->_template = $template;
    }

    /**
     * Use this method to disable XoopsLogger
     *
     * @return void
     */
    protected function disableLogger()
    {
        error_reporting(0);
        if (is_object($GLOBALS['xoopsLogger'])) {
            $GLOBALS['xoopsLogger']->activated = false;
        }
    }

    /**
     * Returns the rendered template
     *
     * @return bool|mixed|string
     */
    public function fetch()
    {
        $this->render();

        return $this->tpl->fetch($this->_template);
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
