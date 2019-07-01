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
 * images module
 *
 * @copyright       XOOPS Project (http://xoops.org)
 * @license         GNU GPL 2 or later (http://www.gnu.org/licenses/gpl-2.0.html)
 * @since           2.6.0
 * @author          Mage Grégory (AKA Mage)
 * @version         $Id$
 */
//index.php
define('_AM_IMAGES_NBCAT', "There are <span class='red bold'>%s</span> categories in our database");
define('_AM_IMAGES_NBIMAGES', "There are <span class='red bold'>%s</span> images in our database");

// Common
define('_AM_IMAGES_ACTIONS', 'Actions');
define('_AM_IMAGES_NAME', 'Name');
define('_AM_IMAGES_DISPLAY', 'Display');
define('_AM_IMAGES_WEIGHT', 'Display order in image manager:');
define('_AM_IMAGES_VIEW', 'View');

// Categories.php
define('_AM_IMAGES_CAT_ADD', 'Add a new category');
define('_AM_IMAGES_CAT_EDIT', 'Edit Category');
define('_AM_IMAGES_CAT_SAVE', 'Category saved');
define('_AM_IMAGES_CAT_NOTSAVE', 'Category not saved');
define('_AM_IMAGES_CAT_DELETE', "Are you sure to delete the category : <span class='bold red'>%s</span><br />and all of its images files?");
define('_AM_IMAGES_CAT_SELECT', 'Select category');
define('_AM_IMAGES_CAT_NBIMAGES', 'Images');
define('_AM_IMAGES_CAT_MAXSIZE', 'Max size');
define('_AM_IMAGES_CAT_MAXWIDTH', 'Max width');
define('_AM_IMAGES_CAT_MAXHEIGHT', 'Max height');
define('_AM_IMAGES_CAT_OFF', 'Display in image manager');
define('_AM_IMAGES_CAT_ON', 'Does not display in image manager');
define('_AM_IMAGES_CAT_INDB', ' Store in the database (as binary "blob" data)');
define('_AM_IMAGES_CAT_ASFILE', ' Store as files (in uploads directory)<br />');
define('_AM_IMAGES_CAT_NAME', 'Category Name:');
define('_AM_IMAGES_CAT_READ_GRP', 'Select groups for image manager use:<br /><br /><span style="font-weight: normal;">These are groups allowed to use the image manager for selecting images but not uploading. Webmaster has automatic access.</span>');
define('_AM_IMAGES_CAT_WRITE_GRP', 'Select groups allowed to upload images:<br /><br /><span style="font-weight: normal;">Typical usage is for moderator and admin groups.</span>');
define('_AM_IMAGES_CAT_DISPLAY', 'Display this category ?');
define('_AM_IMAGES_CAT_STR_TYPE', 'Images are uploaded to:');
define('_AM_IMAGES_CAT_STR_TYOPENG', 'This can not be changed afterwards!');

define('_AM_IMAGES_CAT_SIZE', 'Max size allowed (bytes):');
define('_AM_IMAGES_CAT_WIDTH', 'Max width allowed (pixels):');
define('_AM_IMAGES_CAT_HEIGHT', 'Max height allowed (pixels):');

// images.php
define('_AM_IMAGES_IMG_ADD', 'Add a new images');
define('_AM_IMAGES_IMG_EDIT', 'Edit Image');
define('_AM_IMAGES_IMG_SAVE', 'Image saved');
define('_AM_IMAGES_IMG_DELETE', "Are you sure to delete the image : <span class='bold red'>%s</span>?");
define('_AM_IMAGES_IMG_URL', 'Image url');
define('_AM_IMAGES_IMG_FILE', 'Image file:');
