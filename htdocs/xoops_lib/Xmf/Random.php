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

namespace Xmf;

use RandomLib\Factory;

/**
 * XOOPS Random generator
 *
 * @category  Xmf\Random
 * @package   Xmf
 * @author    Richard Griffith <richard@geekwright.com>
 * @copyright 2015-2016 XOOPS Project (http://xoops.org)
 * @license   GNU GPL 2 or later (http://www.gnu.org/licenses/gpl-2.0.html)
 * @link      http://xoops.org
 */
class Random
{
    /**
     * Create a one time token
     *
     * Generates a low strength random number of size $bytes and hash with the
     * algorithm specified in $hash.
     *
     * @param string  $hash  hash function to use
     * @param integer $bytes the number of random bit to generate
     *
     * @return string hashed token
     */
    public static function generateOneTimeToken($hash = 'sha512', $bytes = 64)
    {
        $factory = new Factory;
        $generator = $factory->getLowStrengthGenerator();
        $token = hash($hash, $generator->generate($bytes));
        return $token;
    }

    /**
     * Create a medium strength key
     *
     * Generates a medium strength random number of size $bytes and hash with the
     * algorithm specified in $hash.
     *
     * @param string  $hash  hash function to use
     * @param integer $bytes the number of random bytes to generate
     *
     * @return string hashed token
     */
    public static function generateKey($hash = 'sha512', $bytes = 128)
    {
        $factory = new Factory;
        $generator = $factory->getMediumStrengthGenerator();
        $token = hash($hash, $generator->generate($bytes));
        return $token;
    }
}
