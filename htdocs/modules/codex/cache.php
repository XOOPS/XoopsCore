<?php
/*
 You may not change or alter any portion of this comment or credits
 of supporting developers from this source code or any supporting source code
 which is considered copyrighted (c) material of the original comment or credit authors.

 This program is distributed in the hope that it will be useful,
 but WITHOUT ANY WARRANTY; without even the implied warranty of
 MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
 */

use Xoops\Core\Request;

/**
 * @copyright       The XOOPS Project http://sourceforge.net/projects/xoops/
 * @license         GNU GPL 2 or later (http://www.gnu.org/licenses/gpl-2.0.html)
 * @author          trabis <lusopoemas@gmail.com>
 * @version         $Id$
 */

include dirname(dirname(__DIR__)) . DIRECTORY_SEPARATOR . 'mainfile.php';

$xoops = Xoops::getInstance();
$xoops->header();

// we ask Xoops for our cache
$cache = $xoops->cache();

/**
 * If requested, delete the caches for our module. The cache has hierarchical
 * keys, so we can treat them similar to a directory tree. One delete clears
 * everything underneath, so we don't have to delete each item.
 *
 * module/{dirname} is the naming standard for module specific cache entries.
 */
if (Request::getBool('delete', false, 'GET')) {
    $cache->delete('module/codex');
}

$keys = array(
    'module/codex/firstkey',
    'module/codex/secondkey',
    'module/codex/alternate',
    'module/codex/xmfdemo'
);

echo '<h3>Current Cache State</h3>';
echo '<dl>';
foreach ($keys as $key) {
    echo "<dt>{$key}</dt>";
    $value = '<em>Not Found</em>';
    if (!$value = $cache->read($key)) {
        $value = '<em>Not Found</em>';
    }
    echo "<dd>{$value}</dd>";
}
echo '</dl>';

echo '<h3>Caching Content</h3>';
echo '<h4>Manual read() and write()</h4>';
// We can do things manually
$key = 'module/codex/firstkey';
echo $key . ' ';
if (!$readData = $cache->read($key)) {
    echo 'Had to rebuild: ';
    $content = 'This is my content';
    $cache->write($key, $content);
    $readData = $content;
}
echo $readData . '<br />';

echo '<h4>Streamlined with cacheRead()</h4>';
// We can let the cache handle lots of the details. We just provide a function
// that builds the content to be cached. The function can be any callback/closure.
$key = 'module/codex/secondkey';
echo $key . ' ' . $cache->cacheRead($key, 'getSomeContent') . '<br />';

echo '<h3>Simplify even more with Xmf</h3>';
// Xmf provide cache simplified cache functions that automatically follow the
// naming standard for module specific cache keys.
$xmfCache = new \Xmf\Module\Cache();
$key = 'xmfdemo';
echo $key . ' ' . $xmfCache->cacheRead($key, 'getSomeContent') . '<br />';

echo '<h3>Alternate Caches</h3>';
// Xoops cache() method can take a name parameter, to choose a cache configuration.
// Alternate caches can be defined in var-path/configs/cache.php if needed.
// The default cache definition is named 'default' and it will be used if no
// name is specified to cache() or if no cache is defined for the specified name.
$key = 'module/codex/alternate';
echo $key . ' ' . $xoops->cache('alternate')->cacheRead($key, 'getSomeContent') . '<br />';

echo '<br /><a href="?">Refresh</a> - <a href="?delete=1">Delete caches</a>';

Xoops_Utils::dumpFile(__FILE__);
$xoops->footer();

function getSomeContent()
{
    $content = 'This is just some content for the cache demo.';
    return $content;
}
