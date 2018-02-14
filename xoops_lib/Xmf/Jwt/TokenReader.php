<?php
/*
 You may not change or alter any portion of this comment or credits
 of supporting developers from this source code or any supporting source code
 which is considered copyrighted (c) material of the original comment or credit authors.

 This program is distributed in the hope that it will be useful,
 but WITHOUT ANY WARRANTY; without even the implied warranty of
 MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
 */

namespace Xmf\Jwt;

use Xmf\Request;

/**
 * Validate and get payload from a token string
 *
 * @category  Xmf\Jwt\TokenReader
 * @package   Xmf
 * @author    Richard Griffith <richard@geekwright.com>
 * @copyright 2016 XOOPS Project (http://xoops.org)
 * @license   GNU GPL 2 or later (http://www.gnu.org/licenses/gpl-2.0.html)
 * @link      http://xoops.org
 */
class TokenReader
{
    /**
     * Validate and decode a JSON Web Token string
     *
     * @param string             $keyName      name of the key to used to sign the token
     * @param string             $token        the token string to validate and decode
     * @param array|\Traversable $assertClaims traversable set of claims, claim => value, to assert
     *
     * @return object|false payload as stdClass, or false if token was invalid
     */
    public static function fromString($keyName, $token, $assertClaims = array())
    {
        $jwt = new JsonWebToken(KeyFactory::build($keyName));
        return $jwt->decode($token, $assertClaims);
    }

    /**
     * Validate and decode a JSON Web Token string from a cookie
     *
     * @param string             $keyName      name of the key to used to sign the token
     * @param string             $cookieName   name of cookie that sources the token
     * @param array|\Traversable $assertClaims traversable set of claims, claim => value, to assert
     *
     * @return object|false payload as stdClass, or false if token was invalid
     */
    public static function fromCookie($keyName, $cookieName, $assertClaims = array())
    {
        $token = Request::getString($cookieName, '', 'COOKIE');
        if (empty($token)) {
            return false;
        }
        return static::fromString($keyName, $token, $assertClaims);
    }

    /**
     * Validate and decode a JSON Web Token string from a request (i.e. POST body)
     *
     * @param string             $keyName       name of the key to used to sign the token
     * @param string             $attributeName name of cookie that sources the token
     * @param array|\Traversable $assertClaims  traversable set of claims, claim => value, to assert
     *
     * @return object|false payload as stdClass, or false if token was invalid
     */
    public static function fromRequest($keyName, $attributeName, $assertClaims = array())
    {
        $token = Request::getString($attributeName, '');
        if (empty($token)) {
            return false;
        }
        return static::fromString($keyName, $token, $assertClaims);
    }

    /**
     * Validate and decode a JSON Web Token string from a header
     *
     * @param string             $keyName      name of the key to used to sign the token
     * @param array|\Traversable $assertClaims traversable set of claims, claim => value, to assert
     * @param string             $headerName   name of header that sources the token
     *
     * @return object|false payload as stdClass, or false if token was invalid
     */
    public static function fromHeader($keyName, $assertClaims = array(), $headerName = 'Authorization')
    {
        $header = Request::getHeader($headerName, '');
        if (empty($header)) {
            return false;
        }
        $header = trim($header);
        $space = strpos($header, ' '); // expecting "Bearer base64-token-string"
        if (false !== $space) {
            $header = substr($header, $space);
        }
        $token = trim($header);
        return static::fromString($keyName, $token, $assertClaims);
    }
}
