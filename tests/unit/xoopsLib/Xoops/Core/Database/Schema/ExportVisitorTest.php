<?php
require_once(dirname(__FILE__).'/../../../../../init_new.php');

use Doctrine\DBAL\Types\Type;

/**
* PHPUnit special settings :
* @backupGlobals disabled
* @backupStaticAttributes disabled
*/
class ExportVisitorTest extends \PHPUnit_Framework_TestCase
{
    protected $myclass = 'Xoops\Core\Database\Schema\ExportVisitor';
	
    public function test___construct()
	{
		$instance = new $this->myclass();
		$this->assertInstanceOf($this->myclass, $instance);
		$this->assertInstanceOf('Doctrine\DBAL\Schema\Visitor\Visitor', $instance);
    }
	
    public function test_getSchemaArray()
	{
		$instance = new $this->myclass();
		
		$value = $instance->getSchemaArray();
		
		$this->assertTrue(is_array($value));
		$this->assertTrue(empty($value));
    }
	
    public function test_acceptSchema()
	{
		$instance = new $this->myclass();
		
		$schema = new Doctrine\DBAL\Schema\Schema();		
		
		$instance->acceptSchema($schema);
		
		$value = $instance->getSchemaArray();
		$this->assertTrue(is_array($value));
		$this->assertTrue(empty($value));
    }
	
    public function test_acceptTable()
	{
		$instance = new $this->myclass();
		
		$table = new Doctrine\DBAL\Schema\Table('groups');
		
		$instance->acceptTable($table);
		
		$value = $instance->getSchemaArray();
		$this->assertTrue(is_array($value));
		$this->assertTrue(!empty($value['tables']));
    }
	
    public function test_acceptColumn()
	{
		$instance = new $this->myclass();
		
		$table = new Doctrine\DBAL\Schema\Table('groups');
		$type = Type::getType(Type::INTEGER);
		$col_name = 'groupid';
		$column = new Doctrine\DBAL\Schema\Column($col_name,$type);
		
		$instance->acceptColumn($table,$column);
		
		$value = $instance->getSchemaArray();
		$this->assertTrue(is_array($value));
		$this->assertTrue(!empty($value['tables']['groups']['columns']['groupid']));
		$this->assertSame($col_name,$value['tables']['groups']['columns'][$col_name]['name']);
		$this->assertSame('integer',$value['tables']['groups']['columns'][$col_name]['type']);
    }
	
    public function test_acceptForeignKey()
	{
		$instance = new $this->myclass();
		$this->assertInstanceOf($this->myclass, $instance);
		
		$tName = 'groups';
		$table = new Doctrine\DBAL\Schema\Table($tName);
		
		$columns = array('groupid');
		$fk_table = 'group_permission';
		$fk_name = 'fk_name';
		$fk_options = array('o'=>'o1');
		$fk_columns = array('group_permission');
		$fk_constraint = new Doctrine\DBAL\Schema\ForeignKeyConstraint(
			$columns,$fk_table,$fk_columns, $fk_name, $fk_options);
		
		$instance->acceptForeignKey($table,$fk_constraint);
		
		$value = $instance->getSchemaArray();
		$this->assertTrue(is_array($value));
		$this->assertTrue(!empty($value['tables'][$tName]['constraint']));
		$tmp = $value['tables'][$tName]['constraint'][0];
		$this->assertSame($fk_name,$tmp['name']);
		$this->assertSame($columns,$tmp['localcolumns']);
		$this->assertSame($fk_table,$tmp['foreigntable']);
		$this->assertSame($fk_columns,$tmp['foreigncolumns']);
		$this->assertSame($fk_options,$tmp['options']);
    }
	
    public function test_acceptIndex()
	{
		$instance = new $this->myclass();
		$this->assertInstanceOf($this->myclass, $instance);
		
		$tName = 'groups';
		$table = new Doctrine\DBAL\Schema\Table($tName);
		
		$name = 'index_name';
		$columns = array('name','description');
		$unique = true;
		$primary = true;
		$index = new Doctrine\DBAL\Schema\Index(
			$name,$columns, $unique, $primary);
		
		$instance->acceptIndex($table,$index);
		
		$value = $instance->getSchemaArray();
		$this->assertTrue(is_array($value));
		$this->assertTrue(!empty($value['tables'][$tName]['indexes'][$name]));
		$tmp = $value['tables'][$tName]['indexes'][$name];
		$this->assertSame($name,$tmp['name']);
		$this->assertSame($columns,$tmp['columns']);
		$this->assertSame($unique,$tmp['unique']);
		$this->assertSame($primary,$tmp['primary']);
    }
	
    public function test_acceptSequence()
	{
		$instance = new $this->myclass();
		$this->assertInstanceOf($this->myclass, $instance);
		
		$name = 'sequence_name';
		$alloc_size = 10;
		$initial_value = 11;
		$sequence = new Doctrine\DBAL\Schema\Sequence(
			$name,$alloc_size, $initial_value);
		
		$instance->acceptSequence($sequence);
		
		$value = $instance->getSchemaArray();
		$this->assertTrue(is_array($value));
		$this->assertTrue(!empty($value['sequence'][$name]));
		$tmp = $value['sequence'][$name];
		$this->assertSame($name,$tmp['name']);
		$this->assertSame($alloc_size,$tmp['allocationsize']);
		$this->assertSame($initial_value,$tmp['initialvalue']);

    }

}
