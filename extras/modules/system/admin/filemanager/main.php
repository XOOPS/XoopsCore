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
 * Filemanager main page
 *
 * @copyright   The XOOPS Project http://sourceforge.net/projects/xoops/
 * @license     GNU GPL 2 (http://www.gnu.org/licenses/gpl-2.0.html)
 * @author      Maxime Cointin (AKA Kraven30)
 * @package     system
 * @version     $Id$
 */

defined('XOOPS_ROOT_PATH') or die('Restricted access');

// Get main instance
$xoops = Xoops::getInstance();
$system = System::getInstance();
$system_breadcrumb = SystemBreadcrumb::getInstance();

// Check users rights
if (!$xoops->isUser() || !$xoops->isModule() || !$xoops->user->isAdmin($xoops->module->mid())) {
    exit(XoopsLocale::E_NO_ACCESS_PERMISSION);
}
//  Check is active
if (!$xoops->getModuleConfig('active_filemanager', 'system')) {
    $xoops->redirect('admin.php', 2, XoopsLocale::E_SECTION_NOT_ACTIVE);
}

// Get Action type
$op = $system->cleanVars($_REQUEST, 'op', 'default', 'string');
// Define main template
$xoopsOption['template_main'] = 'system_filemanager.html';
// Call Header
xoops_cp_header();

$xoops->theme()->addScript('media/jquery/ui/jquery.ui.js');
$xoops->theme()->addScript('media/jquery/jquery.js');
$xoops->theme()->addScript('media/jquery/plugins/jquery.easing.js');
$xoops->theme()->addScript('media/jquery/plugins/jqueryFileTree.js');
$xoops->theme()->addScript('modules/system/js/filemanager.js');
$xoops->theme()->addScript('modules/system/js/admin.js');
$xoops->theme()->addScript('modules/system/js/code_mirror/codemirror.js');
// Define Stylesheet
$xoops->theme()->addStylesheet('modules/system/css/admin.css');
$xoops->theme()->addStylesheet('modules/system/css/code_mirror/docs.css');
$xoops->theme()->addStylesheet('media/jquery/ui/' . $xoops->getModuleConfig('jquery_theme', 'system') . '/ui.all.css');
// Define Breadcrumb and tips
$system_breadcrumb->addLink(_AM_SYSTEM_FILEMANAGER_NAV_MAIN, system_adminVersion('filemanager', 'adminpath'));

switch ($op) {
    default:
        // Assign Breadcrumb menu
        $system_breadcrumb->addHelp(system_adminVersion('filemanager', 'help'));
        $system_breadcrumb->addTips(_AM_SYSTEM_FILEMANAGER_NAV_TIPS);
        $system_breadcrumb->render();

        $xoops->tpl()->assign('index', true);
        $xoops->tpl()->debugging = false;

        $nbcolonnes_file = 4;
        $width = 100 / $nbcolonnes_file;
        $root = XOOPS_ROOT_PATH . '/';
        $url_file = XOOPS_URL . '/';
        $xoops->tpl()->assign('width', $width);

        if (file_exists($root)) {
            $files = scandir($root);
            natcasesort($files);
            if (count($files) > 2) {
                $count_file = 1;
                $file_arr = array();
                $edit = false;
                // All files
                foreach ($files as $file) {
                    if (!preg_match('#.back#', $file)) {
                        if (file_exists($root . $file) && $file != '.' && $file != '..' && !is_dir($root . $file)) {
                            /* @var $folder XoopsFileHandler */
                            $folder = XoopsFile::getHandler('file', $root . $file);
                            $extension_verif = $folder->ext();

                            switch ($extension_verif) {
                                case 'ico':
                                case 'png':
                                case 'gif':
                                case 'jpg':
                                case 'jpeg':
                                    $extension_verif = 'picture';
                                    break;
                                case 'html':
                                case 'htm':
                                    $extension_verif = 'html';
                                    $edit = true;
                                    break;
                                case 'zip':
                                case 'rar':
                                case 'tar':
                                case 'gz':
                                    $extension_verif = 'rar';
                                    $edit = true;
                                    $unzip = '<img class="cursorpointer" src="./images/icons/untar.png" onclick=\'filemanager_unzip_file("' . $root . $file . '", "' . $root . '", "' . $file . '");\' width="16" alt="edit" />&nbsp;';
                                    break;
                                case 'css':
                                    $extension_verif = 'css';
                                    $edit = true;
                                    break;
                                case 'avi':
                                case 'mov':
                                case 'real':
                                case 'flv':
                                case 'swf':
                                    $extension_verif = 'movie';
                                    break;
                                case 'log':
                                    $extension_verif = 'log';
                                    $edit = true;
                                    break;
                                case 'php':
                                    $extension_verif = 'php';
                                    $edit = true;
                                    break;
                                case 'info':
                                case 'htaccess':
                                    $extension_verif = 'info';
                                    break;
                                case 'sql':
                                    $extension_verif = 'sql';
                                    $edit = true;
                                    break;
                                default:
                                    $extension_verif = 'file';
                                    $edit = true;
                                    break;
                            }
                            //Edit ?
                            $file_arr['edit'] = $edit;
                            //File
                            $file_arr['path_file'] = $root . $file;
                            $file_arr['path'] = $root;
                            //Chmod
                            $file_arr['chmod'] = substr($folder->perms(), 1);

                            $file_arr['chmod'] = modify_chmod($file_arr['chmod'], $file_arr['path_file'], $count_file);

                            if ($extension_verif == 'picture') {
                                list($width, $height) = getimagesize($root . $file);
                                if ($height > 60) {
                                    $file_arr['img'] = '<img src="' . $url_file . $file . '" height="47" title="" alt="" />';
                                } else {
                                    $file_arr['img'] = '<img src="' . $url_file . $file . '" title="" alt="" />';
                                }
                            } else {
                                $file_arr['img'] = '<img src="./images/mimetypes/' . $extension_verif . '_48.png" title="" alt="" />';
                            }
                            $file_arr['extension'] = $extension_verif;
                            $file_arr['file'] = htmlentities($file);
                            $count_file++;
                            $file_arr['newline'] = ($count_file % $nbcolonnes_file == 1) ? true : false;
                            $xoops->tpl()->assign('newline', $file_arr['newline']);
                            $xoops->tpl()->append('files', $file_arr);
                        }
                        $edit = false;
                    }
                }
            }
        }
        break;

    //save
    case 'filemanager_save':
        //Save the file or restore file
        if (isset($_REQUEST['path_file'])) {
            //save file
            $copy_file = $_REQUEST['path_file'];
            copy($copy_file, $_REQUEST['path'] . $_REQUEST['file'] . '.back');
            //Save modif
            if (isset($_REQUEST['filemanager'])) {
                $open = fopen("" . $_REQUEST['path_file'] . "", "w+");
                if (!fwrite($open, utf8_encode(stripslashes($_REQUEST['filemanager'])))) {
                    $xoops->redirect("admin.php?fct=filemanager", 2, _AM_SYSTEM_FILEMANAGER_ERROR);
                }
                fclose($open);
            }
            $xoops->redirect("admin.php?fct=filemanager", 2, XoopsLocale::S_DATABASE_UPDATED);
        } else {
            //restore
            $old_file = $_REQUEST['path_file'] . '.back';
            //echo $old_file;
            $new_file = $_REQUEST['path_file'];
            //echo $new_file;
            if (file_exists($old_file)) {
                if (unlink($new_file)) {
                    if (rename($old_file, $new_file)) {
                        $xoops->redirect("admin.php?fct=filemanager", 2, XoopsLocale::S_DATABASE_UPDATED);
                    } else {
                        $xoops->redirect("admin.php?fct=filemanager", 2, _AM_SYSTEM_FILEMANAGER_RESTORE_ERROR_FILE_RENAME);
                    }
                } else {
                    $xoops->redirect("admin.php?fct=filemanager", 2, _AM_SYSTEM_FILEMANAGER_RESTORE_ERROR_FILE_DELETE);
                }
            } else {
                $xoops->redirect("admin.php?fct=filemanager", 2, _AM_SYSTEM_FILEMANAGER_RESTORE_ERROR_FILE_EXISTS);
            }
        }
        break;

    case 'filemanager_upload_save':
        if ($_REQUEST['path'] != '') {
            $path = trim($_REQUEST['path']);
        } else {
            $path = XOOPS_ROOT_PATH . '/';
        }
        $mimetypes = include $xoops->path('include/mimetypes.inc.php');
        $uploader = new XoopsMediaUploader($path, $mimetypes, 500000);
        if ($uploader->fetchMedia('upload_file')) {

            if (!$uploader->upload()) {
                $err[] = $uploader->getErrors();
            }
        }
        if (isset($err)) {
            foreach ($err as $line) {
                echo $line;
            }
        }
        $xoops->redirect("admin.php?fct=filemanager", 2, _AM_SYSTEM_FILEMANAGER_UPLOAD_FILE);
        break;

    case 'filemanager_add_dir_save':
        $path = $system->cleanVars($_REQUEST, 'path', XOOPS_ROOT_PATH . '/', 'string');

        $folder = XoopsFile::getHandler('folder');
        if ($folder->create($path . $_REQUEST['dir_name'], 0777)) {
            $indexFile = XOOPS_ROOT_PATH . "/modules/system/index.html";
            copy($indexFile, $path . $_REQUEST['dir_name'] . "/index.html");
            $xoops->redirect('admin.php?fct=filemanager', 2, _AM_SYSTEM_FILEMANAGER_DIR_SUCCESS);
        } else {
            $xoops->redirect('admin.php?fct=filemanager', 2, _AM_SYSTEM_FILEMANAGER_DIR_ERROR);
        }
        break;

    case 'filemanager_add_file_save':
        $path = $system->cleanVars($_REQUEST, 'path', XOOPS_ROOT_PATH . '/', 'string');
        if ($path == '') {
            $path = XOOPS_ROOT_PATH . '/';
        }
        $open = fopen($path . $_REQUEST['file_name'], "w+");
        fclose($open);
        $xoops->redirect('admin.php?fct=filemanager', 2, _AM_SYSTEM_FILEMANAGER_FILE_SUCCESS);
        //if ($file->create ($path . $_REQUEST['file_name'])) {
        //    $xoops->redirect( 'admin.php?fct=filemanager', 2, _AM_SYSTEM_FILEMANAGER_DIR_SUCCESS );
        //} else {
        //  $xoops->redirect( 'admin.php?fct=filemanager', 2, _AM_SYSTEM_FILEMANAGER_DIR_ERROR );
        //}
        break;
}

xoops_cp_footer();
