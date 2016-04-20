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
 * DateSelect - date entry element with calendar popup
 *
 * @category  Xoops\Form\DateSelect
 * @package   Xoops\Form
 * @author    Kazumi Ono <onokazu@xoops.org>
 * @copyright 2001-2016 XOOPS Project (http://xoops.org)
 * @license   GNU GPL 2 or later (http://www.gnu.org/licenses/gpl-2.0.html)
 * @link      http://xoops.org
 */
class DateSelect extends Text
{
    /**
     * __construct
     *
     * @param string|array      $caption caption or array of all attributes
     * @param string            $name    name
     * @param integer|\DateTime $value   unix timestamp or DateTime object
     */
    public function __construct($caption, $name = null, $value = null)
    {
        if (is_array($caption)) {
            parent::__construct($caption);
            $this->setIfNotSet('size', 15);
            $this->setIfNotSet('value', 0);
            $this->set('value', \Xoops\Core\Locale\Time::cleanTime($this->get('value', null)));
        } else {
            parent::__construct([]);
            $this->setCaption($caption);
            $this->setName($name);
            $this->set('size', 15);
            $this->setValue(\Xoops\Core\Locale\Time::cleanTime($value));
        }
    }

    /**
     * render
     *
     * @return string rendered form element
     */
    public function render()
    {
        $xoops = \Xoops::getInstance();

        $display_value = \Xoops\Core\Locale\Time::formatDate($this->getValue(false));

        $dataList = $this->isDatalist();
        if (!empty($dataList)) {
            $this->add('list', 'list_' . $this->getName());
        }

        $this->themeDecorateElement();
        $this->suppressRender(['value']);
        $attributes = $this->renderAttributeString();

        $xoops->theme()->addBaseStylesheetAssets('@jqueryuicss');
        $xoops->theme()->addBaseScriptAssets('@jqueryui');
        \Xoops\Core\Locale\Time::localizeDatePicker();

        $xoops->theme()->addScript(
            '',
            '',
            ' $(function() { $( "#' . $this->get('id') . '" ).datepicker({' .
            'showOn: "focus", changeYear: true, constrainInput: false ' .
            ' }); }); '
        );

        $this->set('class', 'form-control');
        $ret = '<div class="input-group">';
        $ret .= '<input ' . $attributes . ' value="' . $display_value . '" ' . $this->getExtra() .' >';
        $ret .= '<span class="input-group-btn">';
        $ret .= '<button class="btn btn-default" type="button" ';
        $ret .= 'data-toggle="tooltip" data-placement="left" title="' . \XoopsLocale::A_SELECT . '" ';
        $ret .= 'onclick="$( \'#' . $this->get('id') . '\' ).datepicker( \'show\' );"> ';
        $ret .= '<span class="glyphicon glyphicon-calendar" aria-hidden="true"></span></button>';
        $ret .= '</span></div>';

        return $ret;
            //'<input ' . $attributes . 'value="' . $display_value . '" ' . $this->getExtra() .' >';
    }
}
