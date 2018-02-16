<?php
/*
 You may not change or alter any portion of this comment or credits
 of supporting developers from this source code or any supporting source code
 which is considered copyrighted (c) material of the original comment or credit authors.

 This program is distributed in the hope that it will be useful,
 but WITHOUT ANY WARRANTY; without even the implied warranty of
 MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
*/

namespace Xoops\Core\Session;

use Xoops\Core\HttpRequest;
use Xmf\Request;
use Xoops\Core\Kernel\Handlers\XoopsUser;

/**
 * Manage the session representation of a the current User
 *
 * @category  Xoops\Core\Session
 * @package   SessionUser
 * @author    Richard Griffith <richard@geekwright.com>
 * @copyright 2015 XOOPS Project (http://xoops.org)
 * @license   GNU GPL 2 or later (http://www.gnu.org/licenses/gpl-2.0.html)
 * @link      http://xoops.org
 */
class SessionUser
{

    /**
     * @var Manager
     */
    protected $session;

    /**
     * @var \Xoops
     */
    protected $xoops = null;

    /**
     * constructor
     * @param Manager $session the session manager object
     */
    public function __construct(Manager $session)
    {
        $this->session = $session;
        $this->xoops = \Xoops::getInstance();
    }


    /**
     * Check any user data in the current session and clear if invalid.
     *
     * If no user data, check if "remember me" data should be applied
     *
     * @return void
     */
    public function establish()
    {
        $session = $this->session;

        // is user already set in session?
        if ($session->has('xoopsUserId')) {
            $this->addUserToSession($session->get('xoopsUserId'));
            return;
        }

        // is the usercookie available?
        $remember = new RememberMe;
        $userId = $remember->recall();
        if (false !== $userId) {
            $this->setNeedsConfirmed();
            $this->addUserToSession($userId);
        }
    }


    /**
     * Record a login event in the session. This is to be called by the login
     * process, i.e. the user has entered the name and password, and that
     * combination was found valid.
     *
     * @param integer $userId     id of user to establish in the session
     * @param boolean $rememberMe add a persistent login cookie
     *
     * @return void
     */
    public function recordUserLogin($userId, $rememberMe = false)
    {
        $this->setConfirmed();
        $this->addUserToSession($userId);
        if ($rememberMe) {
            $remember = new RememberMe;
            $remember->createUserCookie($userId);
        }
    }

    /**
     * Record a login event in the session. This is to be called by the login
     * process, i.e. the user has entered the name and password, and that
     * combination was found valid.
     *
     * @return void
     */
    public function recordUserLogout()
    {
        $remember = new RememberMe;
        $remember->forget();
        $this->session->clearSession();
    }

    /**
     * Check the we have a remember me cookie, and apply if valid
     *
     * @param integer $userId id of user to establish in the session
     *
     * @return void
     */
    public function addUserToSession($userId)
    {
        $session = $this->session;
        $memberHandler = $this->xoops->getHandlerMember();
        $user = $memberHandler->getUser($userId);
        if ($user instanceof XoopsUser) {
            if ($user->isActive()) {
                // make sure all primary user data is consistent
                $session->set('xoopsUserId', $user->getVar('uid'));
                $session->set('xoopsUserGroups', $user->getGroups());
                if (!$session->has('SESSION_AUTHSTATUS')) {
                    $this->setNeedsConfirmed();
                }
                // all is good, leave the existing info
                return;
            }
        }
        // invalid user - clear everything
        $session->clearSession();
        return;
    }

    /**
     * set authorization status to needs confirmed
     *
     * @return void
     */
    public function setNeedsConfirmed()
    {
        $this->session->set('SESSION_AUTHSTATUS', 'confirm');
    }

    /**
     * set authorization status to is confirmed
     *
     * @return void
     */
    public function setConfirmed()
    {
        $this->session->set('SESSION_AUTHSTATUS', 'ok');
    }

    /**
     * verify the authorization status is confirmed
     *
     * @return boolean true if confirmed, otherwise false
     */
    public function checkConfirmed()
    {
        return $this->session->has('xoopsUserId') &&
                ('ok' === $this->session->get('SESSION_AUTHSTATUS', 'failed'));
    }
}
