<?php
require_once(dirname(__FILE__).'/../../../../init.php');

use Doctrine\DBAL\Query\QueryBuilder;

class Kernel_CriteriaCompoTest_CriteriaElement extends Xoops\Core\Kernel\CriteriaElement
{
	function render() {}
	function renderWhere() {}
	function renderLdap() {}
	function renderQb(QueryBuilder $qb = null, $whereMode = '') {}
	function buildExpressionQb(QueryBuilder $qb) {}
}

class Kernel_CriteriaCompoTestInstance extends Xoops\Core\Kernel\CriteriaCompo
{
	function getConditions()
	{
		return $this->conditions;
	}
	
	function getCriteriaElements()
	{
		return $this->criteriaElements;
	}
}

/**
* PHPUnit special settings :
* @backupGlobals disabled
* @backupStaticAttributes disabled
*/
class Kernel_CriteriaCompoTest extends MY_UnitTestCase
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
		$criteria_element = new Kernel_CriteriaCompoTest_CriteriaElement();
		$condition = 'AND';
        $criteria = new $this->myclass($criteria_element, $condition);
        $this->assertInstanceOf($this->myclass, $criteria);
		$this->assertTrue(count($criteria->getConditions()) == 1);
		$this->assertTrue(count($criteria->getCriteriaElements()) == 1);
        $criteria->add($criteria_element, $condition);
		$this->assertTrue(count($criteria->getConditions()) == 2);
		$this->assertTrue(count($criteria->getCriteriaElements()) == 2);
    }
	
    public function test_render()
	{
		$this->markTestIncomplete();
    }
	
    public function test_renderWhere()
	{
		$this->markTestIncomplete();
    }
	
    public function test_renderLdap()
	{
		$this->markTestIncomplete();
    }
	
    public function test_renderQb()
	{
		$this->markTestIncomplete();
    }
	
    public function test_buildExpressionQb()
	{
		$this->markTestIncomplete();
    }
	
}
