<?php
/*
 You may not change or alter any portion of this comment or credits
 of supporting developers from this source code or any supporting source code
 which is considered copyrighted (c) material of the original comment or credit authors.

 This program is distributed in the hope that it will be useful,
 but WITHOUT ANY WARRANTY; without even the implied warranty of
 MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
*/

use Xoops\Core\Service\AbstractContract;
use Xoops\Core\Service\Contract\AvatarInterface;

/**
 * Gravatars provider for service manager
 *
 * @category  class
 * @package   GravatarsProvider
 * @author    Richard Griffith <richard@geekwright.com>
 * @copyright 2013-2014 The XOOPS Project http://sourceforge.net/projects/xoops/
 * @license   GNU GPL 2 or later (http://www.gnu.org/licenses/old-licenses/gpl-2.0.html)
 * @link      http://xoops.org
 * @since     2.6.0
 */
class GravatarsProvider extends AbstractContract implements AvatarInterface
{
    /**
     * Get a Gravatar URL for a specified email address.
     *
     * @param string $email The email address
     *
     * @return String containing either just a URL or a complete image tag
     *
     * @source http://gravatar.com/site/implement/images/php/
     */
    private static function getGravatar($email)
    {
        $s = 80;   // Size in pixels, defaults to 80px [ 1 - 2048 ]
        $d = 'mm'; // Default imageset to use [ 404 | mm | identicon | monsterid | wavatar ]
        $r = 'g';  // Maximum rating (inclusive) [ g | pg | r | x ]
        if ($helper = Xoops\Module\Helper::getHelper('gravatars')) {
            $v = $helper->getConfig('pixel_size');
            $s = (empty($v)) ? $s : $v;
            $v = $helper->getConfig('default_imageset');
            $d = (empty($v)) ? $d : $v;
            $d = ($d=='default') ? '' : $d; // preferences does not like empty string
            $v = $helper->getConfig('max_rating');
            $r = (empty($v)) ? $r : $v;
        }

        $scheme = \Xoops\Core\HttpRequest::getInstance()->getScheme();
        if ($scheme == 'https') {
            $url = 'https://secure.gravatar.com/avatar/';
        } else {
            $url = 'http://www.gravatar.com/avatar/';
        }
        $url .= md5(strtolower(trim($email)));
        $url .= "?s=$s&d=$d&r=$r";

        return $url;
    }

    /**
     * getUserById - get a user object from a user id
     *
     * @param int $uid a user id
     *
     * @return object|null
     */
    private function getUserById($uid)
    {
        $user = \Xoops::getInstance()->getHandlerMember()->getUser((int) $uid);
        return (is_object($user)) ? $user : null;
    }

    /**
     * getName - get a short name for this service provider. This should be unique within the
     * scope of the named service, so using module dirname is suggested.
     *
     * @return string - a unique name for the service provider
     */
    public function getName()
    {
        return 'gravatars';
    }

    /**
     * getDescription - get human readable description of the service provider
     *
     * @return string
     */
    public function getDescription()
    {
        return 'Use gravatar.com for system avatars.';
    }

    /**
     * getAvatarUrl - given user info return absolute URL to avatar image
     *
     * @param Response $response \Xoops\Core\Service\Response object
     * @param mixed    $userinfo XoopsUser object for user or
     *                           array     user info, 'uid', 'uname' and 'email' required
     *                           int       user uid
     *
     * @return void - response->value set to absolute URL to avatar image
     */
    public function getAvatarUrl($response, $userinfo)
    {
        $noInfo = true;
        if (is_object($userinfo)) {
            if (is_a($userinfo, 'XoopsUser')) {
                $email = $userinfo->getVar('email', 'e');
                $response->setValue(self::getGravatar($email));
                $noInfo = false;
            }
        } elseif (is_array($userinfo)) {
            if (!empty($userinfo['email'])) {
                $response->setValue(self::getGravatar($userinfo['email']));
                $noInfo = false;
            }
        } elseif (is_scalar($userinfo)) {
            $user = $this->getUserById((int) $userinfo);
            if (is_object($user) && is_a($user, 'XoopsUser')) {
                $email = $user->getVar('email', 'e');
                $response->setValue(self::getGravatar($email));
                $noInfo = false;
            }
        }
        if ($noInfo) {
            $response->setSuccess(false)->addErrorMessage('User info is invalid');
        }
    }

    /**
     * getAvatarEditUrl - given user info return absolute URL to edit avatar data
     *
     * @param Response   $response \Xoops\Core\Service\Response object
     * @param \XoopsUser $userinfo XoopsUser object for user
     *
     * @return void - response->value set to absolute URL to editing function for avatar data
     */
    public function getAvatarEditUrl($response, \XoopsUser $userinfo)
    {
        $noInfo = true;

        if (is_object($userinfo)) {
            if (is_a($userinfo, 'XoopsUser')) {
                $email = $userinfo->getVar('email', 'e');
                $link = 'http://www.gravatar.com/' . md5(strtolower(trim($email)));
                $response->setValue($link);
                $noInfo = false;
            }
        }
        if ($noInfo) {
            $response->setSuccess(false)->addErrorMessage('User info is invalid');
        }
    }
}
