<?php
/*
 You may not change or alter any portion of this comment or credits
 of supporting developers from this source code or any supporting source code
 which is considered copyrighted (c) material of the original comment or credit authors.

 This program is distributed in the hope that it will be useful,
 but WITHOUT ANY WARRANTY; without even the implied warranty of
 MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
*/

namespace Xoops\Core\Service;

/**
 * Xoops services manager contract boilerplate
 *
 * All service providers should extend this class, and implement the relevant
 * contract interface
 *
 * @category  Xoops\Core\Service\AbstractContract
 * @package   Xoops\Core
 * @author    Richard Griffith <richard@geekwright.com>
 * @copyright 2013-2014 The XOOPS Project https://github.com/XOOPS/XoopsCore
 * @license   GNU GPL 2 or later (http://www.gnu.org/licenses/gpl-2.0.html)
 * @version   Release: 1.0
 * @link      http://xoops.org
 * @since     2.6.0
 */
abstract class AbstractContract
{
    /** @var integer $priority - lowest value is highest priority */
    protected $priority = Manager::PRIORITY_MEDIUM;

    /**
     * setPriority - set the priority for this contract provider
     *
     * @param integer $priority - priority of this contract provider
     *
     * @return void
     */
    public function setPriority($priority)
    {
        $this->priority = (int) $priority;
    }

    /**
     * getPriority - get the priority for this contract provider
     *
     * @return integer - priority of this contract provider
     */
    public function getPriority()
    {
        return $this->priority;
    }

    /**
     * getMode - get the MODE for the contract. The MODE is set in the contract Interface, and
     * permissible values defined in Manager
     *
     * @return integer - a MODE constant indicating how multiple services are handled
     */
    public function getMode()
    {
        $class = get_called_class();
        return $class::MODE;
    }

    /**
     * getName - get a short name for this service provider. This should be unique within the
     * scope of the named service, so using module dirname is suggested.
     *
     * @return string - a unique name for the service provider
     */
    abstract public function getName();

    /**
     * getDescription - get human readable description of the service provider
     *
     * @return string
     */
    abstract public function getDescription();
}
