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

class XcaptchaRecaptchaForm extends Xoops\Form\ThemeForm
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

        $this->addElement(new Xoops\Form\TextArea(_XCAPTCHA_PRIVATE_KEY, 'private_key', $this->config['private_key'], 5, 50), true);
        $this->addElement(new Xoops\Form\Textarea(_XCAPTCHA_PUBLIC_KEY, 'public_key', $this->config['public_key'], 5, 50), true);

        $theme_form = new Xoops\Form\Select(_XCAPTCHA_THEME, 'theme', $this->config['theme'], $size = 4);
        $theme_form->addOptionArray($this->object->getThemes());
        $this->addElement($theme_form, false);

        $lang_form = new Xoops\Form\Select(_XCAPTCHA_LANG, 'lang', $this->config['lang'], $size = 4);
        $lang_form->addOptionArray($this->object->getLanguages());
        $this->addElement($lang_form, false);

        $this->addElement(new Xoops\Form\Hidden('type', 'recaptcha'));

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
