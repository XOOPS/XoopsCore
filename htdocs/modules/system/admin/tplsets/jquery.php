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
 * Template sets Manager
 *
 * @copyright   The XOOPS Project http://sourceforge.net/projects/xoops/
 * @license     GNU GPL 2 (http://www.gnu.org/licenses/gpl-2.0.html)
 * @author      Maxime Cointin (AKA Kraven30)
 * @package     system
 * @version     $Id$
 */

include dirname(dirname(__DIR__)) . '/header.php';

$xoops = Xoops::getInstance();
$xoops->disableErrorReporting();

if (!$xoops->isUser() || !$xoops->isModule() || !$xoops->user->isAdmin($xoops->module->mid())) {
    exit(XoopsLocale::E_NO_ACCESS_PERMISSION);
}

include_once $xoops->path('modules/system/functions.php');
$xoops->loadLocale('system');

if (isset($_REQUEST["op"])) {
    $op = $_REQUEST["op"];
} else {
    @$op = "default";
}

switch ($op) {
    // Display tree folder
    case "tpls_display_folder":
        $_REQUEST['dir'] = urldecode($_REQUEST['dir']);
        $root = XOOPS_THEME_PATH;
        if (XoopsLoad::fileExists($root . $_REQUEST['dir'])) {
            $files = scandir($root . $_REQUEST['dir']);
            natcasesort($files);
            if (count($files) > 2) { /* The 2 accounts for . and .. */
                echo "<ul class=\"jqueryFileTree\" style=\"display: none;\">";
                // All dirs
                foreach ($files as $file) {

                    if (XoopsLoad::fileExists($root . $_REQUEST['dir'] . $file) && $file != '.' && $file != '..' && is_dir($root . $_REQUEST['dir'] . $file)) {
                        //retirer .svn
                        $file_no_valid = array('.svn', 'icons', 'img', 'images', 'language', 'locale');

                        if (!in_array($file, $file_no_valid)) {
                            echo "<li class=\"directory collapsed\"><a href=\"#\" rel=\"" . htmlentities($_REQUEST['dir'] . $file) . "/\">" . htmlentities($file) . "</a></li>";
                        }
                    }
                }
                // All files
                foreach ($files as $file) {
                    if (XoopsLoad::fileExists($root . $_REQUEST['dir'] . $file) && $file != '.' && $file != '..' && !is_dir($root . $_REQUEST['dir'] . $file) && $file != 'index.html') {
                        $ext = preg_replace('/^.*\./', '', $file);

                        $extensions = array('.tpl', '.html', '.htm', '.css');
                        $extension_verif = strrchr($file, '.');

                        if (in_array($extension_verif, $extensions)) {
                            echo "<li class=\"file ext_$ext\"><a href=\"#\" onclick=\"tpls_edit_file('" . htmlentities($_REQUEST['dir'] . $file) . "', '" . htmlentities($_REQUEST['dir']) . "', '" . htmlentities($file) . "', '" . $ext . "');\" rel=\"tpls_edit_file('" . htmlentities($_REQUEST['dir'] . $file) . "', '" . htmlentities($_REQUEST['dir']) . "', '" . htmlentities($file) . "', '" . $ext . "');\">" . htmlentities($file) . "</a></li>";
                        } else {
                            //echo "<li class=\"file ext_$ext\">" . htmlentities($file) . "</li>";
                        }
                    }
                }
                echo "</ul>";
            }
        }
        break;
    // Edit File
    case 'tpls_edit_file':
        $path_file = realpath(XOOPS_ROOT_PATH . '/themes' . trim($_REQUEST['path_file']));
        $path_file = str_replace('\\', '/', $path_file);

        //Button restore
        if (XoopsLoad::fileExists($path_file . '.back')) {
            $restore = '<button class="ui-corner-all tooltip" type="button" onclick="tpls_restore(\'' . $path_file . '\')" value="' . XoopsLocale::A_RESTORE . '" title="' . XoopsLocale::A_RESTORE . '">
                            <img src="' . system_AdminIcons('revert.png') . '" alt="' . XoopsLocale::A_RESTORE . '" />
                        </button>';
        } else {
            $restore = '';
        }

        $file = XoopsFile::getHandler('file', $path_file);
        $content = $file->read();
        if (empty($content)) {
            echo SystemLocale::EMPTY_FILE;
        }
        $ext = preg_replace('/^.*\./', '', $_REQUEST['path_file']);

        echo '<form name="back" action="admin.php?fct=tplsets&op=tpls_save" method="POST">
              <table border="0">
                  <tr>
                      <td>
                          <div class="xo-btn-actions">
                              <div class="xo-buttons">
                                  <button class="ui-corner-all tooltip" type="submit" value="' . XoopsLocale::A_SAVE . '" title="' . XoopsLocale::A_SAVE . '">
                                      <img src="' . system_AdminIcons('save.png') . '" alt="' . XoopsLocale::A_SAVE . '" />
                                  </button>
                                  ' . $restore . '
                                  <button class="ui-corner-all tooltip" type="button" onclick="$(\'#display_contenu\').hide();$(\'#display_form\').fadeIn(\'fast\');" title="' . XoopsLocale::A_CANCEL . '">
                                      <img src="' . system_AdminIcons('cancel.png') . '" alt="' . XoopsLocale::A_CANCEL . '" />
                                  </button>
                                  <div class="clear"></div>
                             </div>
                         </div>
                      </td>
                  </tr>
                  <tr>
                      <td><textarea id="code_mirror" name="templates" rows=24 cols=110>' . $content . '</textarea></td>
                  </tr>
              </table>';
        echo '<input type="hidden" name="path_file" value="' . $path_file . '"><input type="hidden" name="file" value="' . trim($_REQUEST['file']) . '"><input type="hidden" name="ext" value="' . $ext . '"></form>';
        break;

    // Restore backup file
    case 'tpls_restore':
        $extensions = array('.tpl', '.html', '.htm', '.css');

        //check if the file is inside themes directory
        $valid_dir = stristr(realpath($_REQUEST['path_file']), realpath(XOOPS_ROOT_PATH . '/themes'));

        $old_file = $_REQUEST['path_file'] . '.back';
        $new_file = $_REQUEST['path_file'];

        $extension_verif = strrchr($new_file, '.');
        if ($valid_dir && in_array($extension_verif, $extensions) && XoopsLoad::fileExists($old_file) && XoopsLoad::fileExists($new_file)) {
            if (unlink($new_file)) {
                if (rename($old_file, $new_file)) {
                    echo $xoops->alert('info', SystemLocale::S_RESTORED);
                    exit();
                }
            }
        }
        echo $xoops->alert('error', SystemLocale::E_NOT_RESTORED);
        break;
}
