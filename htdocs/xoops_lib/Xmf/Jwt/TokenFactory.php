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

/**
 * Build a token
 *
 * @category  Xmf\Jwt\TokenFactory
 * @package   Xmf
 * @author    Richard Griffith <richard@geekwright.com>
 * @copyright 2016 XOOPS Project (http://xoops.org)
 * @license   GNU GPL 2 or later (http://www.gnu.org/licenses/gpl-2.0.html)
 * @link      http://xoops.org
 */
class TokenFactory
{
    /**
     * Create a JSON Web Token string
     *
     * @param string            $keyName          name of the key to use to sign the token
     * @param array|Traversable $payload          traversable set of claims, claim => value
     * @param int               $expirationOffset seconds from now that token will expire. If not specified,
     *                                             an "exp" claim will not be added or updated
     *
     * @return string a token string returned from JsonWebToken::create()
     *
     * @throws \DomainException;
     * @throws \InvalidArgumentException;
     * @throws \UnexpectedValueException;
     */
    public static function build($keyName, $payload, $expirationOffset = 0)
    {
        $token = new JsonWebToken(KeyFactory::build($keyName));
        return $token->create($payload, $expirationOffset);
    }
}
