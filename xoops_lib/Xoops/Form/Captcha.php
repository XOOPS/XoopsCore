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
 *      $xoopsform->addElement(new \Xoops\Form\Captcha($caption, $name, $skipmember, $configs));
 * </code>
 *
 * For verification:
 * <code>
 *      $xoopsCaptcha = \XoopsCaptcha::getInstance();
 *      if (! $xoopsCaptcha->verify() ) {
 *          echo $xoopsCaptcha->getMessage();
 *          ...
 *      }
 * </code>
 *
 * @category  Xoops\Form\Captcha
 * @package   Xoops\Form
 * @author    Taiwen Jiang <phppp@users.sourceforge.net>
 * @copyright 2008-2015 XOOPS Project (http://xoops.org)
 * @license   GNU GPL 2 or later (http://www.gnu.org/licenses/gpl-2.0.html)
 * @link      http://xoops.org
 */
class Captcha extends Element
{
    /**
     * @var \XoopsCaptcha
     */
    public $captchaHandler;

    /**
     * __construct
     *
     * @param string|array $caption    Caption (default defined in captcha/language/) or array of all attributes
     * @param string       $name       Name for the input box
     * @param boolean      $skipmember Skip CAPTCHA check for members
     * @param array        $configs    key/value pairs
     */
    public function __construct($caption = '', $name = 'xoopscaptcha', $skipmember = true, $configs = array())
    {
        $this->captchaHandler = \XoopsCaptcha::getInstance();

        if (is_array($caption)) {
            parent::__construct($caption);
        } else {
            parent::__construct([]);
            $this->setIfNotEmpty('caption', $caption);
            $this->setIfNotEmpty('name', $name);
            $this->setIfNotSet(':skipmember', $skipmember);
            $this->setIfNotEmpty(':configs', $configs);
        }

        $this->setIfNotSet('caption', $this->captchaHandler->getCaption());
        $this->setIfNotSet('name', 'xoopscaptcha');

        $configs = $this->get(':configs', []);
        $configs['name'] = $this->get('name');
        $configs['skipmember'] = $this->get(':skipmember', true);
        $configs = $this->captchaHandler->loadConfig();

        $this->captchaHandler->setConfigs($configs);
        if (! $this->captchaHandler->isActive()) {
            $this->setHidden();
        }
    }

    /**
     * setConfig
     *
     * @param string $name name
     * @param mixed  $val  value
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
