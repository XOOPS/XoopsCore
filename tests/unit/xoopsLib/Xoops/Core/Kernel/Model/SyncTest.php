<?php
require_once(dirname(__FILE__).'/../../../../../init_new.php');

use Xoops\Core\Kernel\Model\Sync;

/**
* PHPUnit special settings :
* @backupGlobals disabled
* @backupStaticAttributes disabled
*/
class SyncTest extends \PHPUnit_Framework_TestCase
{
    protected $conn = null;

    protected $myClass = 'Xoops\Core\Kernel\Model\Sync';
    protected $myAbstractClass = 'Xoops\Core\Kernel\XoopsModelAbstract';

    public function setUp()
    {
        $db = XoopsDatabaseFactory::getDatabaseConnection();
        $this->conn = $db->conn;
    }

    public function test___construct()
    {
        $instance=new $this->myClass();
        $this->assertInstanceOf($this->myClass, $instance);
        $this->assertInstanceOf($this->myAbstractClass, $instance);
    }

    public function test_cleanOrphan()
    {
        $instance=new $this->myClass();
        $this->assertinstanceOf($this->myClass, $instance);

        $handler = new XoopsMembershipHandler($this->conn);
        $result = $instance->setHandler($handler);
        $this->assertTrue($result);

        $db = XoopsDatabaseFactory::getDatabaseConnection();
        $handler->table_link=$db->prefix('groups');
        $handler->field_link='groupid';
        $handler->field_object='groupid';

        $values=$instance->cleanOrphan();
        $this->assertTrue(is_int($values));
        $this->assertTrue($values == 0);

    }
}
