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
 * @copyright 2000-2020 XOOPS Project (https://xoops.org)
 * @license   GNU GPL 2 or later (http://www.gnu.org/licenses/gpl-2.0.html)
 * @link      https://xoops.org
 */
class Bootstrap3Renderer implements RendererInterface
{
    public function render(\Xoops\Form\Element $element): string
    {
        $methodName = 'render' . str_replace('\\', '', get_class($element));
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
    protected function renderXoopsFormButton(\Xoops\Form\Button $element): string
    {
        if (false == $element->hasClassLike('btn')) {
            $element->add('class', 'btn btn-default');
        }
        $attributes = $element->renderAttributeString();

        return '<input ' . $attributes . $element->getExtra() . ' >';
    }

    /**
     * Render support for XoopsFormButtonTray
     *
     * @param XoopsFormButtonTray $element form element
     *
     * @return string rendered form element
     */
    protected function renderXoopsFormButtonTray(\Xoops\Form\ButtonTray $element): string
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
    protected function renderXoopsFormColorPicker(\Xoops\Form\ColorPicker $element): string
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
        $ret .= '<input ' . $attributes . ' ' . $element->getExtra() . ' >';
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
    protected function renderXoopsFormDateSelect(\Xoops\Form\DateSelect $element): string
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
        $ret .= '<input ' . $attributes . ' value="' . $display_value . '" ' . $element->getExtra() . ' >';
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
    protected function renderXoopsFormDhtmlTextArea(\Xoops\Form\DhtmlTextArea $element): string
    {
        if ($element->htmlEditor && is_object($element->htmlEditor)) {
            if (!isset($element->htmlEditor->isEnabled) || $element->htmlEditor->isEnabled) {
                return $element->htmlEditor->render();
            }
        }
        static $js_loaded;

        $xoops = \Xoops::getInstance();

        $extra = ('' != $element->getExtra() ? ' ' . $element->getExtra() : '');
        $ret = '';
        // actions
        $ret .= $element->xoopsCodeControls() . "<br />\n";
        // fonts
        $ret .= $element->typographyControls();

        // the textarea box
        $element->suppressRender(['value']);
        $element->set('class', 'form-control');
        $attributes = $element->renderAttributeString();

        $ret .= '<textarea ' . $attributes . $extra . '>' . $element->getValue() . "</textarea>\n";

        if (empty($element->skipPreview)) {
            if (!$xoops->theme()) {
                $element->js .= implode('', file($xoops->path('media/xoops/image.js')));
            } else {
                $xoops->theme()->addScript('media/xoops/image.js', ['type' => 'text/javascript']);
            }
            $button = "<input id='" . $element->getName() . "_preview_button' " . "type='button' " . "class='btn btn-sm btn-default' value='" . \XoopsLocale::A_PREVIEW . "' " . "onclick=\"form_instantPreview('" . XOOPS_URL . "', '" . $element->getName() . "','" . XOOPS_URL . "/images', " . (int)($element->doHtml) . ", '" . $xoops->security()->createToken() . "')\"" . ' />';
            $ret .= '<br />' . "<div id='" . $element->getName() . "_hidden' style='display: block;'> " . '<fieldset>' . '<legend>' . $button . '</legend>' . "<div id='" . $element->getName() . "_hidden_data'>" . \XoopsLocale::CLICK_PREVIEW_TO_SEE_CONTENT . '</div>' . '</fieldset>' . '</div>';
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
     * Render support for XoopsFormPassword
     *
     * @param XoopsFormPassword $element form element
     *
     * @return string rendered form element
     */
    protected function renderXoopsFormPassword(\Xoops\Form\Password $element): string
    {
        $element->add('class', 'form-control');
        $attributes = $element->renderAttributeString();

        return '<input ' . $attributes . $element->getExtra() . ' >';
    }

    /**
     * Render support for XoopsFormSelect
     *
     * @param XoopsFormSelect $element form element
     *
     * @return string rendered form element
     */
    protected function renderXoopsFormSelect(\Xoops\Form\Select $element): string
    {
        $selected = (array) $element->getValue();

        $ele_options = $element->getOptions();

        $extra = ('' != $element->getExtra() ? ' ' . $element->getExtra() : '');
        $element->add('class', 'form-control');
        $attributes = $element->renderAttributeString();
        $ret = '<select ' . $attributes . $extra . ' >' . "\n";

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
    protected function renderXoopsFormText(\Xoops\Form\Text $element): string
    {
        $element->add('class', 'form-control');
        $dataList = $element->isDatalist();
        if (!empty($dataList)) {
            $element->add('list', 'list_' . $element->getName());
        }

        $attributes = $element->renderAttributeString();

        return '<input ' . $attributes . ' ' . $element->getExtra() . ' >';
    }

    /**
     * Render support for XoopsFormTextArea
     *
     * @param XoopsFormTextArea $element form element
     *
     * @return string rendered form element
     */
    protected function renderXoopsFormTextArea(\Xoops\Form\TextArea $element): string
    {
        $element->suppressRender(['value']);
        $element->add('class', 'form-control');
        $attributes = $element->renderAttributeString();

        return '<textarea ' . $attributes . ' ' . $element->getExtra() . ' >'
            . $element->getValue() . '</textarea>';
    }
}
