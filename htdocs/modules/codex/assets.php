<?php
/*
 You may not change or alter any portion of this comment or credits
 of supporting developers from this source code or any supporting source code
 which is considered copyrighted (c) material of the original comment or credit authors.

 This program is distributed in the hope that it will be useful,
 but WITHOUT ANY WARRANTY; without even the implied warranty of
 MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
 */

/**
 * @copyright       XOOPS Project (http://xoops.org)
 * @license         GNU GPL 2 or later (http://www.gnu.org/licenses/gpl-2.0.html)
 * @author          Richard Griffith <richard@geekwright.com>
 */

require dirname(dirname(__DIR__)) . '/mainfile.php';

$xoops = Xoops::getInstance();
$xoops->header();

// set custom filters for outputing scss and less assets
$xoops->theme()->setNamedAsset('testscss', 'modules/codex/assets/scss/test.scss', 'scssphp');
$xoops->theme()->setNamedAsset('testless', 'modules/codex/assets/less/test.less', 'lessphp');

// add custom filtered assets just like any other assets
$xoops->theme()->addStylesheetAssets(array('@testscss', '@testless'));

echo <<<EOT
    <p class="scss-example">This is an example of new asset managment capabilities
        using styles created directly from SCSS and LESS sources.</p>
    <div class="shape" id="shape1"></div>
    <div class="shape" id="shape2"></div>
    <div class="shape" id="shape3"></div>
EOT;

\Xoops\Utils::dumpFile(__FILE__);

echo '<h3>Simple SCSS example - assets/scss/test.scss</h3>';
\Xoops\Utils::dumpFile($xoops->path('modules/codex/assets/scss/test.scss'));

echo '<h3>Simple Less example - assets/less/test.less</h3>';
\Xoops\Utils::dumpFile($xoops->path('modules/codex/assets/less/test.less'));

$xoops->footer();
