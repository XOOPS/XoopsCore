<?php
namespace Xoops\Test\Core\Service\Data;

use Xoops\Core\Service\Data\Message;

class MessageTest extends \PHPUnit\Framework\TestCase
{
    /**
     * @var Message
     */
    protected $object;

    /**
     * Sets up the fixture, for example, opens a network connection.
     * This method is called before a test is executed.
     */
    protected function setUp()
    {
        $this->object = new Message();
    }

    /**
     * Tears down the fixture, for example, closes a network connection.
     * This method is called after a test is executed.
     */
    protected function tearDown()
    {
    }

    public function testContract()
    {
        $this->assertInstanceOf('\Xoops\Core\Service\Data\Message', $this->object);
    }

    public function testNewMessageWithArguments()
    {
        $message = new Message(1, 2, 'subject', 'body');
        $this->assertInstanceOf('\Xoops\Core\Service\Data\Message', $message);
        $this->assertEquals(1, $message->getToId());
        $this->assertEquals(2, $message->getFromId());
        $this->assertEquals('subject', $message->getSubject());
        $this->assertEquals('body', $message->getBody());
    }

    public function testNewMessageWithFluent()
    {
        $message = new Message(1, 2, 'subject', 'body');
        $message->setToId(1)->setFromId(2)->setSubject('subject')->setBody('body');
        $this->assertEquals(1, $message->getToId());
        $this->assertEquals(2, $message->getFromId());
        $this->assertEquals('subject', $message->getSubject());
        $this->assertEquals('body', $message->getBody());
    }

    public function testNewMessageMissingArguments()
    {
        $message = new Message(null, 2);
        $this->assertEquals(2, $message->getFromId());
        $this->expectException('\LogicException');
        $message->getToId();
    }

    public function testNewMessageBadArguments()
    {
        $this->expectException('\InvalidArgumentException');
        $message = new Message(-1);
    }

    public function testSetToIdException()
    {
        try {
            $this->object->setToId('0');
        } catch (\InvalidArgumentException $e) {
            $this->assertContains('To', $e->getMessage());
        }
        $this->assertInstanceOf('\InvalidArgumentException', $e);
    }

    public function testSetFromIdException()
    {
        try {
            $this->object->setFromId(-88);
        } catch (\InvalidArgumentException $e) {
            $this->assertContains('From', $e->getMessage());
        }
        $this->assertInstanceOf('\InvalidArgumentException', $e);
    }

    public function testSetSubjectException()
    {
        try {
            $this->object->setSubject(' ');
        } catch (\InvalidArgumentException $e) {
            $this->assertContains('Subject', $e->getMessage());
        }
        $this->assertInstanceOf('\InvalidArgumentException', $e);
    }

    public function testSetBodyException()
    {
        try {
            $this->object->setBody("\n");
        } catch (\InvalidArgumentException $e) {
            $this->assertContains('Body', $e->getMessage());
        }
        $this->assertInstanceOf('\InvalidArgumentException', $e);
    }

    public function testGetToIdException()
    {
        try {
            $this->object->getToId();
        } catch (\LogicException $e) {
            $this->assertContains('To', $e->getMessage());
        }
        $this->assertInstanceOf('\LogicException', $e);
    }

    public function testGetFromIdException()
    {
        try {
            $this->object->getFromId();
        } catch (\LogicException $e) {
            $this->assertContains('From', $e->getMessage());
        }
        $this->assertInstanceOf('\LogicException', $e);
    }

    public function testGetSubjectException()
    {
        try {
            $this->object->getSubject();
        } catch (\LogicException $e) {
            $this->assertContains('Subject', $e->getMessage());
        }
        $this->assertInstanceOf('\LogicException', $e);
    }

    public function testGetBodyException()
    {
        try {
            $this->object->getBody();
        } catch (\LogicException $e) {
            $this->assertContains('Body', $e->getMessage());
        }
        $this->assertInstanceOf('\LogicException', $e);
    }
}
