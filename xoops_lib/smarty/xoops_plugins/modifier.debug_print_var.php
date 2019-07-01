<?php
/**
 * Smarty plugin
 * @package Smarty
 * @subpackage plugins
 * @param mixed $var
 * @param mixed $depth
 * @param mixed $length
 */

/**
 * Smarty debug_print_var modifier plugin
 *
 * Modified version of the default smarty plug-in that prevents endless looping when dealing with assigned
 * objects
 *
 *
 *
 * Type:     modifier<br>
 * Name:     debug_print_var<br>
 * Purpose:  formats variable contents for display in the console
 * @link http://smarty.php.net/manual/en/language.modifier.debug.print.var.php
 *          debug_print_var (Smarty online manual)
 * @param array|object
 * @param integer
 * @param integer
 * @return string
 */
function smarty_modifier_debug_print_var($var, $depth = 0, $length = 40)
{
    $_replace = ["\n" => '<i>&#92;n</i>', "\r" => '<i>&#92;r</i>', "\t" => '<i>&#92;t</i>'];
    if (is_array($var)) {
        $results = '<b>Array (' . count($var) . ')</b>';
        foreach ($var as $curr_key => $curr_val) {
            $return = smarty_modifier_debug_print_var($curr_val, $depth + 1, $length);
            $results .= '<br>' . str_repeat('&nbsp;', $depth * 2) . '<b>' . strtr($curr_key, $_replace) . "</b> =&gt; {$return}";
        }
    } elseif (is_object($var)) {
        $object_vars = get_object_vars($var);
        $results = '<b>' . get_class($var) . ' Object (' . count($object_vars) . ')</b>';
        foreach ($object_vars as $curr_key => $curr_val) {
            if (is_object($curr_val)) {
                $return = '[object ' . get_class($curr_val) . ']';
            } else {
                $return = smarty_modifier_debug_print_var($curr_val, $depth + 1, $length);
            }
            $results .= '<br>' . str_repeat('&nbsp;', $depth * 2) . "<b>{$curr_key}</b> =&gt; {$return}";
        }
    } elseif (is_resource($var)) {
        $results = '<i>' . (string)$var . '</i>';
    } elseif (empty($var) && '0' != $var) {
        $results = '<i>empty</i>';
    } else {
        if (mb_strlen($var) > $length) {
            $results = mb_substr($var, 0, $length - 3) . '...';
        } else {
            $results = $var;
        }
        $results = htmlspecialchars($results);
        $results = strtr($results, $_replace);
    }

    return $results;
}

/* vim: set expandtab: */
