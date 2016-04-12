<?php
require_once(dirname(__FILE__).'/../../../../init_new.php');


use Doctrine\DBAL\Driver;
use Doctrine\DBAL\Configuration;
use Doctrine\Common\EventManager;

/**
* PHPUnit special settings :
* @backupGlobals disabled
* @backupStaticAttributes disabled
*/
class ConnectionTest extends \PHPUnit_Framework_TestCase
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

    public function test_getSafe()
    {
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

    public function test_getForce()
    {
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
        //  insertPrefix($tableName, array $data, array $types = array())
    }

    public function test_updatePrefix()
    {
        //  updatePrefix($tableName, array $data, array $identifier, array $types = array())
    }

    public function test_deletePrefix()
    {
        //  deletePrefix($tableName, array $identifier)
    }

    public function test_executeQuery()
    {
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
        //  executeUpdate($query, array $params = array(), array $types = array())
    }

    public function test_beginTransaction()
    {
        //  beginTransaction()
    }

    public function test_commit()
    {
        //  commit()
    }

    public function test_rollBack()
    {
        //  rollBack()
    }

    public function test_query()
    {
        //  query()
    }

    public function test_queryFromFile()
    {
        //  queryFromFile($file)
    }

    public function test_quoteSlash()
    {
        //  quoteSlash($input)
    }

    public function test_createXoopsQueryBuilder()
    {
        //  createXoopsQueryBuilder()
    }
}
