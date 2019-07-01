<?php
/*
 You may not change or alter any portion of this comment or credits
 of supporting developers from this source code or any supporting source code
 which is considered copyrighted (c) material of the original comment or credit authors.

 This program is distributed in the hope that it will be useful,
 but WITHOUT ANY WARRANTY; without even the implied warranty of
 MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
*/
/*
 * Convenience class for handling directories.
 *
 *
 * CakePHP(tm) :  Rapid Development Framework <http://www.cakephp.org/>
 * Copyright 2005-2008, Cake Software Foundation, Inc.
 *                                     1785 E. Sahara Avenue, Suite 490-204
 *                                     Las Vegas, Nevada 89104
 *
 * Licensed under The MIT License
 * Redistributions of files must retain the above copyright notice.
 */

/**
 * Folder engine For XOOPS
 *
 * @category  Xoops\Class\File\Folder
 * @package   Folder
 * @author    Taiwen Jiang <phppp@users.sourceforge.net>
 * @copyright 2005-2008 Cake Software Foundation, Inc.
 * @license   http://www.opensource.org/licenses/mit-license.php The MIT License
 * @version   $Id$
 * @link      http://www.cakefoundation.org/projects/info/cakephp CakePHP(tm) Project
 * @since     CakePHP(tm) v 0.2.9
 */
class XoopsFolderHandler
{
    /**
     * Path to Folder.
     *
     * @var string
     * @access public
     */
    public $path = null;

    /**
     * Sortedness.
     *
     * @var boolean
     * @access public
     */
    public $sort = false;

    /**
     * mode to be used on create.
     *
     * @var int|string
     * @access public
     */
    public $mode = '0755';

    /**
     * holds messages from last method.
     *
     * @var array
     * @access private
     */
    private $messages = [];

    /**
     * holds errors from last method.
     *
     * @var array
     * @access private
     */
    private $errors = false;

    /**
     * holds array of complete directory paths.
     *
     * @var array
     * @access private
     */
    private $directories;

    /**
     * holds array of complete file paths.
     *
     * @var array
     * @access private
     */
    private $files;

    /**
     * Constructor.
     *
     * @param string $path   Path to folder
     * @param bool   $create Create folder if not found
     * @param mixed  $mode   Mode (CHMOD) to apply to created folder, false to ignore
     */
    public function __construct($path = '', $create = true, $mode = false)
    {
        if (empty($path)) {
            $path = \XoopsBaseConfig::get('var-path') . '/caches/xoops_cache';
        }
        if ($mode) {
            $this->mode = intval($mode, 8);
        }
        if (!XoopsLoad::fileExists($path) && true == $create) {
            $this->create($path, $this->mode);
        }
        if (!$this->isAbsolute($path)) {
            $path1 = $this->realpath($path);
            if (false === $path1) {
                throw new InvalidArgumentException($path . ' not found');
            }
            $path = $path1;
        }
        $this->cd($path);
    }

    /**
     * Return current path.
     *
     * @return string Current path
     * @access public
     */
    public function pwd()
    {
        return $this->path;
    }

    /**
     * Change directory to $desired_path.
     *
     * @param string $path Path to the directory to change to
     *
     * @return string|false The new path. Returns false on failure
     * @access public
     */
    public function cd($path)
    {
        $path = $this->realpath($path);
        if (is_dir($path) && XoopsLoad::fileExists($path)) {
            return $this->path = $path;
        }

        return false;
    }

    /**
     * Returns an array of the contents of the current directory, or false on failure.
     * The returned array holds two arrays: one of dirs and one of files.
     *
     * @param bool $sort       sort list or not
     * @param mixed   $exceptions either an array or boolean true will no grab dot files
     *
     * @return mixed Contents of current directory as an array, false on failure
     * @access public
     */
    public function read($sort = true, $exceptions = false)
    {
        $dirs = $files = [];
        $dir = opendir($this->path);
        if (false !== $dir) {
            while (false !== ($n = readdir($dir))) {
                if ('.' === $n || '..' === $n) {
                    continue;
                }
                $item = false;
                if (is_array($exceptions)) {
                    if (!in_array($n, $exceptions)) {
                        $item = $n;
                    }
                } elseif (false === $exceptions || (true === $exceptions && '.' !== $n[0])) {
                    $item = $n;
                }
                if (false !== $item) {
                    if (is_dir($this->addPathElement($this->path, $item))) {
                        $dirs[] = $item;
                    } else {
                        $files[] = $item;
                    }
                }
            }
            if ($sort || $this->sort) {
                sort($dirs);
                sort($files);
            }
            closedir($dir);
        }

        return [
            $dirs, $files,
        ];
    }

    /**
     * Returns an array of all matching files in current directory.
     *
     * @param string $regexp_pattern Preg_match pattern (Defaults to: .*)
     * @param bool   $sort           sort file list or not
     * @param mixed  $exceptions either an array or boolean true will no grab dot files
     *
     * @return array Files that match given pattern
     * @access public
     */
    public function find($regexp_pattern = '.*', $sort = false, $exceptions = false)
    {
        $data = $this->read($sort, $exceptions);
        if (!is_array($data)) {
            return [];
        }
        list($dirs, $files) = $data;
        $found = [];
        foreach ($files as $file) {
            if (preg_match("/^{$regexp_pattern}$/i", $file)) {
                $found[] = $file;
            }
        }

        return $found;
    }

    /**
     * Returns an array of all matching files in and below current directory.
     *
     * @param string $pattern Preg_match pattern (Defaults to: .*)
     * @param bool   $sort    sort files or not
     * @param mixed  $exceptions either an array or boolean true will no grab dot files
     *
     * @return array Files matching $pattern
     * @access public
     */
    public function findRecursive($pattern = '.*', $sort = false, $exceptions = false)
    {
        $startsOn = $this->path;
        $out = $this->findRecursiveHelper($pattern, $sort, $exceptions);
        $this->cd($startsOn);

        return $out;
    }

    /**
     * Private helper function for findRecursive.
     *
     * @param string $pattern Pattern to match against
     * @param bool   $sort    sort files or not.
     * @param mixed  $exceptions either an array or boolean true will no grab dot files
     *
     * @return array Files matching pattern
     * @access private
     */
    private function findRecursiveHelper($pattern, $sort = false, $exceptions = false)
    {
        list($dirs, $files) = $this->read($sort, $exceptions);
        $found = [];
        foreach ($files as $file) {
            if (preg_match("/^{$pattern}$/i", $file)) {
                $found[] = $this->addPathElement($this->path, $file);
            }
        }
        $start = $this->path;
        foreach ($dirs as $dir) {
            $this->cd($this->addPathElement($start, $dir));
            $found = array_merge($found, $this->findRecursive($pattern, $sort, $exceptions));
        }

        return $found;
    }

    /**
     * Returns true if given $path is a Windows path.
     *
     * @param string $path Path to check
     *
     * @return bool true if windows path, false otherwise
     * @access public
     * @static
     */
    public static function isWindowsPath($path)
    {
        if (preg_match('/^[A-Z]:/i', $path) || false !== mb_strpos($path, '\\')) {
            return true;
        }

        return false;
    }

    /**
     * Returns true if given $path is an absolute path.
     *
     * @param string $path Path to check
     *
     * @return bool
     * @access public
     * @static
     */
    public static function isAbsolute($path)
    {
        $path = str_replace('\\', '/', $path);
        $match = preg_match('/^(\/|[A-Z]:\/|\/\/)/', $path);

        return (1 == $match);
    }

    /**
     * Returns a correct set of slashes for given $path. (\ for Windows paths and / for other paths.)
     *
     * @param string $path Path to check
     *
     * @return string Set of slashes (\ or /)
     * @access public
     * @static
     */
    public static function normalizePath($path)
    {
        if (self::isWindowsPath($path)) {
            return '\\';
        }

        return '/';
    }

    /**
     * Returns a correct set of slashes for given $path. (\\ for Windows paths and / for other paths.)
     *
     * @param string $path Path to check
     *
     * @return string Set of slashes ("\\" or "/")
     * @access public
     * @static
     */
    public static function correctSlashFor($path)
    {
        if (self::isWindowsPath($path)) {
            return '\\';
        }

        return '/';
    }

    /**
     * Returns $path with added terminating slash (corrected for Windows or other OS).
     *
     * @param string $path Path to check
     *
     * @return string Path with ending slash
     * @access public
     * @static
     */
    public static function slashTerm($path)
    {
        if (self::isSlashTerm($path)) {
            return $path;
        }

        return $path . self::correctSlashFor($path);
    }

    /**
     * Returns $path with $element added, with correct slash in-between.
     *
     * @param string $path    Path
     * @param string $element Element to and at end of path
     *
     * @return string Combined path
     * @access public
     * @static
     */
    public static function addPathElement($path, $element)
    {
        return self::slashTerm($path) . $element;
    }

    /**
     * Returns true if the File is in a given XoopsPath.
     *
     * @param string $path path to look for file in
     *
     * @return bool
     * @access public
     */
    public function inXoopsPath($path = '')
    {
        $xoops_root_path = \XoopsBaseConfig::get('root-path');
        $dir = mb_substr($this->slashTerm($xoops_root_path), 0, -1);
        $newdir = $dir . ('/' === $path[0] ? '' : '/') . $path;

        return $this->inPath($newdir);
    }

    /**
     * Returns true if the File is in given path.
     *
     * @param string $path    Path to search
     * @param bool   $reverse reverse lookup
     *
     * @return bool
     */
    public function inPath($path = '', $reverse = false)
    {
        $dir = $this->slashTerm($path);
        $current = $this->slashTerm($this->pwd());
        $dir = str_replace('\\', '/', $dir);
        $current = str_replace('\\', '/', $current);
        if (!$reverse) {
            $return = mb_strpos($current, $dir);
        } else {
            $return = mb_strpos($dir, $current);
        }

        return (false !== $return);
    }

    /**
     * Change the mode on a directory structure recursively.
     *
     * @param string   $path       The path to chmod
     * @param int|bool $mode       octal value 0755
     * @param bool     $recursive  chmod recursively
     * @param array    $exceptions array of files, directories to skip
     *
     * @return bool Returns TRUE on success, FALSE on failure
     * @access public
     */
    public function chmod($path, $mode = false, $recursive = true, $exceptions = [])
    {
        if (!$mode) {
            $mode = $this->mode;
        }
        if (false === $recursive && is_dir($path)) {
            if (chmod($path, intval($mode, 8))) {
                $this->messages[] = sprintf('%s changed to %s', $path, $mode);

                return true;
            }
            $this->errors[] = sprintf('%s NOT changed to %s', $path, $mode);

            return false;
        }
        if (is_dir($path)) {
            list($paths) = $this->tree($path);
            foreach ($paths as $fullpath) {
                $check = explode('/', $fullpath);
                $count = count($check);

                if (in_array($check[$count - 1], $exceptions)) {
                    continue;
                }

                if (chmod($fullpath, intval($mode, 8))) {
                    $this->messages[] = sprintf('%s changed to %s', $fullpath, $mode);
                } else {
                    $this->errors[] = sprintf('%s NOT changed to %s', $fullpath, $mode);
                }
            }
            if (empty($this->errors)) {
                return true;
            }
        }

        return false;
    }

    /**
     * Returns an array of nested directories and files in each directory
     *
     * @param string  $path   the directory path to build the tree from
     * @param bool $hidden return hidden files and directories
     * @param string  $type   either file or dir. null returns both files and directories
     *
     * @return mixed array of nested directories and files in each directory
     * @access public
     */
    public function tree($path, $hidden = true, $type = null)
    {
        $path = rtrim($path, '/');
        $this->files = [];
        $this->directories = [
            $path,
        ];
        $directories = [];
        while (count($this->directories)) {
            $dir = array_pop($this->directories);
            $this->treeHelper($dir, $hidden);
            array_push($directories, $dir);
        }
        if (null === $type) {
            return [
                $directories, $this->files,
            ];
        }
        if ('dir' === $type) {
            return $directories;
        }

        return $this->files;
    }

    /**
     * Private method to list directories and files in each directory
     *
     * @param string  $path   path name of directory
     * @param bool $hidden show hidden files
     *
     * @access private
     *
     * @return void
     */
    private function treeHelper($path, $hidden)
    {
        if (is_dir($path)) {
            $dirHandle = opendir($path);
            while (false !== ($item = readdir($dirHandle))) {
                if ('.' === $item || '..' === $item) {
                    continue;
                }
                $found = false;
                if ((true === $hidden) || (false === $hidden && '.' !== $item[0])) {
                    $found = $path . '/' . $item;
                }
                if (false !== $found) {
                    if (is_dir($found)) {
                        array_push($this->directories, $found);
                    } else {
                        array_push($this->files, $found);
                    }
                }
            }
            closedir($dirHandle);
        }
    }

    /**
     * Create a directory structure recursively.
     *
     * @param string   $pathname The directory structure to create
     * @param int|bool $mode     octal value 0755
     *
     * @return bool Returns TRUE on success, FALSE on failure
     * @access public
     */
    public function create($pathname, $mode = false)
    {
        if (is_dir($pathname) || empty($pathname)) {
            return true;
        }
        if (!$mode) {
            $mode = $this->mode;
        }
        if (is_file($pathname)) {
            $this->errors[] = sprintf('%s is a file', $pathname);

            return true;
        }
        $nextPathname = mb_substr($pathname, 0, mb_strrpos($pathname, '/'));
        if ($this->create($nextPathname, $mode)) {
            if (!XoopsLoad::fileExists($pathname)) {
                if (mkdir($pathname, intval($mode, 8))) {
                    $this->messages[] = sprintf('%s created', $pathname);

                    return true;
                }
                $this->errors[] = sprintf('%s NOT created', $pathname);

                return false;
            }
        }

        return true;
    }

    /**
     * Returns the size in bytes of this Folder.
     *
     * @return int
     */
    public function dirsize()
    {
        return $this->dirsize2($this->path, 0);
    }

    /**
     * Accumulate the size of a path, recursively sizing any sub directories
     *
     * @param string $path name of directory being sized
     * @param int    $size starting size
     *
     * @return int accumulated size
     */
    private function dirsize2($path, $size = 0)
    {
        $count = $size;
        $files = array_diff(scandir($path), ['.', '..']);
        foreach ($files as $file) {
            $name = "$path/$file";
            if (is_dir($name)) {
                $count += $this->dirsize2($name, $count);
            } else {
                $count += filesize($name);
            }
        }

        return $count;
    }

    /**
     * Recursively Remove directories if system allow.
     *
     * @param string $path Path of directory to delete
     *
     * @return bool Success
     * @access public
     */
    public function delete($path)
    {
        $files = array_diff(scandir($path), ['.', '..']);
        foreach ($files as $file) {
            $name = "$path/$file";
            if (is_dir($name)) {
                $this->delete($name);
            } else {
                if (@unlink($name)) {
                    $this->messages[] = sprintf('%s removed', $name);
                } else {
                    $this->errors[] = sprintf('%s NOT removed', $name);
                }
            }
        }
        if (false === @rmdir($path)) {
            $this->errors[] = sprintf('%s NOT removed', $path);

            return false;
        }
        $this->messages[] = sprintf('%s removed', $path);

        return true;
    }

    /**
     * Recursive directory copy.
     *
     * @param array $options (to, from, chmod, skip)
     *
     * @return bool
     * @access public
     */
    public function copy($options = [])
    {
        $to = null;
        if (is_string($options)) {
            $to = $options;
            $options = [];
        }
        $options = array_merge(
            [
                'to' => $to,
                'from' => $this->path,
                'mode' => $this->mode,
                'skip' => [],
            ],
            $options
        );

        $fromDir = $options['from'];
        $toDir = $options['to'];
        $mode = $options['mode'];
        if (!$this->cd($fromDir)) {
            $this->errors[] = sprintf('%s not found', $fromDir);

            return false;
        }
        if (!is_dir($toDir)) {
            mkdir($toDir, $mode);
        }
        if (!is_writable($toDir)) {
            $this->errors[] = sprintf('%s not writable', $toDir);

            return false;
        }
        $exceptions = array_merge(['.', '..', '.svn'], $options['skip']);
        $handle = opendir($fromDir);
        if ($handle) {
            while (false !== ($item = readdir($handle))) {
                if (!in_array($item, $exceptions)) {
                    $from = $this->addPathElement($fromDir, $item);
                    $to = $this->addPathElement($toDir, $item);
                    if (is_file($from)) {
                        if (copy($from, $to)) {
                            chmod($to, intval($mode, 8));
                            touch($to, filemtime($from));
                            $this->messages[] = sprintf('%s copied to %s', $from, $to);
                        } else {
                            $this->errors[] = sprintf('%s NOT copied to %s', $from, $to);
                        }
                    }
                    if (is_dir($from) && !XoopsLoad::fileExists($to)) {
                        if (mkdir($to, intval($mode, 8))) {
                            chmod($to, intval($mode, 8));
                            $this->messages[] = sprintf('%s created', $to);
                            $options = array_merge($options, ['to' => $to, 'from' => $from]);
                            $this->copy($options);
                        } else {
                            $this->errors[] = sprintf('%s not created', $to);
                        }
                    }
                }
            }
            closedir($handle);
        } else {
            return false;
        }
        if (!empty($this->errors)) {
            return false;
        }

        return true;
    }

    /**
     * Recursive directory move.
     *
     * @param array $options (to, from, chmod, skip)
     *
     * @return string|false Success
     * @access public
     */
    public function move($options)
    {
        $to = null;
        if (is_string($options)) {
            $to = $options;
            $options = (array)$options;
        }
        $options = array_merge(
            [
                'to' => $to,
                'from' => $this->path,
                'mode' => $this->mode,
                'skip' => [],
            ],
            $options
        );
        if ($this->copy($options)) {
            if ($this->delete($options['from'])) {
                return $this->cd($options['to']);
            }
        }

        return false;
    }

    /**
     * get messages from latest method
     *
     * @return array
     * @access public
     */
    public function messages()
    {
        return $this->messages;
    }

    /**
     * get error from latest method
     *
     * @return array
     * @access public
     */
    public function errors()
    {
        return $this->errors;
    }

    /**
     * Get the real path (taking ".." and such into account)
     *
     * @param string $path Path to resolve
     *
     * @return string The resolved path
     */
    public function realpath($path)
    {
        return realpath($path);
    }

    /**
     * Returns true if given $path ends in a slash (i.e. is slash-terminated).
     *
     * @param string $path Path to check
     *
     * @return bool true if path ends with slash, false otherwise
     * @access public
     * @static
     */
    public static function isSlashTerm($path)
    {
        if (preg_match('/[\/\\\]$/', $path)) {
            return true;
        }

        return false;
    }
}
