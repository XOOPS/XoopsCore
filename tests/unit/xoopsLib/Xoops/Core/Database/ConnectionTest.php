<?php
require_once(__DIR__.'/../../../../init_new.php');


use Doctrine\DBAL\Driver;
use Doctrine\DBAL\Configuration;
use Doctrine\Common\EventManager;

class ConnectionTest extends \PHPUnit\Framework\TestCase
{
    protected $myclass = '\Xoops\Core\Database\Connection';

    /** @var  Xoops\Core\Database\Connection */
    protected $object;

    public function setUp()
    {
        $params = array();
        $config = new Configuration();
        $eventManager = new EventManager();
        $driver = new Doctrine\DBAL\Driver\PDOMySql\Driver();

        $this->object = new $this->myclass($params, $driver, $config, $eventManager);
    }

    public function test___construct()
    {
        $params = array();
        $config = new Configuration();
        $eventManager = new EventManager();
        $driver = new Doctrine\DBAL\Driver\PDOMySql\Driver();

        $instance = new $this->myclass($params, $driver, $config, $eventManager);
        $this->assertInstanceOf('\Xoops\Core\Database\Connection', $instance);
    }

    public function test_setSafe()
    {
        $class = $this->myclass;
        $this->object->setSafe(true);
        $x = $this->object->getSafe();
        $this->assertTrue($x);

        $this->object->setSafe(false);
        $x = $this->object->getSafe();
        $this->assertFalse($x);
    }

    public function test_setForce()
    {
        $this->object->setForce(true);
        $x = $this->object->getForce();
        $this->assertTrue($x);

        $this->object->setForce(false);
        $x = $this->object->getForce();
        $this->assertFalse($x);
    }

    public function test_prefix()
    {
        $x = $this->object->prefix('');
        $db_prefix = \XoopsBaseConfig::get('db-prefix');
        $this->assertSame($db_prefix, $x);

        $table = 'toto';
        $x = $this->object->prefix($table);
        $this->assertSame($db_prefix.'_'.$table, $x);
    }

    public function test_insertPrefix()
    {
        $this->markTestIncomplete('No test yet');
        //  insertPrefix($tableName, array $data, array $types = array())
    }

    public function test_updatePrefix()
    {
        $this->markTestIncomplete('No test yet');
        //  updatePrefix($tableName, array $data, array $identifier, array $types = array())
    }

    public function test_deletePrefix()
    {
        $this->markTestIncomplete('No test yet');
        //  deletePrefix($tableName, array $identifier)
    }

    public function test_executeQuery()
    {
        $this->markTestIncomplete('No test yet');
        /*
        executeQuery(
        $query,
        array $params = array(),
        $types = array(),
        \Doctrine\DBAL\Cache\QueryCacheProfile $qcp = null
        */
    }

    public function test_executeUpdate()
    {
        $this->markTestIncomplete('No test yet');
        //  executeUpdate($query, array $params = array(), array $types = array())
    }

    public function test_beginTransaction()
    {
        $this->markTestIncomplete('No test yet');
        //  beginTransaction()
    }

    public function test_commit()
    {
        $this->markTestIncomplete('No test yet');
        //  commit()
    }

    public function test_rollBack()
    {
        $this->markTestIncomplete('No test yet');
        //  rollBack()
    }

    public function test_query()
    {
        $this->markTestIncomplete('No test yet');
        //  query()
    }

    public function test_queryFromFile()
    {
        $this->markTestIncomplete('No test yet');
        //  queryFromFile($file)
    }

    public function test_quoteSlash()
    {
        $this->markTestIncomplete('No test yet');
        //  quoteSlash($input)
    }

    public function test_createXoopsQueryBuilder()
    {
        $this->markTestIncomplete('No test yet');
        //  createXoopsQueryBuilder()
    }
}
