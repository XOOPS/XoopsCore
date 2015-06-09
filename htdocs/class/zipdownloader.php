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
 * XOOPS zip downloader
 *
 * @copyright       XOOPS Project (http://xoops.org)
 * @license         GNU GPL 2 or later (http://www.gnu.org/licenses/gpl-2.0.html)
 * @package         class
 * @since           2.0.0
 * @author          Kazumi Ono (AKA onokazu) http://www.myweb.ne.jp/, http://jp.xoops.org/
 * @version         $Id$
 */

/**
 * Abstract base class for forms
 *
 * @author Kazumi Ono <onokazu@xoops.org>
 * @author John Neill <catzwolf@xoops.org>
 * @copyright copyright (c) XOOPS.org
 * @package class
 */
class XoopsZipDownloader extends XoopsDownloader
{
    /**
     * Constructor
     *
     * @param string $ext      extention
     * @param string $mimyType mimi type
     *
     * @return XoopsZipDownloader
     */

    public function __construct($ext = '.zip', $mimyType = 'application/x-zip')
    {
        $this->archiver = new zipfile();
        $this->ext = trim($ext);
        $this->mimeType = trim($mimyType);
    }

    /**
     * Add file
     *
     * @param string $filepath    path
     * @param string $newfilename name
     *
     * @return false|null
     */
    public function addFile($filepath, $newfilename = null)
    {
        // Read in the file's contents
        $fp = @fopen($filepath, "r");
        if ($fp === false) {
            return false;
        }
        $data = fread($fp, filesize($filepath));
        fclose($fp);
        $filename = (isset($newfilename) && trim($newfilename) != '') ? trim($newfilename) : $filepath;
        $result = $this->archiver->addFile($data, $filename, filemtime($filename));
        return $result;
    }

    /**
     * Add Binary File
     *
     * @param string $filepath    path
     * @param string $newfilename name
     *
     * @return false|null
     */
    public function addBinaryFile($filepath, $newfilename = null)
    {
        // Read in the file's contents
        $fp = @fopen($filepath, "rb");
        if ($fp === false) {
            return false;
        }
        $data = fread($fp, filesize($filepath));
        fclose($fp);
        $filename = (isset($newfilename) && trim($newfilename) != '') ? trim($newfilename) : $filepath;
        $result = $this->archiver->addFile($data, $filename, filemtime($filename));
        return $result;
    }

    /**
     * Add File Data
     *
     * @param string &$data    data
     * @param string $filename name
     * @param int    $time     time
     *
     * @return result
     */
    public function addFileData(&$data, $filename, $time = 0)
    {
        $result = $this->archiver->addFile($data, $filename, $time);
        return $result;
    }

    /**
     * Add Binary File Data
     *
     * @param string &$data    data
     * @param string $filename name
     * @param int    $time     file time
     *
     * @return result|null
     */
    public function addBinaryFileData(&$data, $filename, $time = 0)
    {
        $result = $this->addFileData($data, $filename, $time);
        return $result;
    }

    /**
     * Fownload Data as a Zip file
     *
     * @param string $name zip name
     * @param bool   $gzip unused
     *
     * @return void
     */
    public function download($name, $gzip = true)
    {
        $this->_header($name . $this->ext);
        $result = $this->archiver->file();
        if ($result !== false) {
            echo $result;
        }
    }
}
