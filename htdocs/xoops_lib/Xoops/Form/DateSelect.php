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
 * @author    Kazumi Ono (AKA onokazu) http://www.myweb.ne.jp/, http://jp.xoops.org/
 * @copyright 2001-2014 XOOPS Project (http://xoops.org)
 * @license   GNU GPL 2 or later (http://www.gnu.org/licenses/gpl-2.0.html)
 * @link      http://xoops.org
 * @since     2.0.0
*/
class DateSelect extends Text
{

    /**
     * __construct
     *
     * @param string  $caption caption
     * @param string  $name    name
     * @param integer $size    field size
     * @param integer $value   date as unix timestamp
     */
    public function __construct($caption, $name, $size = 2, $value = 0)
    {
        if ($value !== '') {
            $value = ($value === 0) ? time() : (int)($value);
        }
        parent::__construct($caption, $name, $size, 2, $value);
    }

    /**
     * render
     *
     * @return string rendered form element
     */
    public function render()
    {
        static $included = false;
        $xoops = \Xoops::getInstance();

        $ele_value = (string) $this->getValue(false);
        $display_value = $ele_value;
        if (0 < (int)($ele_value)) {
            $display_value = date(\XoopsLocale::getFormatShortDate(), $ele_value);
        }

        if ($this->getSize() > $this->getMaxcols()) {
            $maxcols = $this->getMaxcols();
        } else {
            $maxcols = $this->getSize();
        }
        $this->addAttribute('class', 'span' . $maxcols);
        $dlist = $this->isDatalist();
        if (!empty($dlist)) {
            $this->addAttribute('list', 'list_' . $this->getName());
        }

        $attributes = $this->renderAttributeString();

        $xoops->theme()->addBaseStylesheetAssets('@jqueryuicss');
        $xoops->theme()->addBaseScriptAssets('@jqueryui');

        // TODO - select and apply script by locale, example:
        // $i18nScript = 'media/jquery/ui/i18n/datepicker-es.js';
        // $xoops->theme()->addBaseScriptAssets($i18nScript);

        $xoops->theme()->addScript(
            '',
            '',
            ' $(function() { $( "#' . $this->getAttribute('id') . '" ).datepicker({' .
            'showOn: "button", buttonImageOnly: false, ' .
            'buttonImage: "' . $xoops->url('media/xoops/images/icons/calendar.png') .'", ' .
            'buttonImageOnly: false, buttonText: "' . \XoopsLocale::A_SELECT . '" }); }); '
        );

        return '<input ' . $attributes . 'value="' . $display_value . '" '
            . $this->getExtra() .' >';
    }
}
