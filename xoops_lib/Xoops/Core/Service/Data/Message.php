<?php
/*
 You may not change or alter any portion of this comment or credits
 of supporting developers from this source code or any supporting source code
 which is considered copyrighted (c) material of the original comment or credit authors.

 This program is distributed in the hope that it will be useful,
 but WITHOUT ANY WARRANTY; without even the implied warranty of
 MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
*/

namespace Xoops\Core\Service\Data;

use Xmf\Assert;

/**
 * The Message data object is a minimal message from one user to another user
 *
 * Message data object used for message and mailer services
 *
 * All service providers should extend this class, and implement the relevant
 * contract interface
 *
 * @category  Xoops\Core\Service\Data
 * @package   Xoops\Core
 * @author    Richard Griffith <richard@geekwright.com>
 * @copyright 2018 XOOPS Project (https://xoops.org)
 * @license   GNU GPL 2 or later (http://www.gnu.org/licenses/gpl-2.0.html)
 * @link      https://xoops.org
 */
class Message
{
    /** @var int $fromId */
    protected $fromId = 0;

    /** @var int $toId */
    protected $toId = 0;

    /** @var string $subject */
    protected $subject = '';

    /** @var string $body */
    protected $body = '';

    /** @var string[] $assertMessages */
    protected $assertMessages = [
        'to'      => 'To Id must be a valid userid',
        'from'    => 'From id must be a valid userid',
        'subject' => 'Subject must be specified',
        'body'    => 'Body must be specified',
    ];

    /**
     * Message constructor.
     *
     * @param null|int    $toId    userid message is to
     * @param null|int    $fromId  userid message is from
     * @param null|string $subject message subject
     * @param null|string $body    message body
     *
     * @throws \InvalidArgumentException
     */
    public function __construct(?int $toId = null, ?int $fromId = null, ?string $subject = null, ?string $body = null)
    {
        if (null!==$toId) {
            $this->setToId($toId);
        }
        if (null!==$fromId) {
            $this->setFromId($fromId);
        }
        if (null!==$subject) {
            $this->setSubject($subject);
        }
        if (null!==$body) {
            $this->setBody($body);
        }
    }

    /**
     * Set to id
     *
     * @param int $toId userid message is to
     *
     * @return $this
     *
     * @throws \InvalidArgumentException
     */
    public function setToId(int $toId) : Message
    {
        Assert::greaterThan($toId, 0, $this->assertMessages['to']);
        $this->toId = $toId;
        return $this;
    }

    /**
     * Set from id
     *
     * @param int $fromId userid message is from
     *
     * @return $this
     *
     * @throws \InvalidArgumentException
     */
    public function setFromId(int $fromId) : Message
    {
        Assert::greaterThan($fromId, 0, $this->assertMessages['from']);
        $this->fromId = $fromId;
        return $this;
    }

    /**
     * Set subject
     *
     * @param string $subject message subject
     *
     * @return $this
     *
     * @throws \InvalidArgumentException
     */
    public function setSubject(string $subject) : Message
    {
        $subject = trim($subject);
        Assert::stringNotEmpty($subject, $this->assertMessages['subject']);
        $this->subject = $subject;
        return $this;
    }

    /**
     * Set subject
     *
     * @param string $body message body
     *
     * @return $this
     *
     * @throws \InvalidArgumentException
     */
    public function setBody(string $body) : Message
    {
        $body = trim($body);
        Assert::stringNotEmpty($body, $this->assertMessages['body']);
        $this->body = $body;
        return $this;
    }

    /**
     * getToId
     *
     * @return int the toId
     *
     * @throws \LogicException (property was not properly set)
     */
    public function getToId() : int
    {
        try {
            Assert::greaterThan($this->toId, 0, $this->assertMessages['to']);
        } catch (\InvalidArgumentException $e) {
            throw new \LogicException($e->getMessage(), $e->getCode(), $e);
        }
        return $this->toId;
    }

    /**
     * getFromId
     *
     * @return int the fromId
     *
     * @throws \LogicException (property was not properly set)
     */
    public function getFromId() : int
    {
        try {
            Assert::greaterThan($this->fromId, 0, $this->assertMessages['from']);
        } catch (\InvalidArgumentException $e) {
            throw new \LogicException($e->getMessage(), $e->getCode(), $e);
        }
        return $this->fromId;
    }

    /**
     * getSubject
     *
     * @return string the message subject
     *
     * @throws \LogicException (property was not properly set)
     */
    public function getSubject() : string
    {
        try {
            Assert::stringNotEmpty($this->subject, $this->assertMessages['subject']);
        } catch (\InvalidArgumentException $e) {
            throw new \LogicException($e->getMessage(), $e->getCode(), $e);
        }

        return $this->subject;
    }

    /**
     * getBody
     *
     * @return string the message body
     *
     * @throws \LogicException (property was not properly set)
     */
    public function getBody() : string
    {
        try {
            Assert::stringNotEmpty($this->body, $this->assertMessages['body']);
        } catch (\InvalidArgumentException $e) {
            throw new \LogicException($e->getMessage(), $e->getCode(), $e);
        }
        return $this->body;
    }
}
