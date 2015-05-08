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

defined('XOOPS_ROOT_PATH') or die('Restricted access');

class XcaptchaImage extends Xcaptcha
{
    public $config = array();

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
        $config = array();
        $_POST['num_chars'] = $system->CleanVars($_POST, 'num_chars', 6, 'int');
        $_POST['casesensitive'] = $system->CleanVars($_POST, 'casesensitive', false, 'boolean');
        $_POST['fontsize_min'] = $system->CleanVars($_POST, 'fontsize_min', 10, 'int');
        $_POST['fontsize_max'] = $system->CleanVars($_POST, 'fontsize_max', 24, 'int');
        $_POST['background_type'] = $system->CleanVars($_POST, 'background_type', 0, 'int');
        $_POST['background_num'] = $system->CleanVars($_POST, 'background_num', 50, 'int');
        $_POST['polygon_point'] = $system->CleanVars($_POST, 'polygon_point', 3, 'int');
        $_POST['skip_characters'] = $system->CleanVars($_POST, 'skip_characters', 'o|0|i|l|1', 'string');
        $_POST['skip_characters'] = explode('|', $_POST['skip_characters']);
        foreach (array_keys($this->config) as $key) {
            $config[$key] = $_POST[$key];
        }
        return $config;
    }
}
