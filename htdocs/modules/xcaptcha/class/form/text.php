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
 * @license         GNU GPL 2 or later (http://www.gnu.org/licenses/gpl-2.0.html)
 * @package         Xcaptcha
 * @since           2.6.0
 * @author          Laurent JEN (Aka DuGris)
 * @version         $Id$
 */

defined('XOOPS_INITIALIZED') or die('Restricted access');

class XcaptchaTextForm extends Xoops\Form\ThemeForm
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

        $this->addElement(new Xoops\Form\Text(_XCAPTCHA_NUM_CHARS, 'num_chars', 2, 2, $this->config['num_chars']), true);

        $this->addElement(new Xoops\Form\Hidden('type', 'text'));

        $button_tray = new Xoops\Form\ElementTray('', '');
        $button_tray->addElement(new Xoops\Form\Hidden('op', 'save'));
        $button_tray->addElement(new Xoops\Form\Button('', 'submit', XoopsLocale::A_SUBMIT, 'submit'));
        $button_tray->addElement(new Xoops\Form\Button('', 'reset', XoopsLocale::A_RESET, 'reset'));
        $cancel_send = new Xoops\Form\Button('', 'cancel', XoopsLocale::A_CANCEL, 'button');
        $cancel_send->setExtra("onclick='javascript:history.go(-1);'");
        $button_tray->addElement($cancel_send);

        $this->addElement($button_tray);
    }
}
