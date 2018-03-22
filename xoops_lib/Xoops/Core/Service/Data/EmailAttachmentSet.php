<?php
/*
 You may not change or alter any portion of this comment or credits of supporting
 developers from this source code or any supporting source code which is considered
 copyrighted (c) material of the original  comment or credit authors.

 This program is distributed in the hope that it will be useful,
 but WITHOUT ANY WARRANTY; without even the implied warranty of
 MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
*/

namespace Xoops\Core\Service\Data;

use Xmf\Assert;
use Xoops\Core\Service\Data\EmailAttachment;

/**
 * The EmailAttachmentSet data object is a traversable list of EmailAttachment objects
 *
 * This is an Immutable data object. That means any changes to the data (state)
 * return a new object, while the internal state of the original object is preserved.
 *
 * All data is validated for type and value, and an exception is generated when
 * data on any operation for a property when it is not valid.
 *
 * The EmailAttachmentSet data object is used for mailer services
 *
 * @category  Xoops\Core\Service\Data
 * @package   Xoops\Core
 * @author    Richard Griffith <richard@geekwright.com>
 * @copyright 2018 XOOPS Project (https://xoops.org)
 * @license   GNU GPL 2 or later (http://www.gnu.org/licenses/gpl-2.0.html)
 * @link      https://xoops.org
 */
class EmailAttachmentSet
{
    /** @var EmailAttachment[] $attachments an array of EmailAttachment objects */
    protected $attachments;

    /* assert messages */
    protected const MESSAGE_ATTACHMENT = 'EmailAttachment is invalid';
    protected const MESSAGE_LIST       = 'EmailAttachment list is empty';

    /**
     *  constructor.
     *
     * If an argument is null, the corresponding value will not be set. Values can be set
     * later with the with*() methods, but each will result in a new object.
     *
     * @param null|EmailAttachment[] $addresses an array of EmailAddress objects
     *
     * @throws \InvalidArgumentException
     * @throws \LogicException
     */
    public function __construct(?array $attachments = null)
    {
        if (null!==$attachments) {
            Assert::allIsInstanceOf($attachments, EmailAttachment::class, static::MESSAGE_ATTACHMENT);
            try {
                /** @var EmailAttachment $attachment */
                foreach ($attachments as $attachment) {
                    $attachment->getFilename();
                    $attachment->getStringBody();
                }
            } catch (\LogicException $e) {
                throw new \InvalidArgumentException($e->getMessage(), $e->getCode(), $e);
            }
            $this->attachments = $attachments;
        }
    }

    /**
     * withAddedAttachments - return a new object with the supplied EmailAddress array added
     *
     * @param EmailAttachment[] $attachments an array of EmailAttachment objects
     *
     * @return EmailAttachmentSet
     *
     * @throws \InvalidArgumentException
     */
    public function withAddedAttachments(array $attachments) : EmailAttachmentSet
    {
        Assert::allIsInstanceOf($attachments, EmailAttachment::class, static::MESSAGE_ATTACHMENT);
        try {
            /** @var EmailAttachment $attachment */
            foreach ($attachments as $attachment) {
                $attachment->getFilename();
                $attachment->getStringBody();
            }
            $existingAttachments = (null === $this->attachments) ? [] : $this->getAttachments();
        } catch (\LogicException $e) {
            throw new \InvalidArgumentException($e->getMessage(), $e->getCode(), $e);
        }
        $new = clone $this;
        $new->attachments = array_merge($existingAttachments, $attachments);
        return $new;
    }

    /**
     * getAttachments
     *
     * @return EmailAttachment[] an array of EmailAttachment objects
     *
     * @throws \LogicException (property was not properly set before used)
     */
    public function getAttachments() : array
    {
        try {
            Assert::notNull($this->attachments, static::MESSAGE_LIST);
            Assert::allIsInstanceOf($this->attachments, EmailAttachment::class, static::MESSAGE_ATTACHMENT);
            /** @var EmailAttachment $attachment */
            foreach ($this->attachments as $attachment) {
                $attachment->getFilename();
                $attachment->getStringBody();
            }
        } catch (\InvalidArgumentException $e) {
            throw new \LogicException($e->getMessage(), $e->getCode(), $e);
        }
        return $this->attachments;
    }

    /**
     * getEachAttachment - return each EmailAttachment in the list
     *
     * @return \Generator|EmailAttachment[]
     *
     * @throws \LogicException (property was not properly set before used)
     */
    public function getEachAttachment() : \Generator
    {
        $this->getAttachments();
        foreach ($this->attachments as $attachment) {
            yield $attachment;
        }
    }
}
