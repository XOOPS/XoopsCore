<?php
/*
 You may not change or alter any portion of this comment or credits
 of supporting developers from this source code or any supporting source code
 which is considered copyrighted (c) material of the original comment or credit authors.

 This program is distributed in the hope that it will be useful,
 but WITHOUT ANY WARRANTY; without even the implied warranty of
 MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
 */

use Xmf\Module\Session;

/**
 * @copyright       XOOPS Project (http://xoops.org)
 * @license         GNU GPL V2 or later (http://www.gnu.org/licenses/gpl-2.0.html)
 * @package         Publisher
 * @since           1.0
 * @author          trabis <lusopoemas@gmail.com>
 * @author          The SmartFactory <www.smartfactory.ca>
 * @author          trabis <lusopoemas@gmail.com>
 */
class PublisherUtils
{
    /**
     * Includes scripts in HTML header
     *
     * @return void
     */
    public static function cpHeader()
    {
        $xoops = Xoops::getInstance();
        $publisher = Publisher::getInstance();
        $xoops->header();

        $css = array();
        $css[] = $publisher->path('css/publisher.css');
        $xoops->theme()->addBaseStylesheetAssets($css);

        $js = array();
        $js[] = $publisher->path('js/funcs.js');
        $js[] = $publisher->path('js/cookies.js');
        $js[] = $publisher->path('js/ajaxupload.3.9.js');
        $js[] = $publisher->path('js/publisher.js');
        $xoops->theme()->addBaseScriptAssets($js);
    }

    /**
     * Default sorting for a given order
     *
     * @param string $sort
     *
     * @return string
     */
    public static function getOrderBy($sort)
    {
        if (in_array($sort, array("datesub", "counter"))) {
            return 'DESC';
        }

        return 'ASC';
    }

    /**
     * @credits Thanks to Mithandir
     *
     * @param string $str
     * @param int    $start
     * @param int    $length
     * @param string $trimmarker
     *
     * @return string
     */
    public static function substr($str, $start, $length, $trimmarker = '...')
    {
        // if the string is empty, let's get out ;-)
        if ($str == '') {
            return $str;
        }

        // reverse a string that is shortened with '' as trimmarker
        $reversed_string = strrev(XoopsLocale::substr($str, $start, $length, ''));

        // find first space in reversed string
        $position_of_space = strpos($reversed_string, " ", 0);

        // truncate the original string to a length of $length
        // minus the position of the last space
        // plus the length of the $trimmarker
        $truncated_string = XoopsLocale::substr($str, $start, $length - $position_of_space + strlen($trimmarker), $trimmarker);

        return $truncated_string;
    }

    /**
     * @param string $document
     *
     * @return string
     */
    public static function html2text($document)
    {
        // PHP Manual:: function preg_replace
        // $document should contain an HTML document.
        // This will remove HTML tags, javascript sections
        // and white space. It will also convert some
        // common HTML entities to their text equivalent.
        // Credits : newbb2
        $search = array(
            "'<script[^>]*?>.*?</script>'si", // Strip out javascript
            "'<img.*?/>'si", // Strip out img tags
            "'<[\/\!]*?[^<>]*?>'si", // Strip out HTML tags
            "'([\r\n])[\s]+'", // Strip out white space
            "'&(quot|#34);'i", // Replace HTML entities
            "'&(amp|#38);'i", "'&(lt|#60);'i", "'&(gt|#62);'i", "'&(nbsp|#160);'i", "'&(iexcl|#161);'i",
            "'&(cent|#162);'i", "'&(pound|#163);'i", "'&(copy|#169);'i", "'&#(\d+);'e"
        ); // evaluate as php

        $replace = array(
            "", "", "", "\\1", "\"", "&", "<", ">", " ", chr(161), chr(162), chr(163), chr(169), "chr(\\1)"
        );

        $text = preg_replace($search, $replace, $document);

        return $text;
    }

    /**
     * @return string[]
     */
    public static function getAllowedImagesTypes()
    {
        return array(
            'jpg/jpeg', 'image/bmp', 'image/gif', 'image/jpeg', 'image/jpg', 'image/x-png', 'image/png', 'image/pjpeg'
        );
    }

    /**
     * @param bool $withLink
     *
     * @return string
     */
    public static function moduleHome($withLink = true)
    {
        $xoops = Xoops::getInstance();
        $publisher = Publisher::getInstance();

        if (!$publisher->getConfig('format_breadcrumb_modname')) {
            return '';
        }

        if (!$withLink) {
            return $publisher->getModule()->getVar('name');
        } else {
            return '<a href="' . $xoops->url(PUBLISHER_URL) . '/">' . $publisher->getModule()->getVar('name') . '</a>';
        }
    }

    /**
     * Copy a file, or a folder and its contents
     *
     * @author      Aidan Lister <aidan@php.net>
     * @version     1.0.0
     *
     * @param string $source The source
     * @param string $dest   The destination
     *
     * @return bool Returns true on success, false on failure
     */
    public static function copyr($source, $dest)
    {
        // Simple copy for a file
        if (is_file($source)) {
            return copy($source, $dest);
        }

        // Make destination directory
        if (!is_dir($dest)) {
            mkdir($dest);
        }

        // Loop through the folder
        $dir = dir($source);
        while (false !== $entry = $dir->read()) {
            // Skip pointers
            if ($entry == '.' || $entry == '..') {
                continue;
            }

            // Deep copy directories
            if (is_dir("$source/$entry") && ($dest !== "$source/$entry")) {
                self::copyr("$source/$entry", "$dest/$entry");
            } else {
                copy("$source/$entry", "$dest/$entry");
            }
        }

        // Clean up
        $dir->close();

        return true;
    }

    /**
     * @credits Thanks to the NewBB2 Development Team
     * @param string $item
     * @param bool   $getStatus
     *
     * @todo check undefined string
     * @return bool|int|string
     */
    public static function getPathStatus($item, $getStatus = false)
    {
        $publisher = Publisher::getInstance();
        if ($item == 'root') {
            $path = '';
        } else {
            $path = $item;
        }

        $thePath = self::getUploadDir(true, $path);

        if (empty($thePath)) {
            return false;
        }
        if (@is_writable($thePath)) {
            $pathCheckResult = 1;
            $path_status = _AM_PUBLISHER_AVAILABLE;
        } elseif (!@is_dir($thePath)) {
            $pathCheckResult = -1;
            $path_status = _AM_PUBLISHER_NOTAVAILABLE . " <a href='" . $publisher->url("admin/index.php?op=createdir&amp;path={$item}") . "'>" . _AM_PUBLISHER_CREATETHEDIR . "</a>";
        } else {
            $pathCheckResult = -2;
            $path_status = _AM_PUBLISHER_NOTWRITABLE . " <a href='" . $publisher->url("admin/index.php?op=setperm&amp;path={$item}") . "'>" . _AM_SCS_SETMPERM . "</a>";
        }
        if (!$getStatus) {
            return $path_status;
        } else {
            return $pathCheckResult;
        }
    }

    /**
     * @credits Thanks to the NewBB2 Development Team
     *
     * @param string $target
     *
     * @return bool
     */
    public static function mkdir($target)
    {
        // http://www.php.net/manual/en/function.mkdir.php
        // saint at corenova.com
        // bart at cdasites dot com
        if (is_dir($target) || empty($target)) {
            return true; // best case check first
        }

        if (XoopsLoad::fileExists($target) && !is_dir($target)) {
            return false;
        }

        if (self::mkdir(substr($target, 0, strrpos($target, '/')))) {
            if (!XoopsLoad::fileExists($target)) {
                $res = mkdir($target, 0777); // crawl back up & create dir tree
                self::chmod($target);

                return $res;
            }
        }
        $res = is_dir($target);

        return $res;
    }

    /**
     * @credits Thanks to the NewBB2 Development Team
     *
     * @param string $target
     * @param int    $mode
     *
     * @return bool
     */
    public static function chmod($target, $mode = 0777)
    {
        return @chmod($target, $mode);
    }

    /**
     * @param bool $hasPath
     * @param bool $item
     *
     * @return string
     */
    public static function getUploadDir($hasPath = true, $item = false)
    {
        $xoops = Xoops::getInstance();
        if ($item) {
            if ($item == 'root') {
                $item = '';
            } else {
                $item = $item . '/';
            }
        } else {
            $item = '';
        }

        if ($hasPath) {
            return $xoops->path(PUBLISHER_UPLOADS_PATH . '/' . $item);
        } else {
            return $xoops->url(PUBLISHER_UPLOADS_URL . '/' . $item);
        }
    }

    /**
     * @param string $item
     * @param bool   $hasPath
     *
     * @return string
     */
    public static function getImageDir($item = '', $hasPath = true)
    {
        if ($item) {
            $item = "images/{$item}";
        } else {
            $item = "images";
        }

        return self::getUploadDir($hasPath, $item);
    }

    /**
     * @param array $errors
     *
     * @return string
     */
    public static function formatErrors($errors = array())
    {
        $ret = '';
        foreach ($errors as $value) {
            $ret .= '<br /> - ' . $value;
        }

        return $ret;
    }

    /**
     * Check is current user is author of a given article
     *
     * @param object $itemObj
     *
     * @return bool
     */
    public static function IsUserAuthor($itemObj)
    {
        $xoops = Xoops::getInstance();

        return ($xoops->isUser() && is_object($itemObj) && ($xoops->user->getVar('uid') == $itemObj->getVar('uid')));
    }

    /**
     * Check is current user is moderator of a given article
     *
     * @param PublisherItem $itemObj
     *
     * @return bool
     */
    public static function IsUserModerator($itemObj)
    {
        $publisher = Publisher::getInstance();
        $categoriesGranted = $publisher->getPermissionHandler()->getGrantedItems('category_moderation');

        return (is_object($itemObj) && in_array($itemObj->getVar('categoryid'), $categoriesGranted));
    }

    public static function IsUserAdmin()
    {
        return Publisher::getInstance()->isUserAdmin();
    }

    /**
     * Saves permissions for the selected category
     *
     * @param array   $groups     : group with granted permission
     * @param integer $categoryid : categoryid on which we are setting permissions
     * @param string  $perm_name  : name of the permission
     *
     * @todo Move to category class
     * @return boolean : TRUE if the no errors occured
     */
    public static function saveCategoryPermissions($groups, $categoryid, $perm_name)
    {
        $xoops = Xoops::getInstance();
        $publisher = Publisher::getInstance();

        $result = true;

        $module_id = $publisher->getModule()->getVar('mid');
        $gperm_handler = $xoops->getHandlerGroupperm();
        // First, if the permissions are already there, delete them
        $gperm_handler->deleteByModule($module_id, $perm_name, $categoryid);

        // Save the new permissions
        if (count($groups) > 0) {
            foreach ($groups as $group_id) {
                $gperm_handler->addRight($perm_name, $categoryid, $group_id, $module_id);
            }
        }

        return $result;
    }

    /**
     * @param string $tablename
     * @param string $iconname
     * @param string $tabletitle
     * @param string $tabledsc
     * @param bool   $open
     *
     * @return void
     */
    public static function openCollapsableBar($tablename = '', $iconname = '', $tabletitle = '', $tabledsc = '', $open = true)
    {
        $publisher = Publisher::getInstance();
        $image = 'open12.gif';
        $display = 'none';
        if ($open) {
            $image = 'close12.gif';
            $display = 'block';
        }

        echo "<h3 style=\"color: #2F5376; font-weight: bold; font-size: 14px; margin: 6px 0 0 0; \"><a href='javascript:;' onclick=\"toggle('" . $tablename . "'); toggleIcon('" . $iconname . "')\";>";
        echo "<img id='" . $iconname . "' src='" . $publisher->url("images/links/" . $image) . "' alt='' /></a>&nbsp;" . $tabletitle . "</h3>";
        echo "<div id='" . $tablename . "' style='display: " . $display . ";'>";
        if ($tabledsc != '') {
            echo "<span style=\"color: #567; margin: 3px 0 12px 0; font-size: small; display: block; \">" . $tabledsc . "</span>";
        }
    }

    /**
     * @param string $name
     * @param string $icon
     *
     * @return void
     */
    public static function closeCollapsableBar($name, $icon)
    {
        echo "</div>";

        $urls = self::getCurrentUrls();
        $path = $urls['phpself'];

        $cookie_name = $path . '_publisher_collaps_' . $name;
        $cookie_name = str_replace('.', '_', $cookie_name);
        $cookie = self::getCookieVar($cookie_name, '');

        if ($cookie == 'none') {
            echo '
        <script type="text/javascript"><!--
        toggle("' . $name . '"); toggleIcon("' . $icon . '");
        //-->
        </script>
        ';
        }
    }

    /**
     * @param string $name
     * @param string $value
     * @param int    $time
     *
     * @return void
     */
    public static function setCookieVar($name, $value, $time = 0)
    {
        if ($time == 0) {
            $time = time() + 3600 * 24 * 365;
        }
        setcookie($name, $value, $time, '/');
    }

    /**
     * @param string $name
     * @param string $default
     *
     * @return string
     */
    public static function getCookieVar($name, $default = '')
    {
        if (isset($_COOKIE[$name]) && ($_COOKIE[$name] > '')) {
            return $_COOKIE[$name];
        } else {
            return $default;
        }
    }

    /**
     * @return array
     */
    public static function getCurrentUrls()
    {
        $http = strpos(\XoopsBaseConfig::get('url'), "https://") === false ? "http://" : "https://";
        $phpself = $_SERVER['PHP_SELF'];
        $httphost = $_SERVER['HTTP_HOST'];
        $querystring = isset($_SERVER['QUERY_STRING']) ? $_SERVER['QUERY_STRING'] : '';

        if ($querystring != '') {
            $querystring = '?' . $querystring;
        }

        $currenturl = $http . $httphost . $phpself . $querystring;

        $urls = array();
        $urls['http'] = $http;
        $urls['httphost'] = $httphost;
        $urls['phpself'] = $phpself;
        $urls['querystring'] = $querystring;
        $urls['full'] = $currenturl;

        return $urls;
    }

    /**
     * @return string
     */
    public static function getCurrentPage()
    {
        $urls = self::getCurrentUrls();

        return $urls['full'];
    }

    /**
     * @param object $categoryObj
     * @param int    $selectedid
     * @param int    $level
     * @param string $ret
     *
     * @todo move to ccategory class
     * @return string
     */
    public static function addCategoryOption($categoryObj, $selectedid = 0, $level = 0, $ret = '')
    {
        $publisher = Publisher::getInstance();

        $spaces = '';
        for ($j = 0; $j < $level; ++$j) {
            $spaces .= '--';
        }

        $ret .= "<option value='" . $categoryObj->getVar('categoryid') . "'";
        if (is_array($selectedid) && in_array($categoryObj->getVar('categoryid'), $selectedid)) {
            $ret .= " selected='selected'";
        } elseif ($categoryObj->getVar('categoryid') == $selectedid) {
            $ret .= " selected='selected'";
        }
        $ret .= ">" . $spaces . $categoryObj->getVar('name') . "</option>\n";

        $subCategoriesObj = $publisher->getCategoryHandler()->getCategories(0, 0, $categoryObj->getVar('categoryid'));
        if (count($subCategoriesObj) > 0) {
            ++$level;
            foreach ($subCategoriesObj as $subCategoryObj) {
                $ret .= self::addCategoryOption($subCategoryObj, $selectedid, $level);
            }
        }

        return $ret;
    }

    /**
     * @param int    $selectedid
     * @param int    $parentcategory
     * @param bool   $allCatOption
     * @param string $selectname
     *
     * @todo move to category class
     * @return string
     */
    public static function createCategorySelect($selectedid = 0, $parentcategory = 0, $allCatOption = true, $selectname = 'options[0]')
    {
        $publisher = Publisher::getInstance();

        $selectedid = explode(',', $selectedid);

        $ret = "<select name='" . $selectname . "[]' multiple='multiple' size='10'>";
        if ($allCatOption) {
            $ret .= "<option value='0'";
            if (in_array(0, $selectedid)) {
                $ret .= " selected='selected'";
            }
            $ret .= ">" . _MB_PUBLISHER_ALLCAT . "</option>";
        }

        // Creating category objects
        $categoriesObj = $publisher->getCategoryHandler()->getCategories(0, 0, $parentcategory);

        if (count($categoriesObj) > 0) {
            foreach ($categoriesObj as $catID => $categoryObj) {
                $ret .= self::addCategoryOption($categoryObj, $selectedid);
            }
        }
        $ret .= "</select>";

        return $ret;
    }

    /**
     * @param int  $selectedid
     * @param int  $parentcategory
     * @param bool $allCatOption
     *
     * @todo move to category class
     * @return string
     */
    public static function createCategoryOptions($selectedid = 0, $parentcategory = 0, $allCatOption = true)
    {
        $publisher = Publisher::getInstance();

        $ret = "";
        if ($allCatOption) {
            $ret .= "<option value='0'";
            $ret .= ">" . _MB_PUBLISHER_ALLCAT . "</option>\n";
        }

        // Creating category objects
        $categoriesObj = $publisher->getCategoryHandler()->getCategories(0, 0, $parentcategory);
        if (count($categoriesObj) > 0) {
            foreach ($categoriesObj as $categoryObj) {
                $ret .= self::addCategoryOption($categoryObj, $selectedid);
            }
        }

        return $ret;
    }

    /**
     * @param array  $err_arr
     * @param string $reseturl
     *
     * @todo check this undefined strings
     * @return void
     */
    public static function renderErrors(&$err_arr, $reseturl = '')
    {
        if (is_array($err_arr) && count($err_arr) > 0) {
            echo '<div id="readOnly" class="errorMsg" style="border:1px solid #D24D00; background:#FEFECC url(' . PUBLISHER_URL . '/images/important-32.png) no-repeat 7px 50%;color:#333;padding-left:45px;">';

            echo '<h4 style="text-align:left;margin:0; padding-top:0">' . _AM_PUBLISHER_MSG_SUBMISSION_ERR;

            if ($reseturl) {
                echo ' <a href="' . $reseturl . '">[' . _AM_PUBLISHER_TEXT_SESSION_RESET . ']</a>';
            }

            echo '</h4><ul>';

            foreach ($err_arr as $key => $error) {
                if (is_array($error)) {
                    foreach ($error as $err) {
                        echo '<li><a href="#' . $key . '" onclick="var e = xoopsGetElementById(\'' . $key . '\'); e.focus();">' . htmlspecialchars($err) . '</a></li>';
                    }
                } else {
                    echo '<li><a href="#' . $key . '" onclick="var e = xoopsGetElementById(\'' . $key . '\'); e.focus();">' . htmlspecialchars($error) . '</a></li>';
                }
            }
            echo "</ul></div><br />";
        }
    }

    /**
     * Generate publisher URL
     *
     * @param string $page
     * @param array  $vars
     * @param bool   $encodeAmp
     *
     * @return string
     * @credit : xHelp module, developped by 3Dev
     */
    public static function makeURI($page, $vars = array(), $encodeAmp = true)
    {
        $joinStr = '';

        $amp = ($encodeAmp ? '&amp;' : '&');

        if (!count($vars)) {
            return $page;
        }

        $qs = '';
        foreach ($vars as $key => $value) {
            $qs .= $joinStr . $key . '=' . $value;
            $joinStr = $amp;
        }

        return $page . '?' . $qs;
    }

    /**
     * @param string $subject
     *
     * @return string
     */
    public static function tellafriend($subject = '')
    {
        $xoops = Xoops::getInstance();
        if (stristr($subject, '%')) {
            $subject = rawurldecode($subject);
        }
        $target_uri = $xoops->url($_SERVER['REQUEST_URI']);

        return $xoops->url('modules/tellafriend/index.php?target_uri=' . rawurlencode($target_uri) . '&amp;subject=' . rawurlencode($subject));
    }

    /**
     * @param bool $another
     * @param bool $withRedirect
     * @param      $itemObj
     *
     * @return bool|string
     */
    public static function uploadFile($another = false, $withRedirect = true, &$itemObj)
    {
        $xoops = Xoops::getInstance();

        $publisher = Publisher::getInstance();

        $itemid = isset($_POST['itemid']) ? (int)($_POST['itemid']) : 0;
        $uid = $xoops->isUser() ? $xoops->user->getVar('uid') : 0;
        $session = new Session();
        $session->set('publisher_file_filename', isset($_POST['item_file_name']) ? $_POST['item_file_name'] : '');
        $session->set('publisher_file_description', isset($_POST['item_file_description']) ? $_POST['item_file_description'] : '');
        $session->set('publisher_file_status', isset($_POST['item_file_status']) ? (int)($_POST['item_file_status']) : 1);
        $session->set('publisher_file_uid', $uid);
        $session->set('publisher_file_itemid', $itemid);

        if (!is_object($itemObj)) {
            $itemObj = $publisher->getItemHandler()->get($itemid);
        }

        $fileObj = $publisher->getFileHandler()->create();
        $fileObj->setVar('name', isset($_POST['item_file_name']) ? $_POST['item_file_name'] : '');
        $fileObj->setVar('description', isset($_POST['item_file_description']) ? $_POST['item_file_description'] : '');
        $fileObj->setVar('status', isset($_POST['item_file_status']) ? (int)($_POST['item_file_status']) : 1);
        $fileObj->setVar('uid', $uid);
        $fileObj->setVar('itemid', $itemObj->getVar('itemid'));
        $fileObj->setVar('datesub', time());

        // Get available mimetypes for file uploading
        $allowed_mimetypes = $publisher->getMimetypeHandler()->getArrayByType();
        // TODO : display the available mimetypes to the user
        $errors = array();
        /* @var $fileObj PublisherFile */
        if ($publisher->getConfig('perm_upload') && is_uploaded_file($_FILES['item_upload_file']['tmp_name'])) {
            if (!$ret = $fileObj->checkUpload('item_upload_file', $allowed_mimetypes, $errors)) {
                $errorstxt = implode('<br />', $errors);

                $message = sprintf(_CO_PUBLISHER_MESSAGE_FILE_ERROR, $errorstxt);
                if ($withRedirect) {
                    $xoops->redirect("file.php?op=mod&itemid=" . $itemid, 5, $message);
                } else {
                    return $message;
                }
            }
        }

        // Storing the file
        if (!$fileObj->store($allowed_mimetypes)) {
            if ($withRedirect) {
                $xoops->redirect("file.php?op=mod&itemid=" . $fileObj->getVar('itemid'), 3, _CO_PUBLISHER_FILEUPLOAD_ERROR . self::formatErrors($fileObj->getErrors()));
            } else {
                return _CO_PUBLISHER_FILEUPLOAD_ERROR . self::formatErrors($fileObj->getErrors());
            }
        }

        if ($withRedirect) {
            $redirect_page = $another ? 'file.php' : 'item.php';
            $xoops->redirect($redirect_page . "?op=mod&itemid=" . $fileObj->getVar('itemid'), 2, _CO_PUBLISHER_FILEUPLOAD_SUCCESS);
        }

        return true;
    }

    /**
     * @return string
     */
    public static function newFeatureTag()
    {
        $ret = '<span style="padding-right: 4px; font-weight: bold; color: red;">' . _CO_PUBLISHER_NEW_FEATURE . '</span>';

        return $ret;
    }

    /**
     * Smarty truncate_tagsafe modifier plugin
     * Type:     modifier<br>
     * Name:     truncate_tagsafe<br>
     * Purpose:  Truncate a string to a certain length if necessary,
     *           optionally splitting in the middle of a word, and
     *           appending the $etc string or inserting $etc into the middle.
     *           Makes sure no tags are left half-open or half-closed
     *           (e.g. "Banana in a <a...")
     *
     * @author   Monte Ohrt <monte at ohrt dot com>, modified by Amos Robinson
     *           <amos dot robinson at gmail dot com>
     *
     * @param string
     * @param integer
     * @param string
     * @param boolean
     * @param boolean
     *
     * @return string
     */
    public static function truncateTagSafe($string, $length = 80, $etc = '...', $break_words = false)
    {
        if ($length == 0) {
            return '';
        }

        if (strlen($string) > $length) {
            $length -= strlen($etc);
            if (!$break_words) {
                $string = preg_replace('/\s+?(\S+)?$/', '', substr($string, 0, $length + 1));
                $string = preg_replace('/<[^>]*$/', '', $string);
                $string = self::closeTags($string);
            }

            return $string . $etc;
        } else {
            return $string;
        }
    }

    /**
     * @author   Monte Ohrt <monte at ohrt dot com>, modified by Amos Robinson
     *           <amos dot robinson at gmail dot com>
     *
     * @param string $string
     *
     * @return string
     */
    public static function closeTags($string)
    {
        // match opened tags
        if (preg_match_all('/<([a-z\:\-]+)[^\/]>/', $string, $start_tags)) {
            $start_tags = $start_tags[1];
            // match closed tags
            if (preg_match_all('/<\/([a-z]+)>/', $string, $end_tags)) {
                $complete_tags = array();
                $end_tags = $end_tags[1];

                foreach ($start_tags as $val) {
                    $posb = array_search($val, $end_tags);
                    if (is_integer($posb)) {
                        unset($end_tags[$posb]);
                    } else {
                        $complete_tags[] = $val;
                    }
                }
            } else {
                $complete_tags = $start_tags;
            }

            $complete_tags = array_reverse($complete_tags);
            for ($i = 0; $i < count($complete_tags); ++$i) {
                $string .= '</' . $complete_tags[$i] . '>';
            }
        }

        return $string;
    }

    /**
     * @param int $itemid
     *
     * @return string
     */
    public static function ratingBar($itemid)
    {
        $xoops = Xoops::getInstance();
        $publisher = Publisher::getInstance();
        $rating_unitwidth = 30;
        $units = 5;

        $criteria = new Criteria('itemid', $itemid);
        $ratingObjs = $publisher->getRatingHandler()->getObjects($criteria);
        unset($criteria);

        $uid = $xoops->isUser() ? $xoops->user->getVar('uid') : 0;
        $count = count($ratingObjs);
        $current_rating = 0;
        $voted = false;
        $ip = getenv('REMOTE_ADDR');

        /* @var $ratingObj PublisherRating */
        foreach ($ratingObjs as $ratingObj) {
            $current_rating += $ratingObj->getVar('rate');
            if ($ratingObj->getVar('ip') == $ip || ($uid > 0 && $uid == $ratingObj->getVar('uid'))) {
                $voted = true;
            }
        }

        $tense = $count == 1 ? _MD_PUBLISHER_VOTE_lVOTE : _MD_PUBLISHER_VOTE_lVOTES; //plural form votes/vote

        // now draw the rating bar
        $rating_width = @number_format($current_rating / $count, 2) * $rating_unitwidth;
        $rating1 = @number_format($current_rating / $count, 1);
        $rating2 = @number_format($current_rating / $count, 2);

        $groups = $xoops->getUserGroups();
        $gperm_handler = $publisher->getGrouppermHandler();

        if (!$gperm_handler->checkRight('global', _PUBLISHER_RATE, $groups, $publisher->getModule()->getVar('mid'))) {
            $static_rater = array();
            $static_rater[] .= "\n" . '<div class="publisher_ratingblock">';
            $static_rater[] .= '<div id="unit_long' . $itemid . '">';
            $static_rater[] .= '<div id="unit_ul' . $itemid . '" class="publisher_unit-rating" style="width:' . $rating_unitwidth * $units . 'px;">';
            $static_rater[] .= '<div class="publisher_current-rating" style="width:' . $rating_width . 'px;">' . _MD_PUBLISHER_VOTE_RATING . ' ' . $rating2 . '/' . $units . '</div>';
            $static_rater[] .= '</div>';
            $static_rater[] .= '<div class="publisher_static">' . _MD_PUBLISHER_VOTE_RATING . ': <strong> ' . $rating1 . '</strong>/' . $units . ' (' . $count . ' ' . $tense . ') <br /><em>' . _MD_PUBLISHER_VOTE_DISABLE . '</em></div>';
            $static_rater[] .= '</div>';
            $static_rater[] .= '</div>' . "\n\n";

            return join("\n", $static_rater);
        } else {
            $rater = '';
            $rater .= '<div class="publisher_ratingblock">';
            $rater .= '<div id="unit_long' . $itemid . '">';
            $rater .= '<div id="unit_ul' . $itemid . '" class="publisher_unit-rating" style="width:' . $rating_unitwidth * $units . 'px;">';
            $rater .= '<div class="publisher_current-rating" style="width:' . $rating_width . 'px;">' . _MD_PUBLISHER_VOTE_RATING . ' ' . $rating2 . '/' . $units . '</div>';

            for ($ncount = 1; $ncount <= $units; ++$ncount) { // loop from 1 to the number of units
                if (!$voted) { // if the user hasn't yet voted, draw the voting stars
                    $rater .= '<div><a href="' . PUBLISHER_URL . '/rate.php?itemid=' . $itemid . '&amp;rating=' . $ncount . '" title="' . $ncount . ' ' . _MD_PUBLISHER_VOTE_OUTOF . ' ' . $units . '" class="publisher_r' . $ncount . '-unit rater" rel="nofollow">' . $ncount . '</a></div>';
                }
            }

            $rater .= '  </div>';
            $rater .= '  <div';

            if ($voted) {
                $rater .= ' class="publisher_voted"';
            }

            $rater .= '>' . _MD_PUBLISHER_VOTE_RATING . ': <strong> ' . $rating1 . '</strong>/' . $units . ' (' . $count . ' ' . $tense . ')';
            $rater .= '  </div>';
            $rater .= '</div>';
            $rater .= '</div>';

            return $rater;
        }
    }

    /**
     * @param array $allowed_editors
     *
     * @return array
     */
    public static function getEditors($allowed_editors = null)
    {
        $ret = array();
        $nohtml = false;
        $editor_handler = XoopsEditorHandler::getInstance();
        $editors = $editor_handler->getList($nohtml);
        foreach ($editors as $name => $title) {
            $key = self::stringToInt($name);
            if (is_array($allowed_editors)) {
                //for submit page
                if (in_array($key, $allowed_editors)) {
                    $ret[] = $name;
                }
            } else {
                //for admin permissions page
                $ret[$key]['name'] = $name;
                $ret[$key]['title'] = $title;
            }
        }

        return $ret;
    }

    /**
     * @param string $string
     * @param int    $length
     *
     * @return int
     */
    public static function stringToInt($string = '', $length = 5)
    {
        for ($i = 0, $final = "", $string = substr(md5($string), $length); $i < $length; $final .= (int)($string[$i]), ++$i) {
        }

        return (int)($final);
    }

    /**
     * @param string $item
     *
     * @return string
     */
    public static function convertCharset($item)
    {
        if (XoopsLocale::getCharset() == 'UTF-8') {
            return $item;
        }

        if (XoopsLocale::getCharset() != 'windows-1256') {
            return utf8_encode($item);
        }

        if ($unserialize = unserialize($item)) {
            foreach ($unserialize as $key => $value) {
                $unserialize[$key] = @iconv('windows-1256', 'UTF-8', $value);
            }
            $serialize = serialize($unserialize);

            return $serialize;
        } else {
            return @iconv('windows-1256', 'UTF-8', $item);
        }
    }

    public static function seoTitle($title = '', $withExt = true)
    {

        /**
         * if XOOPS ML is present, let's sanitize the title with the current language
         */
        $myts = MyTextSanitizer::getInstance();
        if (method_exists($myts, 'formatForML')) {
            $title = $myts->formatForML($title);
        }

        // Transformation de la chaine en minuscule
        // Codage de la chaine afin d'éviter les erreurs 500 en cas de caractères imprévus
        $title = rawurlencode(strtolower($title));

        // Transformation des ponctuations
        //                 Tab     Space      !        "        #        %        &        '        (        )        ,        /        :        ;        <        =        >        ?        @        [        \        ]        ^        {        |        }        ~       .
        $pattern = array(
            "/%09/", "/%20/", "/%21/", "/%22/", "/%23/", "/%25/", "/%26/", "/%27/", "/%28/", "/%29/", "/%2C/", "/%2F/",
            "/%3A/", "/%3B/", "/%3C/", "/%3D/", "/%3E/", "/%3F/", "/%40/", "/%5B/", "/%5C/", "/%5D/", "/%5E/", "/%7B/",
            "/%7C/", "/%7D/", "/%7E/", "/\./"
        );
        $rep_pat = array(
            "-", "-", "", "", "", "-100", "", "-", "", "", "", "-", "", "", "", "-", "", "", "-at-", "", "-", "", "-",
            "", "-", "", "-", ""
        );
        $title = preg_replace($pattern, $rep_pat, $title);

        // Transformation des caractères accentués
        //                  è        é        ê        ë        ç        à        â        ä        î        ï        ù        ü        û        ô        ö
        $pattern = array(
            "/%B0/", "/%E8/", "/%E9/", "/%EA/", "/%EB/", "/%E7/", "/%E0/", "/%E2/", "/%E4/", "/%EE/", "/%EF/", "/%F9/",
            "/%FC/", "/%FB/", "/%F4/", "/%F6/"
        );
        $rep_pat = array("-", "e", "e", "e", "e", "c", "a", "a", "a", "i", "i", "u", "u", "u", "o", "o");
        $title = preg_replace($pattern, $rep_pat, $title);

        if (sizeof($title) > 0) {
            if ($withExt) {
                $title .= '.html';
            }

            return $title;
        }

        return '';
    }

    /**
     * seoGenUrl
     *
     * @param string  $op
     * @param integer $id
     * @param string  $short_url
     *
     * @return string
     */
    public static function seoGenUrl($op, $id, $short_url = "")
    {
        $publisher = Publisher::getInstance();
        if ($publisher->getConfig('seo_url_rewrite') != 'none') {
            if (!empty($short_url)) {
                $short_url = $short_url . '.html';
            }

            if ($publisher->getConfig('seo_url_rewrite') == 'htaccess') {
                // generate SEO url using htaccess
                return \XoopsBaseConfig::get('url') . '/' . $publisher->getConfig('seo_module_name') . ".${op}.${id}/${short_url}";
            } else {
                if ($publisher->getConfig('seo_url_rewrite') == 'path-info') {
                    // generate SEO url using path-info
                    return $publisher->url("index.php/${op}.${id}/${short_url}");
                } else {
                    die('Unknown SEO method.');
                }
            }
        } else {
            // generate classic url
            switch ($op) {
                case 'category':
                    return $publisher->url("${op}.php?categoryid=${id}");
                case 'item':
                case 'print':
                    return $publisher->url("${op}.php?itemid=${id}");
                default:
                    die('Unknown SEO operation.');
            }
        }
    }

    /**
     * @param string $url
     * @param int    $width
     * @param int    $height
     *
     * @return string
     */
    public static function displayFlash($url, $width = 0, $height = 0)
    {
        if (!$width || !$height) {
            if (!$dimension = @getimagesize($url)) {
                return "<a href='{$url}' target='_blank'>{$url}</a>";
            }
            if (!$width) {
                $height = $dimension[1] * $width / $dimension[0];
            } elseif (!empty($height)) {
                $width = $dimension[0] * $height / $dimension[1];
            } else {
                list($width, $height) = array($dimension[0], $dimension[1]);
            }
        }

        $rp = "<object width='{$width}' height='{$height}' classid='clsid:D27CDB6E-AE6D-11cf-96B8-444553540000' codebase='http://download.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=5,0,0,0'>";
        $rp .= "<param name='movie' value='{$url}'>";
        $rp .= "<param name='QUALITY' value='high'>";
        $rp .= "<PARAM NAME='bgcolor' VALUE='#FFFFFF'>";
        $rp .= "<param name='wmode' value='transparent'>";
        $rp .= "<embed src='{$url}' width='{$width}' height='{$height}' quality='high' bgcolor='#FFFFFF' wmode='transparent'  pluginspage='http://www.macromedia.com/go/getflashplayer' type='application/x-shockwave-flash'></embed>";
        $rp .= "</object>";

        return $rp;
    }
}
