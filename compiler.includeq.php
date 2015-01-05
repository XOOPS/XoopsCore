<?php
/**
 * includeq Smarty compiler plug-in
 *
 * See the enclosed file LICENSE for licensing information.
 * If you did not receive this file, get it at http://www.fsf.org/copyleft/gpl.html
 *
 * @copyright   The XOOPS project http://www.xoops.org/
 * @license     GNU GPL 2 (http://www.gnu.org/licenses/old-licenses/gpl-2.0.html)
 * @author      Skalpa Keo <skalpa@xoops.org>
 * @package     xos_opal
 * @subpackage  xos_opal_Smarty
 * @since       2.0.14
 * @version     $Id$
 */

/**
 * Quick include template plug-in
 *
 * Like {@link smarty_compiler_foreachq() foreachq}, this plug-in has been written to provide
 * a faster version of an already existing Smarty function. <var>includeq</var> can be used
 * as a replacement for the Smarty
 * {@link http://smarty.php.net/manual/en/language.function.include.php include} function as long
 * as you are aware of the differences between them.
 *
 * Normally, when you include a template, Smarty does the following:
 * - Backup all your template variables in an array
 * - Include the template you specified
 * - Restore the template variables from the previously created backup array
 *
 * The advantage of this method is that it makes the main template variables <i>safe</i>: if your
 * main template uses a variable called <var>$stuff</var> and the included template modifies it
 * value, the main template will recover the original value automatically.
 *
 * While this can be useful in some cases (for example, when you include templates you have absolutely
 * no control over), some may consider this a limitation and it has the disadvantage of slowing down
 * the inclusion mechanism a lot.
 *
 * <var>includeq</var> fixes that: the code it generates doesn't contain the variables backup/recovery
 * mechanism and thus makes templates inclusion faster. Note that however, this new behavior may
 * create problems in some cases (but you can prevent them most of the times, for example by always
 * using a <var>tmp_</var> prefix for the variables you create in included templates looping sections).
 */
function smarty_compiler_includeq($tag_args, &$comp)
{
    $attrs = $comp->_parse_attrs($tag_args);
    $arg_list = array();

    if (empty($attrs['file'])) {
        $comp->_syntax_error("missing 'file' attribute in includeq tag", E_USER_ERROR, __FILE__, __LINE__);
    }

    foreach ($attrs as $arg_name => $arg_value) {
        if ($arg_name == 'file') {
            $include_file = $arg_value;
            continue;
        } else if ($arg_name == 'assign') {
            $assign_var = $arg_value;
            continue;
        }
        if (is_bool($arg_value)) {
            $arg_value = $arg_value ? 'true' : 'false';
        }
        $arg_list[] = "'$arg_name' => $arg_value";
    }

    $output = '';

    if (isset($assign_var)) {
        $output .= "ob_start();\n";
    }

    //$output .= "\$_smarty_tpl_vars = \$this->_tpl_vars;\n";
    $_params = "array('smarty_include_tpl_file' => " . $include_file . ", 'smarty_include_vars' => array(" . implode(',', (array)$arg_list) . "))";
    $output .= "\$this->_smarty_include($_params);\n";
    //"\$this->_tpl_vars = \$_smarty_tpl_vars;\n" .
    //"unset(\$_smarty_tpl_vars);\n";

    if (isset($assign_var)) {
        $output .= "\$this->assign(" . $assign_var . ", ob_get_contents()); ob_end_clean();\n";
    }
    //$output .= '';
    return $output;
}

?>
