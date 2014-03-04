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
 * XOOPS form element of dhtmltextarea
 *
 * @copyright       The XOOPS Project http://sourceforge.net/projects/xoops/
 * @license         GNU GPL 2 (http://www.gnu.org/licenses/old-licenses/gpl-2.0.html)
 * @package         class
 * @subpackage      xoopsform
 * @since           2.0.0
 * @author          Kazumi Ono (AKA onokazu) http://www.myweb.ne.jp/, http://jp.xoops.org/
 * @author          Taiwen Jiang <phppp@users.sourceforge.net>
 * @author          Vinod <smartvinu@gmail.com>
 * @version         $Id$
 */

defined('XOOPS_ROOT_PATH') or die('Restricted access');

/**
 *  A textarea with xoopsish formatting and smilie buttons
 *
 */
class XoopsFormDhtmlTextArea extends XoopsFormTextArea
{
    /**
     * Extended HTML editor
     *
     * <p>If an extended HTML editor is set, the renderer will be replaced by the specified editor, usually a visual or WYSIWYG editor.</p>
     *
     * <ul>Developer and user guide:
     *      <li>
     *          <ul>For run-time settings per call
     *              <li>To use an editor pre-configured by {@link XoopsEditor}, e.g. 'fckeditor': <code>$options['editor'] = 'fckeditor';</code></li>
     *              <li>To use a custom editor, e.g. 'MyEditor' class located in "/modules/myeditor/myeditor.php": <code>$options['editor'] = array('MyEditor', XOOPS_ROOT_PATH . "/modules/myeditor/myeditor.php");</code></li>
     *          </ul>
     *      </li>
     *      <li>
     *          <ul>For pre-configured settings, which will force to use a editor if no specific editor is set for call
     *              <li>
     *                  <ul>Set up custom configs: in XOOPS_VAR_PATH . '/configs/xoopsconfig.php' set a editor as default, e.g.
     *                      <li>a pre-configured editor 'fckeditor': <code>return array('editor' => 'fckeditor');</code></li>
     *                      <li>a custom editor 'MyEditor' class located in "/modules/myeditor/myeditor.php": <code>return array('editor' => array('MyEditor', XOOPS_ROOT_PATH . "/modules/myeditor/myeditor.php");</code></li>
     *                  </ul>
     *              </li>
     *              <li>To disable the default editor, in XOOPS_VAR_PATH . '/configs/xoopsconfig.php': <code>return array();</code></li>
     *              <li>To disable the default editor for a specific call: <code>$options['editor'] = 'dhtmltextarea';</code></li>
     *          </ul>
     *      </li>
     * </ul>
     */
    public $htmlEditor = array();

    /**
     * Hidden text
     *
     * @var string
     */
    private $_hiddenText;

    /**
     * @var bool
     */
    public $skipPreview = false;

    /**
     * @var bool
     */
    public $doHtml = false;

    /**
     * @var string
     */
    public $js = '';

    /**
     * @var array
     */
    public $configs = array();

    /**
     * Constructor
     *
     * @param string $caption Caption
     * @param string $name "name" attribute
     * @param string $value Initial text
     * @param int $rows Number of rows
     * @param int $cols Number of columns
     * @param string $hiddentext Identifier for hidden Text
     * @param array $options Extra options
     */
    public function __construct($caption, $name, $value = "", $rows = 5, $cols = 50, $hiddentext = "xoopsHiddenText", $options =
        array())
    {
        static $inLoop = 0;

        $inLoop++;
        // Second loop, invalid, return directly
        if ($inLoop > 2) {
            return;
        }

        // Else, initialize
        parent::__construct($caption, $name, $value, $rows, $cols);
        $this->_hiddenText = $hiddentext;

        if ($inLoop > 1) {
            return;
        }

        $xoops = Xoops::getInstance();
        if (!isset($options['editor'])) {
            if ($editor = $xoops->getConfig('editor')) {
                $options['editor'] = $editor;
            }
        }

        if (!empty($this->htmlEditor) || !empty($options['editor'])) {
            $options['name'] = $this->getName();
            $options['value'] = $this->getValue();
            if (!empty($options['editor'])) {
                $this->htmlEditor = is_array($options['editor']) ? $options['editor'] : array($options['editor']);
            }

            if (count($this->htmlEditor) == 1) {
                $editor_handler = XoopsEditorHandler::getInstance();
                $this->htmlEditor = $editor_handler->get($this->htmlEditor[0], $options);
                if ($inLoop > 1) {
                    $this->htmlEditor = null;
                }
            } else {
                list ($class, $path) = $this->htmlEditor;
                include_once XOOPS_ROOT_PATH . $path;
                if (class_exists($class)) {
                    $this->htmlEditor = new $class($options);
                }
                if ($inLoop > 1) {
                    $this->htmlEditor = null;
                }
            }
        }

        $inLoop = 0;
    }

    /**
     * Prepare HTML for output
     *
     * @return string HTML
     */
    public function render()
    {
        if ($this->htmlEditor && is_object($this->htmlEditor)) {
            if (!isset($this->htmlEditor->isEnabled) || $this->htmlEditor->isEnabled) {
                return $this->htmlEditor->render();
            }
        }
        static $js_loaded;

        $xoops = Xoops::getInstance();
        if ($this->getCols() > $this->getMaxcols()) {
            $maxcols = 5;
        } else {
            $maxcols = $this->getCols();
        }
        $class = ($this->getClass() != '' ? " class='span" . $maxcols . " " . $this->getClass() . "'" : " class='span" . $maxcols . "'");
        $extra = ($this->getExtra() != '' ? " " . $this->getExtra() : '');
        $required = ($this->isRequired() ? ' required' : '');
        $ret = "";
        // actions
        $ret .= $this->codeIcon() . "<br />\n";
        // fonts
        $ret .= $this->fontArray();
        // length checker
        $ret .= "<input type='button' class='btn' onclick=\"XoopsCheckLength('" . $this->getName() . "', '" . @$this->configs['maxlength'] . "', '" . XoopsLocale::F_CURRENT_TEXT_LENGTH . "', '" . XoopsLocale::MAXIMUM_LENGTH . "');\" value=' ? ' title='" . XoopsLocale::CHECK_TEXT_LENGTH . "' />";
        $ret .= "<br />\n";
        // the textarea box
        $ret .= "<textarea" . $class . " id='" . $this->getName() . "' name='" . $this->getName() . "' title='" . $this->getTitle() . "' onselect=\"xoopsSavePosition('" . $this->getName() . "');\" onclick=\"xoopsSavePosition('" . $this->getName() . "');\" onkeyup=\"xoopsSavePosition('" . $this->getName() . "');\" rows='" . $this->getRows() . "'" . $extra . $required . ">" . $this->getValue() . "</textarea><br />\n";

        if (empty($this->skipPreview)) {
            if (!$xoops->theme()) {
                $this->js .= implode("", file(XOOPS_ROOT_PATH . "/class/textsanitizer/image/image.js"));
            } else {
                $xoops->theme()->addScript('/class/textsanitizer/image/image.js', array('type' => 'text/javascript'));
            }
            $button = "<input id='" . $this->getName() . "_preview_button' " . "type='button' " . "class='btn' value='" . XoopsLocale::A_PREVIEW . "' " . "onclick=\"form_instantPreview('" . XOOPS_URL . "', '" . $this->getName() . "','" . XOOPS_URL . "/images', " . intval($this->doHtml) . ", '" . $xoops->security()->createToken() . "')\"" . " />";
            $ret .= "<br />" . "<div id='" . $this->getName() . "_hidden' style='display: block;'> " . "<fieldset>" . "<legend>" . $button . "</legend>" . "<div id='" . $this->getName() . "_hidden_data'>" . XoopsLocale::CLICK_PREVIEW_TO_SEE_CONTENT . "</div>" . "</fieldset>" . "</div>";
        }
        // Load javascript
        if (empty($js_loaded)) {
            $javascript = (($this->js)
                ? '<script type="text/javascript">' . $this->js . '</script>'
                : '') . '<script type="text/javascript" src="' . XOOPS_URL . '/include/formdhtmltextarea.js"></script>';
            $ret = $javascript . $ret;
            $js_loaded = true;
        }
        return $ret;
    }

    /**
     * XoopsFormDhtmlTextArea::codeIcon()
     *
     * @return string
     */
    public function codeIcon()
    {
        $textarea_id = $this->getName();
        $code = "<a name='moresmiley'></a>";
        $code .= "<img src='" . XOOPS_URL . "/images/url.gif' alt='" . XoopsLocale::URL . "' title='" . XoopsLocale::URL . "' onclick='xoopsCodeUrl(\"{$textarea_id}\", \"" . htmlspecialchars(XoopsLocale::ENTER_LINK_URL, ENT_QUOTES) . "\", \"" . htmlspecialchars(XoopsLocale::ENTER_WEBSITE_TITLE, ENT_QUOTES) . "\");' onmouseover='style.cursor=\"hand\"'/>&nbsp;";
        $code .= "<img src='" . XOOPS_URL . "/images/email.gif' alt='" . XoopsLocale::EMAIL . "' title='" . XoopsLocale::EMAIL . "' onclick='xoopsCodeEmail(\"{$textarea_id}\", \"" . htmlspecialchars(XoopsLocale::ENTER_EMAIL, ENT_QUOTES) . "\");'  onmouseover='style.cursor=\"hand\"'/>&nbsp;";
        $code .= "<img src='" . XOOPS_URL . "/images/imgsrc.gif' alt='" . XoopsLocale::IMAGES . "' title='" . XoopsLocale::IMAGES . "' onclick='xoopsCodeImg(\"{$textarea_id}\", \"" . htmlspecialchars(XoopsLocale::ENTER_IMAGE_URL, ENT_QUOTES) . "\", \"" . htmlspecialchars(XoopsLocale::ENTER_IMAGE_POSITION, ENT_QUOTES) . "\", \"" . htmlspecialchars(XoopsLocale::IMAGE_POSITION_DESCRIPTION, ENT_QUOTES) . "\", \"" . htmlspecialchars(XoopsLocale::E_ENTER_IMAGE_POSITION, ENT_QUOTES) . "\", \"" . htmlspecialchars(XoopsLocale::WIDTH, ENT_QUOTES) . "\");'  onmouseover='style.cursor=\"hand\"'/>&nbsp;";

        $myts = MyTextSanitizer::getInstance();
        $extensions = array_filter($myts->config['extensions']);
        foreach (array_keys($extensions) as $key) {
            $extension = $myts->loadExtension($key);
            @list ($encode, $js) = $extension->encode($textarea_id);
            if (empty($encode)) {
                continue;
            }
            $code .= $encode;
            if (!empty($js)) {
                $this->js .= $js;
            }
        }
        $code .= "<img src='" . XOOPS_URL . "/images/code.gif' alt='" . XoopsLocale::SOURCE_CODE . "' title='" . XoopsLocale::SOURCE_CODE . "' onclick='xoopsCodeCode(\"{$textarea_id}\", \"" . htmlspecialchars(XoopsLocale::ENTER_CODE, ENT_QUOTES) . "\");'  onmouseover='style.cursor=\"hand\"'/>&nbsp;";
        $code .= "<img src='" . XOOPS_URL . "/images/quote.gif' alt='" . XoopsLocale::QUOTE . "' title='" . XoopsLocale::QUOTE . "' onclick='xoopsCodeQuote(\"{$textarea_id}\", \"" . htmlspecialchars(XoopsLocale::ENTER_QUOTE, ENT_QUOTES) . "\");' onmouseover='style.cursor=\"hand\"'/>&nbsp;";

        XoopsPreload::getInstance()->triggerEvent('core.class.xoopsform.formdhtmltextarea.codeicon', array(&$code, $this));
        return $code;
    }

    /**
     * @return string
     */
    public function fontArray()
    {
        $textarea_id = $this->getName();
        $hiddentext = $this->_hiddenText;

        $fontStr = "<script type=\"text/javascript\" language=\"JavaScript\">";
        $fontStr .= "var _editor_dialog = ''" . "+ '<select class=\"span2\" id=\'{$textarea_id}Size\' onchange=\'xoopsSetElementAttribute(\"size\", this.options[this.selectedIndex].value, \"{$textarea_id}\", \"{$hiddentext}\");\'>'";
        $fontStr .= "+ '<option value=\'SIZE\'>" . XoopsLocale::SIZE . "</option>'";
        $localeFontSizes = XoopsLocale::getFontSizes();
        foreach ($localeFontSizes as $_val => $_name) {
            $fontStr .= " + '<option value=\'{$_val}\'>{$_name}</option>'";
        }
        $fontStr .= " + '</select> '";
        $fontStr .= "+ '<select class=\"span2\" id=\'{$textarea_id}Font\' onchange=\'xoopsSetElementAttribute(\"font\", this.options[this.selectedIndex].value, \"{$textarea_id}\", \"{$hiddentext}\");\'>'";
        $fontStr .= "+ '<option value=\'FONT\'>" . XoopsLocale::FONT . "</option>'";
        $localeFonts = XoopsLocale::getFonts();
        $fontarray = !empty($localeFonts) ? $localeFonts :
            array("Arial", "Courier", "Georgia", "Helvetica", "Impact", "Verdana", "Haettenschweiler");
        foreach ($fontarray as $font) {
            $fontStr .= " + '<option value=\'{$font}\'>{$font}</option>'";
        }
        $fontStr .= " + '</select> '";
        $fontStr .= "+ '<select class=\"span2\" id=\'{$textarea_id}Color\' onchange=\'xoopsSetElementAttribute(\"color\", this.options[this.selectedIndex].value, \"{$textarea_id}\", \"{$hiddentext}\");\'>'";
        $fontStr .= "+ '<option value=\'COLOR\'>" . XoopsLocale::COLOR . "</option>';";
        $fontStr .= "var _color_array = new Array('00', '33', '66', '99', 'CC', 'FF');
            for(var i = 0; i < _color_array.length; i ++) {
                for(var j = 0; j < _color_array.length; j ++) {
                    for(var k = 0; k < _color_array.length; k ++) {
                        var _color_ele = _color_array[i] + _color_array[j] + _color_array[k];
                        _editor_dialog += '<option value=\''+_color_ele+'\' style=\'background-color:#'+_color_ele+';color:#'+_color_ele+';\'>#'+_color_ele+'</option>';
                    }
                }
            }
            _editor_dialog += '</select>';";

        $fontStr .= "document.write(_editor_dialog); </script>";

        $styleStr = "<img src='" . XOOPS_URL . "/images/bold.gif' alt='" . XoopsLocale::BOLD . "' title='" . XoopsLocale::BOLD . "' onmouseover='style.cursor=\"hand\"' onclick='xoopsMakeBold(\"{$hiddentext}\", \"{$textarea_id}\");' />&nbsp;";
        $styleStr .= "<img src='" . XOOPS_URL . "/images/italic.gif' alt='" . XoopsLocale::ITALIC . "' title='" . XoopsLocale::ITALIC . "' onmouseover='style.cursor=\"hand\"' onclick='xoopsMakeItalic(\"{$hiddentext}\", \"{$textarea_id}\");' />&nbsp;";
        $styleStr .= "<img src='" . XOOPS_URL . "/images/underline.gif' alt='" . XoopsLocale::UNDERLINE . "' title='" . XoopsLocale::UNDERLINE . "' onmouseover='style.cursor=\"hand\"' onclick='xoopsMakeUnderline(\"{$hiddentext}\", \"{$textarea_id}\");'/>&nbsp;";
        $styleStr .= "<img src='" . XOOPS_URL . "/images/linethrough.gif' alt='" . XoopsLocale::LINE_THROUGH . "' title='" . XoopsLocale::LINE_THROUGH . "' onmouseover='style.cursor=\"hand\"' onclick='xoopsMakeLineThrough(\"{$hiddentext}\", \"{$textarea_id}\");' />&nbsp;";

        $alignStr = "<img src='" . XOOPS_URL . "/images/alignleft.gif' alt='" . XoopsLocale::LEFT . "' title='" . XoopsLocale::LEFT . "' onmouseover='style.cursor=\"hand\"' onclick='xoopsMakeLeft(\"{$hiddentext}\", \"{$textarea_id}\");' />&nbsp;";
        $alignStr .= "<img src='" . XOOPS_URL . "/images/aligncenter.gif' alt='" . XoopsLocale::CENTER . "' title='" . XoopsLocale::CENTER . "' onmouseover='style.cursor=\"hand\"' onclick='xoopsMakeCenter(\"{$hiddentext}\", \"{$textarea_id}\");' />&nbsp;";
        $alignStr .= "<img src='" . XOOPS_URL . "/images/alignright.gif' alt='" . XoopsLocale::RIGHT . "' title='" . XoopsLocale::RIGHT . "' onmouseover='style.cursor=\"hand\"' onclick='xoopsMakeRight(\"{$hiddentext}\", \"{$textarea_id}\");' />&nbsp;";
        $fontStr = $fontStr . "<br />\n{$styleStr}&nbsp;{$alignStr}&nbsp;\n";
        return $fontStr;
    }

    /**
     * @return bool|string
     */
    public function renderValidationJS()
    {
        if ($this->htmlEditor && is_object($this->htmlEditor) && method_exists($this->htmlEditor, 'renderValidationJS')) {
            if (!isset($this->htmlEditor->isEnabled) || $this->htmlEditor->isEnabled) {
                return $this->htmlEditor->renderValidationJS();
            }
        }
        return parent::renderValidationJS();
    }
}
