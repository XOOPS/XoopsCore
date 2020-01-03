<?php

use Xoops\Core\ObjectTree;
use Xoops\Form\Select;

/*
 You may not change or alter any portion of this comment or credits
 of supporting developers from this source code or any supporting source code
 which is considered copyrighted (c) material of the original comment or credit authors.

 This program is distributed in the hope that it will be useful,
 but WITHOUT ANY WARRANTY; without even the implied warranty of
 MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
*/

/**
 * XOOPS tree class
 *
 * @copyright   2000-2020 XOOPS Project (https://xoops.org)
 * @license     GNU GPL 2 or later (https://www.gnu.org/licenses/gpl-2.0.html)
 * @since       2.0.0
 * @author      Kazumi Ono (http://www.myweb.ne.jp/, http://jp.xoops.org/)
 */

/**
 * A tree structures with {@link XoopsObject}s as nodes
 *
 * @package    Kernel
 * @subpackage Core
 * @author     Kazumi Ono <onokazu@xoops.org>
 *
 * @property-read array $_tree direct access to tree (deprecated)
 */
class XoopsObjectTree extends ObjectTree
{
    /**
     * Constructor
     *
     * @param XoopsObject[] $objectArr array of XoopsObject that form the tree
     * @param string        $myId      field name of the ID for each object
     * @param string        $parentId  field name of the ID in each object of parent object
     * @param string|null   $rootId    optional field name of the root object ID,
     *                                 i.e. the top comment in a series of nested comments
     */
    public function __construct($objectArr, $myId, $parentId, $rootId = null)
    {
        $trace = debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS, 1);
        \Xoops::getInstance()->deprecated(
            'XoopsObjectTree is deprecated, please use Xoops\\Core\\ObjectTree, ' .
            "accessed from {$trace[0]['file']} line {$trace[0]['line']},"
        );
        parent::__construct($objectArr, $myId, $parentId, $rootId);
    }

    /**
     * Make options for a select box from
     *
     * @param string $fieldName   Name of the member variable from the
     *                            node objects that should be used as the title for the options.
     * @param string $selected    Value to display as selected
     * @param int    $key         ID of the object to display as the root of select options
     * @param string $ret         (reference to a string when called from outside) Result from previous recursions
     * @param string $prefix_orig String to indent items at deeper levels
     * @param string $prefix_curr String to indent the current item
     *
     * @return void
     * @deprecated since 2.5.9, please use makeSelectElement() functionality
     */
    protected function makeSelBoxOptions($fieldName, $selected, $key, &$ret, $prefix_orig, $prefix_curr = '')
    {
        if ($key > 0) {
            $value = $this->tree[$key]['obj']->getVar($this->myId);
            $ret .= '<option value="' . $value . '"';
            if ($value == $selected) {
                $ret .= ' selected';
            }
            $ret .= '>' . $prefix_curr . $this->tree[$key]['obj']->getVar($fieldName) . '</option>';
            $prefix_curr .= $prefix_orig;
        }
        if (isset($this->tree[$key]['child']) && !empty($this->tree[$key]['child'])) {
            foreach ($this->tree[$key]['child'] as $childKey) {
                $this->makeSelBoxOptions($fieldName, $selected, $childKey, $ret, $prefix_orig, $prefix_curr);
            }
        }
    }

    /**
     * Make a select box with options from the tree
     *
     * @param  string  $name           Name of the select box
     * @param  string  $fieldName      Name of the member variable from the
     *                                 node objects that should be used as the title for the options.
     * @param  string  $prefix         String to indent deeper levels
     * @param  string  $selected       Value to display as selected
     * @param  bool    $addEmptyOption Set TRUE to add an empty option with value "0" at the top of the hierarchy
     * @param  int $key            ID of the object to display as the root of select options
     * @param  string  $extra          extra content to add to the element
     * @return string  HTML select box
     *
     * @deprecated since 2.5.9, please use makeSelectElement()
     */
    public function makeSelBox(
        $name,
        $fieldName,
        $prefix = '-',
        $selected = '',
        $addEmptyOption = false,
        $key = 0,
        $extra = ''
    ) {
        $trace = debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS, 1);
        \Xoops::getInstance()->deprecated(
            'makeSelBox() is deprecated since 2.5.9, please use makeSelectElement(), ' .
            "accessed from {$trace[0]['file']} line {$trace[0]['line']},"
        );
        $ret = '<select name="' . $name . '" id="' . $name . '" ' . $extra . '>';
        if (false !== (bool)$addEmptyOption) {
            $ret .= '<option value="0"></option>';
        }
        $this->makeSelBoxOptions($fieldName, $selected, $key, $ret, $prefix);

        return $ret . '</select>';
    }

    /**
     * Make a select box with options from the tree
     *
     * @param  string  $name           Name of the select box
     * @param  string  $fieldName      Name of the member variable from the
     *                                 node objects that should be used as the title for the options.
     * @param  string  $prefix         String to indent deeper levels
     * @param  string  $selected       Value to display as selected
     * @param  bool    $addEmptyOption Set TRUE to add an empty option with value "0" at the top of the hierarchy
     * @param  int $key            ID of the object to display as the root of select options
     * @param  string  $extra          extra content to add to the element
     * @param  string  $caption        optional caption for form element
     *
     * @return Select form element
     *
     * @deprecated use Xoops\Core\ObjectTree::makeSelect()
     */
    public function makeSelectElement(
        $name,
        $fieldName,
        $prefix = '-',
        $selected = '',
        $addEmptyOption = false,
        $key = 0,
        $extra = '',
        $caption = ''
    ) {
        $element = new Select($caption, $name, $selected);
        $element->setExtra($extra);

        if (false !== (bool)$addEmptyOption) {
            $element->addOption('0', ' ');
        }
        $this->addSelectOptions($element, $fieldName, $key, $prefix);

        return $element;
    }

    /**
     * Magic __get method
     *
     * Some modules did not respect the leading underscore is private convention and broke
     * when code was modernized. This will keep them running for now.
     *
     * @param string $name unknown variable name requested
     *                      currently only '_tree' is supported
     *
     * @return mixed value
     */
    public function __get($name)
    {
        $trace = debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS, 1);
        if ('_tree' === $name) {
            \Xoops::getInstance()->deprecated(
                'XoopsObjectTree::$_tree is deprecated since 2.5.9, please use makeSelectElement(), ' .
                "accessed from {$trace[0]['file']} line {$trace[0]['line']},"
            );

            return $this->tree;
        }
        trigger_error(
            'Undefined property: XoopsObjectTree::$' . $name .
            " in {$trace[0]['file']} line {$trace[0]['line']}, ",
            E_USER_NOTICE
        );

        return null;
    }
}
