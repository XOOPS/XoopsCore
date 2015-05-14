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
 * @copyright       The XUUPS Project http://sourceforge.net/projects/xuups/
 * @license         GNU GPL V2 or later (http://www.gnu.org/licenses/gpl-2.0.html)
 * @package         Publisher
 * @since           1.0
 * @author          trabis <lusopoemas@gmail.com>
 * @version         $Id$
 */

include_once __DIR__ . "/admin_header.php";

$xoops = Xoops::getInstance();
PublisherUtils::cpHeader();
//publisher_adminMenu(-1, _AM_PUBLISHER_CLONE);
PublisherUtils::openCollapsableBar('clone', 'cloneicon', _AM_PUBLISHER_CLONE, _AM_PUBLISHER_CLONE_DSC);

if (@$_POST['op'] == 'submit') {

    if (!$xoops->security()->check()) {
        $xoops->redirect('clone.php', 3, implode('<br />', $xoops->security()->getErrors()));
    }

    $clone = $_POST['clone'];

    //check if name is valid
    if (empty($clone) || preg_match('/[^a-zA-Z0-9\_\-]/', $clone)) {
        $xoops->redirect('clone.php', 3, sprintf(_AM_PUBLISHER_CLONE_INVALIDNAME, $clone));
        exit();
    }

    // Check wether the cloned module exists or not
    if ($clone && is_dir(\XoopsBaseConfig::get('root-path') . '/modules/' . $clone)) {
        $xoops->redirect('clone.php', 3, sprintf(_AM_PUBLISHER_CLONE_EXISTS, $clone));
    }

    $patterns = array(
        strtolower(PUBLISHER_DIRNAME) => strtolower($clone),
        strtoupper(PUBLISHER_DIRNAME) => strtoupper($clone),
        ucfirst(strtolower(PUBLISHER_DIRNAME)) => ucfirst(strtolower($clone))
    );

    $patKeys = array_keys($patterns);
    $patValues = array_values($patterns);
    publisher_cloneFileFolder(PUBLISHER_ROOT_PATH);
    $logocreated = publisher_createLogo(strtolower($clone));

    $msg = "";
    if (is_dir(\XoopsBaseConfig::get('root-path') . '/modules/' . strtolower($clone))) {
        $msg .= sprintf(_AM_PUBLISHER_CLONE_CONGRAT, "<a href='" . \XoopsBaseConfig::get('url') . "/modules/system/admin.php?fct=modulesadmin'>" . ucfirst(strtolower($clone)) . "</a>") . "<br />\n";
        if (!$logocreated) {
            $msg .= _AM_PUBLISHER_CLONE_IMAGEFAIL;
        }
    } else {
        $msg .= _AM_PUBLISHER_CLONE_FAIL;
    }
    echo $msg;

} else {
    $form = new Xoops\Form\ThemeForm(sprintf(_AM_PUBLISHER_CLONE_TITLE, $publisher->getModule()->getVar('name', 'E')), 'clone', 'clone.php', 'post', true);
    $clone = new Xoops\Form\Text(_AM_PUBLISHER_CLONE_NAME, 'clone', 20, 20, '');
    $clone->setDescription(_AM_PUBLISHER_CLONE_NAME_DSC);
    $form->addElement($clone, true);
    $form->addElement(new Xoops\Form\Hidden('op', 'submit'));
    $form->addElement(new Xoops\Form\Button('', '', XoopsLocale::A_SUBMIT, 'submit'));
    $form->display();
}

// End of collapsable bar
PublisherUtils::closeCollapsableBar('clone', 'cloneicon');
$xoops->footer();

// work around for PHP < 5.0.x
if (!function_exists('file_put_contents')) {
    function file_put_contents($filename, $data, $file_append = false)
    {
        if ($fp = fopen($filename, (!$file_append ? 'w+' : 'a+'))) {
            fputs($fp, $data);
            fclose($fp);
        }
    }
}

// recursive clonning script
function publisher_cloneFileFolder($path)
{
    global $patKeys;
    global $patValues;

    $newPath = str_replace($patKeys[0], $patValues[0], $path);

    if (is_dir($path)) {
        // create new dir
        mkdir($newPath);

        // check all files in dir, and process it
        if ($handle = opendir($path)) {
            while ($file = readdir($handle)) {
                if ($file != '.' && $file != '..' && $file != '.svn') {
                    publisher_cloneFileFolder("{$path}/{$file}");
                }
            }
            closedir($handle);
        }
    } else {

        if (preg_match('/(.jpg|.gif|.png|.zip)$/i', $path)) {
            // image
            copy($path, $newPath);
        } else {
            // file, read it
            $content = file_get_contents($path);
            $content = str_replace($patKeys, $patValues, $content);
            file_put_contents($newPath, $content);
        }
    }
}

function publisher_createLogo($dirname)
{
    if (!extension_loaded("gd")) {
        return false;
    } else {
        $required_functions = array("imagecreatetruecolor", "imagecolorallocate", "imagefilledrectangle", "imagejpeg", "imagedestroy", "imageftbbox");
        foreach ($required_functions as $func) {
            if (!function_exists($func)) {
                return false;
            }
        }
    }

    if (!XoopsLoad::fileExists($imageBase = \XoopsBaseConfig::get('root-path') . "/modules/" . $dirname . "/images/module_logo.png") || !XoopsLoad::fileExists($font = \XoopsBaseConfig::get('root-path') . "/modules/" . $dirname . "/images/VeraBd.ttf")) {
        return false;
    }

    $imageModule = imagecreatefrompng($imageBase);

    //Erase old text
    $grey_color = imagecolorallocate($imageModule, 237, 237, 237);
    imagefilledrectangle($imageModule, 5, 35, 85, 46, $grey_color);

    // Write text
    $text_color = imagecolorallocate($imageModule, 0, 0, 0);
    $space_to_border = (80 - strlen($dirname) * 6.5) / 2;
    imagefttext($imageModule, 8.5, 0, $space_to_border, 45, $text_color, $font, ucfirst($dirname), array());

    // Set transparency color
    $white = imagecolorallocatealpha($imageModule, 255, 255, 255, 127);
    imagefill($imageModule, 0, 0, $white);
    imagecolortransparent($imageModule, $white);
    imagepng($imageModule, \XoopsBaseConfig::get('root-path') . "/modules/" . $dirname . "/images/module_logo.png");
    imagedestroy($imageModule);
    return true;
}
