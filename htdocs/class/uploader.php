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
 * XOOPS file uploader
 *
 * @copyright   XOOPS Project (http://xoops.org)
 * @license     GNU GPL 2 or later (http://www.gnu.org/licenses/gpl-2.0.html)
 * @package     class
 * @since       2.0.0
 * @author      Kazumi Ono (http://www.myweb.ne.jp/, http://jp.xoops.org/)
 * @author      Taiwen Jiang <phppp@users.sourceforge.net>
 * @version     $Id$
 */

/**
 * !
 * Example
 *
 * include_once 'uploader.php';
 * $allowed_mimetypes = array('image/gif', 'image/jpeg', 'image/pjpeg', 'image/x-png');
 * $maxfilesize = 50000;
 * $maxfilewidth = 120;
 * $maxfileheight = 120;
 * $uploader = new XoopsMediaUploader(
 *      '/home/xoops/uploads',
 *      $allowed_mimetypes,
 *      $maxfilesize,
 *      $maxfilewidth,
 *      $maxfileheight
 * );
 * if ($uploader->fetchMedia($_POST['uploade_file_name'])) {
 * if (!$uploader->upload()) {
 * echo $uploader->getErrors();
 * } else {
 * echo '<h4>File uploaded successfully!</h4>'
 * echo 'Saved as: ' . $uploader->getSavedFileName() . '<br />';
 * echo 'Full path: ' . $uploader->getSavedDestination();
 * }
 * } else {
 * echo $uploader->getErrors();
 * }
 */

/**
 * XOOPS file uploader
 *
 * @category  Xoops\Core\XoopsMediaUploader
 * @package   XoopsMediaUploader
 * @author    Kazumi Ono (http://www.myweb.ne.jp/, http://jp.xoops.org/)
 * @author    Taiwen Jiang <phppp@users.sourceforge.net>
 * @copyright 2003-2014 XOOPS Project (http://xoops.org)
 * @license   GNU GPL 2 or later (http://www.gnu.org/licenses/gpl-2.0.html)
 * @link      http://xoops.org
 */
class XoopsMediaUploader extends \Xoops\Core\MediaUploader
{
}
