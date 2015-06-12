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

class XcaptchaText extends Xcaptcha
{
    public $config = array();

    public $plugin;

    function __construct()
    {
        $this->xcaptcha_handler = Xcaptcha::getInstance();
        $this->config = $this->xcaptcha_handler->loadConfig('text');
        $this->plugin = 'text';
    }

    function VerifyData()
    {
        $system = System::getInstance();
        $config = array();
        $_POST['num_chars'] = $system->cleanVars($_POST, 'num_chars', 6, 'int');
        foreach (array_keys($this->config) as $key) {
            $config[$key] = $_POST[$key];
        }
        return $config;
    }
}
