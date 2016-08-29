<?php
require_once(dirname(__FILE__).'/../../../../init_new.php');

/**
* PHPUnit special settings :
* @backupGlobals disabled
* @backupStaticAttributes disabled
*/
class QueryBuilderTest extends \PHPUnit_Framework_TestCase
{
    protected $myclass = '\Xoops\Core\Database\QueryBuilder';
    protected $conn = null;

    public function setUp()
    {
        if (empty($this->conn)) {
            $this->conn = Xoops::getInstance()->db();
        }
    }

    public function test___construct()
    {
        $instance = new $this->myclass($this->conn);
        $this->assertInstanceOf('\Xoops\Core\Database\QueryBuilder', $instance);
        $this->assertInstanceOf('\Doctrine\DBAL\Query\QueryBuilder', $instance);
    }

    public function test_deletePrefix()
    {
        // deletePrefix
    }

    public function test_updatePrefix()
    {
        // updatePrefix
    }

    public function test_fromPrefix()
    {
        // fromPrefix
    }

    public function test_joinPrefix()
    {
        //  joinPrefix
    }

    public function test_innerJoinPrefix()
    {
        //  innerJoinPrefix
    }

    public function test_leftJoinPrefix()
    {
        //  leftJoinPrefix
    }

    public function test_rightJoinPrefix()
    {
        // rightJoinPrefix
    }

}
