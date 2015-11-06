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
 * jQuery File Tree PHP Connector
 * Output a list of files for jQuery File Tree
 *
 * @copyright   The XOOPS Project http://sourceforge.net/projects/xoops/
 * @license     GNU GPL 2 (http://www.gnu.org/licenses/gpl-2.0.html)
 * @author      Maxime Cointin (AKA Kraven30)
 * @package     system
 * @version     $Id$
 */

// Require mainfile
require dirname(dirname(dirname(dirname(dirname(__FILE__))))) . '/mainfile.php' ;

$xoops = Xoops::getInstance();
$xoops->disableErrorReporting();

$system = System::getInstance();

// Include module functions
include $xoops->path('modules/system/include/functions.php');
// Load language
system_loadLanguage('filemanager', 'system');
// Get Action type
$op = $system->cleanVars($_REQUEST, 'op', 'filemanager_display_folder', 'string');

switch ($op) {

    // Display files in tree
    case 'filemanager_display_folder':

        $_REQUEST['dir'] = urldecode($_REQUEST['dir']);
        $root = XOOPS_ROOT_PATH . '/';

        if (file_exists($root . $_REQUEST['dir'])) {
            $files = scandir($root . $_REQUEST['dir']);
            natcasesort($files);

            if (count($files) > 2) { /* The 2 accounts for . and .. */
                echo "<ul class=\"jqueryFileTree\" style=\"display: none;\">";
                if ('/' == $_REQUEST['dir']) {
                    $url = $xoops->url('modules/system/admin.php?fct=filemanager');
                    echo "<a href=\"" . $url . "\"><strong>/</strong></a>";
                }
                // All dirs
                foreach ($files as $file) {
                    if (file_exists($root . $_REQUEST['dir'] . $file) && $file != '.' && $file != '..' && is_dir($root . $_REQUEST['dir'] . $file)) {
                        //retirer .svn
                        $file_no_valid = array('.svn', 'conf', 'db', 'locks', 'hooks', 'cache', 'templates_c');

                        if (!in_array($file, $file_no_valid)) {
                            echo "<li class=\"directory collapsed\"><a href='" . $_REQUEST['dir'] . $file . "' rel=\"" . htmlentities($_REQUEST['dir'] . $file) . "/\">" . htmlentities($file) . "</a></li>";
                        }
                    }
                }
                echo "</ul>";
            }
        }
        break;

    // Display files
    case 'filemanager_display_file':
        $nbcolonnes_file = 4;
        $width = 100 / $nbcolonnes_file;
        $_REQUEST['file'] = urldecode($_REQUEST['file']);
        //For come back and display files
        if ($_REQUEST['status'] == 1) {
            $path_file = $_REQUEST['file'];
        } else {
            $file_arr = explode("/", $_REQUEST['file']);
            $path_file = XOOPS_ROOT_PATH . '/';
            $url_file = XOOPS_URL . '/';

            for ($i = 3; $i < count($file_arr); $i++) {
                $path_file .= $file_arr[$i] . '/';
                $url_file .= $file_arr[$i] . '/';
            }
        }

        if (file_exists($path_file)) {
            $files = scandir($path_file);
            natcasesort($files);
            // The 2 accounts for . and ..
            if (count($files) > 2) {
                echo '
               <div class="xo-btn-actions">
                    <div class="xo-buttons">
                        <button class="ui-corner-all tooltip" type="button" onclick="filemanager_load_tree();filemanager_display_file(\'\', 0)" title="' . _AM_SYSTEM_FILEMANAGER_HOME . '">
                            <img src="' . system_AdminIcons('home.png') . '" alt="' . _AM_SYSTEM_FILEMANAGER_HOME . '" />
                        </button>
                        <button class="ui-corner-all tooltip" onclick="filemanager_add_directory(\'' . $path_file . '\')" title="' . _AM_SYSTEM_FILEMANAGER_ADDDIR . '">
                            <img src="' . system_AdminIcons('folder_add.png') . '" alt="' . _AM_SYSTEM_FILEMANAGER_ADDDIR . '" />
                        </button>';
                $verif = true;
                $protected = array(
                    '', 'class', 'Frameworks', 'images', 'include', 'kernel', 'language', 'locale', 'modules', 'themes',
                    'uploads', 'xoops_lib', 'xoops_data'
                );
                foreach ($protected as $folder) {
                    $root_path = XOOPS_ROOT_PATH . '/' . $folder . '/';
                    if (eregi($root_path, $path_file)) {
                        if (($root_path == $path_file)) {
                            $verif = false;
                        }
                    }
                    if (XOOPS_ROOT_PATH . '/' == $path_file) {
                        $verif = false;
                    }
                }
                if ($verif) {
                    echo '<button class="ui-corner-all tooltip" onclick="filemanager_confirm_delete_directory(\'' . $path_file . '\')" title="' . _AM_SYSTEM_FILEMANAGER_DELDIR . '">
                                    <img src="' . system_AdminIcons('folder_delete.png') . '" alt="' . _AM_SYSTEM_FILEMANAGER_DELDIR . '" />
                                  </button>';
                }
                echo '<button class="ui-corner-all tooltip" onclick="filemanager_add_file(\'' . $path_file . '\')" title="' . _AM_SYSTEM_FILEMANAGER_ADDFILE . '">
                            <img src="' . system_AdminIcons('add.png') . '" alt="' . _AM_SYSTEM_FILEMANAGER_ADDFILE . '" />
                        </button>
                        <button class="ui-corner-all tooltip" onclick="filemanager_upload(\'' . $path_file . '\')" title="' . _AM_SYSTEM_FILEMANAGER_UPLOAD . '">
                            <img src="' . system_AdminIcons('upload.png') . '" alt="' . _AM_SYSTEM_FILEMANAGER_UPLOAD . '" />
                        </button>
                    </div>
                <div class="clear">&nbsp;</div>
                </div>';
                $count_file = 1;
                // All files
                echo '<table cellpadding="0" cellspacing="0"  border="0" align="center">
                        <tr>
                            <td align="center" width="' . $width . '%" style="padding-bottom:12px">';
                foreach ($files as $file) {
                    if (!preg_match('#.back#', $file)) {
                        if (file_exists($path_file . $file) && $file != '.' && $file != '..' && !is_dir($path_file . $file)) {
                            //echo $path_file . $file.'<br />';
                            $unzip = '';
                            $edit = false;
                            $file1 = XoopsFile::getHandler('file', $path_file . $file);
                            $extension_verif = $file1->ext();

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
                                    $unzip = '<img class="cursorpointer" src="./images/icons/untar.png" onclick=\'filemanager_unzip_file("' . $path_file . $file . '", "' . $path_file . '", "' . $file . '");\' width="16" alt="edit" />&nbsp;';
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
                            if ($edit == true) {
                                $edit = '<img class="cursorpointer" src="' . system_AdminIcons('edit.png') . '" onclick=\'filemanager_edit_file("' . $path_file . $file . '", "' . $path_file . '", "' . $file . '", "' . $extension_verif . '");\' width="16" alt="edit" />';
                            } else {
                                $edit = '';
                            }
                            //Chmod
                            $chmod = substr($file1->perms(), 1);

                            $chmod = modify_chmod($chmod, $path_file . $file, $count_file);

                            //Img
                            if ($extension_verif == 'picture') {
                                list($width, $height) = getimagesize($path_file . $file);
                                if ($height > 60) {
                                    $img = '<img src="' . $url_file . $file . '" height="47" title="" alt="" />';
                                } else {
                                    $img = '<img src="' . $url_file . $file . '" title="" alt="" />';
                                }
                            } else {
                                $img = '<img src="./images/mimetypes/' . $extension_verif . '_48.png" title="" alt="" />';
                            }
                            echo '<div style="border: 1px solid #cccccc;">
                                            <table cellpadding="0" cellspacing="0">
                                                <tr class="odd">
                                                    <td align="left">' . $chmod . '</td>
                                                    <td align="right">' . $unzip . $edit . '&nbsp;<img class="cursorpointer" src="' . system_AdminIcons('delete.png') . '" onclick=\'filemanager_confirm_delete_file("' . $path_file . $file . '", "' . $path_file . '");\' width="16" alt="delete" /></td>
                                                </tr>
                                                <tr>
                                                    <td colspan="2" align="center" height="60px"><br />' . $img . '</td>
                                                </tr>
                                                <tr>
                                                    <td colspan="2" align="center">' . $file . '<br /><br /></td>
                                                </tr>
                                            </table>
                                        </div>
                                  </td>';

                            $count_file++;
                            $newline = ($count_file % $nbcolonnes_file == 1) ? true : false;

                            if ($newline) {
                                echo '</tr><tr><td align="center" style="padding-bottom:12px">';
                            } else {
                                echo '<td align="center" style="padding-bottom:12px">';
                            }
                        }
                    }
                }
                echo '</tr></table>';
            }
        }
        break;

    //Edit file
    case 'filemanager_edit_file':
        $path_file = trim($_REQUEST['path_file']);

        if ($_REQUEST['path'] != '') {
            $path = trim($_REQUEST['path']);
        } else {
            $path = XOOPS_ROOT_PATH . '/';
        }

        //Button restore
        if (file_exists($path_file . '.back')) {
            $restore = '<button class="ui-corner-all tooltip" type="button" onclick="filemanager_restore(\'' . $path_file . '\')" value="' . _AM_SYSTEM_FILEMANAGER_RESTORE . '" title="' . _AM_SYSTEM_FILEMANAGER_RESTORE . '">
                            <img src="' . system_AdminIcons('revert.png') . '" alt="' . _AM_SYSTEM_FILEMANAGER_RESTORE . '" />
                        </button>';
        } else {
            $restore = '';
        }

        $file = XoopsFile::getHandler('file', $path_file);
        $content = $file->read();
        if (empty($content)) {
            echo _AM_SYSTEM_FILEMANAGER_EMPTY_FILE;
        }

        $ext = preg_replace('/^.*\./', '', $_REQUEST['file']);

        echo '<form name="back" action="admin.php?fct=filemanager&op=filemanager_save" method="POST">
              <table border="0">
                  <tr>
                      <td>
                          <div class="xo-btn-actions">
                              <div class="xo-buttons">
                                  <button class="ui-corner-all tooltip" type="submit" value="' . _AM_SYSTEM_FILEMANAGER_SAVE . '" title="' . _AM_SYSTEM_FILEMANAGER_SAVE . '">
                                      <img src="' . system_AdminIcons('save.png') . '" alt="' . _AM_SYSTEM_FILEMANAGER_SAVE . '" />
                                  </button>
                                  ' . $restore . '
                                  <button class="ui-corner-all tooltip" type="button" onclick="$(\'#edit_file\').fadeOut(\'fast\');$(\'#display_file\').fadeIn(\'fast\');" title="' . _AM_SYSTEM_FILEMANAGER_CANCEL . '">
                                      <img src="' . system_AdminIcons('cancel.png') . '" alt="' . _AM_SYSTEM_FILEMANAGER_CANCEL . '" />
                                  </button>
                                  <div class="clear"></div>
                             </div>
                         </div>
                    </td>
                </tr>
                <tr><td>
                <textarea id="code_mirror" name="filemanager" rows=24 cols=110>' . $content . '</textarea>
                </td></tr>
              </table>';
        echo '<input type="hidden" name="path_file" value="' . $path_file . '"><input type="hidden" name="path" value="' . $path . '"><input type="hidden" name="file" value="' . trim($_REQUEST['file']) . '"><input type="hidden" name="ext" value="' . $ext . '"></form>';
        break;

    case 'filemanager_unzip_file':
        $path_file = trim($_REQUEST['path_file']);

        if ($_REQUEST['path'] != '') {
            $path = trim($_REQUEST['path']);
        } else {
            $path = XOOPS_ROOT_PATH . '/';
        }
        $file = $_REQUEST['file'];

        XoopsLoad::load('pclzip', 'system');
        XoopsLoad::load('pcltar', 'system');
        $file1 = XoopsFile::getHandler('file', $path_file);
        $extension = $file1->ext();
        switch ($extension) {
            case 'zip':
                $archive = new PclZip($path_file);
                if ($archive->extract(PCLZIP_OPT_PATH, $path) == 0) {
                    echo $xoops->alert('error', _AM_SYSTEM_FILEMANAGER_EXTRACT_ERROR);
                } else {
                    echo $xoops->alert('info', _AM_SYSTEM_FILEMANAGER_EXTRACT_FILE);
                }
                break;
            case 'tar':
            case 'gz':
                PclTarExtract($path_file, $path);
                break;
        }

        break;

    //Confirm delete file
    case 'filemanager_confirm_delete_file':
        echo '<div class="confirmMsg">' . sprintf(_AM_SYSTEM_FILEMANAGER_SUREDEL, $_REQUEST['file']) . '<br /><br /><div class="buttons"><a href="#" class="ui-corner-all" onclick="filemanager_delete_file(\'' . $_REQUEST['path_file'] . '\', \'' . $_REQUEST['path'] . '\');">' . _AM_SYSTEM_FILEMANAGER_DELETE . '</a>&nbsp;&nbsp;<a href="#" class="ui-corner-all" onclick="$(\'#confirm_delete\').hide();filemanager_load_tree(); filemanager_display_file(\'\', 0)">' . _AM_SYSTEM_FILEMANAGER_CANCEL . '</a></div></div>';
        break;

    //Delete one file
    case 'filemanager_delete_file':
        $file = XoopsFile::getHandler('file', $_REQUEST['path_file']);
        if (!$file->delete()) {
            echo $xoops->alert('error',_AM_SYSTEM_FILEMANAGER_ERROR);
        } else {
            echo $xoops->alert('info', _AM_SYSTEM_FILEMANAGER_DELETE_FILE);
        }
        break;

    case 'filemanager_upload':

        if ($_REQUEST['path'] != '') {
            $path = trim($_REQUEST['path']);
        } else {
            $path = XOOPS_ROOT_PATH . '/';
        }

        $form = new Xoops\Form\ThemeForm('', 'upload_form', 'admin.php?fct=filemanager', "post", true);
        $form->setExtra('enctype="multipart/form-data"');
        $form->addElement(new Xoops\Form\File(_AM_SYSTEM_FILEMANAGER_UPLOAD_CHOOSE, 'upload_file'), false);
        $form->addElement(new Xoops\Form\Hidden('op', 'filemanager_upload_save'));
        $form->addElement(new Xoops\Form\Hidden('path', $path));
        $form->addElement(new Xoops\Form\Button('', 'up_button', XoopsLocale::A_SUBMIT, 'submit'));
        echo $form->render();
        break;

    case 'filemanager_add_dir':
        if ($_REQUEST['path'] != '') {
            $path = trim($_REQUEST['path']);
        } else {
            $path = XOOPS_ROOT_PATH . '/';
        }

        $form = new Xoops\Form\Theme\Form('', 'newdir_form', 'admin.php?fct=filemanager', "post", true);
        $form->setExtra('enctype="multipart/form-data"');
        $form->addElement(new Xoops\Form\Text(_AM_SYSTEM_FILEMANAGER_ADDDIR_NAME, 'dir_name', 50, 255), true);
        $form->addElement(new Xoops\Form\Hidden('op', 'filemanager_add_dir_save'));
        $form->addElement(new Xoops\Form\Hidden('path', $path));
        $form->addElement(new Xoops\Form\Button('', 'dir_button', XoopsLocale::A_SUBMIT, 'submit'));
        echo $form->render();
        break;

    //Confirm delete directory
    case 'filemanager_confirm_delete_directory':
        $path = $system->cleanVars($_REQUEST, 'path', '', 'string');
        echo '<div class="confirmMsg">' . sprintf(_AM_SYSTEM_FILEMANAGER_DIR_SUREDEL, $path) . '<br /><br /><div class="buttons"><a href="#" class="ui-corner-all" onclick="filemanager_delete_directory(\'' . $path . '\');">' . _AM_SYSTEM_FILEMANAGER_DELETE . '</a>&nbsp;&nbsp;<a href="#" class="ui-corner-all" onclick="$(\'#confirm_delete\').hide();filemanager_load_tree(); filemanager_display_file(\'\', 0)">' . _AM_SYSTEM_FILEMANAGER_CANCEL . '</a></div></div>';
        break;

    // Delete one directory
    case 'filemanager_delete_directory':
        $path = $system->cleanVars($_REQUEST, 'path', '', 'string');

        function deltree($dossier)
        {
            if (($dir = opendir($dossier)) === false) {
                return false;
            }

            while ($name = readdir($dir)) {
                if ($name === '.' || $name === '..') {
                    continue;
                }
                $full_name = $dossier . '/' . $name;

                if (is_dir($full_name)) {
                    deltree($full_name);
                } else {
                    unlink($full_name);
                }
            }
            closedir($dir);
            @rmdir($dossier);
            return true;
        }

        if (deltree($_REQUEST['path'])) {
            echo $xoops->alert('info',_AM_SYSTEM_FILEMANAGER_DELDIR_OK);
        } else {
            echo $xoops->alert('error',_AM_SYSTEM_FILEMANAGER_DELDIR_NOTOK);
        }
        break;

    case 'filemanager_add_file':
        if ($_REQUEST['path'] != '') {
            $path = trim($_REQUEST['path']);
        } else {
            $path = XOOPS_ROOT_PATH . '/';
        }

        $form = new Xoops\Form\ThemeForm('', 'newdir_form', 'admin.php?fct=filemanager', "post", true);
        $form->setExtra('enctype="multipart/form-data"');
        $form->addElement(new Xoops\Form\Text(_AM_SYSTEM_FILEMANAGER_ADDFILE, 'file_name', 50, 255), true);
        $form->addElement(new Xoops\Form\Hidden('op', 'filemanager_add_file_save'));
        $form->addElement(new Xoops\Form\Hidden('path', $path));
        $form->addElement(new Xoops\Form\Button('', 'dir_button', XoopsLocale::A_SUBMIT, 'submit'));
        echo $form->render();
        break;
        break;

    case 'filemanager_modify_chmod':

        if ($_REQUEST['path_file'] != '') {
            $path = trim($_REQUEST['path_file']);
        } else {
            $path = XOOPS_ROOT_PATH . '/';
        }

        if (chmod($path, '0' . $_REQUEST['chmod'])) {
            $new_chmod = modify_chmod($_REQUEST['chmod'], $path, $_REQUEST['id']);
            echo $new_chmod;
        }
        //echo $_REQUEST['chmod'];
        break;

    //Restore
    case 'filemanager_restore':
        $old_file = $_REQUEST['path_file'] . '.back';
        $new_file = $_REQUEST['path_file'];
        if (file_exists($old_file)) {
            if (unlink($new_file)) {
                if (rename($old_file, $new_file)) {
                    //$xoops->redirect("admin.php?fct=tplsets", 2, XoopsLocale::S_DATABASE_UPDATED);
                } else {
                    //$xoops->redirect("admin.php?fct=tplsets", 2, _AM_SYSTEM_TEMPLATES_RESTORE_ERROR_FILE_RENAME);
                }
            } else {
                //$xoops->redirect("admin.php?fct=tplsets", 2, _AM_SYSTEM_TEMPLATES_RESTORE_ERROR_FILE_DELETE);
            }
        } else {
            //$xoops->redirect("admin.php?fct=tplsets", 2, _AM_SYSTEM_TEMPLATES_RESTORE_ERROR_FILE_EXISTS);
        }
        break;
}
