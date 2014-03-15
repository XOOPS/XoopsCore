<?php
require_once(dirname(__FILE__).'/../../../../init.php');

class XoopsObjectHandlerTestInstance extends Xoops\Core\Kernel\XoopsObjectHandler
{
	function __construct(XoopsConnection $db)
	{
		parent::__construct($db);
	}
}

/**
* PHPUnit special settings :
* @backupGlobals disabled
* @backupStaticAttributes disabled
*/
class XoopsObjectHandlerTest extends MY_UnitTestCase
{
	protected $myclass = 'XoopsObjectHandlerTestInstance';

    public function test___publicProperties()
	{
		$items = array('db2');
		foreach($items as $item) {
			$prop = new ReflectionProperty($this->myclass,$item);
			$this->assertTrue($prop->isPublic());
		}
    }
	
    public function test___construct()
	{
		$conn = XoopsDatabaseFactory::getConnection();
        $instance = new $this->myclass($conn);
        $this->assertInstanceOf($this->myclass, $instance);
		$this->assertSame($conn, $instance->db2);
    }
	
}
