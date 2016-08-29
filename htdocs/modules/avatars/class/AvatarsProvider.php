<?php
/*
 You may not change or alter any portion of this comment or credits
 of supporting developers from this source code or any supporting source code
 which is considered copyrighted (c) material of the original comment or credit authors.

 This program is distributed in the hope that it will be useful,
 but WITHOUT ANY WARRANTY; without even the implied warranty of
 MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
*/

use Xoops\Core\Kernel\Handlers\XoopsUser;
use Xoops\Core\Service\AbstractContract;
use Xoops\Core\Service\Contract\AvatarInterface;

/**
 * Avatars provider for service manager
 *
 * @category  class
 * @package   AvatarsProvider
 * @author    Richard Griffith <richard@geekwright.com>
 * @copyright 2014 XOOPS Project (http://xoops.org)
 * @license   GNU GPL 2 or later (http://www.gnu.org/licenses/gpl-2.0.html)
 * @link      http://xoops.org
 * @since     2.6.0
 */
class AvatarsProvider extends AbstractContract implements AvatarInterface
{
	protected $xoops_url;
	protected $xoops_upload_url;

    public function __construct()
    {
		$this->xoops_url = \XoopsBaseConfig::get('url');
		$this->xoops_upload_url = \XoopsBaseConfig::get('uploads-url');
    }

    /**
     * getName - get a short name for this service provider. This should be unique within the
     * scope of the named service, so using module dirname is suggested.
     *
     * @return string - a unique name for the service provider
     */
    public function getName()
    {
        return 'avatars';
    }

    /**
     * getDescription - get human readable description of the service provider
     *
     * @return string
     */
    public function getDescription()
    {
        return 'Traditional XOOPS avatars.';
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
     * getAvatarUrl - given user info return absolute URL to avatar image
     *
     * @param Response $response \Xoops\Core\Service\Response object
     * @param mixed    $userinfo XoopsUser object for user or
     *                           array of user info, 'uid', 'uname' and 'email' required
     *
     * @return void - response->value set to absolute URL to avatar image
     */
    public function getAvatarUrl($response, $userinfo)
    {
        $noInfo = true;
        if (is_object($userinfo)) {
            if ($userinfo instanceof XoopsUser) {
                if ($userinfo->getVar('user_avatar')
                    && 'blank.gif' !== $userinfo->getVar('user_avatar')
                ) {
                    $response->setValue($this->xoops_upload_url . "/" . $userinfo->getVar('user_avatar'));
                }
                $noInfo = false;
            }
        } elseif (is_array($userinfo)) {
            if (!empty($userinfo['user_avatar']) && $userinfo['user_avatar'] !== 'blank.gif') {
                $response->setValue($this->xoops_upload_url . "/" . $userinfo['user_avatar']);
                $noInfo = false;
            }
        } elseif (is_scalar($userinfo)) {
            $user = $this->getUserById((int) $userinfo);
            if (is_object($user) && ($user instanceof XoopsUser)) {
                if ($user->getVar('user_avatar')
                    && 'blank.gif' !== $user->getVar('user_avatar')
                ) {
                    $response->setValue($this->xoops_upload_url . "/" . $user->getVar('user_avatar'));
                }
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
     * @param Response  $response \Xoops\Core\Service\Response object
     * @param XoopsUser $userinfo XoopsUser object for user
     *
     * @return void - response->value set to absolute URL to editing function for avatar data
     */
    public function getAvatarEditUrl($response, XoopsUser $userinfo)
    {
        $noInfo = true;
        if ($userinfo instanceof XoopsUser) {
            $link = $this->xoops_url . '/modules/avatars/include/editavatar.php';
            $response->setValue($link);
            $noInfo = false;
        }
        if ($noInfo) {
            $response->setSuccess(false)->addErrorMessage('User info is invalid');
        }
    }
}
