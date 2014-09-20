<?php
require_once(__DIR__.'/../init.php');

use Doctrine\DBAL\Query\QueryBuilder;

class CriteriaElementTestInstance extends CriteriaElement
{
	function render() {}
	function renderWhere() {}
	function renderLdap() {}
	function renderQb(QueryBuilder $qb = null, $whereMode = '') {}
	function buildExpressionQb(QueryBuilder $qb) {}
}

/**
* PHPUnit special settings :
* @backupGlobals disabled
* @backupStaticAttributes disabled
*/
class CriteriaElementTest extends MY_UnitTestCase
{
    protected $myclass = 'CriteriaElementTestInstance';
    
    public function test___construct()
	{
		$x = new $this->myclass();
        $this->assertInstanceOf('CriteriaElement', $x);
        $this->assertInstanceOf('\Xoops\Core\Kernel\CriteriaElement', $x);
    }
        
}
