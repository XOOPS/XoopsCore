<?php

use Doctrine\DBAL\Schema\Table;

require_once(__DIR__.'/../../../../../init_new.php');

class PrefixStripperTest extends \PHPUnit\Framework\TestCase
{
    protected $myClass = 'Xoops\Core\Database\Schema\PrefixStripper';

    public function test___construct()
    {
        $instance = new $this->myClass('prefix_');
        $this->assertInstanceOf($this->myClass, $instance);
        $this->assertInstanceOf('Doctrine\DBAL\Schema\Schema', $instance);
    }

    public function test_setTableFilter()
    {
        $instance = new $this->myClass('prefix_', ['table1']);
        $table1 = new Table('prefix_table1');
        $table2 = new Table('prefix_table2');
        $instance->addTable($table1);
        $instance->addTable($table2);
        $this->assertTrue($instance->hasTable('table1'));
        $this->assertFalse($instance->hasTable('table2'));
    }

    public function test_addTable()
    {
        $table = new Doctrine\DBAL\Schema\Table('prefix_system_group');

        $instance = new $this->myClass('prefix_');
        $instance->addTable($table);

        $this->assertTrue($instance->hasTable('system_group'));
    }

    public function test_addSequence()
    {
        $sequence = new Doctrine\DBAL\Schema\Sequence('sequence');

        $instance = new $this->myClass('prefix_', []);
        $instance->addSequence($sequence);
        $value = $instance->getSequence('sequence');
        $this->assertInstanceOf('Doctrine\DBAL\Schema\Sequence', $value);
    }
}
