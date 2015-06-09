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
 * Abstract class for CAPTCHA method
 *
 * Currently there are two types of CAPTCHA forms, text and image
 * The default mode is "text", it can be changed in the priority:
 * 1 If mode is set through XoopsFormCaptcha::setConfig("mode", $mode), take it
 * 2 Elseif mode is set though captcha/config.php, take it
 * 3 Else, take "text"
 *
 * PHP 5.3
 *
 * @category  Xoops\Class\Captcha\CaptchaMethod
 * @package   CaptchaMethod
 * @author    Taiwen Jiang <phppp@users.sourceforge.net>
 * @copyright 2013 XOOPS Project (http://xoops.org)
 * @license   GNU GPL 2 or later (http://www.gnu.org/licenses/gpl-2.0.html)
 * @version   $Id$
 * @link      http://xoops.org
 * @since     2.6.0
 */
abstract class XoopsCaptchaMethod
{
    /**
     * @var XoopsCaptcha
     */
    public $handler;

    /**
     * @var array
     */
    public $config;

    /**
     * @var string
     */
    public $code;

    /**
     * XoopsCaptchaMethod::__construct()
     *
     * @param mixed $handler
     */
    public function __construct($handler = null)
    {
        $this->handler = $handler;
    }

    /**
     * XoopsCaptchaMethod::isActive()
     *
     * @return bool
     */
    public function isActive()
    {
        return true;
    }

    /**
     * XoopsCaptchaMethod::loadConfig()
     *
     * @param string $name
     *
     * @return array
     */
    public function loadConfig($name = '')
    {
        if (!is_object($this->handler))
            $this->config = array();
        else
            $this->config = empty($name)
                ? $this->handler->config
                : array_merge($this->handler->config, $this->handler->loadConfig($name));
    }

    /**
     * XoopsCaptchaMethod::getCode()
     *
     * @return string
     */
    public function getCode()
    {
        return (string)($this->code);
    }

    /**
     * XoopsCaptchaMethod::render()
     *
     * @return string
     */
    public function render()
    {
        return '';
    }

    /**
     * @return string
     */
    public function renderValidationJS()
    {
        return '';
    }

    /**
     * XoopsCaptchaMethod::verify()
     *
     * @param string $sessionName
     *
     * @return bool
     */
    public function verify($sessionName = null)
    {
        $is_valid = false;
        if (!empty($_SESSION["{$sessionName}_code"])) {
            $func = !empty($this->config['casesensitive']) ? 'strcmp' : 'strcasecmp';
            $is_valid = !$func(trim(@$_POST[$sessionName]), $_SESSION["{$sessionName}_code"]);
        }
        return $is_valid;
    }

    /**
     * @return bool
     */
    public function destroyGarbage()
    {
        return true;
    }

}
