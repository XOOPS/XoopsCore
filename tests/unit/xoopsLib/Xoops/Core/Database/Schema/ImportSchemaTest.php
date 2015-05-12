<?php
require_once(dirname(__FILE__).'/../../../../../init_new.php');

use Doctrine\DBAL\Types\Type;

/**
* PHPUnit special settings :
* @backupGlobals disabled
* @backupStaticAttributes disabled
*/
class ImportSchemaTest extends \PHPUnit_Framework_TestCase
{
    protected $myClass = 'Xoops\Core\Database\Schema\ImportSchema';
	
    public function test___construct()
	{
		$instance = new $this->myClass();
		$this->assertInstanceOf($this->myClass, $instance);
    }
	
    public function test_importSchemaArray()
	{
		$instance = new Xoops\Core\Database\Schema\ExportVisitor();
		
		$table = new Doctrine\DBAL\Schema\Table('groups');
		$type = Type::getType(Type::INTEGER);
		$col_name = 'groupid';
		$column = new Doctrine\DBAL\Schema\Column($col_name,$type);
		
		$instance->acceptColumn($table,$column);
		
		$columns = array('groupid');
		$fk_table = 'group_permission';
		$fk_name = 'fk_name';
		$fk_options = array('o'=>'o1');
		$fk_columns = array('group_permission');
		$fk_constraint = new Doctrine\DBAL\Schema\ForeignKeyConstraint(
			$columns,$fk_table,$fk_columns, $fk_name, $fk_options);
		
		$instance->acceptForeignKey($table,$fk_constraint);
		
		$name = 'index_name';
		$columns = array('name','description');
		$unique = true;
		$primary = true;
		$index = new Doctrine\DBAL\Schema\Index(
			$name,$columns, $unique, $primary);
		
		$instance->acceptIndex($table,$index);
		
		$name = 'sequence_name';
		$alloc_size = 10;
		$initial_value = 11;
		$sequence = new Doctrine\DBAL\Schema\Sequence(
			$name,$alloc_size, $initial_value);
		
		$instance->acceptSequence($sequence);
		
		$schema = $instance->getSchemaArray();
		
		$import = new $this->myClass();
		$value = $import->importSchemaArray($schema);
		
		$this->assertInstanceOf('Doctrine\DBAL\Schema\Schema', $value);
    }
	
    public function test_importTables()
	{
		// see test_importSchemaArray
    }
	
    public function test_importSequences()
	{
		// see test_importSequences
    }

}
