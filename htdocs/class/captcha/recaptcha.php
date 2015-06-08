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
 * CAPTCHA for Recaptcha mode
 *
 * PHP 5.3
 *
 * @category  Xoops\Class\Captcha\CaptchaRecaptcha
 * @package   CaptchaRecaptcha
 * @author    trabis <lusopoemas@gmail.com>
 * @copyright 2013 XOOPS Project (http://xoops.org)
 * @license   GNU GPL 2 or later (http://www.gnu.org/licenses/gpl-2.0.html)
 * @version   $Id$
 * @link      http://xoops.org
 * @since     2.6.0
 */
class XoopsCaptchaRecaptcha extends XoopsCaptchaMethod
{
    /**
     * XoopsCaptchaRecaptcha::isActive()
     *
     * @return bool
     */
    public function isActive()
    {
        return true;
    }

    /**
     * XoopsCaptchaRecaptcha::render()
     *
     * @return string
     */
    public function render()
    {
        include_once __DIR__ . '/recaptcha/recaptchalib.php';
        $form = "<script type=\"text/javascript\">
            var RecaptchaOptions = {
            theme : '" . (empty($this->config['theme']) ? '' : $this->config['theme']) . "',
            lang : '" . (empty($this->config['lang']) ? '' : $this->config['lang']) . "'
            };
            </script>";
        $public_key = empty($this->config['public_key']) ? '' : $this->config['public_key'];
        $form .= recaptcha_get_html($public_key);
        return $form;
    }

    /**
     * XoopsCaptchaRecaptcha::verify()
     *
     * @param $sessionName name of session
     * @return bool
     */
    public function verify($sessionName = null)
    {
        $is_valid = false;
        include_once __DIR__ . '/recaptcha/recaptchalib.php';
        if (!empty($_POST['recaptcha_response_field'])) {
            $resp = recaptcha_check_answer($this->config['private_key'], $_SERVER['REMOTE_ADDR'],
				$_POST['recaptcha_challenge_field'], $_POST['recaptcha_response_field']);
            if (!$resp->is_valid) {
                $this->handler->message[] = $resp->error;
            } else {
                $is_valid = true;
            }
        }
        return $is_valid;
    }
}
