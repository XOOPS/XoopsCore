<?php
require_once(__DIR__ . '/../../../../init_new.php');

use Xoops\Core\Kernel\Criteria;

class Kernel_CriteriaCompoTestInstance extends Xoops\Core\Kernel\CriteriaCompo
{
    public function getConditions()
    {
        return $this->conditions;
    }

    public function getCriteriaElements()
    {
        return $this->criteriaElements;
    }
}

class Kernel_CriteriaCompoTest extends \PHPUnit\Framework\TestCase
{
    protected $myclass = 'Kernel_CriteriaCompoTestInstance';

    public function test___construct()
    {
        $criteria = new $this->myclass();
        $this->assertInstanceOf($this->myclass, $criteria);
        $this->assertInstanceOf('Xoops\Core\Kernel\CriteriaElement', $criteria);
    }

    public function test_add()
    {
        $criteria_element = new Criteria('dummy_field');
        $condition = 'AND';
        $criteria = new $this->myclass($criteria_element, $condition);
        $this->assertInstanceOf($this->myclass, $criteria);
        $this->assertTrue(1 == count($criteria->getConditions()));
        $this->assertTrue(1 == count($criteria->getCriteriaElements()));
        $criteria->add($criteria_element, $condition);
        $this->assertTrue(2 == count($criteria->getConditions()));
        $this->assertTrue(2 == count($criteria->getCriteriaElements()));
    }

    public function test_render()
    {
        $criteria_element = new Criteria('dummy_field');
        $condition = 'AND';
        $criteria = new $this->myclass($criteria_element, $condition);
        $this->assertInstanceOf($this->myclass, $criteria);
        $x = $criteria->render();
        $this->assertSame('(1)', $x);
    }

    public function test_renderWhere()
    {
        $criteria_element = new Criteria('dummy_field');
        $condition = 'AND';
        $criteria = new $this->myclass($criteria_element, $condition);
        $this->assertInstanceOf($this->myclass, $criteria);
        $x = $criteria->renderWhere();
        $this->assertSame('WHERE (1)', $x);
    }

    public function test_renderLdap()
    {
        $criteria_element = new Criteria('dummy_field');
        $condition = 'AND';
        $criteria = new $this->myclass($criteria_element, $condition);
        $this->assertInstanceOf($this->myclass, $criteria);
        $x = $criteria->renderLdap();
        $this->assertSame('(dummy_field = )', $x);
    }

    public function test_renderQb()
    {
        $criteria_element = new Criteria('dummy_field');
        $condition = 'AND';
        $criteria = new $this->myclass($criteria_element, $condition);
        $this->assertInstanceOf($this->myclass, $criteria);
        $x = $criteria->renderQb();
        $this->assertInstanceOf('Xoops\Core\Database\QueryBuilder', $x);
        $qb = \Xoops::getInstance()->db()->createXoopsQueryBuilder();
        $x = $criteria->renderQb($qb);
        $this->assertInstanceOf('Xoops\Core\Database\QueryBuilder', $x);
    }

    public function test_buildExpressionQb()
    {
        $criteria_element = new Criteria('dummy_field');
        $condition = 'AND';
        $criteria = new $this->myclass($criteria_element, $condition);
        $this->assertInstanceOf($this->myclass, $criteria);
        $qb = \Xoops::getInstance()->db()->createXoopsQueryBuilder();
        $x = $criteria->buildExpressionQb($qb);
        $this->assertSame('(dummy_field = :dcValue1)', $x);
    }
}
