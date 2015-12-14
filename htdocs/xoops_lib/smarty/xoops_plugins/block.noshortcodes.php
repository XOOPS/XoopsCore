<?php
/*
 * Smarty plugin
 * ------------------------------------------------------------
 * Type:       block
 * Name:       noshortcode
 * Purpose:    XOOPS smarty plugin to disable theme level ShortCode processing for a section of a template.
 *             ShortCodes are surrounded by square brackets ([]). The outputfilter.shortcodes.php
 *             plugin is called to process shortcodes anywhere in the entire output, however, there
 *             are times when it might be desired to disable that processing for a section of a
 *             template. This is accomplished by converting any brackets ([]) to visually equivalent
 *             HTML entities (&#91;&#93;) so they will be ignored by the outputfilter.
 * Author:     Richard Griffith <richard@geekwright.com>
 * Version:    1.0
 *
 * Parameters: none
 *
 * Example:
 * {noshortcodes}[skip]{/noshortcodes}
 *
 * Example output:
 * &#91;skip&#93;
 * ------------------------------------------------------------
 */

function smarty_block_noshortcodes($params, $content, $template, &$repeat)
{
    // only output on the closing tag
    if(!$repeat){
        if (isset($content)) {
            $ts = \Xoops\Core\Text\Sanitizer::getInstance();
            return $ts->escapeShortCodes($content);
        }
    }
}
