<?php

namespace Xoops\Core\Exception;

/**
 * NotSupportedException - represents an exception caused by accessing features that are not supported.
 *
 * @category  Xoops\Core\Exception
 * @package   Xoops
 * @author    trabis <lusopoemas@gmail.com>
 * @copyright 2000-2020 XOOPS Project (https://xoops.org)
 * @license   GNU GPL 2 or later (http://www.gnu.org/licenses/gpl-2.0.html)
 * @link      https://xoops.org
 */
class NotSupportedException extends \UnexpectedValueException
{
    /**
     * @return string the user-friendly name of this exception
     */
    public function getName()
    {
        return 'Not Supported';
    }
}
