<?php
/*
 You may not change or alter any portion of this comment or credits
 of supporting developers from this source code or any supporting source code
 which is considered copyrighted (c) material of the original comment or credit authors.

 This program is distributed in the hope that it will be useful,
 but WITHOUT ANY WARRANTY; without even the implied warranty of
 MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
*/

namespace Xoops\Core\Kernel;

use Doctrine\DBAL\Query\QueryBuilder;

/**
 * Collection of multiple CriteriaElement objects
 *
 * @category  Xoops\Core\Kernel\CriteriaCompo
 * @package   Xoops\Core\Kernel
 * @author    Kazumi Ono <onokazu@xoops.org>
 * @author    Nathan Dial <ndial@trillion21.com>
 * @author    Taiwen Jiang <phppp@users.sourceforge.net>
 * @copyright 2000-2020 XOOPS Project (https://xoops.org)
 * @license   GNU GPL 2 or later (http://www.gnu.org/licenses/gpl-2.0.html)
 * @link      https://xoops.org
 * @since     2.0.0
 */
class CriteriaCompo extends CriteriaElement
{
    /**
     * The elements of the collection
     *
     * @var CriteriaElement[] array of objects
     */
    protected $criteriaElements = [];

    /**
     * Conditions
     *
     * @var array
     */
    protected $conditions = [];

    /**
     * Constructor
     *
     * @param CriteriaElement|null $ele       a criteria element to start the compo
     * @param string               $condition joining condition for element, AND or OR
     */
    public function __construct(CriteriaElement $ele = null, $condition = 'AND')
    {
        if (isset($ele)) {
            $this->add($ele, $condition);
        }
    }

    /**
     * add a criteria element
     *
     * @param CriteriaElement $criteriaElement a criteria element to add to the compo
     * @param string          $condition       joining condition for element, AND or OR
     *
     * @return CriteriaCompo
     */
    public function add(CriteriaElement $criteriaElement, $condition = 'AND')
    {
        $this->criteriaElements[] = $criteriaElement;
        $this->conditions[] = $condition;

        return $this;
    }

    /**
     * Make the criteria into a query string
     *
     * @return string
     */
    public function render()
    {
        $ret = '';
        foreach ($this->criteriaElements as $i => $element) {
            if (!is_object($element)) {
                continue;
            }
            /* @var $element CriteriaElement */
            if (0 == $i) {
                $ret = $element->render();
            } else {
                if (!$render = $element->render()) {
                    continue;
                }
                $ret .= ' ' . $this->conditions[$i] . ' (' . $render . ')';
            }
            $ret = "({$ret})";
        }
        $ret = ('()' === $ret) ? '(1)' : $ret;

        return $ret;
    }

    /**
     * Make the criteria into a SQL "WHERE" clause
     *
     * @return string
     */
    public function renderWhere()
    {
        $ret = $this->render();
        $ret = ('' != $ret) ? 'WHERE ' . $ret : $ret;

        return $ret;
    }

    /**
     * Generate an LDAP filter from criteria
     *
     * @return string
     * @author Nathan Dial ndial@trillion21.com
     */
    public function renderLdap()
    {
        $ret = '';
        foreach ($this->criteriaElements as $i => $element) {
            /* @var $element CriteriaElement */
            if (0 == $i) {
                $ret = $element->renderLdap();
            } else {
                $cond = mb_strtoupper($this->conditions[$i]);
                $op = ('OR' === $cond) ? '|' : '&';
                $ret = "({$op}{$ret}" . $element->renderLdap() . ')';
            }
        }

        return $ret;
    }

    /**
     * Render as Doctrine QueryBuilder instructions
     *
     * @param QueryBuilder $qb        query builder instance
     * @param string       $whereMode how does this fit in the passed in QueryBuilder?
     *                                '' = as where,'and'= as andWhere, 'or' = as orWhere
     *
     * @return QueryBuilder query builder instance
     */
    public function renderQb(QueryBuilder $qb = null, $whereMode = '')
    {
        if (null == $qb) {
            $qb = \Xoops::getInstance()->db()->createXoopsQueryBuilder();
            $whereMode = ''; // first entry in new instance must be where
        }

        $expr = '';
        foreach ($this->criteriaElements as $i => $element) {
            $expr_part = $element->buildExpressionQb($qb);
            if (false !== $expr_part) {
                if (0 == $i) {
                    $expr = $expr_part;
                } else {
                    $expr .= ' ' . mb_strtoupper($this->conditions[$i]) . ' ' . $expr_part;
                }
            }
        }

        if (!empty($expr)) {
            $expr = '(' . $expr . ')'; // group all conditions in this compo

            switch (mb_strtolower($whereMode)) {
                case 'and':
                    $qb->andWhere($expr);
                    break;
                case 'or':
                    $qb->orWhere($expr);
                    break;
                case '':
                    $qb->where($expr);
                    break;
            }
        }

        if (0 != $this->limit || 0 != $this->start) {
            $qb->setFirstResult($this->start)
                ->setMaxResults($this->limit);
        }

        if (!empty($this->groupBy)) {
            $qb->groupBy($this->groupBy);
        }

        if (!empty($this->sort)) {
            $qb->orderBy($this->sort, $this->order);
        }

        return $qb;
    }

    /**
     * Build an expression to be included in a Doctrine QueryBuilder instance.
     *
     * This method will build an expression, adding any parameters to the query,
     * but the caller is responsible for adding the expression to the query, for
     * example as where() parameter. This allows the caller to handle all context,
     * such as parenthetical groupings.
     *
     * @param QueryBuilder $qb query builder instance
     *
     * @return string expression
     */
    public function buildExpressionQb(QueryBuilder $qb)
    {
        $expr = false;
        foreach ($this->criteriaElements as $i => $element) {
            $expr_part = $element->buildExpressionQb($qb);
            if (false !== $expr_part) {
                if (0 == $i) {
                    $expr = $expr_part;
                } else {
                    $expr .= ' ' . mb_strtoupper($this->conditions[$i]) . ' ' . $expr_part;
                }
            }
        }

        if (!empty($expr)) {
            $expr = '(' . $expr . ')'; // group all conditions in this compo
        }

        return $expr;
    }
}
