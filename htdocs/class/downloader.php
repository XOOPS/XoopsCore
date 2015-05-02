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
 * XOOPS downloader
 *
 * @copyright       The XOOPS Project http://sourceforge.net/projects/xoops/
 * @license         GNU GPL 2 or later (http://www.gnu.org/licenses/gpl-2.0.html)
 * @package         class
 * @since           2.0.0
 * @author          Kazumi Ono <onokazu@xoops.org>
 * @version         $Id$
 */

/**
 * Sends non HTML files through a http socket
 *
 * @package class
 */
abstract class XoopsDownloader
{

    /**
     * @var string
     */
    protected $mimetype;

    /**
     * @var string
     */
    protected $ext;

    /**
     * @var XoopsDownloader
     */
    protected $archiver;


    /**
     * Send the HTTP header
     *
     * @param string $filename
     * @access protected
     */
    protected function _header($filename)
    {
        if (function_exists('mb_http_output')) {
            mb_http_output('pass');
        }
        header('Content-Type: ' . $this->mimetype);
        if (preg_match("/MSIE ([0-9]\.[0-9]{1,2})/", $_SERVER['HTTP_USER_AGENT'])) {
            header('Content-Disposition: attachment; filename="' . $filename . '"');
            header('Expires: 0');
            header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
            header('Pragma: public');
        } else {
            header('Content-Disposition: attachment; filename="' . $filename . '"');
            header('Expires: 0');
            header('Pragma: no-cache');
        }
    }

    /**
     * @abstract
     * @param $filepath
     * @param boolean|string $newfilename
     * @return void
     */
     abstract function addFile($filepath, $newfilename = null);

    /**
     * @abstract
     * @param $filepath
     * @param null $newfilename
     * @return void
     */
    abstract function addBinaryFile($filepath, $newfilename = null);

    /**
     * @abstract
     * @param $data
     * @param $filename
     * @param int $time
     * @return void
     */
    abstract function addFileData(&$data, $filename, $time = 0);

    /**
     * @abstract
     * @param $data
     * @param $filename
     * @param int $time
     * @return void
     */
    abstract function addBinaryFileData(&$data, $filename, $time = 0);

    /**
     * @abstract
     * @param $name
     * @param bool $gzip
     * @return void
     */
    abstract function download($name, $gzip = true);
}
