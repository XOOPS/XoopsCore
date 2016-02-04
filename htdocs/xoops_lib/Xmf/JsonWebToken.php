<?php
/*
 You may not change or alter any portion of this comment or credits
 of supporting developers from this source code or any supporting source code
 which is considered copyrighted (c) material of the original comment or credit authors.

 This program is distributed in the hope that it will be useful,
 but WITHOUT ANY WARRANTY; without even the implied warranty of
 MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
 */

namespace Xmf;

use Firebase\JWT\JWT;

/**
 * Xmf\JsonWebToken
 *
 * Basic JWT support
 *
 * @category  Xmf\JsonWebToken
 * @package   Xmf
 * @author    Richard Griffith <richard@geekwright.com>
 * @copyright 2016 XOOPS Project (http://xoops.org)
 * @license   GNU GPL 2 or later (http://www.gnu.org/licenses/gpl-2.0.html)
 * @version   Release: 1.0
 * @link      http://xoops.org
 */
class JsonWebToken
{
    /**
     * @var string
     */
    protected $key;

    /**
     * @var string
     */
    protected $algorithm = 'HS256';

    /**
     * @var array
     */
    protected $claims = array();

    /**
     * JsonWebToken constructor.
     *
     * @param string $key       key for signing/validating
     * @param string $algorithm algorithm to use for signing/validating
     */
    public function __construct($key = null, $algorithm = 'HS256')
    {
        if (null === $key) {
            $keyFile = \XoopsBaseConfig::get('xoops-path') . '/data/syskey.php';
            $key = include_once $keyFile;
        }
        $this->setKey($key);
        $this->setAlgorithm($algorithm);
    }

    /**
     * Set the key
     *
     * @param string $key key for signing/validating
     *
     * @return JsonWebToken
     */
    public function setKey($key)
    {
        $this->key = $key;
        return $this;
    }

    /**
     * @param string $algorithm algorithm to use for signing/validating
     *
     * @return JsonWebToken
     *
     * @throws \DomainException
     */
    public function setAlgorithm($algorithm)
    {
        if (array_key_exists($algorithm, JWT::$supported_algs)) {
            $this->algorithm = $algorithm;
            return $this;
        }
        throw new \DomainException('Algorithm not supported');
    }

    /**
     * Decode a JWT string, validating signature and well defined registered claims,
     * and optionally validate against a list of supplied claims
     *
     * @param string $jwtString    string containing the JWT to decode
     * @param array  $assertClaims associative array, claim => value, of claims to assert
     *
     * @return object|false
     */
    public function decode($jwtString, $assertClaims = array())
    {
        $allowedAlgorithms = array($this->algorithm);
        try {
            $values = JWT::decode($jwtString, $this->key, $allowedAlgorithms);
        } catch (\Exception $e) {
            trigger_error($e->getMessage(), E_USER_NOTICE);
            return false;
        }
        foreach ($assertClaims as $claim => $assert) {
            if (!property_exists($values, $claim)) {
                return false;
            } elseif ($values->$claim != $assert) {
                return false;
            }
        }
        return $values;
    }

    /**
     * @param array $payload          associative array og claims, claim => value
     * @param int   $expirationOffset seconds from now that token will expire. If not specified,
     *                                 an "exp" claim will not be added or updated
     *
     * @return string encoded and signed jwt string
     *
     * @throws \DomainException;
     * @throws \InvalidArgumentException;
     * @throws \UnexpectedValueException;
     */
    public function create($payload, $expirationOffset = 0)
    {
        if ((int) $expirationOffset > 0) {
            $payload['exp'] = time() + (int) $expirationOffset;
        }
        $value = JWT::encode($payload, $this->key, $this->algorithm);
        return $value;
    }
}
