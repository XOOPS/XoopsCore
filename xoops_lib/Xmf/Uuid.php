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

/**
 * Generate UUID
 *
 * @category  Xmf\Uuid
 * @package   Xmf
 * @author    Richard Griffith <richard@geekwright.com>
 * @copyright 2017 XOOPS Project (http://xoops.org)
 * @license   GNU GPL 2 or later (http://www.gnu.org/licenses/gpl-2.0.html)
 * @link      http://xoops.org
 */
class Uuid
{
    /**
     * generate - generate a version 4 (random) UUID
     *
     * Based on comment by pavel.volyntsev(at)gmail at http://php.net/manual/en/function.com-create-guid.php
     *
     * @return string UUID
     *
     * @throws \Exception on insufficient entropy
     */
    public static function generate()
    {
        $data = random_bytes(16);

        $data[6] = chr(ord($data[6]) & 0x0f | 0x40); // set version to 0100
        $data[8] = chr(ord($data[8]) & 0x3f | 0x80); // set bits 6-7 to 10

        return vsprintf('%s%s-%s-%s-%s-%s%s%s', str_split(bin2hex($data), 4));
    }
}
