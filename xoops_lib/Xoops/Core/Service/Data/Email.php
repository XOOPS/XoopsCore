<?php
/**
 * You may not change or alter any portion of this comment or credits of supporting
 * developers from this source code or any supporting source code which is considered
 * copyrighted (c) material of the original  comment or credit authors.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
 *
 * @copyright 2018 XOOPS Project (https://xoops.org)
 * @license   GNU GPL 2 or later (http://www.gnu.org/licenses/gpl-2.0.html)
 * @link      https://xoops.org
 */

namespace Xoops\Core\Service\Data;

use Xmf\Assert;
use Xoops\Core\Service\Data\EmailAddress;
use Xoops\Core\Service\Data\EmailAddressList;
use Xoops\Core\Service\Data\EmailAttachment;
use Xoops\Core\Service\Data\EmailAttachmentSet;

/**
 * The Email data object is a full email message using EmailAddress addresses.
 * This differs from the Message object by allowing communication to occur with
 * non-users and/or with full email capabilities such as multiple recipients,
 * CC, BCC, Reply To and attachments.
 *
 * This is an Immutable data object. That means any changes to the data (state)
 * return a new object, while the internal state of the original object is preserved.
 *
 * All data is validated for type and value, and an exception is generated when
 * data on any operation for a property when it is not valid.
 *
 * The Email data object is used for message and mailer services
 *
 * @category  Xoops\Core\Service\Data
 * @package   Xoops\Core
 * @author    Richard Griffith <richard@geekwright.com>
 * @copyright 2018 XOOPS Project (https://xoops.org)
 * @license   GNU GPL 2 or later (http://www.gnu.org/licenses/gpl-2.0.html)
 * @link      https://xoops.org
 */
class Email
{
    /** @var string $subject the subject of the message, a required non-empty string */
    protected $subject;

    /** @var string $body the body of the message, a required non-empty string */
    protected $body;

    /** @var string $htmlBody an alternate representation of the message body, a non-empty string */
    protected $htmlBody;

    /** @var EmailAddress $fromAddress the email address that message is from */
    protected $fromAddress;

    /** @var EmailAddressList $toAddresses addresses that the message is to */
    protected $toAddresses;

    /** @var EmailAddressList $ccAddresses addresses the message should be CC'ed to */
    protected $ccAddresses;

    /** @var EmailAddressList $bccAddresses addresses the message should be BCC'ed to */
    protected $bccAddresses;

    /** @var EmailAddressList $replyToAddresses addresses that should receive replies to this message */
    protected $replyToAddresses;

    /** @var EmailAddress $readReceiptAddress the email address requesting a read receipt */
    protected $readReceiptAddress;

    /** @var EmailAttachmentSet attachments to be part of the email */
    protected $attachmentSet;

    /* assert messages */
    protected const MESSAGE_BODY    = 'Body must be specified';
    protected const MESSAGE_FROM    = 'From address must be specified';
    protected const MESSAGE_SUBJECT = 'Subject must be specified';
    protected const MESSAGE_BCC     = 'Invalid BCC address specified';
    protected const MESSAGE_CC      = 'Invalid CC address specified';
    protected const MESSAGE_REPLY   = 'Invalid Reply To address specified';
    protected const MESSAGE_RR      = 'Invalid Read Receipt address specified';
    protected const MESSAGE_TO      = 'A valid To address must be specified';

    protected const PROPERTY_ADDRESS_BCC   = 'bccAddresses';
    protected const PROPERTY_ADDRESS_CC    = 'ccAddresses';
    protected const PROPERTY_ADDRESS_REPLY = 'replyToAddresses';
    protected const PROPERTY_ADDRESS_TO    = 'toAddresses';

    protected const VALID_ADDRESS_PROPERTIES = [
        self::PROPERTY_ADDRESS_BCC,
        self::PROPERTY_ADDRESS_CC,
        self::PROPERTY_ADDRESS_REPLY,
        self::PROPERTY_ADDRESS_TO,
    ];

    /**
     * Email constructor.
     *
     * If an argument is null, the corresponding value will not be set. Values can be set
     * later with the with*() methods, but each will result in a new object.
     *
     * @param null|string       $subject     the subject of the message, a non-empty string
     * @param null|string       $body        the body of the message, a non-empty string
     * @param null|EmailAddress $fromAddress the user id sending the message, a positive integer
     * @param null|EmailAddress $toAddress   the user id to receive the message, a positive integer
     *
     * @throws \InvalidArgumentException
     */
    public function __construct(
        ?string $subject = null,
        ?string $body = null,
        ?EmailAddress $fromAddress = null,
        ?EmailAddress $toAddress = null
    ) {
        if (null!==$subject) {
            $subject = trim($subject);
            Assert::stringNotEmpty($subject, static::MESSAGE_SUBJECT);
            $this->subject = $subject;
        }
        if (null!==$body) {
            $body = trim($body);
            Assert::stringNotEmpty($body, static::MESSAGE_BODY);
            $this->body = $body;
        }
        try {
            if (null!==$fromAddress) {
                $fromAddress->getEmail();
                $this->fromAddress = $fromAddress;
            }
            if (null!==$toAddress) {
                $toAddress->getEmail();
                $this->toAddresses = new EmailAddressList([$toAddress]);
            }
        } catch (\LogicException $e) {
            throw new \InvalidArgumentException($e->getMessage(), $e->getCode(), $e);
        }
    }

    /**
     * Return a new object with a the specified body
     *
     * @param string $body message body
     *
     * @return Email
     *
     * @throws \InvalidArgumentException
     */
    public function withBody(string $body) : Email
    {
        $body = trim($body);
        Assert::stringNotEmpty($body, static::MESSAGE_BODY);
        $new = clone $this;
        $new->body = $body;
        return $new;
    }

    /**
     * Return a new object with a the specified HTML body
     *
     * The htmlBody is optional, while a body (plain text) is required, and must always be specified.
     *
     * @param string $body HTML message body
     *
     * @return Email
     *
     * @throws \InvalidArgumentException
     */
    public function withHtmlBody(string $body) : Email
    {
        $body = trim($body);
        Assert::stringNotEmpty($body, static::MESSAGE_BODY);
        $new = clone $this;
        $new->htmlBody = $body;
        return $new;
    }

    /**
     * Return a new object with a the specified fromAddress
     *
     * @param EmailAddress $fromAddress the sending/from email address
     *
     * @return Email a new object with specified change
     *
     * @throws \InvalidArgumentException (property was not properly set before used)
     */
    public function withFromAddress(EmailAddress $fromAddress) : Email
    {
        try {
            $fromAddress->getEmail();
        } catch (\LogicException $e) {
            throw new \InvalidArgumentException($e->getMessage(), $e->getCode(), $e);
        }

        $new = clone $this;
        $new->fromAddress = $fromAddress;
        return $new;
    }

    /**
     * Return a new object with a the specified subject
     *
     * @param string $subject message subject
     *
     * @return Email
     *
     * @throws \InvalidArgumentException
     */
    public function withSubject(string $subject) : Email
    {
        $subject = trim($subject);
        Assert::stringNotEmpty($subject, static::MESSAGE_SUBJECT);
        $new = clone $this;
        $new->subject = $subject;
        return $new;
    }

    /**
     * withAddresses - utility method to validate and assign a set of addresses
     *
     * @param string           $property  property to set, one of VALID_ADDRESS_PROPERTIES
     * @param EmailAddressList $addresses addresses to be assigned to property
     *
     * @return Email a new object with a the specified property set to the specified addresses
     *
     * @throws \InvalidArgumentException (a property was not set before used)
     */
    protected function withAddresses(string $property, EmailAddressList $addresses) : Email
    {
        Assert::oneOf($property, static::VALID_ADDRESS_PROPERTIES);
        try {
            $addresses->getAddresses();
        } catch (\LogicException $e) {
            throw new \InvalidArgumentException($e->getMessage(), $e->getCode(), $e);
        }

        $new = clone $this;
        $new->{$property} = $addresses;
        return $new;
    }

    /**
     * Return a new object with a the specified bccAddresses
     *
     * @param EmailAddressList $bccAddresses the addresses to be BCC'ed
     *
     * @return Email
     *
     * @throws \InvalidArgumentException (a property was not properly set before used)
     */
    public function withBccAddresses(EmailAddressList $bccAddresses) : Email
    {
        return $this->withAddresses(static::PROPERTY_ADDRESS_BCC, $bccAddresses);
    }

    /**
     * Return a new object with a the specified ccAddresses
     *
     * @param EmailAddressList $ccAddresses the addresses to be CC'ed
     *
     * @return Email
     *
     * @throws \InvalidArgumentException (a property was not properly set before used)
     */
    public function withCcAddresses(EmailAddressList $ccAddresses) : Email
    {
        return $this->withAddresses(static::PROPERTY_ADDRESS_CC, $ccAddresses);
    }

    /**
     * Return a new object with a the specified replyToAddresses
     *
     * @param EmailAddressList $replyToAddresses the addresses to receive replies
     *
     * @return Email
     *
     * @throws \InvalidArgumentException (a property was not properly set before used)
     */
    public function withReplyToAddresses(EmailAddressList $replyToAddresses) : Email
    {
        return $this->withAddresses(static::PROPERTY_ADDRESS_REPLY, $replyToAddresses);
    }

    /**
     * Return a new object with a the specified toAddresses
     *
     * @param EmailAddressList $toAddresses the addresses to receive the message
     *
     * @return Email
     *
     * @throws \InvalidArgumentException (a property was not properly set before used)
     */
    public function withToAddresses(EmailAddressList $toAddresses) : Email
    {
        return $this->withAddresses(static::PROPERTY_ADDRESS_TO, $toAddresses);
    }

    /**
     * Return a new object with a the specified fromAddress
     *
     * @param EmailAddress $readReceiptAddress requests a read receipt to this address
     *
     * @return Email a new object with specified change
     *
     * @throws \InvalidArgumentException (property was not properly set before used)
     */
    public function withReadReceiptAddress(EmailAddress $readReceiptAddress) : Email
    {
        try {
            $readReceiptAddress->getEmail();
        } catch (\LogicException $e) {
            throw new \InvalidArgumentException($e->getMessage(), $e->getCode(), $e);
        }

        $new = clone $this;
        $new->readReceiptAddress = $readReceiptAddress;
        return $new;
    }

    /**
     * withAttachments - return a new object with a the specified set of attachments
     *
     * @param EmailAttachmentSet $attachmentSet
     *
     * @return Email
     *
     * @throws \InvalidArgumentException
     */
    public function withAttachments(EmailAttachmentSet $attachmentSet) : Email
    {
        try {
            $attachmentSet->getAttachments();
        } catch (\LogicException $e) {
            throw new \InvalidArgumentException($e->getMessage(), $e->getCode(), $e);
        }

        $new = clone $this;
        $new->attachmentSet = $attachmentSet;
        return $new;
    }

    /**
     * getBody
     *
     * @return string the message body
     *
     * @throws \LogicException (property was not properly set before used)
     */
    public function getBody() : string
    {
        try {
            Assert::stringNotEmpty($this->body, static::MESSAGE_BODY);
        } catch (\InvalidArgumentException $e) {
            throw new \LogicException($e->getMessage(), $e->getCode(), $e);
        }
        return $this->body;
    }

    /**
     * getHtmlBody
     *
     * @return string the message body
     *
     * @throws \LogicException (property was not properly set before used)
     */
    public function getHtmlBody() : ?string
    {
        try {
            Assert::nullOrStringNotEmpty($this->htmlBody, static::MESSAGE_BODY);
        } catch (\InvalidArgumentException $e) {
            throw new \LogicException($e->getMessage(), $e->getCode(), $e);
        }
        return $this->htmlBody;
    }

    /**
     * getFromAddress
     *
     * @return EmailAddress the fromAddress
     *
     * @throws \LogicException (property was not properly set before used)
     */
    public function getFromAddress() : EmailAddress
    {
        try {
            Assert::notNull($this->fromAddress, static::MESSAGE_FROM);
            Assert::isInstanceOf($this->fromAddress, EmailAddress::class, static::MESSAGE_FROM);
            $this->fromAddress->getEmail();
        } catch (\InvalidArgumentException | \LogicException $e) {
            throw new \LogicException($e->getMessage(), $e->getCode(), $e);
        }
        return $this->fromAddress;
    }

    /**
     * getSubject
     *
     * @return string the message subject
     *
     * @throws \LogicException (property was not properly set before used)
     */
    public function getSubject() : string
    {
        try {
            Assert::stringNotEmpty($this->subject, static::MESSAGE_SUBJECT);
        } catch (\InvalidArgumentException $e) {
            throw new \LogicException($e->getMessage(), $e->getCode(), $e);
        }

        return $this->subject;
    }

    /**
     * getAddresses
     *
     * @param string $property addresses property to get, one of VALID_ADDRESS_PROPERTIES
     * @param string $message  message for any Assert exception
     *
     * @return EmailAddressList|null the specified addresses property or null if not set
     *
     * @throws \LogicException (property was not properly set before used)
     */
    protected function getAddresses(string $property, string $message) : ?EmailAddressList
    {
        try {
            Assert::oneOf($property, static::VALID_ADDRESS_PROPERTIES);
            if (null !== $this->{$property}) {
                Assert::allIsInstanceOf($this->{$property}->getAddresses(), EmailAddress::class, $message);
            }
        } catch (\InvalidArgumentException | \LogicException $e) {
            throw new \LogicException($e->getMessage(), $e->getCode(), $e);
        }
        return $this->{$property};
    }

    /**
     * getBccAddresses
     *
     * @return EmailAddressList|null the BCC address list or null if not set
     *
     * @throws \LogicException (property was not properly set before used)
     */
    public function getBccAddresses() : ?EmailAddressList
    {
        return $this->getAddresses(static::PROPERTY_ADDRESS_BCC, static::MESSAGE_BCC);
    }

    /**
     * getCcAddresses
     *
     * @return EmailAddressList|null the CC address list or null if not set
     *
     * @throws \LogicException (property was not properly set before used)
     */
    public function getCcAddresses() : ?EmailAddressList
    {
        return $this->getAddresses(static::PROPERTY_ADDRESS_CC, static::MESSAGE_CC);
    }

    /**
     * getReplyToAddresses
     *
     * @return EmailAddressList|null the ReplyTo address list or null if not set
     *
     * @throws \LogicException (property was not properly set before used)
     */
    public function getReplyToAddresses() : ?EmailAddressList
    {
        return $this->getAddresses(static::PROPERTY_ADDRESS_REPLY, static::MESSAGE_REPLY);
    }

    /**
     * getToAddresses
     *
     * @return EmailAddressList the To addresses
     *
     * @throws \LogicException (property was not properly set before used)
     */
    public function getToAddresses() : EmailAddressList
    {
        try {
            Assert::notEmpty($this->toAddresses, static::MESSAGE_TO);
        } catch (\InvalidArgumentException $e) {
            throw new \LogicException($e->getMessage(), $e->getCode(), $e);
        }
        return $this->getAddresses(static::PROPERTY_ADDRESS_TO, static::MESSAGE_TO);
    }

    /**
     * getReadReceiptAddress
     *
     * @return EmailAddress|null the readReceiptAddress or null if not set
     *
     * @throws \LogicException (property was not properly set before used)
     */
    public function getReadReceiptAddress() : ?EmailAddress
    {
        if (null !== $this->readReceiptAddress) {
            try {
                Assert::isInstanceOf($this->readReceiptAddress, EmailAddress::class, static::MESSAGE_RR);
                $this->readReceiptAddress->getEmail();
            } catch (\InvalidArgumentException | \LogicException $e) {
                throw new \LogicException($e->getMessage(), $e->getCode(), $e);
            }
        }
        return $this->readReceiptAddress;
    }

    /**
     * getAttachments
     *
     * @return EmailAttachmentSet|null the set of attachments or null if not set
     *
     * @throws \LogicException (property was not properly set before used)
     */
    public function getAttachments() : ?EmailAttachmentSet
    {
        if (null !== $this->attachmentSet) {
            try {
                $this->attachmentSet->getAttachments();
            } catch (\LogicException $e) {
                throw new \LogicException($e->getMessage(), $e->getCode(), $e);
            }
        }
        return $this->attachmentSet;
    }
}
