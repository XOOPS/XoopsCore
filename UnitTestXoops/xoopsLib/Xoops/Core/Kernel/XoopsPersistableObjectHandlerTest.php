<?php
require_once(dirname(__FILE__).'/../../../../init.php');

class XoopsPersistableObjectHandlerTestInstance extends Xoops\Core\Kernel\XoopsPersistableObjectHandler
{
    function __construct(
        \XoopsConnection $db,
        $table = '',
        $className = '',
        $keyName = '',
        $identifierName = ''
	) {
		parent::__construct($db,$table,$className,$keyName,$identiferName);
	}
}

/**
* PHPUnit special settings :
* @backupGlobals disabled
* @backupStaticAttributes disabled
*/
class XoopsPersistableObjectHandlerTest extends MY_UnitTestCase
{
	protected $myclass = 'XoopsPersistableObjectHandlerTestInstance';

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
        $table = 'table';
        $className = 'className';
        $keyName = 'keyName';
        $identifierName = 'identifierName';
        $instance = new $this->myclass($conn,$table,$className,$keyName,$identifierName);
        $this->assertInstanceOf($this->myclass, $instance);
		$this->assertSame($conn, $instance->db2);
    }
	
}
