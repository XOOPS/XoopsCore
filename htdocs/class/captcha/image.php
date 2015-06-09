<?php
/*
 You may not change or alter any portion of this comment or credits
 of supporting developers from this source code or any supporting source code
 which is considered copyrighted (c) material of the original comment or credit authors.

 This program is distributed in the hope that it will be useful,
 but WITHOUT ANY WARRANTY; without even the implied warranty of
 MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
*/

/**
 * CAPTCHA for Image mode
 *
 * Based on DuGris' SecurityImage
 *
 * PHP 5.3
 *
 * @category  Xoops\Class\Captcha\CaptchaMethod
 * @package   CaptchaMethod
 * @author    Taiwen Jiang <phppp@users.sourceforge.net>
 * @copyright 2013 XOOPS Project (http://xoops.org)
 * @license   GNU GPL 2 or later (http://www.gnu.org/licenses/gpl-2.0.html)
 * @version   $Id$
 * @link      http://xoops.org
 * @since     2.6.0
 */
class XoopsCaptchaImage extends XoopsCaptchaMethod
{

    /**
     * XoopsCaptchaImage::isActive()
     *
     * @return bool
     */
    public function isActive()
    {
        if (!extension_loaded('gd')) {
            trigger_error('GD library is not loaded', E_USER_WARNING);
            return false;
        } else {
            $required_functions = array(
                'imagecreatetruecolor' ,
                'imagecolorallocate' ,
                'imagefilledrectangle' ,
                'imagejpeg' ,
                'imagedestroy' ,
                'imageftbbox');
            foreach ($required_functions as $func) {
                if (!function_exists($func)) {
                    trigger_error('Function ' . $func . ' is not defined', E_USER_WARNING);
                    return false;
                }
            }
        }
        return true;
    }

    /**
     * XoopsCaptchaImage::render()
     *
     * @return string
     */
    public function render()
    {
		$xoops_url = \XoopsBaseConfig::get('url');
        $js = "<script type='text/javascript'>
                function xoops_captcha_refresh(imgId)
                {
                    xoopsGetElementById(imgId).src = '" . $xoops_url . "/class/captcha/image/scripts/image.php?refresh='+Math.random();
                }
                </script>";
        $image = $this->loadImage();
        $image .= "<br /><a href=\"javascript: xoops_captcha_refresh('" . ($this->config['name']) . "')\">" . XoopsLocale::CLICK_TO_REFRESH_IMAGE_IF_NOT_CLEAR . "</a>";
        $input = '<input type="text" name="' . $this->config['name'] . '" id="' . $this->config['name'] . '" size="' . $this->config['num_chars'] . '" maxlength="' . $this->config['num_chars'] . '" value="" required>';
        $rule = XoopsLocale::INPUT_LETTERS_IN_THE_IMAGE;
        $rule .= '<br />' . (empty($this->config['casesensitive']) ? XoopsLocale::CODE_IS_CASE_INSENSITIVE : XoopsLocale::CODE_IS_CASE_SENSITIVE);
        if (!empty($this->config['maxattempts'])) {
            $rule .= '<br />' . sprintf(XoopsLocale::F_MAXIMUM_ATTEMPTS, $this->config['maxattempts']);
        }
        return $js . $image . '<br /><br />' . $input . '<br />' . $rule;
    }

    /**
     * XoopsCaptchaImage::loadImage()
     *
     * @return string
     */
    public function loadImage()
    {
		$xoops_url = \XoopsBaseConfig::get('url');
        return '<img id="' . ($this->config["name"]) . '" src="' . $xoops_url . '/class/captcha/image/scripts/image.php" onclick=\'this.src="' . $xoops_url . '/class/captcha/image/scripts/image.php?refresh="+Math.random()' . '\' style="cursor: pointer; vertical-align: middle;" alt="" />';
    }
}
