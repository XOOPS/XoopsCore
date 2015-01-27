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
 * @copyright       The XOOPS Project http://sourceforge.net/projects/xoops/
 * @license         GNU GPL 2 or later (http://www.gnu.org/licenses/gpl-2.0.html)
 * @author          trabis <lusopoemas@gmail.com>
 * @version         $Id$
 */

class SearchSearchForm extends Xoops\Form\ThemeForm
{
    /**
     * We are not using this for objects but we need to override the constructor
     *
     * @param null $obj
     */
    public function __construct($obj = null)
    {
    }

    public function getSearchFrom($andor, $queries, $mids, $mid)
    {
        $xoops = Xoops::getInstance();
        $search = Search::getInstance();
        // create form
        parent::__construct(_MD_SEARCH, 'search', 'index.php', 'get');

        // create form elements
        $this->addElement(new Xoops\Form\Text(_MD_SEARCH_KEYWORDS, 'query', 30, 255, htmlspecialchars(stripslashes($this->queryArrayToString($queries)), ENT_QUOTES)), true);
        $type_select = new Xoops\Form\Select(_MD_SEARCH_TYPE, 'andor', $andor);
        $type_select->addOptionArray(array(
            'AND' => _MD_SEARCH_ALL, 'OR' => _MD_SEARCH_ANY, 'exact' => _MD_SEARCH_EXACT
        ));
        $this->addElement($type_select);
        if (!empty($mids)) {
            $mods_checkbox = new Xoops\Form\Checkbox(_MD_SEARCH_SEARCHIN, 'mids[]', $mids);
        } else {
            $mods_checkbox = new Xoops\Form\Checkbox(_MD_SEARCH_SEARCHIN, 'mids[]', $mid);
        }
        if (empty($modules)) {
            $gperm_handler = $xoops->getHandlerGroupperm();
            $available_modules = $gperm_handler->getItemIds('module_read', $search->getUserGroups());
            $available_plugins = \Xoops\Module\Plugin::getPlugins('search');

            //todo, would be nice to have the module ids availabe also
            $criteria = new CriteriaCompo();
            $criteria->add(new Criteria('dirname', "('" . implode("','", array_keys($available_plugins)) . "')", 'IN'));
            if (isset($available_modules) && !empty($available_modules)) {
                $criteria->add(new Criteria('mid', '(' . implode(',', $available_modules) . ')', 'IN'));
            }
            $module_handler = $xoops->getHandlerModule();
            $mods_checkbox->addOptionArray($module_handler->getNameList($criteria));
        } else {
            /* @var $module XoopsModule */
            $module_array = array();
            foreach ($modules as $mid => $module) {
                $module_array[$mid] = $module->getVar('name');
            }
            $mods_checkbox->addOptionArray($module_array);
        }
        $this->addElement($mods_checkbox);
        if ($search->getConfig('keyword_min') > 0) {
            $this->addElement(new Xoops\Form\Label(_MD_SEARCH_SEARCHRULE, sprintf(_MD_SEARCH_KEYIGNORE, $search->getConfig('keyword_min'))));
        }
        $this->addElement(new Xoops\Form\Hidden('action', 'results'));
        $this->addElement(new Xoops\Form\Token('id'));
        $this->addElement(new Xoops\Form\Button('', 'submit', _MD_SEARCH, 'submit'));
        return $this;
    }

    /**
     * queryArrayToString - convert array of query terms to string respecting quoting
     * conventions
     *
     * @param string[] $queries query terms
     *
     * @return string equivalent query string
     */
    private function queryArrayToString($queries)
    {
        $query = '';
        foreach ($queries as $term) {
            if (false === strpos($term, ' ')) {
                $query .= $term . ' ';
            } else {
                $query .= '"' . $term . '" ';
            }
        }
        $query = trim($query);
        return $query;
    }
}
