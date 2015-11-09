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
 * SelectEditor
 *
 * @category  Xoops\Form\SelectEditor
 * @package   Xoops\Form
 * @author    Taiwen Jiang <phppp@users.sourceforge.net>
 * @copyright 2001-2015 XOOPS Project (http://xoops.org)
 * @license   GNU GPL 2 or later (http://www.gnu.org/licenses/gpl-2.0.html)
 * @link      http://xoops.org
 */
class SelectEditor extends ElementTray
{
    /**
     * @var array
     */
    public $allowed_editors = array();

    /**
     * @var Form
     */
    public $form;

    /**
     * @var string
     */
    public $value;

    /**
     * @var string
     */
    public $name;

    /**
     * @var bool
     */
    public $nohtml;

    /**
     * Constructor
     *
     * @param Form    $form            the form calling the editor selection
     * @param string  $name            editor name
     * @param string  $value           Pre-selected text value
     * @param boolean $nohtml          dohtml disabled
     * @param array   $allowed_editors allowed editors
     */
    public function __construct(
        Form $form,
        $name = 'editor',
        $value = null,
        $nohtml = false,
        $allowed_editors = array()
    ) {
        parent::__construct(\XoopsLocale::A_SELECT);
        $this->allowed_editors = $allowed_editors;
        $this->form = $form;
        $this->name = $name;
        $this->value = $value;
        $this->nohtml = $nohtml;
    }

    /**
     * render
     *
     * @return string
     */
    public function render()
    {
        $editor_handler = \XoopsEditorHandler::getInstance();
        $editor_handler->allowed_editors = $this->allowed_editors;
        $option_select = new Select("", $this->name, $this->value);
        $onchangeCode = '"if(this.options[this.selectedIndex].value.length > 0 ){window.document.forms.'
            . $this->form->getName() . '.submit();}"';
        $option_select->set('onchange', $onchangeCode);
        $option_select->addOptionArray($editor_handler->getList($this->nohtml));
        $this->addElement($option_select);
        return parent::render();
    }
}
