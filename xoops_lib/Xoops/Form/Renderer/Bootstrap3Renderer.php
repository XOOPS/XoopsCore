<?php
/**
 * You may not change or alter any portion of this comment or credits
 * of supporting developers from this source code or any supporting source code
 * which is considered copyrighted (c) material of the original comment or credit authors.
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
 */
namespace Xoops\Form\Renderer;

use Xoops\Form\RendererInterface;
/**
 * Bootstrap3Renderer style form renderer
 *
 * @author    Grégory Mage
 * @copyright 2019 XOOPS Project (http://xoops.org)
 * @license   GNU GPL 2 or later (http://www.gnu.org/licenses/gpl-2.0.html)
 * @link      http://xoops.org
 */
class Bootstrap3Renderer implements RendererInterface
{
    public function render(\Xoops\Form\Element $element):string
    {
        $methodName = 'render' . str_replace('\\' , '', get_class($element));
        if (true === method_exists($this, $methodName)) {
            return $this->$methodName($element);
        }
        return $element->defaultRender();
    }

    /**
     * Example of an override render method for a specific element class.
     * Each method will be specific to a concrete implementation of Xoops\Form\Element
     *
     * @param \Xoops\Form\Button $element Provides access to the element we are rendering.
     *                                    The strong type makes sure we get what we expect.
     *
     * @return string the rendering of $element
     *
    protected function renderXoopsFormButton(\Xoops\Form\Button $element):string
    {
        // do the rendering and return a string
		return '';
    }*/

	/**
     * Render support for XoopsFormButton
     *
     * @param XoopsFormButton $element form element
     *
     * @return string rendered form element
     */
	protected function renderXoopsFormButton(\Xoops\Form\Button $element):string
    {
		if (false == $element->hasClassLike('btn')) {
			$element->add('class', 'btn btn-default');
		}		
		$attributes = $element->renderAttributeString();
        return '<input ' . $attributes . $element->getExtra() .' >';
	}

	/**
     * Render support for XoopsFormButtonTray
     *
     * @param XoopsFormButtonTray $element form element
     *
     * @return string rendered form element
     */
	protected function renderXoopsFormButtonTray(\Xoops\Form\ButtonTray $element):string
    {		
		$ret = '';
        $element->add('class', 'btn');
        $class = 'class="' . $element->getClass() . '"';

        $attributes = $element->renderAttributeString();

        if ((bool) $element->get(':showdelete', false)) {
            $ret .= '<input type="submit"' . $class . ' name="delete" id="delete" value="'
                . \XoopsLocale::A_DELETE . '" onclick="this.form.elements.op.value=\'delete\'">';
        }
        $ret .= ' <input type="button" ' . $class . ' value="' . \XoopsLocale::A_CANCEL
            . '" onclick="history.go(-1);return true;" />'
            . ' <input type="reset"' . $class . ' name="reset"  id="reset" value="' . \XoopsLocale::A_RESET . '" />'
            . ' <input ' . $attributes . $element->getExtra() . ' />';
        return $ret;
	}
	
	/**
     * Render support for XoopsFormColorPicker
     *
     * @param XoopsFormColorPicker $element form element
     *
     * @return string rendered form element
     */
	protected function renderXoopsFormColorPicker(\Xoops\Form\ColorPicker $element):string
    {
        $xoops = \Xoops::getInstance();
        if ($xoops->theme()) {
            $xoops->theme()->addScript('include/color-picker.js');
        } else {
            echo '<script type="text/javascript" src="' . $xoops->url('/include/color-picker.js') . '"></script>';
        }
        $temp = $element->get('value', '');
        if (!empty($temp)) {
            $element->set('style', 'background-color:' . $temp . ';');
        }
        $element->set('class', 'form-control');
        $ret = '<div class="input-group">';
        $attributes = $element->renderAttributeString();
        $ret .= '<input ' . $attributes . ' ' . $element->getExtra() .' >';
        $ret .= '<span class="input-group-btn">';
        $ret .= '<button class="btn btn-default" type="button" ';
        $ret .= 'data-toggle="tooltip" data-placement="left" title="' . \XoopsLocale::A_SELECT . '" ';
        $ret .= 'onclick="return TCP.popup(\'';
        $ret .= $xoops->url('/include/') . '\',document.getElementById(\'' . $element->getName() . '\'));">';
        $ret .= '<span class="glyphicon glyphicon-option-horizontal" aria-hidden="true"></span></button>';
        $ret .= '</span></div>';

        return $ret;
	}
	
	/**
     * Render support for XoopsFormDateSelect
     *
     * @param XoopsFormDateSelect $element form element
     *
     * @return string rendered form element
     */
	protected function renderXoopsFormDateSelect(\Xoops\Form\DateSelect $element):string
    {
        $xoops = \Xoops::getInstance();

        $display_value = \Xoops\Core\Locale\Time::formatDate($element->getValue(false));

        $dataList = $element->isDatalist();
        if (!empty($dataList)) {
            $element->add('list', 'list_' . $element->getName());
        }

		$element->add('class', 'form-control');
        $element->suppressRender(['value']);
        $attributes = $element->renderAttributeString();

        $xoops->theme()->addBaseStylesheetAssets('@jqueryuicss');
        $xoops->theme()->addBaseScriptAssets('@jqueryui');
        \Xoops\Core\Locale\Time::localizeDatePicker();

        $xoops->theme()->addScript(
            '',
            '',
            ' $(function() { $( "#' . $element->get('id') . '" ).datepicker({' .
            'showOn: "focus", changeYear: true, constrainInput: false ' .
            ' }); }); '
        );

        $ret = '<div class="input-group">';
        $ret .= '<input ' . $attributes . ' value="' . $display_value . '" ' . $element->getExtra() .' >';
        $ret .= '<span class="input-group-btn">';
        $ret .= '<button class="btn btn-default" type="button" ';
        $ret .= 'data-toggle="tooltip" data-placement="left" title="' . \XoopsLocale::A_SELECT . '" ';
        $ret .= 'onclick="$( \'#' . $element->get('id') . '\' ).datepicker( \'show\' );"> ';
        $ret .= '<span class="glyphicon glyphicon-calendar" aria-hidden="true"></span></button>';
        $ret .= '</span></div>';

        return $ret;
	}

	/**
     * Render support for XoopsFormDhtmlTextArea
     *
     * @param XoopsFormDhtmlTextArea $element form element
     *
     * @return string rendered form element
     */
	protected function renderXoopsFormDhtmlTextArea(\Xoops\Form\DhtmlTextArea $element):string
    {
        if ($element->htmlEditor && is_object($element->htmlEditor)) {
            if (!isset($element->htmlEditor->isEnabled) || $element->htmlEditor->isEnabled) {
                return $element->htmlEditor->render();
            }
        }
        static $js_loaded;

        $xoops = \Xoops::getInstance();

        $extra = ($element->getExtra() != '' ? " " . $element->getExtra() : '');
        $ret = "";
        // actions
        $ret .= $this->XoopsFormDhtmlTextAreaCodeIcon($element) . "<br>\n";
        // fonts	
		$ret .= $this->XoopsFormDhtmlTextAreaTypography($element);
		$ret .= "<br>\n";
        // the textarea box
		$element->add('class', 'form-control');
        $element->suppressRender(['value']);
        $attributes = $element->renderAttributeString();

        $ret .= '<textarea ' . $attributes . $extra . '>' . $element->getValue() . "</textarea>\n";

        if (empty($element->skipPreview)) {
            if (!$xoops->theme()) {
                $element->js .= implode("", file($xoops->path('media/xoops/image.js')));
            } else {
                $xoops->theme()->addScript('media/xoops/image.js', array('type' => 'text/javascript'));
            }
            $button = "<input id='" . $element->getName() . "_preview_button' " . "type='button' " . "class='btn btn-sm btn-default' value='" . \XoopsLocale::A_PREVIEW . "' " . "onclick=\"form_instantPreview('" . XOOPS_URL . "', '" . $element->getName() . "','" . XOOPS_URL . "/images', " . (int)($element->doHtml) . ", '" . $xoops->security()->createToken() . "')\"" . " />";
            $ret .= "<br />" . "<div id='" . $element->getName() . "_hidden' style='display: block;'> " . "<fieldset>" . "<legend>" . $button . "</legend>" . "<div id='" . $element->getName() . "_hidden_data'>" . \XoopsLocale::CLICK_PREVIEW_TO_SEE_CONTENT . "</div>" . "</fieldset>" . "</div>";
        }
        // Load javascript
        if (empty($js_loaded)) {
            $javascript = (($element->js)
                ? '<script type="text/javascript">' . $element->js . '</script>'
                : '') . '<script type="text/javascript" src="' . \XoopsBaseConfig::get('url') . '/include/formdhtmltextarea.js"></script>';
            $ret = $javascript . $ret;
            $js_loaded = true;
        }
        return $ret;
	}
	
	/**
     * Render xoopscode buttons for editor, include calling text sanitizer extensions
     *
     * @param XoopsFormDhtmlTextArea $element form element
     *
     * @return string rendered buttons for xoopscode assistance
     */
	
	public function XoopsFormDhtmlTextAreaCodeIcon($element)
    {
        $textarea_id = $element->getName();
		$xoops = \Xoops::getInstance();
        $myts = \Xoops\Core\Text\Sanitizer::getInstance();
		
		$code = '';
        $code .= "<div class='row'><div class='col-md-12'>";
        $code .= "<button type='button' class='btn btn-default btn-sm' onclick='xoopsCodeUrl(\"{$textarea_id}\", \"" . $myts->escapeForJavascript(\XoopsLocale::ENTER_LINK_URL) . "\", \"" . $myts->escapeForJavascript(\XoopsLocale::ENTER_WEBSITE_TITLE) . "\");' onmouseover='style.cursor=\"hand\"' title='" . \XoopsLocale::URL . "'><span class='fa fa-fw fa-link' aria-hidden='true'></span></button>";
        $code .= "<button type='button' class='btn btn-default btn-sm' onclick='xoopsCodeEmail(\"{$textarea_id}\", \"" . $myts->escapeForJavascript(\XoopsLocale::ENTER_EMAIL) . "\");' onmouseover='style.cursor=\"hand\"' title='" . \XoopsLocale::EMAIL . "'><span class='fa fa-fw fa-envelope-o' aria-hidden='true'></span></button>";
        $code .= "<button type='button' class='btn btn-default btn-sm' onclick='xoopsCodeImg(\"{$textarea_id}\", \"" . $myts->escapeForJavascript(\XoopsLocale::ENTER_IMAGE_URL) . "\", \"" . $myts->escapeForJavascript(\XoopsLocale::ENTER_IMAGE_POSITION) . "\", \"" . $myts->escapeForJavascript(\XoopsLocale::IMAGE_POSITION_DESCRIPTION) . "\", \"" . $myts->escapeForJavascript(\XoopsLocale::E_ENTER_IMAGE_POSITION) . "\", \"" . $myts->escapeForJavascript(\XoopsLocale::WIDTH) . "\");' onmouseover='style.cursor=\"hand\"' title='" . \XoopsLocale::IMAGES . "'><span class='fa fa-fw fa-file-image-o' aria-hidden='true'></span></button>";
		
		$response = \Xoops::getInstance()->service('emoji')->renderEmojiSelector($element->getName());
        if ($response->isSuccess()) {
            $emojiSelector = $response->getValue();
            $code .= $emojiSelector;
        }

        $extensions = array_filter($myts->listExtensions());
        foreach ($extensions as $extension) {
            list ($button, $js) = $myts->getDhtmlEditorSupport($extension, $textarea_id);
            if (!empty($button)) {
                $code .= $button;
            }
            if (!empty($js)) {
                $element->js .= $js;
            }
        }
		
		$code .= "<button type='button' class='btn btn-default btn-sm' onclick='xoopsCodeCode(\"{$textarea_id}\", \"" . $myts->escapeForJavascript(\XoopsLocale::ENTER_CODE) . "\");' onmouseover='style.cursor=\"hand\"' title='" . \XoopsLocale::SOURCE_CODE . "'><span class='fa fa-fw fa-code' aria-hidden='true'></span></button>";
        $code .= "<button type='button' class='btn btn-default btn-sm' onclick='xoopsCodeQuote(\"{$textarea_id}\", \"" . $myts->escapeForJavascript(\XoopsLocale::ENTER_QUOTE) . "\");' onmouseover='style.cursor=\"hand\"' title='" . \XoopsLocale::QUOTE . "'><span class='fa fa-fw fa-quote-right' aria-hidden='true'></span></button>";
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
    protected function XoopsFormDhtmlTextAreaTypography($element)
    {
        $textarea_id = $element->getName();
		$hiddentext  = $element->hiddenText;

        $fontarray = !empty($GLOBALS['formtextdhtml_fonts']) ? $GLOBALS['formtextdhtml_fonts'] : array(
            'Arial',
            'Courier',
            'Georgia',
            'Helvetica',
            'Impact',
            'Verdana',
            'Haettenschweiler');

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
        foreach (\XoopsLocale::getFontSizes() as $value => $name) {
            $fontStr .= '<li><a href="javascript:xoopsSetElementAttribute(\'size\', \'' . $value . '\', \''
                . $textarea_id . '\', \'' . $hiddentext . '\');">' . $name . '</a></li>';
        }
        $fontStr .= '</ul></div>';

        $fontStr .= '<div class="btn-group">'
            . '<button type="button" class="btn btn-default btn-sm dropdown-toggle" title="'. \XoopsLocale::FONT .'"'
            . ' data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">'
            . '<span class = "glyphicon glyphicon-font"></span><span class="caret"></span></button>'
            . '<ul class="dropdown-menu">';
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
        foreach ($colorArray as $color => $hex) {
            $fontStr .= '<li><a href="javascript:xoopsSetElementAttribute(\'color\', \'' . $hex . '\', \''
                . $textarea_id . '\', \'' . $hiddentext . '\');">'
                . '<span style="color:#' . $hex . ';">' . $color .'</span></a></li>';
        }
        $fontStr .= '</ul></div>';
        $fontStr .= '</div>';

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
            . $element->getName() . "', '" . @$element->configs['maxlength'] . "', '"
            . \XoopsLocale::F_CURRENT_TEXT_LENGTH . "', '" . \XoopsLocale::MAXIMUM_LENGTH . "');\" title='"
            . \XoopsLocale::CHECK_TEXT_LENGTH . "'><span class='fa fa-check-square-o' aria-hidden='true'></span></button>";
        $fontStr .= "</div></div>";

        return $fontStr;
    }

	/**
     * Render support for XoopsFormPassword
     *
     * @param XoopsFormPassword $element form element
     *
     * @return string rendered form element
     */
	protected function renderXoopsFormPassword(\Xoops\Form\Password $element):string
    {
        $element->add('class', 'form-control');
		$attributes = $element->renderAttributeString();
        return '<input ' . $attributes . $element->getExtra() .' >';
	}
	
	/**
     * Render support for XoopsFormSelect
     *
     * @param XoopsFormSelect $element form element
     *
     * @return string rendered form element
     */
	protected function renderXoopsFormSelect(\Xoops\Form\Select $element):string
    {
        $selected = (array) $element->getValue();

        $ele_options = $element->getOptions();

        $extra = ($element->getExtra() != '' ? " " . $element->getExtra() : '');
        $element->add('class', 'form-control');
        $attributes = $element->renderAttributeString();
        $ret = '<select ' . $attributes . $extra .' >' . "\n";

        if (empty($ele_optgroup)) {
            foreach ($ele_options as $value => $display) {
                if (is_array($display)) {
                    $ret .= '<optgroup label="' . $value . '">' . "\n";
                    foreach ($display as $optvalue => $optdisplay) {
                        $ret .= $element->renderOption($optvalue, $optdisplay, $selected);
                    }
                } else {
                    $ret .= $element->renderOption($value, $display, $selected);
                }
            }
        }
        $ret .= '</select>' . "\n";

        return $ret;
	}
	
	/**
     * Render support for XoopsFormText
     *
     * @param XoopsFormText $element form element
     *
     * @return string rendered form element
     */
	protected function renderXoopsFormText(\Xoops\Form\Text $element):string
    {
        $element->add('class', 'form-control');
        $dataList = $element->isDatalist();
        if (!empty($dataList)) {
            $element->add('list', 'list_' . $element->getName());
        }

        $attributes = $element->renderAttributeString();
        return '<input ' . $attributes . ' ' . $element->getExtra() .' >';
	}
	
	/**
     * Render support for XoopsFormTextArea
     *
     * @param XoopsFormTextArea $element form element
     *
     * @return string rendered form element
     */
	protected function renderXoopsFormTextArea(\Xoops\Form\TextArea $element):string
    {
        $element->suppressRender(['value']);
		$element->add('class', 'form-control');
        $attributes = $element->renderAttributeString();
        return '<textarea ' . $attributes . ' ' . $element->getExtra() .' >'
            . $element->getValue() . '</textarea>';
	}
}
