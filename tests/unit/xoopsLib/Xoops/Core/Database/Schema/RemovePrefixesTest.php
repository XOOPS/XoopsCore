<?php
require_once(__DIR__.'/../../../../../init_new.php');

use Doctrine\DBAL\Types\Type;

class RemovePrefixesTest extends \PHPUnit\Framework\TestCase
{
    protected $myClass = 'Xoops\Core\Database\Schema\RemovePrefixes';

    public function test___construct()
    {
        $instance = new $this->myClass();
        $this->assertInstanceOf($this->myClass, $instance);
        $this->assertInstanceOf('Doctrine\DBAL\Schema\Visitor\Visitor', $instance);
    }

    public function test_getNewSchema()
    {
        $instance = new $this->myClass();

        $value = $instance->getNewSchema();
        $this->assertInstanceOf('Xoops\Core\Database\Schema\PrefixStripper', $value);
    }

    public function test_setTableFilter()
    {
        $instance = new $this->myClass();
        $instance->setTableFilter(array());
        $this->assertTrue(true);
    }

    public function test_acceptSchema()
    {
        $instance = new $this->myClass();

        $schema = new Doctrine\DBAL\Schema\Schema();
        $value = $instance->acceptSchema($schema);
        $this->assertSame(null, $value);
    }

    public function test_acceptTable()
    {
        $instance = new $this->myClass();

        $table = new Doctrine\DBAL\Schema\Table('system_group');
        $value = $instance->acceptTable($table);
        $this->assertSame(null, $value);
        $value = $instance->getNewSchema();
        // var_dump($value);
    }

    public function test_acceptColumn()
    {
        $instance = new $this->myClass();

        $table = new Doctrine\DBAL\Schema\Table('system_group');
        $type = Type::getType(Type::INTEGER);
        $col_name = 'groupid';
        $column = new Doctrine\DBAL\Schema\Column($col_name, $type);
        $value = $instance->acceptColumn($table, $column);
        $this->assertSame(null, $value);
    }

    public function test_acceptForeignKey()
    {
        $instance = new $this->myClass();

        $table = new Doctrine\DBAL\Schema\Table('system_group');

        $columns = array('groupid');
        $fk_table = 'system_permission';
        $fk_name = 'fk_name';
        $fk_options = array('o'=>'o1');
        $fk_columns = array('system_permission');
        $fk_constraint = new Doctrine\DBAL\Schema\ForeignKeyConstraint(
            $columns, $fk_table, $fk_columns, $fk_name, $fk_options);

        $value = $instance->acceptForeignKey($table, $fk_constraint);
        $this->assertSame(null, $value);
    }

    public function test_acceptIndex()
    {
        $instance = new $this->myClass();

        $table = new Doctrine\DBAL\Schema\Table('system_group');

        $name = 'index_name';
        $columns = array('name','description');
        $unique = true;
        $primary = true;
        $index = new Doctrine\DBAL\Schema\Index(
            $name, $columns, $unique, $primary);

        $value = $instance->acceptIndex($table, $index);
        $this->assertSame(null, $value);
    }

    public function test_acceptSequence()
    {
        $instance = new $this->myClass();

        $table = new Doctrine\DBAL\Schema\Table('system_group');

        $name = 'sequence_name';
        $alloc_size = 10;
        $initial_value = 11;
        $sequence = new Doctrine\DBAL\Schema\Sequence(
            $name, $alloc_size, $initial_value);

        $value = $instance->acceptSequence($sequence);
        $this->assertSame(null, $value);
    }
}
