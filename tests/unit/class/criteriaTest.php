<?php
require_once(dirname(__FILE__).'/../init_new.php');

/**
* PHPUnit special settings :
* @backupGlobals disabled
* @backupStaticAttributes disabled
*/
class CriteriaTest extends \PHPUnit_Framework_TestCase
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
