<?php
/*
 * You may not change or alter any portion of this comment or credits
 * of supporting developers from this source code or any supporting source code
 * which is considered copyrighted (c) material of the original comment or credit authors.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
 */

namespace Xoops\Core;

use Xmf\Random;

/**
 * XOOPS security handler
 *
 * @category  Xoops\Core
 * @package   Security
 * @author    Kazumi Ono <onokazu@xoops.org>
 * @author    Jan Pedersen <mithrandir@xoops.org>
 * @author    John Neill <catzwolf@xoops.org>
 * @author    Richard Griffith <richard@geekwright.com>
 * @copyright 2000-2020 XOOPS Project (https://xoops.org)
 * @license   GNU GPL 2 or later (http://www.gnu.org/licenses/gpl-2.0.html)
 * @version   Release: 1.0
 * @link      https://xoops.org
 * @since     2.0.0
 */
class Security
{
    private $errors = [];

    /**
     * Check if there is a valid token in $_REQUEST[$name . '_REQUEST']
     *
     * @param bool         $clearIfValid whether to clear the token after validation
     * @param string|false $token        token to validate
     * @param string       $name         name of session variable
     *
     * @return bool
     */
    public function check($clearIfValid = true, $token = false, $name = 'XOOPS_TOKEN')
    {
        return $this->validateToken($token, $clearIfValid, $name);
    }

    /**
     * Create a token in the user's session
     *
     * @param int    $timeout time in seconds the token should be valid
     * @param string $name    name of session variable
     *
     * @return string token value
     */
    public function createToken($timeout = 300, $name = 'XOOPS_TOKEN')
    {
        $this->garbageCollection($name);
        $timeout = ($timeout <= 0) ? 300 : $timeout;
        $token_id = Random::generateOneTimeToken();
        // save token data on the server
        if (!isset($_SESSION[$name . '_SESSION'])) {
            $_SESSION[$name . '_SESSION'] = [];
        }
        $token_data = [
            'id' => $token_id, 'expire' => time() + (int)($timeout),
        ];
        array_push($_SESSION[$name . '_SESSION'], $token_data);

        return $token_id;
    }

    /**
     * Check if a token is valid. If no token is specified, $_REQUEST[$name . '_REQUEST'] is checked
     *
     * @param string|false $token        token to validate
     * @param bool         $clearIfValid whether to clear the token value if valid
     * @param string       $name         session name to validate
     *
     * @return bool
     */
    public function validateToken($token = false, $clearIfValid = true, $name = 'XOOPS_TOKEN')
    {
        $ret = false;
        $log = [];
        $token = (false !== $token)
            ? $token
            : (isset($_REQUEST[$name . '_REQUEST']) ? $_REQUEST[$name . '_REQUEST'] : '');
        if (empty($token) || empty($_SESSION[$name . '_SESSION'])) {
            $str = 'No valid token found in request/session';
            $this->setErrors($str);
            $log[] = ['Token Validation', $str];
        } else {
            $token_data = &$_SESSION[$name . '_SESSION'];
            if (is_array($token_data)) {
                foreach (array_keys($token_data) as $i) {
                    if ($token === $token_data[$i]['id']) {
                        if ($this->filterToken($token_data[$i])) {
                            if ($clearIfValid) {
                                // token should be valid once, so clear it once validated
                                unset($token_data[$i]);
                            }
                            $log[] = ['Token Validation', 'Valid token found'];
                            $ret = true;
                        } else {
                            $str = 'Valid token expired';
                            $this->setErrors($str);
                            $log[] = ['Token Validation', $str];
                        }
                    }
                }
            }
            if (!$ret) {
                $log[] = ['Token Validation', 'No valid token found'];
            }
            $this->garbageCollection($name);
        }
        \Xoops::getInstance()->events()->triggerEvent('core.security.validatetoken.end', [$log]);

        return $ret;
    }

    /**
     * Clear all token values from user's session
     *
     * @param string $name session name
     *
     * @return void
     */
    public function clearTokens($name = 'XOOPS_TOKEN')
    {
        $_SESSION[$name . '_SESSION'] = [];
    }

    /**
     * Check whether a token value is expired or not
     *
     * @param string $token token
     *
     * @return bool
     */
    public function filterToken($token)
    {
        return (!empty($token['expire']) && $token['expire'] >= time());
    }

    /**
     * Perform garbage collection, clearing expired tokens
     *
     * @param string $name session name
     *
     * @return void
     */
    public function garbageCollection($name = 'XOOPS_TOKEN')
    {
        $sessionName = $name . '_SESSION';
        if (!empty($_SESSION[$sessionName]) && is_array($_SESSION[$sessionName])) {
            $_SESSION[$sessionName] = array_filter($_SESSION[$sessionName], [$this, 'filterToken']);
        }
    }

    /**
     * Check the user agent's HTTP REFERER against XOOPS_URL
     *
     * @param int $docheck 0 to not check the referer (used with XML-RPC), 1 to actively check it
     *
     * @return bool
     */
    public function checkReferer($docheck = 1)
    {
        $ref = \Xoops::getInstance()->getEnv('HTTP_REFERER');
        if (0 == $docheck) {
            return true;
        }
        if ('' == $ref) {
            return false;
        }
        if (0 !== mb_strpos($ref, \XoopsBaseConfig::get('url'))) {
            return false;
        }

        return true;
    }

    /**
     * Check if visitor's IP address is banned
     * Should be changed to return bool and let the action be up to the calling script
     *
     * @return void
     */
    public function checkBadips()
    {
        $xoops = \Xoops::getInstance();
        if (1 == $xoops->getConfig('enable_badips')
            && isset($_SERVER['REMOTE_ADDR'])
            && '' != $_SERVER['REMOTE_ADDR']
        ) {
            foreach ($xoops->getConfig('bad_ips') as $bi) {
                if (!empty($bi) && preg_match('/' . $bi . '/', $_SERVER['REMOTE_ADDR'])) {
                    exit();
                }
            }
        }
    }

    /**
     * Get the HTML code for a Xoops\Form\Token object - provides a hidden token field
     * used in forms that do not use Xoops\Form elements
     *
     * @param string $name session token name
     *
     * @return string
     */
    public function getTokenHTML($name = 'XOOPS_TOKEN')
    {
        $token = new \Xoops\Form\Token($name);

        return $token->render();
    }

    /**
     * Add an error
     *
     * @param string $error message
     *
     * @return void
     */
    public function setErrors($error)
    {
        $this->errors[] = trim($error);
    }

    /**
     * Get generated errors
     *
     * @param bool $ashtml Format using HTML?
     *
     * @return array|string Array of array messages OR HTML string
     */
    public function getErrors($ashtml = false)
    {
        if (!$ashtml) {
            return $this->errors;
        }
        $ret = '';
        if (is_array($this->errors)) {
            $ret = implode('<br />', $this->errors) . '<br />';
        }

        return $ret;
    }
}
