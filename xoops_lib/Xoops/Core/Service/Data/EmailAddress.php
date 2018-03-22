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
 * The EmailAddress data object is used for message and mailer services
 *
 * @category  Xoops\Core\Service\Data
 * @package   Xoops\Core
 * @author    Richard Griffith <richard@geekwright.com>
 * @copyright 2018 XOOPS Project (https://xoops.org)
 * @license   GNU GPL 2 or later (http://www.gnu.org/licenses/gpl-2.0.html)
 * @link      https://xoops.org
 */
class EmailAddress
{
    /** @var string $email an email address, i.e. user@example.com */
    protected $email;

    /** @var string $displayName a display name associated with an email address, i.e. "John Doe" */
    protected $displayName;

    /* assert messages */
    protected const MESSAGE_ADDRESS = 'Email address is invalid';
    protected const MESSAGE_NAME    = 'Display name is invalid';

    /**
     * EmailAddress constructor.
     *
     * If an argument is null, the corresponding value will not be set. Values can be set
     * later with the with*() methods, but each will result in a new object.
     *
     * @param null|string $email       an email address, i.e. user@example.com
     * @param null|string $displayName an optional display name associated with the email
     *                                 address, i.e. "John Doe" or other non-empty string.
     *                                 Empty or all whitespace strings will be ignored.
     *
     * @throws \InvalidArgumentException
     */
    public function __construct(?string $email = null, ?string $displayName = null)
    {
        if (null!==$email) {
            $email = trim($email);
            Assert::true(
                false!==filter_var($email, FILTER_VALIDATE_EMAIL, FILTER_FLAG_EMAIL_UNICODE),
                static::MESSAGE_ADDRESS
            );
            $this->email = $email;
        }
        if (null!==$displayName) {
            $displayName = trim($displayName);
            $displayName = empty($displayName) ? null : $displayName;
            Assert::nullOrStringNotEmpty($displayName, static::MESSAGE_NAME);
            $this->displayName = $displayName;
        }
    }

    /**
     * getEmail
     *
     * @return string an email address
     *
     * @throws \LogicException (property was not properly set before used)
     */
    public function getEmail() : string
    {
        try {
            Assert::true(
                false!==filter_var($this->email, FILTER_VALIDATE_EMAIL, FILTER_FLAG_EMAIL_UNICODE),
                static::MESSAGE_ADDRESS
            );
        } catch (\InvalidArgumentException $e) {
            throw new \LogicException($e->getMessage(), $e->getCode(), $e);
        }
        return $this->email;
    }

    /**
     * getDisplayName
     *
     * @return string|null the displayName or null if not set
     *
     * @throws \LogicException (property was not properly set before used)
     */
    public function getDisplayName() : ?string
    {
        try {
            Assert::nullOrStringNotEmpty($this->displayName, static::MESSAGE_NAME);
        } catch (\InvalidArgumentException $e) {
            throw new \LogicException($e->getMessage(), $e->getCode(), $e);
        }
        return $this->displayName;
    }

    /**
     * Return a new object with a the specified email
     *
     * @param string $email an email address, i.e. user@example.com
     *
     * @return EmailAddress
     *
     * @throws \InvalidArgumentException
     */
    public function withEmail(string $email) : EmailAddress
    {
        return new static($email, $this->displayName);
    }

    /**
     * Return a new object with a the specified email
     *
     * @param string $displayName a display name associated with the email
     *                            address, i.e. "John Doe" or other non-empty string.
     *                            Empty or all whitespace strings will be ignored.
     *
     * @return EmailAddress
     *
     * @throws \InvalidArgumentException
     */
    public function withDisplayName(string $displayName) : EmailAddress
    {
        return new static($this->email, $displayName);
    }
}
