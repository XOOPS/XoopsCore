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
 * Upgrader from 2.0.18 to 2.3.0
 *
 * See the enclosed file license.txt for licensing information.
 * If you did not receive this file, get it at http://www.fsf.org/copyleft/gpl.html
 *
 * @copyright   The XOOPS project http://www.xoops.org/
 * @license     http://www.fsf.org/copyleft/gpl.html GNU General Public License (GPL)
 * @package     upgrader
 * @since       2.3.0
 * @author      Taiwen Jiang <phppp@users.sourceforge.net>
 * @version     $Id$
 */
class PathStuffController
{
    public $xoopsPath = [
            'lib' => '',
            'data' => '',
            ];
    public $path_lookup = [
            'data' => 'VAR_PATH',
            'lib' => 'PATH',
            ];

    public $validPath = [
            'data' => 0,
            'lib' => 0,
            ];

    public $permErrors = [
            'data' => null,
            ];

    public function PathStuffController()
    {
        if (isset($_SESSION['settings']['VAR_PATH'])) {
            foreach ($this->path_lookup as $req => $sess) {
                $this->xoopsPath[$req] = $_SESSION['settings'][$sess];
            }
        } else {
            $path = XOOPS_ROOT_PATH;
            if (defined('XOOPS_PATH')) {
                $this->xoopsPath['lib'] = XOOPS_PATH;
            } elseif (defined('XOOPS_TRUST_PATH')) {
                $this->xoopsPath['lib'] = XOOPS_TRUST_PATH;
            } else {
                $this->xoopsPath['lib'] = dirname($path) . '/xoops_lib';
                if (!is_dir($this->xoopsPath['lib'] . '/')) {
                    $this->xoopsPath['lib'] = $path . '/xoops_lib';
                }
            }
            if (defined('XOOPS_VAR_PATH')) {
                $this->xoopsPath['data'] = XOOPS_VAR_PATH;
            } else {
                $this->xoopsPath['data'] = dirname($path) . '/xoops_data';
                if (!is_dir($this->xoopsPath['data'] . '/')) {
                    $this->xoopsPath['data'] = $path . '/xoops_data';
                }
            }
        }
    }

    public function execute()
    {
        $this->readRequest();
        $valid = $this->validate();
        if ('POST' == $_SERVER['REQUEST_METHOD'] && 'path' == @$_POST['task']) {
            foreach ($this->path_lookup as $req => $sess) {
                $_SESSION['settings'][$sess] = $this->xoopsPath[$req];
            }
            if ($valid) {
                return $_SESSION['settings'];
            }

            return false;
        }
    }

    public function readRequest()
    {
        if ('POST' == $_SERVER['REQUEST_METHOD'] && 'path' == @$_POST['task']) {
            $request = $_POST;
            foreach ($this->path_lookup as $req => $sess) {
                if (isset($request[$req])) {
                    $request[$req] = str_replace('\\', '/', trim($request[$req]));
                    if ('/' == mb_substr($request[$req], -1)) {
                        $request[$req] = mb_substr($request[$req], 0, -1);
                    }
                    $this->xoopsPath[$req] = $request[$req];
                }
            }
        }
    }

    public function validate()
    {
        foreach (array_keys($this->xoopsPath) as $path) {
            if ($this->checkPath($path)) {
                $this->checkPermissions($path);
            }
        }
        $validPaths = (array_sum(array_values($this->validPath)) == count(array_keys($this->validPath))) ? 1 : 0;
        $validPerms = true;
        foreach ($this->permErrors as $key => $errs) {
            if (empty($errs)) {
                continue;
            }
            foreach ($errs as $path => $status) {
                if (empty($status)) {
                    $validPerms = false;
                    break;
                }
            }
        }

        return ($validPaths && $validPerms);
    }

    public function checkPath($PATH = '')
    {
        $ret = 1;
        if ('lib' == $PATH || empty($PATH)) {
            $path = 'lib';
            if (is_dir($this->xoopsPath[$path]) && is_readable($this->xoopsPath[$path])) {
                $this->validPath[$path] = 1;
            }
            $ret *= $this->validPath[$path];
        }
        if ('data' == $PATH || empty($PATH)) {
            $path = 'data';
            if (is_dir($this->xoopsPath[$path]) && is_readable($this->xoopsPath[$path])) {
                $this->validPath[$path] = 1;
            }
            $ret *= $this->validPath[$path];
        }

        return $ret;
    }

    public function setPermission($parent, $path, &$error)
    {
        if (is_array($path)) {
            foreach (array_keys($path) as $item) {
                if (is_string($item)) {
                    $error[$parent . '/' . $item] = $this->makeWritable($parent . '/' . $item);
                    if (empty($path[$item])) {
                        continue;
                    }
                    foreach ($path[$item] as $child) {
                        $this->setPermission($parent . '/' . $item, $child, $error);
                    }
                } else {
                    $error[$parent . '/' . $path[$item]] = $this->makeWritable($parent . '/' . $path[$item]);
                }
            }
        } else {
            $error[$parent . '/' . $path] = $this->makeWritable($parent . '/' . $path);
        }
    }

    public function checkPermissions($path = 'data')
    {
        $paths = [
            'data' => [
                'caches' => [
                    'xoops_cache',
                    'smarty_cache',
                    'smarty_compile',
                    ],
                'configs',
                ],
            ];
        $errors = [
            'data' => null,
            ];
        if (!isset($this->xoopsPath[$path])) {
            return false;
        }
        if (!isset($paths[$path])) {
            return true;
        }
        $this->setPermission($this->xoopsPath[$path], $paths[$path], $errors[$path]);
        if (in_array(false, $errors[$path])) {
            $this->permErrors[$path] = $errors[$path];

            return false;
        }

        return true;
    }

    /**
     * Write-enable the specified file/folder
     * @param string $path
     * @param string $group
     * @param mixed $create
     * @return false on failure, method (u-ser,g-roup,w-orld) on success
     */
    public function makeWritable($path, $group = false, $create = true)
    {
        if (!file_exists($path)) {
            if (!$create) {
                return false;
            }
            $perm = 6;
            @mkdir($path, octdec('0' . $perm . '00'));
        } else {
            $perm = is_dir($path) ? 6 : 7;
        }
        if (!is_writable($path)) {
            // First try using owner bit
            @chmod($path, octdec('0' . $perm . '00'));
            clearstatcache();
            if (!is_writable($path) && false !== $group) {
                // If group has been specified, try using the group bit
                @chgrp($path, $group);
                @chmod($path, octdec('0' . $perm . $perm . '0'));
            }
            clearstatcache();
            if (!is_writable($path)) {
                @chmod($path, octdec('0' . $perm . $perm . $perm));
            }
        }
        clearstatcache();
        if (is_writable($path)) {
            $info = stat($path);
            //echo $path . ' : ' . sprintf( '%o', $info['mode'] ) . '....';
            if ($info['mode'] & 0002) {
                return 'w';
            } elseif ($info['mode'] & 0020) {
                return 'g';
            }

            return 'u';
        }

        return false;
    }
}
