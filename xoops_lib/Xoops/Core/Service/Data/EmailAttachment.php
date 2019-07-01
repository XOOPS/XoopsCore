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

/**
 * The EmailAddress data object is a email address with optional display name
 *
 * This is an Immutable data object. That means any changes to the data (state)
 * return a new object, while the internal state of the original object is preserved.
 *
 * All data is validated for type and value, and an exception is generated when
 * data on any operation for a property when it is not valid.
 *
 * The EmailAttachment data object is used for mailer services
 *
 * @category  Xoops\Core\Service\Data
 * @package   Xoops\Core
 * @author    Richard Griffith <richard@geekwright.com>
 * @copyright 2018 XOOPS Project (https://xoops.org)
 * @license   GNU GPL 2 or later (http://www.gnu.org/licenses/gpl-2.0.html)
 * @link      https://xoops.org
 */
class EmailAttachment
{
    /** @var string $filename fully qualified file name of file to be attached */
    protected $filename;

    /** @var string $mimeType mime-type of attached content */
    protected $mimeType;

    /** @var string $stringBody content to be attached, if not from file */
    protected $stringBody;

    /** @var string $name name or content id of this attachment */
    protected $name;

    /** @var bool $inline treat attachment as inline if true, as download if false (default) */
    protected $inline = false;

    /* assert messages */
    protected const MESSAGE_FILE = 'File is invalid';
    protected const MESSAGE_MIME = 'Mime Type is invalid';
    protected const MESSAGE_NAME = 'Name is invalid';
    protected const MESSAGE_BODY = 'Body string is invalid';
    protected const MESSAGE_INLINE = 'Inline flag is invalid';

    protected const MIME_REGEX = '/^[a-z0-9\-+.]+\/[a-z0-9\-+.]+$/';

    /**
     * EmailAttachment constructor.
     *
     * If an argument is null, the corresponding value will not be set. Values can be set
     * later with the with*() methods, but each will result in a new object.
     *
     * @param null|string $filename fully qualified filename of file to attach
     * @param null|string $mimeType mime-type of file
     *
     * @throws \InvalidArgumentException
     */
    public function __construct(?string $filename = null, ?string $mimeType = null)
    {
        if (null !== $filename) {
            Assert::fileExists($filename, static::MESSAGE_FILE);
            $this->filename = $filename;
        }
        if (null !== $mimeType) {
            Assert::regex($mimeType, static::MIME_REGEX, static::MESSAGE_MIME);
            $this->mimeType = $mimeType;
        }
    }

    /**
     * withFilename
     *
     * @param string $filename fully qualified filename
     *
     * @throws \InvalidArgumentException
     * @return EmailAttachment
     */
    public function withFilename(string $filename): self
    {
        Assert::fileExists($filename, static::MESSAGE_FILE);
        $new = clone $this;
        $new->filename = $filename;

        return $new;
    }

    /**
     * withMimeType
     *
     * @param string $mimeType mime type of the filename contents or stringBody
     *
     * @throws \InvalidArgumentException
     * @return EmailAttachment
     */
    public function withMimeType(string $mimeType): self
    {
        Assert::regex($mimeType, static::MIME_REGEX, static::MESSAGE_MIME);
        $new = clone $this;
        $new->mimeType = $mimeType;

        return $new;
    }

    /**
     * withName
     *
     * @param string $name name or content id for attachment
     *
     * @throws \InvalidArgumentException
     * @return EmailAttachment
     */
    public function withName(string $name): self
    {
        Assert::stringNotEmpty($name, static::MESSAGE_NAME);
        $new = clone $this;
        $new->name = $name;

        return $new;
    }

    /**
     * withStringBody
     *
     * @param string $stringBody alternate body used instead of file contents
     *
     * @throws \InvalidArgumentException
     * @return EmailAttachment
     */
    public function withStringBody(string $stringBody): self
    {
        Assert::stringNotEmpty($stringBody, static::MESSAGE_BODY);
        $new = clone $this;
        $new->stringBody = $stringBody;

        return $new;
    }

    /**
     * withInlineAttribute
     *
     * @param bool $inline true to treat attachment as inline, false for download
     *
     * @throws \InvalidArgumentException
     * @return EmailAttachment
     */
    public function withInlineAttribute(bool $inline = true): self
    {
        Assert::boolean($inline, static::MESSAGE_INLINE);
        $new = clone $this;
        $new->inline = $inline;

        return $new;
    }

    /**
     * getFilename
     *
     * @throws \LogicException (property was not properly set before used)
     * @return string an file name
     */
    public function getFilename(): ?string
    {
        if (null === $this->stringBody) {
            try {
                Assert::notNull($this->filename, static::MESSAGE_FILE);
                Assert::fileExists($this->filename, static::MESSAGE_FILE);
            } catch (\InvalidArgumentException $e) {
                throw new \LogicException($e->getMessage(), $e->getCode(), $e);
            }
        }

        return $this->filename;
    }

    /**
     * getMimeType
     *
     * @throws \LogicException (property was not properly set before used)
     * @return string|null mime type of filename contents or stringBody
     */
    public function getMimeType(): ?string
    {
        try {
            Assert::nullOrRegex($this->mimeType, static::MIME_REGEX, static::MESSAGE_MIME);
        } catch (\InvalidArgumentException $e) {
            throw new \LogicException($e->getMessage(), $e->getCode(), $e);
        }

        return $this->mimeType;
    }

    /**
     * getName
     *
     * @throws \LogicException (property was not properly set before used)
     * @return string|null name or content id for attachment
     */
    public function getName(): ?string
    {
        try {
            Assert::nullOrStringNotEmpty($this->name, static::MESSAGE_NAME);
        } catch (\InvalidArgumentException $e) {
            throw new \LogicException($e->getMessage(), $e->getCode(), $e);
        }

        return $this->name;
    }

    /**
     * getStringBody
     *
     * @throws \LogicException (property was not properly set before used)
     * @return string|null string body attachment contents
     */
    public function getStringBody(): ?string
    {
        if (null === $this->filename) {
            try {
                Assert::stringNotEmpty($this->stringBody, static::MESSAGE_BODY);
            } catch (\InvalidArgumentException $e) {
                throw new \LogicException($e->getMessage(), $e->getCode(), $e);
            }
        }

        return $this->stringBody;
    }

    /**
     * getInlineAttribute
     *
     * @return bool attachment inline attribute, true for inline, false for download
     */
    public function getInlineAttribute(): bool
    {
        return (bool) $this->inline;
    }
}
