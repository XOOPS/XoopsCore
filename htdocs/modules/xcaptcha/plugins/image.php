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

class XcaptchaImage extends Xcaptcha
{
    public $config = [];

    public $plugin;

    public function __construct()
    {
        $this->xcaptcha_handler = Xcaptcha::getInstance();
        $this->config = $this->xcaptcha_handler->loadConfig('image');
        $this->plugin = 'image';
    }

    public function VerifyData()
    {
        $system = System::getInstance();
        $config = [];
        $_POST['num_chars'] = Request::getInt('num_chars', 6, 'POST');
        $_POST['casesensitive'] = Request::getBool('casesensitive', false, 'POST');
        $_POST['fontsize_min'] = Request::getInt('fontsize_min', 10, 'POST');
        $_POST['fontsize_max'] = Request::getInt('fontsize_max', 24, 'POST');
        $_POST['background_type'] = Request::getInt('background_type', 0, 'POST');
        $_POST['background_num'] = Request::getInt('background_num', 50, 'POST');
        $_POST['polygon_point'] = Request::getInt('polygon_point', 3, 'POST');
        $_POST['skip_characters'] = Request::getString('skip_characters', 'o|0|i|l|1', 'POST');
        $_POST['skip_characters'] = explode('|', $_POST['skip_characters']);
        foreach (array_keys($this->config) as $key) {
            $config[$key] = $_POST[$key];
        }

        return $config;
    }
}
