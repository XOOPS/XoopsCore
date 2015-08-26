<?php
require_once(dirname(__FILE__).'/../../init_new.php');

class XoopsDatabaseTestInstance extends XoopsDatabase
{
    function connect($selectdb = true) {}
    function genId($sequence) {}
    function fetchRow($result) {}
    function fetchArray($result) {}
    function fetchBoth($result) {}
    function fetchObject($result) {}
    function getInsertId() {}
    function getRowsNum($result) {}
    function getAffectedRows() {}
    function close() {}
    function freeRecordSet($result) {}
    function error() {}
    function errno() {}
    function quoteString($str) {}
    function quote($string) {}
    function escape($string) {}
    function queryF($sql, $limit = 0, $start = 0) {}
    function query($sql, $limit = 0, $start = 0) {}
    function queryFromFile($file) {}
    function getFieldName($result, $offset) {}
    function getFieldType($result, $offset) {}
    function getFieldsNum($result) {}
}

/**
* PHPUnit special settings :
* @backupGlobals disabled
* @backupStaticAttributes disabled
*/
class XoopsDatabaseTest extends \PHPUnit_Framework_TestCase
{
    protected $myclass = 'XoopsDatabaseTestInstance';

    public function test___construct()
    {
        $instance = new $this->myclass();
        $this->assertInstanceOf($this->myclass, $instance);
    }

    public function test___publicProperties()
    {
        $items = array('conn', 'prefix', 'allowWebChanges');
        foreach($items as $item) {
            $prop = new ReflectionProperty($this->myclass,$item);
            $this->assertTrue($prop->isPublic());
        }
    }

    public function test_setPrefix()
    {
        $instance = new $this->myclass();
        $this->assertInstanceOf($this->myclass, $instance);
        $prefix = 'prefix';
        $value = $instance->setPrefix($prefix);
        $this->assertSame($prefix, $instance->prefix);
    }

    public function test_prefix()
    {
        $instance = new $this->myclass();
        $this->assertInstanceOf($this->myclass, $instance);
        $prefix = 'prefix';
        $value = $instance->setPrefix($prefix);
        $x = $instance->prefix();
        $this->assertSame($prefix,$x);
        $table = 'table';
        $x = $instance->prefix($table);
        $tmp = $prefix.'_'.$table;
        $this->assertSame($tmp,$x);
    }

}
