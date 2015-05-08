<?php
/*
 You may not change or alter any portion of this comment or credits
 of supporting developers from this source code or any supporting source code
 which is considered copyrighted (c) material of the original comment or credit authors.

 This program is distributed in the hope that it will be useful,
 but WITHOUT ANY WARRANTY; without even the implied warranty of
 MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
 */

use Xoops\Core\Database\Connection;
use Xoops\Core\Kernel\XoopsObject;
use Xoops\Core\Kernel\XoopsObjectHandler;
use Xoops\Core\Kernel\XoopsPersistableObjectHandler;
use Xoops\Core\Kernel\Criteria;
use Xoops\Core\Kernel\CriteriaCompo;

/**
 * @copyright       The XUUPS Project http://sourceforge.net/projects/xuups/
 * @license         GNU GPL V2 or later (http://www.gnu.org/licenses/gpl-2.0.html)
 * @package         Publisher
 * @since           1.0
 * @author          trabis <lusopoemas@gmail.com>
 * @author          The SmartFactory <www.smartfactory.ca>
 * @version         $Id$
 */

include_once dirname(__DIR__) . '/include/common.php';

// File status
define("_PUBLISHER_STATUS_FILE_NOTSET", -1);
define("_PUBLISHER_STATUS_FILE_ACTIVE", 1);
define("_PUBLISHER_STATUS_FILE_INACTIVE", 2);

class PublisherFile extends XoopsObject
{
    /**
     * @var Publisher
     * @access public
     */
    public $publisher = null;

    /**
     * @param null|int $id
     */
    public function __construct($id = null)
    {
        $this->publisher = Publisher::getInstance();
        $this->initVar("fileid", XOBJ_DTYPE_INT, 0, false);
        $this->initVar("itemid", XOBJ_DTYPE_INT, null, true);
        $this->initVar("name", XOBJ_DTYPE_TXTBOX, null, true, 255);
        $this->initVar("description", XOBJ_DTYPE_TXTBOX, null, false, 255);
        $this->initVar("filename", XOBJ_DTYPE_TXTBOX, null, true, 255);
        $this->initVar("mimetype", XOBJ_DTYPE_TXTBOX, null, true, 64);
        $this->initVar("uid", XOBJ_DTYPE_INT, 0, false);
        $this->initVar("datesub", XOBJ_DTYPE_INT, null, false);
        $this->initVar("status", XOBJ_DTYPE_INT, 1, false);
        $this->initVar("notifypub", XOBJ_DTYPE_INT, 0, false);
        $this->initVar("counter", XOBJ_DTYPE_INT, null, false);
        if (isset($id)) {
            $file = $this->publisher->getFileHandler()->get($id);
            foreach ($file->vars as $k => $v) {
                $this->assignVar($k, $v['value']);
            }
        }
    }

    /**
     * @param string $post_field
     * @param array  $allowed_mimetypes
     * @param array  $errors
     *
     * @return bool
     */
    public function checkUpload($post_field, $allowed_mimetypes = array(), &$errors)
    {
        $errors = array();
        if (!$this->publisher->getMimetypeHandler()->checkMimeTypes($post_field)) {
            $errors[] = _CO_PUBLISHER_MESSAGE_WRONG_MIMETYPE;
            return false;
        }
        if (empty($allowed_mimetypes)) {
            $allowed_mimetypes = $this->publisher->getMimetypeHandler()->getArrayByType();
        }
        $maxfilesize = $this->publisher->getConfig('maximum_filesize');
        $maxfilewidth = $this->publisher->getConfig('maximum_image_width');
        $maxfileheight = $this->publisher->getConfig('maximum_image_height');
        $uploader = new XoopsMediaUploader(PublisherUtils::getUploadDir(), $allowed_mimetypes, $maxfilesize, $maxfilewidth, $maxfileheight);
        if ($uploader->fetchMedia($post_field)) {
            return true;
        } else {
            $errors = array_merge($errors, $uploader->getErrors(false));
            return false;
        }
    }

    /**
     * @param string      $post_field
     * @param array       $allowed_mimetypes
     * @param array       $errors
     *
     * @return bool
     */
    public function storeUpload($post_field, $allowed_mimetypes = array(), &$errors)
    {
        $itemid = $this->getVar('itemid');
        if (empty($allowed_mimetypes)) {
            $allowed_mimetypes = $this->publisher->getMimetypeHandler()->getArrayByType();
        }
        $maxfilesize = $this->publisher->getConfig('maximum_filesize');
        $maxfilewidth = $this->publisher->getConfig('maximum_image_width');
        $maxfileheight = $this->publisher->getConfig('maximum_image_height');
        if (!is_dir(PublisherUtils::getUploadDir())) {
            mkdir(PublisherUtils::getUploadDir(), 0757);
        }
        $uploader = new XoopsMediaUploader(PublisherUtils::getUploadDir() . '/', $allowed_mimetypes, $maxfilesize, $maxfilewidth, $maxfileheight);
        if ($uploader->fetchMedia($post_field)) {
            $uploader->setTargetFileName($itemid . "_" . $uploader->getMediaName());
            if ($uploader->upload()) {
                $this->setVar('filename', $uploader->getSavedFileName());
                if ($this->getVar('name') == '') {
                    $this->setVar('name', $this->getNameFromFilename());
                }
                $this->setVar('mimetype', $uploader->getMediaType());
                return true;
            } else {
                $errors = array_merge($errors, $uploader->getErrors(false));
                return false;
            }
        } else {
            $errors = array_merge($errors, $uploader->getErrors(false));
            return false;
        }
    }

    /**
     * @param null|array $allowed_mimetypes
     * @param bool       $force
     * @param bool       $doupload
     *
     * @return bool
     */
    public function store($allowed_mimetypes = null, $force = true, $doupload = true)
    {
        if ($this->isNew()) {
            $errors = array();
            if ($doupload) {
                $ret = $this->storeUpload('item_upload_file', $allowed_mimetypes, $errors);
            } else {
                $ret = true;
            }
            if (!$ret) {
                foreach ($errors as $error) {
                    $this->setErrors($error);
                }
                return false;
            }
        }
        return $this->publisher->getFileHandler()->insert($this, $force);
    }

    /**
     * @param string $dateFormat
     * @param string $format
     *
     * @return string
     */
    public function datesub($dateFormat = 's', $format = "S")
    {
        return XoopsLocale::formatTimestamp($this->getVar('datesub', $format), $dateFormat);
    }

    /**
     * @return bool
     */
    public function notLoaded()
    {
        return ($this->getVar('itemid') == 0);
    }

    /**
     * @return string
     */
    public function getFileUrl()
    {
        return PublisherUtils::getUploadDir(false) . $this->getVar('filename');
    }

    /**
     * @return string
     */
    public function getFilePath()
    {
        return PublisherUtils::getUploadDir() . $this->getVar('filename');
    }

    /**
     * @return string
     */
    public function getFileLink()
    {
        return "<a href='" . PUBLISHER_URL . "/visit.php?fileid=" . $this->getVar('fileid') . "'>" . $this->getVar('name') . "</a>";
    }

    /**
     * @return string
     */
    public function getItemLink()
    {
        return "<a href='" . PUBLISHER_URL . "/item.php?itemid=" . $this->getVar('itemid') . "'>" . $this->getVar('name') . "</a>";
    }

    /**
     * Update Counter
     */
    public function updateCounter()
    {
        $this->setVar('counter', $this->getVar('counter') + 1);
        $this->store();
    }

    /**
     * @return string
     */
    public function displayFlash()
    {
        return PublisherUtils::displayFlash($this->getFileUrl());
    }

    /**
     * @return string
     */
    public function getNameFromFilename()
    {
        $ret = $this->getVar('filename');
        $sep_pos = strpos($ret, '_');
        $ret = substr($ret, $sep_pos + 1, strlen($ret) - $sep_pos);
        return $ret;
    }
}

/**
 * Files handler class.
 * This class is responsible for providing data access mechanisms to the data source
 * of File class objects.
 *
 * @author  marcan <marcan@notrevie.ca>
 * @package Publisher
 */
class PublisherFileHandler extends XoopsPersistableObjectHandler
{
    /**
     * @param null|object $db
     */
    public function __construct(Connection $db)
    {
        parent::__construct($db, "publisher_files", 'PublisherFile', "fileid", "name");
    }

    /**
     * delete a file from the database
     *
     * @param XoopsObject|PublisherFile $file reference to the file to delete
     * @param bool   $force
     *
     * @return bool FALSE if failed.
     */
    public function delete(XoopsObject $file, $force = false)
    {
        $ret = false;
        // Delete the actual file
        if (is_file($file->getFilePath()) && unlink($file->getFilePath())) {
            $ret = parent::delete($file, $force);
        }
        return $ret;
    }

    /**
     * delete files related to an item from the database
     *
     * @param object $itemObj reference to the item which files to delete
     *
     * @return bool
     */
    public function deleteItemFiles(&$itemObj)
    {
        if (strtolower(get_class($itemObj)) != 'publisheritem') {
            return false;
        }
        $files = $this->getAllFiles($itemObj->getVar('itemid'));
        $result = true;
        foreach ($files as $file) {
            if (!$this->delete($file)) {
                $result = false;
            }
        }
        return $result;
    }

    /**
     * retrieve all files
     *
     * @param int     $itemid
     * @param int     $status
     * @param int     $limit
     * @param int     $start
     * @param string  $sort
     * @param string  $order
     * @param array   $category
     *
     * @return array array of {@link PublisherFile} objects
     */
    public function &getAllFiles($itemid = 0, $status = -1, $limit = 0, $start = 0, $sort = 'datesub', $order = 'DESC', $category = array())
    {
        $this->table_link = $this->db2->prefix('publisher_items');
        $this->field_object = 'itemid';
        $this->field_link = 'itemid';
        $hasStatusCriteria = false;
        $criteriaStatus = new CriteriaCompo();
        if (is_array($status)) {
            $hasStatusCriteria = true;
            foreach ($status as $v) {
                $criteriaStatus->add(new Criteria('o.status', $v), 'OR');
            }
        } elseif ($status != -1) {
            $hasStatusCriteria = true;
            $criteriaStatus->add(new Criteria('o.status', $status), 'OR');
        }
        $hasCategoryCriteria = false;
        $criteriaCategory = new CriteriaCompo();
        $category = (array)$category;
        if (count($category) > 0 && $category[0] != 0) {
            $hasCategoryCriteria = true;
            foreach ($category as $cat) {
                $criteriaCategory->add(new Criteria('l.categoryid', $cat), 'OR');
            }
        }
        $criteriaItemid = new Criteria('o.itemid', $itemid);
        $criteria = new CriteriaCompo();
        if ($itemid != 0) {
            $criteria->add($criteriaItemid);
        }
        if ($hasStatusCriteria) {
            $criteria->add($criteriaStatus);
        }
        if ($hasCategoryCriteria) {
            $criteria->add($criteriaCategory);
        }
        $criteria->setSort($sort);
        $criteria->setOrder($order);
        $criteria->setLimit($limit);
        $criteria->setStart($start);
        $files = $this->getByLink($criteria, array('o.*'), true);
        return $files;
    }
}
