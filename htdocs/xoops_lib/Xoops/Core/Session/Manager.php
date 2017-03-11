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

use Xoops\Core\AttributeInterface;
use Xoops\Core\HttpRequest;

/**
 * Session management
 *
 * Credits due to Robert Hafner's article "How to Create Bulletproof Sessions"
 * see: http://blog.teamtreehouse.com/how-to-create-bulletproof-sessions
 *
 * @category  Xoops\Core\Session
 * @package   Manager
 * @author    Richard Griffith <richard@geekwright.com>
 * @copyright 2015 XOOPS Project (http://xoops.org)
 * @license   GNU GPL 2 or later (http://www.gnu.org/licenses/gpl-2.0.html)
 * @link      http://xoops.org
 */
class Manager implements AttributeInterface
{
    /**
     * @var \Xoops
     */
    protected $xoops = null;

    /**
     * @var \Xoops\Core\HttpRequest
     */
    protected $httpRequest = null;

    /**
     * @var Fingerprint fingerprint object
     */
    protected $fingerprint = null;

    /**
     * @var SessionUser session user object
     */
    protected $sessionUser = null;

    /**
     * establish access to other classes we will use
     */
    public function __construct()
    {
        $this->xoops = \Xoops::getInstance();
        $this->httpRequest = HttpRequest::getInstance();
        $this->sessionUser = new SessionUser($this);
        $this->fingerprint = new Fingerprint;
    }

    /**
     * Configure and start the session
     *
     * @return void
     */
    public function sessionStart()
    {
        /**
         * Revisit this once basics are working
         *
         * grab session_id from https login form
         *
         *  if ($xoops->getConfig('use_ssl')
         *      && isset($_POST[$xoops->getConfig('sslpost_name')])
         *      && $_POST[$xoops->getConfig('sslpost_name')] != ''
         *  ) {
         *      session_id($_POST[$xoops->getConfig('sslpost_name')]);
         *  } else { set session_name...}
         */

        $name = $this->xoops->getConfig('session_name');
        $name = (empty($name)) ? 'xoops_session' : $name;
        $expire = (int)($this->xoops->getConfig('session_expire'));
        $expire = ($expire>0) ? $expire : 300;

        $path = \XoopsBaseConfig::get('cookie-path');
        $domain = \XoopsBaseConfig::get('cookie-domain');
        $secure = $this->httpRequest->is('ssl');
        session_name($name);
        session_cache_expire($expire);

        session_set_cookie_params(0, $path, $domain, $secure, true);

        $sessionHandler = new Handler;
        session_set_save_handler($sessionHandler);

        //session_register_shutdown();
        register_shutdown_function(array($this, 'sessionShutdown'));

        session_start();

        // if session is empty, make sure it isn't using a passed in id
        if (empty($_SESSION)) {
            $this->regenerateSession();
        }

        // Make sure the session hasn't expired, and destroy it if it has
        if (!$this->validateSession()) {
            $this->clearSession();
            return;
        }

        // Check to see if the session shows sign of hijacking attempt
        if (!$this->fingerprint->checkSessionPrint($this)) {
            $this->regenerateSession(); // session data already cleared, just needs new id
            return;
        }

        // establish valid user data in session, possibly clearing or adding from
        // RememberMe mechanism as needed
        $this->sessionUser->establish();

        // Give a 5% chance of the session id changing on any authenticated request
        //if ($this->has('xoopsUserId') && (rand(1, 100) <= 5)) {
        if ((rand(1, 100) <= 5)) {
            $this->expireSession();
        }
    }

    /**
     * Clear the current session and reset fingerprint
     *
     * @return void
     */
    public function clearSession()
    {
        $this->clear();
        $this->fingerprint->checkSessionPrint($this);
        $this->regenerateSession();
    }

    /**
     * Expire the current session and replace with a fresh one.
     *
     * @return void
     */
    public function expireSession()
    {
        // If this session is obsolete it means there already is a new id
        if ($this->has('SESSION_MANAGER_OBSOLETE')) {
            return;
        }

        // Set current session to expire in 10 seconds
        $this->set('SESSION_MANAGER_OBSOLETE', true);
        $this->set('SESSION_MANAGER_EXPIRES', time() + 10);

        // Grab current session ID and close it
        //$sessionId = session_id();
        //session_write_close();

        // reopen the old session
        //session_id($sessionId);
        //session_start();

        // Create new session without destroying the old one
        session_regenerate_id(false);

        // Now we unset the obsolete and expiration values since we ant to keep this one
        $this->remove('SESSION_MANAGER_OBSOLETE');
        $this->remove('SESSION_MANAGER_EXPIRES');
    }

    /**
     * Generate a new id and delete the old session.
     *
     * This should be called whenever permission levels for a user change.
     *
     * @return void
     */
    public function regenerateSession()
    {
        session_regenerate_id(true);
    }

    /**
     * Validate that the session has not expired.
     *
     * @return boolean true is session is valid and not expired, otherwise false
     */
    protected function validateSession()
    {
        // invalid to have obsolete and not expires
        if ($this->has('SESSION_MANAGER_OBSOLETE') && !$this->has('SESSION_MANAGER_EXPIRES')) {
            return false;
        }

        // if we don't have the expires key, use a future value for test
        if ($this->get('SESSION_MANAGER_EXPIRES', time()+10) < time()) {
            return false;
        }

        return true;
    }

    /**
     * Get the user object used by this session.
     *
     * @return SessionUser
     */
    public function user()
    {
        return $this->sessionUser;
    }

    /**
     * shutdown function
     */
    public function sessionShutdown()
    {
        \Xoops::getInstance()->events()->triggerEvent('core.session.shutdown');
        session_write_close();
    }

    // access session variables as attribute object

    /**
     * Retrieve a session variable value.
     *
     * @param string $name    Name of an session variable
     * @param mixed  $default A default value returned if the requested
     *                        named session variable is not set.
     *
     * @return  mixed  The value of the session variable, or $default if not set.
     */
    public function get($name, $default = null)
    {
        return (isset($_SESSION[$name])) ? $_SESSION[$name] : $default;
    }

    /**
     * Set an attribute value.
     *
     * @param string $name  Name of the attribute option
     * @param mixed  $value Value of the attribute option
     *
     * @return $this
     */
    public function set($name, $value)
    {
        $_SESSION[$name] = $value;
        return $this;
    }

    /**
     * Determine if an attribute exists.
     *
     * @param string $name An attribute name.
     *
     * @return boolean TRUE if the given attribute exists, otherwise FALSE.
     */
    public function has($name)
    {
        return isset($_SESSION[$name]);
    }

    /**
     * Remove an attribute.
     *
     * @param string $name An attribute name.
     *
     * @return mixed An attribute value, if the named attribute existed and
     *               has been removed, otherwise NULL.
     */
    public function remove($name)
    {
        $value = (isset($_SESSION[$name])) ? $_SESSION[$name] : null;
        unset($_SESSION[$name]);

        return $value;
    }

    /**
     * Remove all attributes.
     *
     * @return array old values
     */
    public function clear()
    {
        $oldValues = $_SESSION;
        $_SESSION = array();
        return $oldValues;
    }
}
