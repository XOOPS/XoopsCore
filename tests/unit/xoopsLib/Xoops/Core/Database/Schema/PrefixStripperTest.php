<?php
require_once(__DIR__.'/../../../../../init_new.php');

class PrefixStripperTest extends \PHPUnit\Framework\TestCase
{
    protected $myClass = 'Xoops\Core\Database\Schema\PrefixStripper';

    public function test___construct()
    {
        $instance = new $this->myClass();
        $this->assertInstanceOf($this->myClass, $instance);
        $this->assertInstanceOf('Doctrine\DBAL\Schema\Schema', $instance);
    }

    public function test_setTableFilter()
    {
        $instance = new $this->myClass();

        $tables = array();
        $instance->setTableFilter($tables);
        $this->assertTrue(true);
    }

    public function test_addTable()
    {
        $table = new Doctrine\DBAL\Schema\Table('system_group');

        $instance = new $this->myClass(array($table));

        $value = $instance->getTable('system_group');
        $this->assertinstanceOf('Doctrine\DBAL\Schema\Table', $value);
    }

    public function test_addSequence()
    {
        $sequence = new Doctrine\DBAL\Schema\Sequence('sequence');

        $instance = new $this->myClass(array(), array($sequence));

        $value = $instance->getSequence('sequence');
        $this->assertInstanceOf('Doctrine\DBAL\Schema\Sequence', $value);
    }
}
