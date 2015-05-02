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
 *  TinyMCE adapter for XOOPS
 *
 * @copyright       The XOOPS Project http://sourceforge.net/projects/xoops/
 * @license         GNU GPL 2 or later (http://www.gnu.org/licenses/gpl-2.0.html)
 * @package         class
 * @subpackage      editor
 * @since           2.3.0
 * @author          Taiwen Jiang <phppp@users.sourceforge.net>
 * @version         $Id: formtinymce.php 810 2012-09-21 21:26:54Z kris_fr $
 */

XoopsLoad::load('XoopsEditor');

class XoopsFormTinymce extends XoopsEditor
{
    var $language;
    var $width = "100%";
    var $height = "500px";
    var $editor;

    /**
     * Constructor
     *
     * @param    array   $configs  Editor Options
     */
    function __construct($configs)
    {
        $current_path = __FILE__;
        if (DIRECTORY_SEPARATOR != "/") {
            $current_path = str_replace(strpos($current_path, "\\\\", 2) ? "\\\\" : DIRECTORY_SEPARATOR, "/", $current_path);
        }

        $this->rootPath = "/class/xoopseditor/tinymce";
        parent::__construct($configs);
        $this->configs["elements"] = $this->getName();
        $this->configs["language"] = $this->getLanguage();
        $this->configs["rootpath"] = $this->rootPath;
        $this->configs["area_width"] = isset($this->configs["width"]) ? $this->configs["width"] : $this->width;
        $this->configs["area_height"] = isset($this->configs["height"]) ? $this->configs["height"] : $this->height;
        $this->configs["fonts"] = $this->getFonts();
        $this->setClass($this->getName());

        require_once __DIR__ . "/tinymce.php";
        $this->editor = new TinyMCE($this->configs);
    }

    function XoopsFormTinymce($configs)
    {
        $this->__construct($configs);
    }

    /**
     * Renders the Javascript function needed for client-side for validation
     * I'VE USED THIS EXAMPLE TO WRITE VALIDATION CODE
     * http://tinymce.moxiecode.com/punbb/viewtopic.php?id=12616
     *
     * @return    string
     */
    function renderValidationJS()
    {
        if ($this->isRequired() && $eltname = $this->getName()) {
            //$eltname = $this->getName();
            $eltcaption = $this->getCaption();
            $eltmsg = empty($eltcaption) ? sprintf(XoopsLocale::F_ENTER, $eltname) : sprintf(XoopsLocale::F_ENTER, $eltcaption);
            $eltmsg = str_replace('"', '\"', stripslashes($eltmsg));
            $ret = "\n";
            $ret .= "if ( tinyMCE.get('{$eltname}').getContent() == \"\" || tinyMCE.get('{$eltname}').getContent() == null) ";
            $ret .= "{ window.alert(\"{$eltmsg}\"); tinyMCE.get('{$eltname}').focus(); return false; }";
            return $ret;
        }
        return '';
    }

    /**
     * get language
     *
     * @return    string
     */
    function getLanguage()
    {
        if ($this->language) {
            return $this->language;
        }
        if (defined("_XOOPS_EDITOR_TINYMCE_LANGUAGE")) {
            $this->language = strtolower(constant("_XOOPS_EDITOR_TINYMCE_LANGUAGE"));
        } else {
            $this->language = str_replace('_', '-', strtolower(XoopsLocale::getLangCode()));
            if (strtolower(XoopsLocale::getCharset()) == "utf-8") {
                $this->language .= "_utf8";
            }
        }

        return $this->language;
    }

    function getFonts()
    {
        if (empty($this->config["fonts"]) && defined("_XOOPS_EDITOR_TINYMCE_FONTS")) {
            $this->config["fonts"] = constant("_XOOPS_EDITOR_TINYMCE_FONTS");
        }

        return @$this->config["fonts"];
    }

    /**
     * prepare HTML for output
     *
     * @return    string HTML
     */
    function render()
    {
        $ret = $this->editor->render();
        $ret .= parent::render();

        return $ret;
    }

    /**
     * Check if compatible
     *
     * @return
     */
    function isActive()
    {
        return is_readable(\XoopsBaseConfig::get('root-path') . $this->rootPath . "/tinymce.php");
    }
}
?>
