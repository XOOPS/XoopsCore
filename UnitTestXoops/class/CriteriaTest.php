<?php
require_once(dirname(__FILE__).'/../init.php');

/**
* PHPUnit special settings :
* @backupGlobals disabled
* @backupStaticAttributes disabled
*/
class CriteriaTest extends MY_UnitTestCase
{
    protected $myclass = 'Criteria';
    
    public function SetUp()
	{
    }
    
    public function test___construct()
	{
        $column = 'column';
        $value = 'value';
        $operator = 'operator';
        $prefix = 'prefix';
        $function = 'function';
        $criteria = new $this->myclass($column, $value, $operator, $prefix, $function);
        $this->assertEquals($column, $criteria->column);
        $this->assertEquals($value, $criteria->value);
        $this->assertEquals($operator, $criteria->operator);
        $this->assertEquals($prefix, $criteria->prefix);
        $this->assertEquals($function, $criteria->function);
    }
    
    public function test___construct100() {
        $column = 'column';
        $value = '';
        $operator = '=';
        $prefix = '';
        $function = '';
        $criteria = new $this->myclass($column);
        $this->assertEquals($column, $criteria->column);
        $this->assertEquals($value, $criteria->value);
        $this->assertEquals($operator, $criteria->operator);
        $this->assertEquals($prefix, $criteria->prefix);
        $this->assertEquals($function, $criteria->function);
    }
    
    public function test_render()
	{
        $column = 'column';
        $value = '';
        $operator = '=';
        $prefix = '';
        $function = '';
        $criteria = new $this->myclass($column);
        $clause = $criteria->render();
        $this->assertEquals('', $clause);
    }
    
    public function test_render100()
	{
        $column = 'column';
        $value = 'value';
        $operator = 'operator';
        $prefix = '';
        $function = '';
        $criteria = new $this->myclass($column, $value, $operator, $prefix, $function);
        $clause = $criteria->render();
        $this->assertEquals("$column $operator '$value'", $clause);
    }
    
    public function test_render200()
	{
        $column = 'column';
        $value = 'value';
        $operator = 'is null';
        $prefix = 'prefix';
        $function = '';
        $criteria = new $this->myclass($column, $value, $operator, $prefix, $function);
        $clause = $criteria->render();
        $this->assertEquals("$prefix.$column $operator", $clause);
    }
    
    public function test_render300()
	{
        $column = 'column';
        $value = 'value';
        $operator = 'is NOT null';
        $prefix = 'prefix';
        $function = '';
        $criteria = new $this->myclass($column, $value, $operator, $prefix, $function);
        $clause = $criteria->render();
        $this->assertEquals("$prefix.$column $operator", $clause);
    }
    
    public function test_render400()
	{
        $column = 'column';
        $value = '(0,10)';
        $operator = 'in';
        $prefix = 'prefix';
        $function = '';
        $criteria = new $this->myclass($column, $value, $operator, $prefix, $function);
        $clause = $criteria->render();
        $this->assertEquals("$prefix.$column $operator $value", $clause);
    }
    
    public function test_render500()
	{
        $column = 'column';
        $value = '(0,10)';
        $operator = 'NOT in';
        $prefix = 'prefix';
        $function = '';
        $criteria = new $this->myclass($column, $value, $operator, $prefix, $function);
        $clause = $criteria->render();
        $this->assertEquals("$prefix.$column $operator $value", $clause);
    }
    
}
