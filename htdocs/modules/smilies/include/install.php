<?php
/*
 You may not change or alter any portion of this comment or credits
 of supporting developers from this source code or any supporting source code
 which is considered copyrighted (c) material of the original comment or credit authors.

 This program is distributed in the hope that it will be useful,
 but WITHOUT ANY WARRANTY; without even the implied warranty of
 MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
*/

use Xmf\Database\TableLoad;

/**
 * smilies module - install supplement for smilies module
 *
 * @copyright 2015-2019 XOOPS Project (https://xoops.org)
 * @license   GNU GPL 2 or later (https://www.gnu.org/licenses/gpl-2.0.html)
 * @package   smilies
 * @since     2.6.0
 * @author    Richard Griffith <richard@geekwright.com>
 * @param mixed $module
 */

/**
 * xoops_module_install_smilies - install supplement for smilies module
 *
 * @param object $module module object
 *
 * @return bool true on success
 */
function xoops_module_install_smilies($module)
{
    $xoops = Xoops::getInstance();
    $uploadDirectory = $xoops->path('uploads/smilies');
    if (!mkdir($uploadDirectory, 0755, true) && !is_dir($uploadDirectory)) {
        $module->setErrors('Failed to create directory uploads/smilies');

        return false;
    }
    $mediaPath = $xoops->path('modules/smilies/media');
    $directory = dir($mediaPath);
    while ($filename = $directory->read()) {
        $mediaFilename = $mediaPath . '/' . $filename;
        if (is_file($mediaFilename)) {
            $target = $uploadDirectory . '/' . $filename;
            if (!copy($mediaFilename, $target)) {
                $module->setErrors("Failed copying: {$filename}");

                return false;
            }
            $module->setMessage("Copying media: {$filename}");
        }
    }
    $directory->close();

    $module->setMessage('Loading smilies table');
    $countInserted = TableLoad::loadTableFromYamlFile('smilies', dirname(__DIR__) . '/sql/smiliesdata.yml');

    if ($countInserted < 1) {
        $module->setErrors('Loading smilies table failed');

        return false;
    }

    return true;
}
