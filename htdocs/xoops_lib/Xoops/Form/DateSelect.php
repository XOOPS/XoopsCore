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
     * @param string            $caption caption
     * @param string            $name    name
     * @param integer           $size    field size
     * @param integer|\DateTime $value   unix timestamp or DateTime object
     */
    public function __construct($caption, $name, $size = 12, $value = 0)
    {
        $value = \Xoops\Core\Locale\Time::cleanTime($value);
        parent::__construct($caption, $name, $size, $size , $value);
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

        $display_value = \Xoops\Core\Locale\Time::formatDate($this->getValue(false));
        $this->addAttribute('class', 'span2');
        $dlist = $this->isDatalist();
        if (!empty($dlist)) {
            $this->addAttribute('list', 'list_' . $this->getName());
        }

        $attributes = $this->renderAttributeString();

        $xoops->theme()->addBaseStylesheetAssets('@jqueryuicss');
        $xoops->theme()->addBaseScriptAssets('@jqueryui');
        \Xoops\Core\Locale\Time::localizeDatePicker();

        $xoops->theme()->addScript(
            '',
            '',
            ' $(function() { $( "#' . $this->getAttribute('id') . '" ).datepicker({' .
            'showOn: "button", buttonImageOnly: false, changeYear: true, constrainInput: false, ' .
            'buttonImage: "' . $xoops->url('media/xoops/images/icons/calendar.png') .'", ' .
            'buttonImageOnly: false, buttonText: "' . \XoopsLocale::A_SELECT . '" }); }); '
        );

        return '<input ' . $attributes . 'value="' . $display_value . '" '
            . $this->getExtra() .' >';
    }
}
