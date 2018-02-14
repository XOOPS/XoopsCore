<?php
/**
 * XOOPS securityToken Smarty compiler plug-in
 *
 * @copyright   XOOPS Project (http://xoops.org)
 * @license     GNU GPL 2 or later (http://www.gnu.org/licenses/gpl-2.0.html)
 * @author      Richard Griffith <richard@geekwright.com>
 */

/**
 * Inserts a XOOPS security token
 *
 * Not sure if this is a good idea (sounds like application logic, not presentation,)
 * but there are several token generations done in {php} tags which don't work with
 * Smarty 3.1
 */

function smarty_function_securityToken($params, Smarty_Internal_Template $smarty)
{
    return \Xoops::getInstance()->security()->getTokenHTML();
}
