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
 * The EmailAddressList data object is a traversable list of EmailAddress objects
 *
 * This is an Immutable data object. That means any changes to the data (state)
 * return a new object, while the internal state of the original object is preserved.
 *
 * All data is validated for type and value, and an exception is generated when
 * data on any operation for a property when it is not valid.
 *
 * The EmailAddress data object is used for mailer services
 *
 * @category  Xoops\Core\Service\Data
 * @package   Xoops\Core
 * @author    Richard Griffith <richard@geekwright.com>
 * @copyright 2018 XOOPS Project (https://xoops.org)
 * @license   GNU GPL 2 or later (http://www.gnu.org/licenses/gpl-2.0.html)
 * @link      https://xoops.org
 */
class EmailAddressList
{
    /** @var EmailAddress[] $addresses an array of EmailAddress objects */
    protected $addresses;

    /* assert messages */
    protected const MESSAGE_ADDRESS = 'EmailAddress is invalid';
    protected const MESSAGE_LIST = 'EmailAddress list is empty';

    /**
     * EmailAddress constructor.
     *
     * If an argument is null, the corresponding value will not be set. Values can be set
     * later with the with*() methods, but each will result in a new object.
     *
     * @param null|EmailAddress[] $addresses an array of EmailAddress objects
     *
     * @throws \InvalidArgumentException
     */
    public function __construct(?array $addresses = null)
    {
        if (null !== $addresses) {
            Assert::allIsInstanceOf($addresses, EmailAddress::class, static::MESSAGE_ADDRESS);

            try {
                /** @var EmailAddress $address */
                foreach ($addresses as $address) {
                    $address->getEmail();
                }
            } catch (\LogicException $e) {
                throw new \InvalidArgumentException($e->getMessage(), $e->getCode(), $e);
            }
            $this->addresses = $addresses;
        }
    }

    /**
     * withAddedAddresses - return a new object with the supplied EmailAddress array added
     *
     * @param EmailAddress[] $addresses  an array of EmailAddress objects
     *
     * @throws \InvalidArgumentException
     * @return EmailAddressList
     */
    public function withAddedAddresses(array $addresses): self
    {
        Assert::allIsInstanceOf($addresses, EmailAddress::class, static::MESSAGE_ADDRESS);

        try {
            /** @var EmailAddress $address */
            foreach ($addresses as $address) {
                $address->getEmail();
            }
            $existingAddresses = (null === $this->addresses) ? [] : $this->getAddresses();
        } catch (\LogicException $e) {
            throw new \InvalidArgumentException($e->getMessage(), $e->getCode(), $e);
        }
        $new = clone $this;
        $new->addresses = array_merge($existingAddresses, $addresses);

        return $new;
    }

    /**
     * getAddresses
     *
     * @throws \LogicException (property was not properly set before used)
     * @return EmailAddress[] an array of EmailAddress objects
     */
    public function getAddresses(): array
    {
        try {
            Assert::notNull($this->addresses, static::MESSAGE_LIST);
            Assert::allIsInstanceOf($this->addresses, EmailAddress::class, static::MESSAGE_ADDRESS);
            /** @var EmailAddress $address */
            foreach ($this->addresses as $address) {
                $address->getEmail();
            }
        } catch (\InvalidArgumentException $e) {
            throw new \LogicException($e->getMessage(), $e->getCode(), $e);
        }

        return $this->addresses;
    }

    /**
     * getEachAddress - return each EmailAddress in the list
     *
     * @throws \LogicException (property was not properly set before used)
     * @return \Generator|EmailAddress[]
     */
    public function getEachAddress(): \Generator
    {
        $this->getAddresses();
        foreach ($this->addresses as $address) {
            yield $address;
        }
    }
}
