<?php
require_once(dirname(__FILE__).'/../init.php');

/**
* PHPUnit special settings :
* @backupGlobals disabled
* @backupStaticAttributes disabled
*/
class CriteriaTest extends MY_UnitTestCase
{
    protected $myclass = 'Criteria';
    
    public function test___construct()
	{
		$column = 'column';
		$x = new $this->myclass($column);
        $this->assertInstanceOf($this->myclass, $x);
        $this->assertInstanceOf('\Xoops\Core\Kernel\Criteria', $x);
    }
        
}
