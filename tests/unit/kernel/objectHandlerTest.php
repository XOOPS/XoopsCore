<?php
require_once(dirname(__FILE__).'/../init_new.php');

require_once(XOOPS_TU_ROOT_PATH . '/kernel/object.php');

class Legacy_ObjecthandlerTestInstance extends \XoopsObjectHandler
{
}

/**
* PHPUnit special settings :
* @backupGlobals disabled
* @backupStaticAttributes disabled
*/
class Legacy_ObjecthandlerTest extends \PHPUnit_Framework_TestCase
{
    public $myClass='Legacy_ObjecthandlerTestInstance';

    public function test___publicProperties()
    {
        $items = array('db');
        foreach ($items as $item) {
            $prop = new ReflectionProperty($this->myClass, $item);
            $this->assertTrue($prop->isPublic());
        }
    }

    public function test___construct()
    {
        $conn = \Xoops\Core\Database\Factory::getConnection();
        $instance = new $this->myClass($conn);
        $this->assertInstanceOf($this->myClass, $instance);
        $this->assertInstanceOf('Xoops\Core\Kernel\XoopsObjectHandler', $instance);
    }
}
