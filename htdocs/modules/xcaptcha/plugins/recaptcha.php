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
 * @copyright       The XOOPS Project http://sourceforge.net/projects/xoops/
 * @license         GNU GPL 2 or later (http://www.gnu.org/licenses/gpl-2.0.html)
 * @package         xcaptcha
 * @since           2.6.0
 * @author          Laurent JEN (Aka DuGris)
 * @version         $Id$
 */

defined('XOOPS_INITIALIZED') or die('Restricted access');

class XcaptchaRecaptcha extends Xcaptcha
{
    public $config = array();

    public $plugin;

    function __construct()
    {
        $this->xcaptcha_handler = Xcaptcha::getInstance();
        $this->config = $this->xcaptcha_handler->loadConfig('recaptcha');
        $this->plugin = 'recaptcha';
    }

    function VerifyData()
    {
        $xoops = Xoops::getInstance();
        $default_lang = array_search(ucfirst($xoops->getConfig('language')), $this->getLanguages());
        $default_lang = (!$default_lang) ? 'en' : $default_lang;

        $system = System::getInstance();
        $config = array();
        $_POST['private_key'] = $system->CleanVars($_POST, 'private_key', 'Your private key', 'string');
        $_POST['public_key'] = $system->CleanVars($_POST, 'public_key', 'Your public key', 'string');
        $_POST['theme'] = $system->CleanVars($_POST, 'theme', 'red', 'string');
        $_POST['lang'] = $system->CleanVars($_POST, 'lang', $default_lang, 'string');
        foreach (array_keys($this->config) as $key) {
            $config[$key] = $_POST[$key];
        }
        return $config;
    }

    function getThemes()
    {
        return array(
            'red' => 'RED (default theme)', 'white' => 'WHITE', 'blackglass' => 'BLACKGLASS', 'clean' => 'CLEAN',
        );
    }

    function getLanguages()
    {
        return array(
            'en' => 'English', 'nl' => 'Dutch', 'fr' => 'French', 'de' => 'German', 'it' => 'Italian', 'pt' => 'Portuguese',
            'ru' => 'Russian', 'es' => 'Spanish', 'tr' => 'Turkish',
        );
    }
}
