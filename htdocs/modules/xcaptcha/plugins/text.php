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
 * @copyright      2000-2020 XOOPS Project (https://xoops.org)
 * @license         GNU GPL 2 or later (http://www.gnu.org/licenses/gpl-2.0.html)
 * @package         xcaptcha
 * @since           2.6.0
 * @author          Laurent JEN (Aka DuGris)
 * @version         $Id$
 */
use Xmf\Request;

class XcaptchaText extends Xcaptcha
{
    public $config = [];

    public $plugin;

    public function __construct()
    {
        $this->xcaptcha_handler = Xcaptcha::getInstance();
        $this->config = $this->xcaptcha_handler->loadConfig('text');
        $this->plugin = 'text';
    }

    public function VerifyData()
    {
        $system = System::getInstance();
        $config = [];
        $_POST['num_chars'] = Request::getInt('num_chars', 6, 'POST');
        foreach (array_keys($this->config) as $key) {
            $config[$key] = $_POST[$key];
        }

        return $config;
    }
}
