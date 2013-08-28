<?php
/**
 * Xcaptcha module
 *
 * You may not change or alter any portion of this comment or credits
 * of supporting developers from this source code or any supporting source code
 * which is considered copyrighted (c) material of the original comment or credit authors.
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
 *
 * @copyright       The XOOPS Project http://sourceforge.net/projects/xoops/
 * @license         GNU GPL 2 (http://www.gnu.org/licenses/old-licenses/gpl-2.0.html)
 * @package         Xcaptcha
 * @since           2.6.0
 * @author          Laurent JEN (Aka DuGris)
 * @version         $Id$
 */

defined('XOOPS_ROOT_PATH') or die('Restricted access');

class XcaptchaTextForm extends XoopsThemeForm
{
    /**
     * @param null $obj
     */
    public function __construct($object = null)
    {
        $this->object = $object;
        $this->config = $object->config;

        $xoops = Xoops::getInstance();

        parent::__construct('', 'xcaptchaform', $xoops->getEnv('PHP_SELF'), 'post', true, 'horizontal');

        $this->addElement(new XoopsFormText(_XCAPTCHA_NUM_CHARS, 'num_chars', 2, 2, $this->config['num_chars'] ), true);

        $this->addElement(new XoopsFormHidden('type', 'text' ));

        $button_tray = new XoopsFormElementTray('', '');
        $button_tray->addElement(new XoopsFormHidden('op', 'save'));
        $button_tray->addElement(new XoopsFormButton('', 'submit', XoopsLocale::A_SUBMIT, 'submit'));
        $button_tray->addElement(new XoopsFormButton('', 'reset', XoopsLocale::A_RESET, 'reset'));
        $cancel_send = new XoopsFormButton('', 'cancel', XoopsLocale::A_CANCEL, 'button');
        $cancel_send->setExtra("onclick='javascript:history.go(-1);'");
        $button_tray->addElement($cancel_send);

        $this->addElement($button_tray);
    }
}