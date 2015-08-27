<?php
/*
 You may not change or alter any portion of this comment or credits
 of supporting developers from this source code or any supporting source code
 which is considered copyrighted (c) material of the original comment or credit authors.

 This program is distributed in the hope that it will be useful,
 but WITHOUT ANY WARRANTY; without even the implied warranty of
 MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
*/

namespace Xoops\Core\AssetFilter;

use Assetic\Filter;
use Assetic\Asset\AssetInterface;

/**
 * Provides a standarized asset strategy
 *
 * @category  Xoops\Core\AssetFilter
 * @package   JSqueezeFilter
 * @author    Richard Griffith <richard@geekwright.com>
 * @copyright 2015 XOOPS Project (http://xoops.org)
 * @license   GNU GPL 2 or later (http://www.gnu.org/licenses/gpl-2.0.html)
 * @link      http://xoops.org
 */
class JSqueezeFilter implements Filter\FilterInterface
{
    public function filterLoad(AssetInterface $asset)
    {
    }
    public function filterDump(AssetInterface $asset)
    {
        $jz = new \Patchwork\JSqueeze;
        $asset->setContent($minifiedJs = $jz->squeeze($asset->getContent()), true, true, true);
    }
}
