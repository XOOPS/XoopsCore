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

use Xmf\Key\KeyAbstract;
use Xmf\Request;

/**
 * Validate and get payload from a token string
 *
 * @category  Xmf\Jwt\TokenReader
 * @package   Xmf
 * @author    Richard Griffith <richard@geekwright.com>
 * @copyright 2000-2020 XOOPS Project (https://xoops.org)
 * @license   GNU GPL 2 or later (http://www.gnu.org/licenses/gpl-2.0.html)
 * @link      https://xoops.org
 */
class TokenReader
{
    /**
     * Validate and decode a JSON Web Token string
     *
     * @param KeyAbstract|string $key          the key to use to sign the token, or name of key to build
     * @param string             $token        the token string to validate and decode
     * @param array|\Traversable $assertClaims traversable set of claims, claim => value, to assert
     *
     * @throws \InvalidArgumentException on unusable key name
     * @return \stdClass|false payload as stdClass, or false if token was invalid
     */
    public static function fromString($key, $token, $assertClaims = [])
    {
        $key = ($key instanceof KeyAbstract) ? $key : KeyFactory::build($key);
        $jwt = new JsonWebToken($key);

        return $jwt->decode($token, $assertClaims);
    }

    /**
     * Validate and decode a JSON Web Token string from a cookie
     *
     * @param KeyAbstract|string $key          the key to use to sign the token, or name of key to build
     * @param string             $cookieName   name of cookie that sources the token
     * @param array|\Traversable $assertClaims traversable set of claims, claim => value, to assert
     *
     * @throws \InvalidArgumentException on unusable key name
     * @return \stdClass|false payload as stdClass, or false if token was invalid
     */
    public static function fromCookie($key, $cookieName, $assertClaims = [])
    {
        $token = Request::getString($cookieName, '', 'COOKIE');
        if (empty($token)) {
            return false;
        }

        return static::fromString($key, $token, $assertClaims);
    }

    /**
     * Validate and decode a JSON Web Token string from a request (i.e. POST body)
     *
     * @param KeyAbstract|string $key          the key to use to sign the token, or name of key to build
     * @param string             $attributeName name of cookie that sources the token
     * @param array|\Traversable $assertClaims  traversable set of claims, claim => value, to assert
     *
     * @throws \InvalidArgumentException on unusable key name
     * @return \stdClass|false payload as stdClass, or false if token was invalid
     */
    public static function fromRequest($key, $attributeName, $assertClaims = [])
    {
        $token = Request::getString($attributeName, '');
        if (empty($token)) {
            return false;
        }

        return static::fromString($key, $token, $assertClaims);
    }

    /**
     * Validate and decode a JSON Web Token string from a header
     *
     * @param KeyAbstract|string $key          the key to use to sign the token, or name of key to build
     * @param array|\Traversable $assertClaims traversable set of claims, claim => value, to assert
     * @param string             $headerName   name of header that sources the token
     *
     * @throws \InvalidArgumentException on unusable key name
     * @return \stdClass|false payload as stdClass, or false if token was invalid
     */
    public static function fromHeader($key, $assertClaims = [], $headerName = 'Authorization')
    {
        $header = Request::getHeader($headerName, '');
        if (empty($header)) {
            return false;
        }
        $header = trim($header);
        $space = mb_strpos($header, ' '); // expecting "Bearer base64-token-string"
        if (false !== $space) {
            $header = mb_substr($header, $space);
        }
        $token = trim($header);

        return static::fromString($key, $token, $assertClaims);
    }
}
