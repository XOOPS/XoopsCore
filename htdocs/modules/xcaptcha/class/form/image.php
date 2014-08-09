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

class XcaptchaImageForm extends Xoops\Form\ThemeForm
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

        $this->addElement(new Xoops\Form\RadioYesN0(_XCAPTCHA_CASESENSITIVE, 'casesensitive', $this->config['casesensitive']));

        $fontmin_form = new Xoops\Form\Select(_XCAPTCHA_FONTSIZE_MIN, 'fontsize_min', $this->config['fontsize_min']);
        for ($i = 10; $i <= 30; $i++) {
            $fontmin_form->addOption($i, $i);
        }
        $this->addElement($fontmin_form, false);

        $fontmax_form = new Xoops\Form\Select(_XCAPTCHA_FONTSIZE_MAX, 'fontsize_max', $this->config['fontsize_max']);
        for ($i = 10; $i <= 30; $i++) {
            $fontmax_form->addOption($i, $i);
        }
        $this->addElement($fontmax_form, false);

        $backtype_form = new Xoops\Form\Select(_XCAPTCHA_BACKGROUND_TYPE, 'background_type', $this->config['background_type'], $size = 7);
        $backtype_form->addOption(0, _XCAPTCHA_BACKGROUND_BAR);
        $backtype_form->addOption(1, _XCAPTCHA_BACKGROUND_CIRCLE);
        $backtype_form->addOption(2, _XCAPTCHA_BACKGROUND_LINE);
        $backtype_form->addOption(3, _XCAPTCHA_BACKGROUND_RECTANGLE);
        $backtype_form->addOption(4, _XCAPTCHA_BACKGROUND_ELLIPSE);
        $backtype_form->addOption(5, _XCAPTCHA_BACKGROUND_POLYGONE);
        $backtype_form->addOption(100, _XCAPTCHA_BACKGROUND_IMAGE);
        $this->addElement($backtype_form, false);

        $backnum_form = new Xoops\Form\Select(_XCAPTCHA_BACKGROUND_NUM, 'background_num', $this->config['background_num']);
        for ($i = 10; $i <= 100; $i = $i+10) {
            $backnum_form->addOption($i, $i);
        }
        $this->addElement($backnum_form, false);

        $polygon_point = new Xoops\Form\Select(_XCAPTCHA_POLYGON_POINT, 'polygon_point', $this->config['polygon_point']);
        for ($i = 3; $i <= 20; $i++) {
            $polygon_point->addOption($i, $i);
        }
        $this->addElement($polygon_point, false);

        $value = implode('|', $this->config['skip_characters']);
        $this->addElement(new Xoops\Form\TextArea(_XCAPTCHA_SKIP_CHARACTERS, 'skip_characters', $value, 5, 50), true);

        $this->addElement(new Xoops\Form\Hidden('type', 'image'));

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
