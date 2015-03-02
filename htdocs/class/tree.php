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
 * XOOPS tree class
 *
 * @copyright   The XOOPS Project http://sourceforge.net/projects/xoops/
 * @license     GNU GPL 2 or later (http://www.gnu.org/licenses/gpl-2.0.html)
 * @package     class
 * @since       2.0.0
 * @author      Kazumi Ono (http://www.myweb.ne.jp/, http://jp.xoops.org/)
 * @version     $Id$
 */

defined('XOOPS_ROOT_PATH') or die('Restricted access');

/**
 * A tree structures with {@link XoopsObject}s as nodes
 *
 * @package kernel
 * @subpackage core
 * @author Kazumi Ono <onokazu@xoops.org>
 */
class XoopsObjectTree
{
    /**
     * @var string
     */
    private $_parentId;

    /**
     * @var string
     */
    private $_myId;

    /**
     * @var null|string
     */
    private $_rootId = null;

    /**
     * @var array
     */
    private $_tree = array();

    /**
     * @var array
     */
    private $_objects;

    /**
     * Constructor
     *
     * @param array $objectArr Array of {@link XoopsObject}s
     * @param string $myId field name of object ID
     * @param string $parentId field name of parent object ID
     * @param string $rootId field name of root object ID
     */
    public function __construct(&$objectArr, $myId, $parentId, $rootId = null)
    {
        $this->_objects = $objectArr;
        $this->_myId = $myId;
        $this->_parentId = $parentId;
        if (isset($rootId)) {
            $this->_rootId = $rootId;
        }
        $this->_initialize();
    }

    /**
     * Initialize the object
     *
     * @access private
     */
    private function _initialize()
    {
        /* @var $object XoopsObject */
        foreach ($this->_objects as $object) {
            $key1 = $object->getVar($this->_myId);
            $this->_tree[$key1]['obj'] = $object;
            $key2 = $object->getVar($this->_parentId);
            $this->_tree[$key1]['parent'] = $key2;
            $this->_tree[$key2]['child'][] = $key1;
            if (isset($this->_rootId)) {
                $this->_tree[$key1]['root'] = $object->getVar($this->_rootId);
            }
        }
    }

    /**
     * Get the tree
     *
     * @return array Associative array comprising the tree
     */
    public function getTree()
    {
        return $this->_tree;
    }

    /**
     * returns an object from the tree specified by its id
     *
     * @param string $key ID of the object to retrieve
     * @return object Object within the tree
     */
    public function getByKey($key)
    {
        return $this->_tree[$key]['obj'];
    }

    /**
     * returns an array of all the first child object of an object specified by its id
     *
     * @param string $key ID of the parent object
     * @return array Array of children of the parent
     */
    public function getFirstChild($key)
    {
        $ret = array();
        if (isset($this->_tree[$key]['child'])) {
            foreach ($this->_tree[$key]['child'] as $childkey) {
                $ret[$childkey] = $this->_tree[$childkey]['obj'];
            }
        }
        return $ret;
    }

    /**
     * returns an array of all child objects of an object specified by its id
     *
     * @param string $key ID of the parent
     * @param array $ret (Empty when called from client) Array of children from previous recursions.
     * @return array Array of child nodes.
     */
    public function getAllChild($key, $ret = array())
    {
        if (isset($this->_tree[$key]['child'])) {
            foreach ($this->_tree[$key]['child'] as $childkey) {
                $ret[$childkey] = $this->_tree[$childkey]['obj'];
                $children = $this->getAllChild($childkey, $ret);
                foreach (array_keys($children) as $newkey) {
                    $ret[$newkey] = $children[$newkey];
                }
            }
        }
        return $ret;
    }

    /**
     * returns an array of all parent objects.
     * the key of returned array represents how many levels up from the specified object
     *
     * @param string $key ID of the child object
     * @param array $ret (empty when called from outside) Result from previous recursions
     * @param int $uplevel (empty when called from outside) level of recursion
     * @return array Array of parent nodes.
     */
    public function getAllParent($key, $ret = array(), $uplevel = 1)
    {
        if (isset($this->_tree[$key]['parent']) && isset($this->_tree[$this->_tree[$key]['parent']]['obj'])) {
            $ret[$uplevel] = $this->_tree[$this->_tree[$key]['parent']]['obj'];
            $parents = $this->getAllParent($this->_tree[$key]['parent'], $ret, $uplevel + 1);
            foreach (array_keys($parents) as $newkey) {
                $ret[$newkey] = $parents[$newkey];
            }
        }
        return $ret;
    }

    /**
     * Make options for a select box from
     *
     * @param string $fieldName Name of the member variable from the
     *       node objects that should be used as the title for the options.
     * @param string $selected Value to display as selected
     * @param int $key ID of the object to display as the root of select options
     * @param string $ret (reference to a string when called from outside) Result from previous recursions
     * @param string $prefix_orig String to indent items at deeper levels
     * @param string $prefix_curr String to indent the current item
     *
     * @return void
     */
    private function _makeSelBoxOptions($fieldName, $selected, $key, &$ret, $prefix_orig, $prefix_curr = '')
    {
        if ($key > 0) {
            /* @var $object XoopsObject */
            $object = $this->_tree[$key]['obj'];
            $value = $object->getVar($this->_myId);
            $ret .= '<option value="' . $value . '"';
            if ($value == $selected) {
                $ret .= ' selected="selected"';
            }
            $ret .= '>' . $prefix_curr . $object->getVar($fieldName) . '</option>';
            $prefix_curr .= $prefix_orig;
        }
        if (isset($this->_tree[$key]['child']) && !empty($this->_tree[$key]['child'])) {
            foreach ($this->_tree[$key]['child'] as $childkey) {
                $this->_makeSelBoxOptions($fieldName, $selected, $childkey, $ret, $prefix_orig, $prefix_curr);
            }
        }
    }

    /**
     * Make a select box with options from the tree
     *
     * @param string $fieldName Name of the member variable from the
     *       node objects that should be used as the title for the options.
     * @param string $prefix String to indent deeper levels
     * @param string $selected Value to display as selected
     * @param bool $addEmptyOption Set TRUE to add an empty option with value "0" at the top of the hierarchy
     * @param integer $key ID of the object to display as the root of select options
     * @param string $extra
     * @param string $name
     * @return string HTML select box
     */
    public function makeSelBox($name, $fieldName, $prefix = '-', $selected = '', $addEmptyOption = false, $key = 0, $extra = '')
    {
        $ret = '<select name="' . $name . '" id="' . $name . '" ' . $extra . '>';
        if (false != $addEmptyOption) {
            $ret .= '<option value="0"></option>';
        }
        $this->_makeSelBoxOptions($fieldName, $selected, $key, $ret, $prefix);
        return $ret . '</select>';
    }
    
    /**
     * Make options for a array
     *
     * @param string $fieldName Name of the member variable from the
     *       node objects that should be used as the column.
     * @param string $prefix String to indent deeper levels
     * @param integer $key ID of the object to display as the root of the array
     * @return array
     */
    public function makeArrayTree( $fieldName, $prefix = '-', $key = 0) {
        $ret = array();
        $this->_makeArrayTreeOptions( $fieldName, $key, $ret, $prefix );
        return $ret;
    }

    /**
     * Make a array with options from the tree
     *
     * @param string $fieldName Name of the member variable from the
     *       node objects that should be used as the column.
     * @param integer $key ID of the object to display as the root of the array
     * @param string $prefix_orig String to indent deeper levels (origin)
     * @param string $prefix_curr String to indent deeper levels (current)
     *
     * @return void
     */
    public function _makeArrayTreeOptions( $fieldName, $key, &$ret, $prefix_orig, $prefix_curr = '' ) {
        if ( $key > 0 ) {
            $value = $this->_tree[$key]['obj']->getVar( $this->_myId );
            $ret[$value] = $prefix_curr . $this->_tree[$key]['obj']->getVar( $fieldName );
            $prefix_curr .= $prefix_orig;

        }
        if ( isset( $this->_tree[$key]['child'] ) && !empty( $this->_tree[$key]['child'] ) ) {
            foreach ( $this->_tree[$key]['child'] as $childkey ) {
                $this->_makeArrayTreeOptions( $fieldName, $childkey, $ret, $prefix_orig, $prefix_curr );
            }
        }
    }
}
