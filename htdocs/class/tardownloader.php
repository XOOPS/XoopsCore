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
 * Send tar files through a http socket
 *
 * @copyright   XOOPS Project (http://xoops.org)
 * @license     GNU GPL 2 or later (http://www.gnu.org/licenses/gpl-2.0.html)
 * @package     class
 * @since       2.0.0
 * @author      Kazumi Ono (http://www.myweb.ne.jp/, http://jp.xoops.org/)
 * @version     $Id$
 */
class XoopsTarDownloader extends XoopsDownloader
{
    /**
     * Constructor
     *
     * @param string $ext      file extension
     * @param string $mimyType Mimetype
     */
    public function __construct($ext = '.tar.gz', $mimyType = 'application/x-gzip')
    {
        $this->archiver = new tar();
        $this->ext = trim($ext);
        $this->mimeType = trim($mimyType);
    }

    /**
     * Add a file to the archive
     *
     * @param string $filepath    Full path to the file
     * @param string $newfilename Filename (if you don't want to use the original)
     *
     * @return false|null
     */
    public function addFile($filepath, $newfilename = null)
    {
        $result = $this->archiver->addFile($filepath);
        if (false === $result) {
            return false;
        }
        if (isset($newfilename)) {
            // dirty, but no other way
            for ($i = 0; $i < $this->archiver->numFiles; ++$i) {
                if ($this->archiver->files[$i]['name'] == $filepath) {
                    $this->archiver->files[$i]['name'] = trim($newfilename);
                    break;
                }
            }
        }
    }

    /**
     * Add a binary file to the archive
     *
     * @param string $filepath    Full path to the file
     * @param string $newfilename Filename (if you don't want to use the original)
     *
     * @return false|null
     */
    public function addBinaryFile($filepath, $newfilename = null)
    {
        $result = $this->archiver->addFile($filepath, true);
        if (false === $result) {
            return false;
        }
        if (isset($newfilename)) {
            // dirty, but no other way
            for ($i = 0; $i < $this->archiver->numFiles; ++$i) {
                if ($this->archiver->files[$i]['name'] == $filepath) {
                    $this->archiver->files[$i]['name'] = trim($newfilename);
                    break;
                }
            }
        }
    }

    /**
     * Add a dummy file to the archive
     *
     * @param string  &$data    Data to write
     * @param string  $filename Name for the file in the archive
     * @param int $time     time
     *
     * @return false|null
     */
    public function addFileData(&$data, $filename, $time = 0)
    {
        $dummyfile = \XoopsBaseConfig::get('caches-path') . '/dummy_' . time() . '.html';
        $fp = @fopen($dummyfile, 'wb');
        if (false === $fp) {
            return false;
        }
        fwrite($fp, $data);
        fclose($fp);
        $result = $this->archiver->addFile($dummyfile);
        unlink($dummyfile);
        if (false === $result) {
            return false;
        }
        // dirty, but no other way
        for ($i = 0; $i < $this->archiver->numFiles; ++$i) {
            if ($this->archiver->files[$i]['name'] == $dummyfile) {
                $this->archiver->files[$i]['name'] = $filename;
                if (0 != $time) {
                    $this->archiver->files[$i]['time'] = $time;
                }
                break;
            }
        }
    }

    /**
     * Add a binary dummy file to the archive
     *
     * @param string  &$data    Data to write
     * @param string  $filename Name for the file in the archive
     * @param int $time     time
     *
     * @return false|null
     */
    public function addBinaryFileData(&$data, $filename, $time = 0)
    {
        $dummyfile = \XoopsBaseConfig::get('caches-path') . '/dummy_' . time() . '.html';
        $fp = @fopen($dummyfile, 'wb');
        if (false === $fp) {
            return false;
        }
        fwrite($fp, $data);
        fclose($fp);
        $result = $this->archiver->addFile($dummyfile, true);
        unlink($dummyfile);
        if (false === $result) {
            return false;
        }
        // dirty, but no other way
        for ($i = 0; $i < $this->archiver->numFiles; ++$i) {
            if ($this->archiver->files[$i]['name'] == $dummyfile) {
                $this->archiver->files[$i]['name'] = $filename;
                if (0 != $time) {
                    $this->archiver->files[$i]['time'] = $time;
                }
                break;
            }
        }
    }

    /**
     * Send the file to the client
     *
     * @param string  $name Filename
     * @param bool $gzip Use GZ compression
     *
     * @return void
     */
    public function download($name, $gzip = true)
    {
        $this->_header($name . $this->ext);
        $str = $this->archiver->toTarOutput($name . $this->ext, $gzip);
        if (false !== $str) {
            echo $str;
        }
    }
}
