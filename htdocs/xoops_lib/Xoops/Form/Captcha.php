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
 * Captcha - captcha form element
 *
 * For form creation:
 * Add form element where proper:
 * <code>
 *      $xoopsform->addElement(new XoopsFormCaptcha($caption, $name, $skipmember, $configs));
 * </code>
 *
 * For verification:
 * <code>
 *      $xoopsCaptcha = XoopsCaptcha::getInstance();
 *      if (! $xoopsCaptcha->verify() ) {
 *          echo $xoopsCaptcha->getMessage();
 *          ...
 *      }
 * </code>
 *
 * @category  Xoops\Form\Captcha
 * @package   Xoops\Form
 * @author    Taiwen Jiang <phppp@users.sourceforge.net>
 * @copyright 2008-2014 The XOOPS Project http://sourceforge.net/projects/xoops/
 * @license   GNU GPL 2 or later (http://www.gnu.org/licenses/gpl-2.0.html)
 * @link      http://xoops.org
 * @since     2.3.0
*/
class Captcha extends Element
{
    /**
     * @var XoopsCaptcha
     */
    public $captchaHandler;

    /**
     * __construct
     *
     * @param string  $caption    Caption of the form element, default value is defined in captcha/language/
     * @param string  $name       Name for the input box
     * @param boolean $skipmember Skip CAPTCHA check for members
     * @param array   $configs    key/value pairs
     */
    public function __construct($caption = '', $name = 'xoopscaptcha', $skipmember = true, $configs = array())
    {
        $this->captchaHandler = \XoopsCaptcha::getInstance();
        $configs['name'] = $name;
        $configs['skipmember'] = $skipmember;
        $configs = $this->captchaHandler->loadConfig();
        $this->captchaHandler->setConfigs($configs);
        if (! $this->captchaHandler->isActive()) {
            $this->setHidden();
        } else {
            $caption = ! empty($caption) ? $caption : $this->captchaHandler->getCaption();
            $this->setCaption($caption);
            $this->setName($name);
        }
    }

    /**
     * setConfig
     *
     * @param type $name name
     * @param type $val  value
     *
     * @return boolean
     */
    public function setConfig($name, $val)
    {
        return $this->captchaHandler->setConfig($name, $val);
    }

    /**
     * render
     *
     * @return string
     */
    public function render()
    {
        return $this->captchaHandler->render();
    }

    /**
     * renderValidationJS
     *
     * @return string
     */
    public function renderValidationJS()
    {
        return $this->captchaHandler->renderValidationJS();
    }
}
