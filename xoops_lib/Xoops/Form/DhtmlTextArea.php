<?php
/*
 You may not change or alter any portion of this comment or credits
 of supporting developers from this source code or any supporting source code
 which is considered copyrighted (c) material of the original comment or credit authors.

 This program is distributed in the hope that it will be useful,
 but WITHOUT ANY WARRANTY; without even the implied warranty of
 MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
*/

namespace Xoops\Form;

/**
 * DhtmlTextArea - A textarea with xoopsish formatting and smilie buttons
 *
 * @category  Xoops\Form\DhtmlTextArea
 * @package   Xoops\Form
 * @author    Kazumi Ono <onokazu@xoops.org>
 * @author    Taiwen Jiang <phppp@users.sourceforge.net>
 * @author    Vinod <smartvinu@gmail.com>
 * @copyright 2001-2016 XOOPS Project (http://xoops.org)
 * @license   GNU GPL 2 or later (http://www.gnu.org/licenses/gpl-2.0.html)
 * @link      http://xoops.org
 */
class DhtmlTextArea extends \XoopsEditor
{
    /**
     * Extended HTML editor
     *
     * <p>If an extended HTML editor is set, the renderer will be replaced by the specified editor,
     * usually a visual or WYSIWYG editor.</p>
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
    /**
     * @var \XoopsEditor
     */
    public $htmlEditor;

    /**
     * Hidden text
     *
     * @var string
     */
    private $hiddenText;

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
     * @param string  $caption    Caption
     * @param string  $name       name attribute
     * @param string  $value      Initial text
     * @param integer $rows       Number of rows
     * @param integer $cols       Number of columns
     * @param string  $hiddentext Identifier for hidden Text
     * @param array   $options    Extra options
     */
    public function __construct(
        $caption,
        $name,
        $value = "",
        $rows = 5,
        $cols = 50,
        $hiddentext = "xoopsHiddenText",
        $options = array()
    ) {
        static $inLoop = 0;

        ++$inLoop;
        // Second loop, invalid, return directly
        if ($inLoop > 2) {
            return;
        }

        // Else, initialize
        parent::__construct($caption, $name, $value, $rows, $cols);
        $this->hiddenText = $hiddentext;

        if ($inLoop > 1) {
            return;
        }

        $xoops = \Xoops::getInstance();
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
                $editor_handler = \XoopsEditorHandler::getInstance();
                $this->htmlEditor = $editor_handler->get($this->htmlEditor[0], $options);
                if ($inLoop > 1) {
                    $this->htmlEditor = null;
                }
            } else {
                list ($class, $path) = $this->htmlEditor;
                include_once \XoopsBaseConfig::get('root-path') . $path;
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
     * defaultRender
     *
     * @return string rendered form element
     */
    public function defaultRender()
    {
        if ($this->htmlEditor && is_object($this->htmlEditor)) {
            if (!isset($this->htmlEditor->isEnabled) || $this->htmlEditor->isEnabled) {
                return $this->htmlEditor->render();
            }
        }
        static $js_loaded;

        $xoops = \Xoops::getInstance();

        $extra = ($this->getExtra() != '' ? " " . $this->getExtra() : '');
        $ret = "";
        // actions
        $ret .= $this->xoopsCodeControls() . "<br />\n";
        // fonts
        $ret .= $this->typographyControls();

        // the textarea box

        $this->suppressRender(['value']);
        $attributes = $this->renderAttributeString();

        $ret .= '<textarea ' . $attributes . $extra . '>' . $this->getValue() . "</textarea>\n";

        if (empty($this->skipPreview)) {
            if (!$xoops->theme()) {
                $this->js .= implode("", file($xoops->path('media/xoops/image.js')));
            } else {
                $xoops->theme()->addScript('media/xoops/image.js', array('type' => 'text/javascript'));
            }
            $button = "<input id='" . $this->getName() . "_preview_button' " . "type='button' " . "class='btn btn-sm btn-default' value='" . \XoopsLocale::A_PREVIEW . "' " . "onclick=\"form_instantPreview('" . XOOPS_URL . "', '" . $this->getName() . "','" . XOOPS_URL . "/images', " . (int)($this->doHtml) . ", '" . $xoops->security()->createToken() . "')\"" . " />";
            $ret .= "<br />" . "<div id='" . $this->getName() . "_hidden' style='display: block;'> " . "<fieldset>" . "<legend>" . $button . "</legend>" . "<div id='" . $this->getName() . "_hidden_data'>" . \XoopsLocale::CLICK_PREVIEW_TO_SEE_CONTENT . "</div>" . "</fieldset>" . "</div>";
        }
        // Load javascript
        if (empty($js_loaded)) {
            $javascript = (($this->js)
                ? '<script type="text/javascript">' . $this->js . '</script>'
                : '') . '<script type="text/javascript" src="' . \XoopsBaseConfig::get('url') . '/include/formdhtmltextarea.js"></script>';
            $ret = $javascript . $ret;
            $js_loaded = true;
        }
        return $ret;
    }

    /**
     * xoopsCodeControls
     *
     * @return string
     */
    public function xoopsCodeControls()
    {
        $textarea_id = $this->getName();
        $xoops = \Xoops::getInstance();
        $myts = \Xoops\Core\Text\Sanitizer::getInstance();

        $code = '';
        $code .= '<div class="row"><div class="col-md-12">';
        $code .= '<button type="button" class="btn btn-default btn-sm"  alt="' . \XoopsLocale::URL
            . '" title="' . \XoopsLocale::URL . '" onclick="xoopsCodeUrl(\'' . $textarea_id . '\', \''
            . $myts->escapeForJavascript(\XoopsLocale::ENTER_LINK_URL) . '\', \''
            . $myts->escapeForJavascript(\XoopsLocale::ENTER_WEBSITE_TITLE)
            . '\')" onmouseover="style.cursor=\'hand\'">'
            . '<span class="fa fa-fw fa-link" aria-hidden="true"></span></button>';
        $code .= '<button type="button" class="btn btn-default btn-sm" alt="' . \XoopsLocale::EMAIL
            . '" title="' . \XoopsLocale::EMAIL . '" onclick="xoopsCodeEmail(\'' . $textarea_id . '\', \''
            . $myts->escapeForJavascript(\XoopsLocale::ENTER_EMAIL)
            . '\');"  onmouseover="style.cursor=\'hand\'">'
            . '<span class="fa fa-fw fa-envelope-o" aria-hidden="true"></span></button>';
        $code .= '<button type="button" class="btn btn-default btn-sm" alt="' . \XoopsLocale::IMAGES
            . '" title="' . \XoopsLocale::IMAGES . '" onclick="xoopsCodeImg(\'' . $textarea_id . '\', \''
            . $myts->escapeForJavascript(\XoopsLocale::ENTER_IMAGE_URL) . '\', \''
            . $myts->escapeForJavascript(\XoopsLocale::ENTER_IMAGE_POSITION) . '\', \''
            . $myts->escapeForJavascript(\XoopsLocale::IMAGE_POSITION_DESCRIPTION) . '\', \''
            . $myts->escapeForJavascript(\XoopsLocale::E_ENTER_IMAGE_POSITION) . '\', \''
            . $myts->escapeForJavascript(\XoopsLocale::WIDTH) . '\');" onmouseover="style.cursor=\'hand\'">'
            . '<span class="fa fa-fw fa-file-image-o" aria-hidden="true"></span></button>';

        $extensions = array_filter($myts->listExtensions());
        foreach ($extensions as $extension) {
            list ($button, $js) = $myts->getDhtmlEditorSupport($extension, $textarea_id);
            if (!empty($button)) {
                $code .= $button;
            }
            if (!empty($js)) {
                $this->js .= $js;
            }
        }
        $code .= '<button type="button" class="btn btn-default btn-sm" alt="' . \XoopsLocale::SOURCE_CODE . '" title="'
            . \XoopsLocale::SOURCE_CODE . '" onclick="xoopsCodeCode(\'' . $textarea_id . '\', \''
            . $myts->escapeForJavascript(\XoopsLocale::ENTER_CODE) . '\');" onmouseover="style.cursor=\'hand\'">'
            . '<span class="fa fa-fw fa-code" aria-hidden="true"></span></button>';

        $code .= '<button type="button" class="btn btn-default btn-sm" alt="' . \XoopsLocale::QUOTE . '" title="'
            . \XoopsLocale::QUOTE . '" onclick="xoopsCodeQuote(\'' . $textarea_id . '\', \''
            . $myts->escapeForJavascript(\XoopsLocale::ENTER_QUOTE) . '\');" onmouseover="style.cursor=\'hand\'">'
            . '<span class="fa fa-fw fa-quote-right" aria-hidden="true"></span></button>';

        $response = \Xoops::getInstance()->service('emoji')->renderEmojiSelector($this->getName());
        if ($response->isSuccess()) {
            $emojiSelector = $response->getValue();
            $code .= $emojiSelector;
        }

        $code .= "</div></div>";

        return $code;
    }

    /**
     * Render typography controls for editor (font, size, color)
     *
     * @param XoopsFormDhtmlTextArea $element form element
     *
     * @return string rendered typography controls
     */
    public function typographyControls()
    {
        $textarea_id = $this->getName();
        $hiddentext = $this->hiddenText;

        $fontarray = \XoopsLocale::getFonts();

        $colorArray = array(
            'Black'  => '000000',
            'Blue'   => '38AAFF',
            'Brown'  => '987857',
            'Green'  => '79D271',
            'Grey'   => '888888',
            'Orange' => 'FFA700',
            'Paper'  => 'E0E0E0',
            'Purple' => '363E98',
            'Red'    => 'FF211E',
            'White'  => 'FEFEFE',
            'Yellow' => 'FFD628',
        );

        $fontStr = '<div class="row"><div class="col-md-12"><div class="btn-group" role="toolbar">';
        $fontStr .= '<div class="btn-group">'
            . '<button type="button" class="btn btn-default btn-sm dropdown-toggle" title="'. \XoopsLocale::SIZE .'"'
            . ' data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">'
            . '<span class = "glyphicon glyphicon-text-height"></span><span class="caret"></span></button>'
            . '<ul class="dropdown-menu">';
        $localeFontSizes = \XoopsLocale::getFontSizes();
        foreach ($localeFontSizes as $value => $name) {
            $fontStr .= '<li><a href="javascript:xoopsSetElementAttribute(\'size\', \'' . $value . '\', \''
                . $textarea_id . '\', \'' . $hiddentext . '\');">' . $name . '</a></li>';
        }
        $fontStr .= '</ul></div>';

        $fontStr .= '<div class="btn-group">'
            . '<button type="button" class="btn btn-default btn-sm dropdown-toggle" title="'. \XoopsLocale::FONT .'"'
            . ' data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">'
            . '<span class = "glyphicon glyphicon-font"></span><span class="caret"></span></button>'
            . '<ul class="dropdown-menu">';
        //. _FONT . '&nbsp;&nbsp;<span class="caret"></span></button><ul class="dropdown-menu">';
        foreach ($fontarray as $font) {
            $fontStr .= '<li><a href="javascript:xoopsSetElementAttribute(\'font\', \'' . $font . '\', \''
                . $textarea_id . '\', \'' . $hiddentext . '\');">' . $font . '</a></li>';
        }
        $fontStr .= '</ul></div>';

        $fontStr .= '<div class="btn-group">'
            . '<button type="button" class="btn btn-default btn-sm dropdown-toggle" title="'. \XoopsLocale::COLOR .'"'
            . ' data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">'
            . '<span class = "glyphicon glyphicon-text-color"></span><span class="caret"></span></button>'
            . '<ul class="dropdown-menu">';
        //. _COLOR . '&nbsp;&nbsp;<span class="caret"></span></button><ul class="dropdown-menu">';
        foreach ($colorArray as $color => $hex) {
            $fontStr .= '<li><a href="javascript:xoopsSetElementAttribute(\'color\', \'' . $hex . '\', \''
                . $textarea_id . '\', \'' . $hiddentext . '\');">'
                . '<span style="color:#' . $hex . ';">' . $color .'</span></a></li>';
        }
        $fontStr .= '</ul></div>';
        $fontStr .= '</div>';

        //$styleStr = "<div class='row'><div class='col-md-12'>";
        $styleStr  = "<div class='btn-group' role='group'>";
        $styleStr .= "<button type='button' class='btn btn-default btn-sm' onclick='xoopsMakeBold(\"{$hiddentext}\", \"{$textarea_id}\");' title='" . \XoopsLocale::BOLD . "' aria-label='Left Align'><span class='fa fa-bold' aria-hidden='true'></span></button>";
        $styleStr .= "<button type='button' class='btn btn-default btn-sm' onclick='xoopsMakeItalic(\"{$hiddentext}\", \"{$textarea_id}\");' title='" . \XoopsLocale::ITALIC . "' aria-label='Left Align'><span class='fa fa-italic' aria-hidden='true'></span></button>";
        $styleStr .= "<button type='button' class='btn btn-default btn-sm' onclick='xoopsMakeUnderline(\"{$hiddentext}\", \"{$textarea_id}\");' title='" . \XoopsLocale::UNDERLINE . "' aria-label='Left Align'>" . '<span class="fa fa-underline"></span></button>';
        $styleStr .= "<button type='button' class='btn btn-default btn-sm' onclick='xoopsMakeLineThrough(\"{$hiddentext}\", \"{$textarea_id}\");' title='" . \XoopsLocale::LINE_THROUGH . "' aria-label='Left Align'>" . '<span class="fa fa-strikethrough"></span></button>';
        $styleStr .= "</div>";

        $alignStr = "<div class='btn-group' role='group'>";
        $alignStr .= "<button type='button' class='btn btn-default btn-sm' onclick='xoopsMakeLeft(\"{$hiddentext}\", \"{$textarea_id}\");' title='" . \XoopsLocale::LEFT . "' aria-label='Left Align'><span class='fa fa-align-left' aria-hidden='true'></span></button>";
        $alignStr .= "<button type='button' class='btn btn-default btn-sm' onclick='xoopsMakeCenter(\"{$hiddentext}\", \"{$textarea_id}\");' title='" . \XoopsLocale::CENTER . "' aria-label='Left Align'><span class='fa fa-align-center' aria-hidden='true'></span></button>";
        $alignStr .= "<button type='button' class='btn btn-default btn-sm' onclick='xoopsMakeRight(\"{$hiddentext}\", \"{$textarea_id}\");' title='" . \XoopsLocale::RIGHT . "' aria-label='Left Align'><span class='fa fa-align-right' aria-hidden='true'></span></button>";
        $alignStr .= "</div>";

        $fontStr .= "&nbsp;{$styleStr}&nbsp;{$alignStr}&nbsp;\n";

        $fontStr .= "<button type='button' class='btn btn-default btn-sm' onclick=\"XoopsCheckLength('"
            . $this->getName() . "', '" . @$this->configs['maxlength'] . "', '"
            . \XoopsLocale::F_CURRENT_TEXT_LENGTH . "', '" . \XoopsLocale::MAXIMUM_LENGTH . "');\" title='"
            . \XoopsLocale::CHECK_TEXT_LENGTH . "'><span class='fa fa-check-square-o' aria-hidden='true'></span></button>";
        $fontStr .= "</div></div>";

        return $fontStr;
    }

    /**
     * fontArray
     *
     * @return string
     */
    public function fontArray()
    {
        $textarea_id = $this->getName();
        $hiddentext = $this->hiddenText;

        $fontStr = "<script type=\"text/javascript\" language=\"JavaScript\">";
        $fontStr .= "var _editor_dialog = ''" . "+ '<select class=\"span2\" id=\'{$textarea_id}Size\' onchange=\'xoopsSetElementAttribute(\"size\", this.options[this.selectedIndex].value, \"{$textarea_id}\", \"{$hiddentext}\");\'>'";
        $fontStr .= "+ '<option value=\'SIZE\'>" . \XoopsLocale::SIZE . "</option>'";
        $localeFontSizes = \XoopsLocale::getFontSizes();
        foreach ($localeFontSizes as $_val => $_name) {
            $fontStr .= " + '<option value=\'{$_val}\'>{$_name}</option>'";
        }
        $fontStr .= " + '</select> '";
        $fontStr .= "+ '<select class=\"span2\" id=\'{$textarea_id}Font\' onchange=\'xoopsSetElementAttribute(\"font\", this.options[this.selectedIndex].value, \"{$textarea_id}\", \"{$hiddentext}\");\'>'";
        $fontStr .= "+ '<option value=\'FONT\'>" . \XoopsLocale::FONT . "</option>'";
        $localeFonts = \XoopsLocale::getFonts();
        $fontarray = !empty($localeFonts) ? $localeFonts :
            array("Arial", "Courier", "Georgia", "Helvetica", "Impact", "Verdana", "Haettenschweiler");
        foreach ($fontarray as $font) {
            $fontStr .= " + '<option value=\'{$font}\'>{$font}</option>'";
        }
        $fontStr .= " + '</select> '";
        $fontStr .= "+ '<select class=\"span2\" id=\'{$textarea_id}Color\' onchange=\'xoopsSetElementAttribute(\"color\", this.options[this.selectedIndex].value, \"{$textarea_id}\", \"{$hiddentext}\");\'>'";
        $fontStr .= "+ '<option value=\'COLOR\'>" . \XoopsLocale::COLOR . "</option>';";
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

        $styleStr = "<img src='" . \XoopsBaseConfig::get('url') . "/images/bold.gif' alt='" . \XoopsLocale::BOLD . "' title='" . \XoopsLocale::BOLD . "' onmouseover='style.cursor=\"hand\"' onclick='xoopsMakeBold(\"{$hiddentext}\", \"{$textarea_id}\");' />&nbsp;";
        $styleStr .= "<img src='" . \XoopsBaseConfig::get('url') . "/images/italic.gif' alt='" . \XoopsLocale::ITALIC . "' title='" . \XoopsLocale::ITALIC . "' onmouseover='style.cursor=\"hand\"' onclick='xoopsMakeItalic(\"{$hiddentext}\", \"{$textarea_id}\");' />&nbsp;";
        $styleStr .= "<img src='" . \XoopsBaseConfig::get('url') . "/images/underline.gif' alt='" . \XoopsLocale::UNDERLINE . "' title='" . \XoopsLocale::UNDERLINE . "' onmouseover='style.cursor=\"hand\"' onclick='xoopsMakeUnderline(\"{$hiddentext}\", \"{$textarea_id}\");'/>&nbsp;";
        $styleStr .= "<img src='" . \XoopsBaseConfig::get('url') . "/images/linethrough.gif' alt='" . \XoopsLocale::LINE_THROUGH . "' title='" . \XoopsLocale::LINE_THROUGH . "' onmouseover='style.cursor=\"hand\"' onclick='xoopsMakeLineThrough(\"{$hiddentext}\", \"{$textarea_id}\");' />&nbsp;";

        $alignStr = "<img src='" . \XoopsBaseConfig::get('url') . "/images/alignleft.gif' alt='" . \XoopsLocale::LEFT . "' title='" . \XoopsLocale::LEFT . "' onmouseover='style.cursor=\"hand\"' onclick='xoopsMakeLeft(\"{$hiddentext}\", \"{$textarea_id}\");' />&nbsp;";
        $alignStr .= "<img src='" . \XoopsBaseConfig::get('url') . "/images/aligncenter.gif' alt='" . \XoopsLocale::CENTER . "' title='" . \XoopsLocale::CENTER . "' onmouseover='style.cursor=\"hand\"' onclick='xoopsMakeCenter(\"{$hiddentext}\", \"{$textarea_id}\");' />&nbsp;";
        $alignStr .= "<img src='" . \XoopsBaseConfig::get('url') . "/images/alignright.gif' alt='" . \XoopsLocale::RIGHT . "' title='" . \XoopsLocale::RIGHT . "' onmouseover='style.cursor=\"hand\"' onclick='xoopsMakeRight(\"{$hiddentext}\", \"{$textarea_id}\");' />&nbsp;";
        $fontStr = $fontStr . "<br />\n{$styleStr}&nbsp;{$alignStr}&nbsp;\n";
        return $fontStr;
    }

    /**
     * renderValidationJS
     *
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
