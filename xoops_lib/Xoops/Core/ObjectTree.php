<?php

namespace Xoops\Core;

use Xoops\Core\Kernel\XoopsObject;
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
 * Tree structures with XoopsObjects as nodes
 *
 * @category  Xoops\Core
 * @package   ObjectTree
 * @author    Kazumi Ono <onokazu@xoops.org>
 * @copyright 2000-2020 XOOPS Project (https://xoops.org)
 * @license   GNU GPL 2 or later (https://www.gnu.org/licenses/gpl-2.0.html)
 */
class ObjectTree
{
    /**
     * @var string
     */
    protected $parentId;

    /**
     * @var string
     */
    protected $myId;

    /**
     * @var null|string
     */
    protected $rootId;

    /**
     * @var array;
     */
    protected $tree = [];

    /**
     * @var array
     */
    protected $objects;

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
        $this->objects = $objectArr;
        $this->myId     = $myId;
        $this->parentId = $parentId;
        if (isset($rootId)) {
            $this->rootId = $rootId;
        }
        $this->initialize();
    }

    /**
     * Initialize the object
     *
     * @return void
     */
    protected function initialize(): void
    {
        foreach (array_keys($this->objects) as $i) {
            $key1                         = $this->objects[$i]->getVar($this->myId);
            $this->tree[$key1]['obj']     = $this->objects[$i];
            $key2                         = $this->objects[$i]->getVar($this->parentId);
            $this->tree[$key1]['parent']  = $key2;
            $this->tree[$key2]['child'][] = $key1;
            if (isset($this->rootId)) {
                $this->tree[$key1]['root'] = $this->objects[$i]->getVar($this->rootId);
            }
        }
    }

    /**
     * Get the tree
     *
     * @return array Associative array comprising the tree
     */
    public function getTree(): array
    {
        return $this->tree;
    }

    /**
     * returns an object from the tree specified by its id
     *
     * @param  string $key ID of the object to retrieve
     * @return XoopsObject Object within the tree
     */
    public function getByKey($key): XoopsObject
    {
        return $this->tree[$key]['obj'];
    }

    /**
     * returns an array of all the first child object of an object specified by its id
     *
     * @param  string $key ID of the parent object
     * @return array  Array of children of the parent
     */
    public function getFirstChild($key): array
    {
        $ret = [];
        if (isset($this->tree[$key]['child'])) {
            foreach ($this->tree[$key]['child'] as $childKey) {
                $ret[$childKey] = $this->tree[$childKey]['obj'];
            }
        }
        return $ret;
    }

    /**
     * returns an array of all child objects of an object specified by its id
     *
     * @param  string $key ID of the parent
     * @param  array  $ret (Empty when called from client) Array of children from previous recursions.
     * @return array  Array of child nodes.
     */
    public function getAllChild($key, $ret = []): array
    {
        if (isset($this->tree[$key]['child'])) {
            foreach ($this->tree[$key]['child'] as $childKey) {
                $ret[$childKey] = $this->tree[$childKey]['obj'];
                $children       = $this->getAllChild($childKey, $ret);
                foreach (array_keys($children) as $newKey) {
                    $ret[$newKey] = $children[$newKey];
                }
            }
        }
        return $ret;
    }

    /**
     * returns an array of all parent objects.
     * the key of returned array represents how many levels up from the specified object
     *
     * @param  int   $key     ID of the child object
     * @param  array $ret     (empty when called from outside) Result from previous recursions
     * @param  int   $upLevel (empty when called from outside) level of recursion
     * @return array Array of parent nodes.
     */
    public function getAllParent($key, $ret = [], $upLevel = 1): array
    {
        if (isset($this->tree[$key]['parent']) && isset($this->tree[$this->tree[$key]['parent']]['obj'])) {
            $ret[$upLevel] = $this->tree[$this->tree[$key]['parent']]['obj'];
            $parents       = $this->getAllParent($this->tree[$key]['parent'], $ret, $upLevel + 1);
            foreach (array_keys($parents) as $newKey) {
                $ret[$newKey] = $parents[$newKey];
            }
        }
        return $ret;
    }

    /**
     * Make a select box with options from the tree
     *
     * This replaces makeSelectElement(). The parameters follow the Select element first, followed
     * by the tree descriptions.
     *
     * The $extra parameter has been removed, as setExtra() is deprecated. Please use the Select
     * object's attributes to add any required script for event handlers such as 'onSelect'.
     *
     * @param  string $caption        optional caption for form element
     * @param  string $name           Name of the select box
     * @param  string $selected       Value to display as selected
     * @param  string $fieldName      Name of the member variable from the
     *                                node objects that should be used as the title for the options.
     * @param  string $prefix         String to indent deeper levels
     * @param  bool   $addEmptyOption Set TRUE to add an empty option with value "0" at the top of the hierarchy
     * @param  int    $key            ID of the object to display as the root of select options
     *
     * @return Select form element
     */
    public function makeSelect(
        string $caption,
        string $name,
        string $selected,
        string $fieldName,
        string $prefix = '-',
        bool $addEmptyOption = false,
        int $key = 0
    ): Select {
        $element = new Select($caption, $name, $selected);

        if (false !== $addEmptyOption) {
            $element->addOption('0', ' ');
        }
        $this->addSelectOptions($element, $fieldName, $key, $prefix);

        return $element;
    }

    /**
     * Make options for a select box from
     *
     * @param Select $element     form element to receive tree values as options
     * @param string $fieldName   Name of the member variable from the node objects that
     *                            should be used as the title for the options.
     * @param int    $key         ID of the object to display as the root of select options
     * @param string $prefix_orig String to indent items at deeper levels
     * @param string $prefix_curr String to indent the current item
     *
     * @return void
     */
    protected function addSelectOptions(
        Select $element,
        string $fieldName,
        int $key,
        string $prefix_orig,
        string $prefix_curr = ''
    ): void {
        if ($key > 0) {
            $value = $this->tree[$key]['obj']->getVar($this->myId);
            $name = $prefix_curr . $this->tree[$key]['obj']->getVar($fieldName);
            $element->addOption($value, $name);
            $prefix_curr .= $prefix_orig;
        }
        if (isset($this->tree[$key]['child']) && !empty($this->tree[$key]['child'])) {
            foreach ($this->tree[$key]['child'] as $childKey) {
                $this->addSelectOptions($element, $fieldName, $childKey, $prefix_orig, $prefix_curr);
            }
        }
    }
}
