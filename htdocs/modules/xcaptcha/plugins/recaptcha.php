<?php

/**
 * Xcaptcha extension module
 * You may not change or alter any portion of this comment or credits
 * of supporting developers from this source code or any supporting source code
 * which is considered copyrighted (c) material of the original comment or credit authors.
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
 *
 * @copyright       XOOPS Project (http://xoops.org)
 * @license         GNU GPL 2 or later (http://www.gnu.org/licenses/gpl-2.0.html)
 * @package         xcaptcha
 * @since           2.6.0
 * @author          Laurent JEN (Aka DuGris)
 * @version         $Id$
 */
use Xmf\Request;

class XcaptchaRecaptcha extends Xcaptcha
{
    public $config = [];

    public $plugin;

    public function __construct()
    {
        $this->xcaptcha_handler = Xcaptcha::getInstance();
        $this->config = $this->xcaptcha_handler->loadConfig('recaptcha');
        $this->plugin = 'recaptcha';
    }

    public function VerifyData()
    {
        $xoops = Xoops::getInstance();
        $default_lang = array_search(ucfirst($xoops->getConfig('language')), $this->getLanguages());
        $default_lang = (!$default_lang) ? 'en' : $default_lang;

        $system = System::getInstance();
        $config = [];
        $_POST['private_key'] = Request::getString('private_key', 'Your private key', 'POST');
        $_POST['public_key'] = Request::getString('public_key', 'Your public key', 'POST');
        $_POST['theme'] = Request::getString('theme', 'red', 'POST');
        $_POST['lang'] = Request::getString('lang', $default_lang, 'POST');
        foreach (array_keys($this->config) as $key) {
            $config[$key] = $_POST[$key];
        }

        return $config;
    }

    public function getThemes()
    {
        return [
            'red' => 'RED (default theme)',
            'white' => 'WHITE',
            'blackglass' => 'BLACKGLASS',
            'clean' => 'CLEAN', ];
    }

    public function getLanguages()
    {
        return [
            'en' => 'English',
            'nl' => 'Dutch',
            'fr' => 'French',
            'de' => 'German',
            'it' => 'Italian',
            'pt' => 'Portuguese',
            'ru' => 'Russian',
            'es' => 'Spanish',
            'tr' => 'Turkish', ];
    }
}
