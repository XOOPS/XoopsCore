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
 * Editor - an editor element
 *
 * @category  Xoops\Form\Editor
 * @package   Xoops\Form
 * @author    Taiwen Jiang <phppp@users.sourceforge.net>
 * @copyright 2001-2015 XOOPS Project (http://xoops.org)
 * @license   GNU GPL 2 or later (http://www.gnu.org/licenses/gpl-2.0.html)
 * @link      http://xoops.org
 * @since     2.0.0
 */
class Editor extends TextArea
{
    /**
     * @var null|object
     */
    public $editor;

    /**
     * Constructor
     *
     * @param string     $caption   Caption
     * @param string     $name      Name for textarea field
     * @param array|null $configs   configuration - keys:
     *                                 editor - editor identifier
     *                                 name - textarea field name
     *                                 width, height - dimensions for textarea
     *                                 value - text content
     * @param bool       $nohtml    use non-WYSIWYG editor onfailure
     * @param string     $OnFailure editor to be used if current one failed
     */
    public function __construct($caption, $name, $configs = null, $nohtml = false, $OnFailure = '')
    {
        // Backward compatibility: $name -> editor name; $configs['name'] -> textarea field name
        if (!isset($configs['editor'])) {
            $configs['editor'] = $name;
            $name = $configs['name'];
            // New: $name -> textarea field name;
            //      $configs['editor'] -> editor name;
            //      $configs['name'] -> textarea field name
        } else {
            $configs['name'] = $name;
        }
        parent::__construct($caption, $name);
        $editor_handler = \XoopsEditorHandler::getInstance();
        $this->editor = $editor_handler->get($configs['editor'], $configs, $nohtml, $OnFailure);
    }

    /**
     * renderValidationJS
     * TEMPORARY SOLUTION to 'override' original renderValidationJS method
     * with custom XoopsEditor's renderValidationJS method
     *
     * @return string|false
     */
    public function renderValidationJS()
    {
        if ($this->editor instanceof \XoopsEditor && $this->isRequired()) {
            if (method_exists($this->editor, 'renderValidationJS')) {
                $this->editor->setName($this->getName());
                $this->editor->setCaption($this->getCaption());
                $this->editor->setRequired($this->isRequired());
                $ret = $this->editor->renderValidationJS();
                return $ret;
            } else {
                parent::renderValidationJS();
            }
        }
        return false;
    }

    /**
     * render
     *
     * @return string
     */
    public function render()
    {
        if ($this->editor instanceof \XoopsEditor) {
            return $this->editor->render();
        }
        return '';
    }
}
