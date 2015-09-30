<?php
require_once(dirname(__FILE__).'/../../../init_new.php');

use \Xoops\Core\MimeTypes;

/**
* PHPUnit special settings :
* @backupGlobals disabled
* @backupStaticAttributes disabled
*/
class MimeTypesTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var MimeTypes
     */
    protected $object;

    /**
     * Sets up the fixture, for example, opens a network connection.
     * This method is called before a test is executed.
     */
    protected function setUp()
    {
        // $this->object = new \Xoops\Core\MimeTypes;
    }

    /**
     * Tears down the fixture, for example, closes a network connection.
     * This method is called after a test is executed.
     */
    protected function tearDown()
    {
    }

    /**
     * @covers Xoops\Core\MimeTypes::findExtensions
     */
    public function testFindExtensions()
    {
        $this->assertTrue(in_array('txt', MimeTypes::findExtensions('text/plain')), 'match .txt extension');
        $matches = MimeTypes::findExtensions('image/jpeg');
        $this->assertTrue(in_array('jpg', $matches), 'match .jpg extension');
        $this->assertTrue(in_array('jpeg', $matches), 'match .jpeg extension');
		$x = MimeTypes::findExtensions('failme-no-such-type/no-such-subtype');
        $this->assertTrue(empty($x), 'match garbage mimetype');
    }

    /**
     * @covers Xoops\Core\MimeTypes::findType
     */
    public function testFindType()
    {
        $this->assertEquals('text/plain', MimeTypes::findType('txt'), 'get mimetype for .txt extension');
        $this->assertEquals('image/jpeg', MimeTypes::findType('jpg'), 'get mimetype for .jpg extension');
        $this->assertEquals('image/jpeg', MimeTypes::findType('jpeg'), 'get mimetype for .jpeg extension');
        $this->assertNull(MimeTypes::findType('failme-no-such-extension'), 'get mimetype for garbage extension');
    }
}
