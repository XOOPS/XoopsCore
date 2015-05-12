<?php
/**
 * Xlanguage module
 *
 * You may not change or alter any portion of this comment or credits
 * of supporting developers from this source code or any supporting source code
 * which is considered copyrighted (c) material of the original comment or credit authors.
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
 *
 * @copyright       2010-2014 The XOOPS Project http://sourceforge.net/projects/xoops/
 * @license         GNU GPL 2 or later (http://www.gnu.org/licenses/gpl-2.0.html)
 * @package         Xlanguage
 * @since           2.6.0
 * @author          Laurent JEN (Aka DuGris)
 * @version         $Id$
 *
 * @param XoopsModule $module
 *
 * @return bool
 */

function xoops_module_install_xlanguage(XoopsModule $module)
{
    $xoops = Xoops::getInstance();
    xlanguage_mkdirs($xoops->path(\XoopsBaseConfig::get('var-path')) . '/configs/xlanguage');

    return true;
}

/**
 * @param XoopsModule $module
 * @param             $version
 *
 * @return bool
 */
function xoops_module_update_xlanguage(XoopsModule $module, $version)
{
    return xoops_module_install_xlanguage($module);
}

/**
 * @param              $pathname
 * @param mixed|string $pathout
 *
 * @return bool
 */
function xlanguage_mkdirs($pathname, $pathout = \XoopsBaseConfig::get('root-path'))
{
    $xoops = Xoops::getInstance();
    $pathname = substr($pathname, strlen(\XoopsBaseConfig::get('root-path')));
    $pathname = str_replace(DIRECTORY_SEPARATOR, '/', $pathname);

    $dest = $pathout;
    $paths = explode('/', $pathname);

    foreach ($paths as $path) {
        if (!empty($path)) {
            $dest = $dest . '/' . $path;
            if (!is_dir($dest)) {
                if (!mkdir($dest, 0755)) {
                    return false;
                } else {
                    xlanguage_copyfile($xoops->path('uploads'), 'index.html', $dest);
                }
            }
        }
    }

    return true;
}

/**
 * @param $folder_in
 * @param $source_file
 * @param $folder_out
 *
 * @return bool
 */
function xlanguage_copyfile($folder_in, $source_file, $folder_out)
{
    if (!is_dir($folder_out)) {
        if (!xlanguage_mkdirs($folder_out)) {
            return false;
        }
    }

    // Simple copy for a file
    if (is_file($folder_in . '/' . $source_file)) {
        return copy($folder_in . '/' . $source_file, $folder_out . '/' . basename($source_file));
    }

    return false;
}
