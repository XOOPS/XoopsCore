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
 * Xoops service manager response object
 *
 * An instance of this object is passed as the first argument to all contract provider methods.
 * The contract provider should return values and error data in this object. The object is then
 * the return value from the provider.
 *
 * @category  Xoops\Core\Service\Response
 * @package   Xoops\Core
 * @author    Richard Griffith <richard@geekwright.com>
 * @copyright 2014 The XOOPS Project https://github.com/XOOPS/XoopsCore
 * @license   GNU GPL 2 or later (http://www.gnu.org/licenses/gpl-2.0.html)
 * @version   Release: 1.0
 * @link      http://xoops.org
 * @since     2.6.0
 */
class Response
{
    /** @var mixed $value - return value from Provider */
    protected $value = null;

    /** @var boolean $success - success as determined by service manager or provider */
    protected $success = true;

    /** @var mixed $errorMessage - error description(s) as returned by service manager or provider */
    protected $errorMessage = null;

    /**
     * __construct
     *
     * @param mixed   $value        - value returned by provider
     * @param boolean $success      - true if service request was successful
     * @param mixed   $errorMessage - string or array of strings of any errors to be reported
     */
    public function __construct($value = null, $success = true, $errorMessage = null)
    {
        $this->value = $value;
        $this->success = $success;
        if ($errorMessage!==null) {
            $this->addErrorMessage($errorMessage);
        }
    }

    /**
     * getValue - get return value from provider
     *
     * @return mixed
     */
    public function getValue()
    {
        return $this->value;
    }

    /**
     * isSuccess - success of service request as determined by service manager or provider
     *
     * @return boolean
     */
    public function isSuccess()
    {
        return $this->success;
    }

    /**
     * getErrorMessage - get any messages set by service manager or provider
     *
     * @return mixed
     */
    public function getErrorMessage()
    {
        return $this->errorMessage;
    }

    /**
     * setValue - set value returned by request
     *
     * @param mixed $value value returned from provider
     *
     * @return Response object
     */
    public function setValue($value)
    {
        $this->value = $value;

        return $this;
    }

    /**
     * setSuccess - record success of request
     *
     * @param boolean $success - success of service request as determined by manager or provider
     *
     * @return Response object
     */
    public function setSuccess($success)
    {
        $this->success = $success;

        return $this;
    }

    /**
     * addErrorMessage - add a message
     *
     * @param mixed $errorMessage - message, or array of messages to be added
     *
     * @return Response object
     */
    public function addErrorMessage($errorMessage)
    {
        $ret = array();
        if (is_array($this->errorMessage)) {
            $ret = $this->errorMessage;
        } elseif (is_scalar($this->errorMessage)) {
            $ret[] = $this->errorMessage;
        }
        if (is_array($errorMessage)) {
            $ret = array_merge($ret, $errorMessage);
        } elseif (is_scalar($errorMessage)) {
            $ret[] = $errorMessage;
        }

        $this->errorMessage = $ret;

        return $this;
    }
}
