<?php

namespace XoopsModules\Publisher;

/*
 You may not change or alter any portion of this comment or credits
 of supporting developers from this source code or any supporting source code
 which is considered copyrighted (c) material of the original comment or credit authors.

 This program is distributed in the hope that it will be useful,
 but WITHOUT ANY WARRANTY; without even the implied warranty of
 MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
 */

use RuntimeException;
use Xoops\Core\Kernel\XoopsObject;
use XoopsLocale;
use XoopsMediaUploader;
use XoopsModules\Publisher;

/**
 * @copyright       The XUUPS Project http://sourceforge.net/projects/xuups/
 * @license         GNU GPL V2 or later (http://www.gnu.org/licenses/gpl-2.0.html)
 * @package         Publisher
 * @since           1.0
 * @author          trabis <lusopoemas@gmail.com>
 * @author          The SmartFactory <www.smartfactory.ca>
 * @version         $Id$
 */
require_once \dirname(__DIR__) . '/include/common.php';

// File status
\define('_PUBLISHER_STATUS_FILE_NOTSET', -1);
\define('_PUBLISHER_STATUS_FILE_ACTIVE', 1);
\define('_PUBLISHER_STATUS_FILE_INACTIVE', 2);

/**
 * Class File
 * @package XoopsModules\Publisher
 */
class File extends XoopsObject
{
    /**
     * @var Helper
     * @access public
     */
    public $helper = null;

    /**
     * @param null|int $id
     */
    public function __construct($id = null)
    {
        $this->helper = Helper::getInstance();
        $this->initVar('fileid', \XOBJ_DTYPE_INT, 0, false);
        $this->initVar('itemid', \XOBJ_DTYPE_INT, null, true);
        $this->initVar('name', \XOBJ_DTYPE_TXTBOX, null, true, 255);
        $this->initVar('description', \XOBJ_DTYPE_TXTBOX, null, false, 255);
        $this->initVar('filename', \XOBJ_DTYPE_TXTBOX, null, true, 255);
        $this->initVar('mimetype', \XOBJ_DTYPE_TXTBOX, null, true, 64);
        $this->initVar('uid', \XOBJ_DTYPE_INT, 0, false);
        $this->initVar('datesub', \XOBJ_DTYPE_INT, null, false);
        $this->initVar('status', \XOBJ_DTYPE_INT, 1, false);
        $this->initVar('notifypub', \XOBJ_DTYPE_INT, 0, false);
        $this->initVar('counter', \XOBJ_DTYPE_INT, null, false);
        if (isset($id)) {
            $file = $this->helper->getFileHandler()->get($id);
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
    public function checkUpload($post_field, $allowed_mimetypes, &$errors): ?bool
    {
        $errors = [];
        if (!$this->helper->getMimetypeHandler()->checkMimeTypes($post_field)) {
            $errors[] = _CO_PUBLISHER_MESSAGE_WRONG_MIMETYPE;

            return false;
        }
        if (empty($allowed_mimetypes)) {
            $allowed_mimetypes = $this->helper->getMimetypeHandler()->getArrayByType();
        }
        $maxfilesize = $this->helper->getConfig('maximum_filesize');
        $maxfilewidth = $this->helper->getConfig('maximum_image_width');
        $maxfileheight = $this->helper->getConfig('maximum_image_height');
        $uploader = new XoopsMediaUploader(Publisher\Utils::getUploadDir(), $allowed_mimetypes, $maxfilesize, $maxfilewidth, $maxfileheight);
        if ($uploader->fetchMedia($post_field)) {
            return true;
        }

        $errors = \array_merge($errors, $uploader->getErrors(false));

        return false;
    }

    /**
     * @param string $post_field
     * @param array  $allowed_mimetypes
     * @param array  $errors
     *
     * @return bool
     */
    public function storeUpload($post_field, $allowed_mimetypes, &$errors): ?bool
    {
        $itemid = $this->getVar('itemid');
        if (empty($allowed_mimetypes)) {
            $allowed_mimetypes = $this->helper->getMimetypeHandler()->getArrayByType();
        }
        $maxfilesize = $this->helper->getConfig('maximum_filesize');
        $maxfilewidth = $this->helper->getConfig('maximum_image_width');
        $maxfileheight = $this->helper->getConfig('maximum_image_height');
        if (!\is_dir(Publisher\Utils::getUploadDir())) {
            if (!\mkdir($concurrentDirectory = Publisher\Utils::getUploadDir(), 0757) && !\is_dir($concurrentDirectory)) {
                throw new RuntimeException(\sprintf('Directory "%s" was not created', $concurrentDirectory));
            }
        }
        $uploader = new XoopsMediaUploader(Publisher\Utils::getUploadDir() . '/', $allowed_mimetypes, $maxfilesize, $maxfilewidth, $maxfileheight);
        if ($uploader->fetchMedia($post_field)) {
            $uploader->setTargetFileName($itemid . '_' . $uploader->getMediaName());
            if ($uploader->upload()) {
                $this->setVar('filename', $uploader->getSavedFileName());
                if ('' == $this->getVar('name')) {
                    $this->setVar('name', $this->getNameFromFilename());
                }
                $this->setVar('mimetype', $uploader->getMediaType());

                return true;
            }

            $errors = \array_merge($errors, $uploader->getErrors(false));

            return false;
        }
        $errors = \array_merge($errors, $uploader->getErrors(false));

        return false;
    }

    /**
     * @param null|array $allowed_mimetypes
     * @param bool       $force
     * @param bool       $doupload
     */
    public function store($allowed_mimetypes = null, $force = true, $doupload = true): bool
    {
        if ($this->isNew()) {
            $errors = [];
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

        return $this->helper->getFileHandler()->insert($this, $force);
    }

    /**
     * @param string $dateFormat
     * @param string $format
     */
    public function datesub($dateFormat = 's', $format = 'S'): string
    {
        return XoopsLocale::formatTimestamp($this->getVar('datesub', $format), $dateFormat);
    }

    public function notLoaded(): bool
    {
        return (0 == $this->getVar('itemid'));
    }

    public function getFileUrl(): string
    {
        return Publisher\Utils::getUploadDir(false) . $this->getVar('filename');
    }

    public function getFilePath(): string
    {
        return Publisher\Utils::getUploadDir() . $this->getVar('filename');
    }

    public function getFileLink(): string
    {
        return "<a href='" . \PUBLISHER_URL . '/visit.php?fileid=' . $this->getVar('fileid') . "'>" . $this->getVar('name') . '</a>';
    }

    public function getItemLink(): string
    {
        return "<a href='" . \PUBLISHER_URL . '/item.php?itemid=' . $this->getVar('itemid') . "'>" . $this->getVar('name') . '</a>';
    }

    /**
     * Update Counter
     */
    public function updateCounter(): void
    {
        $this->setVar('counter', $this->getVar('counter') + 1);
        $this->store();
    }

    public function displayFlash(): string
    {
        return Publisher\Utils::displayFlash($this->getFileUrl());
    }

    public function getNameFromFilename(): string
    {
        $ret = $this->getVar('filename');
        $sep_pos = \mb_strpos($ret, '_');
        $ret = \mb_substr($ret, $sep_pos + 1, \mb_strlen($ret) - $sep_pos);

        return $ret;
    }
}
