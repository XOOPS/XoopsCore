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
 * @copyright       The XOOPS Project http://sourceforge.net/projects/xoops/
 * @license         GNU GPL 2 (http://www.gnu.org/licenses/old-licenses/gpl-2.0.html)
 * @package         class
 * @since           2.0.0
 * @author          Kazumi Ono (AKA onokazu) http://www.myweb.ne.jp/, http://jp.xoops.org/
 * @version         $Id$
 */

defined('XOOPS_ROOT_PATH') or die('Restricted access');

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
     * @param string $ext
     * @param string $mimyType
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
     * @param string $filepath
     * @param string $newfilename
     */
    public function addFile($filepath, $newfilename = null)
    {
        // Read in the file's contents
        $fp = fopen($filepath, "r");
        $data = fread($fp, filesize($filepath));
        fclose($fp);
        $filename = (isset($newfilename) && trim($newfilename) != '') ? trim($newfilename) : $filepath;
        $this->archiver->addFile($data, $filename, filemtime($filename));
    }

    /**
     * Add Binary File
     *
     * @param string $filepath
     * @param string $newfilename
     */
    public function addBinaryFile($filepath, $newfilename = null)
    {
        // Read in the file's contents
        $fp = fopen($filepath, "rb");
        $data = fread($fp, filesize($filepath));
        fclose($fp);
        $filename = (isset($newfilename) && trim($newfilename) != '') ? trim($newfilename) : $filepath;
        $this->archiver->addFile($data, $filename, filemtime($filename));
    }

    /**
     * Add File Data
     *
     * @param string $data
     * @param string $filename
     * @param int $time
     *
     * @return void
     */
    public function addFileData(&$data, $filename, $time = 0)
    {
        $this->archiver->addFile($data, $filename, $time);
    }

    /**
     * Add Binary File Data
     *
     * @param string $data
     * @param string $filename
     * @param int $time
     *
     * @return void
     */
    public function addBinaryFileData(&$data, $filename, $time = 0)
    {
        $this->addFileData($data, $filename, $time);
    }

    /**
     * Fownload Data as a Zip file
     *
     * @param string $name
     * @param bool $gzip
     */
    public function download($name, $gzip = true)
    {
        $this->_header($name . $this->ext);
        echo $this->archiver->file();
    }
}