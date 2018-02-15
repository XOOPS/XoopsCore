<?php
require_once(__DIR__.'/../../init_new.php');

class XoopsDatabaseTestInstance extends XoopsDatabase
{
    public function connect($selectdb = true)
    {
    }
    public function genId($sequence)
    {
    }
    public function fetchRow($result)
    {
    }
    public function fetchArray($result)
    {
    }
    public function fetchBoth($result)
    {
    }
    public function fetchObject($result)
    {
    }
    public function getInsertId()
    {
    }
    public function getRowsNum($result)
    {
    }
    public function getAffectedRows()
    {
    }
    public function close()
    {
    }
    public function freeRecordSet($result)
    {
    }
    public function error()
    {
    }
    public function errno()
    {
    }
    public function quoteString($str)
    {
    }
    public function quote($string)
    {
    }
    public function escape($string)
    {
    }
    public function queryF($sql, $limit = 0, $start = 0)
    {
    }
    public function query($sql, $limit = 0, $start = 0)
    {
    }
    public function queryFromFile($file)
    {
    }
    public function getFieldName($result, $offset)
    {
    }
    public function getFieldType($result, $offset)
    {
    }
    public function getFieldsNum($result)
    {
    }
}

class XoopsDatabaseTest extends \PHPUnit\Framework\TestCase
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
        foreach ($items as $item) {
            $prop = new ReflectionProperty($this->myclass, $item);
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
        $this->assertSame($prefix, $x);
        $table = 'table';
        $x = $instance->prefix($table);
        $tmp = $prefix.'_'.$table;
        $this->assertSame($tmp, $x);
    }
}
