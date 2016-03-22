<?php
/*
 * Smarty plugin to enable shortcodes in templates
 */
function smarty_outputfilter_shortcodes($output, Smarty_Internal_Template $template)
{
    $shortcodes = \Xoops\Core\Text\Sanitizer::getInstance()->getShortCodes();
    $shortcodes->addShortcode(
        'nosc42',
        function ($attributes, $content, $tagName) {
            return base64_decode($content);
        }
    );

    // breaks out form elements
    $scPattern = '/(<textarea[\S\s]*\/textarea>|<input[\S\s]*>|<select[\S\s]*\/select>|<script[\S\s]*\/script>|<style[\S\s]*\/style>)/U';

    $text = preg_replace_callback(
        $scPattern,
        function ($innerMatches) {
            return '[nosc42]' . base64_encode($innerMatches[1]) . '[/nosc42]';
        },
        $output
    );
    if ($text===null) {
        trigger_error('preg_last_error=' . preg_last_error(), E_USER_WARNING);
        return $output;
    }

    $text = $shortcodes->process($text);

    return $text;

    /*
    $noscPattern = '/({nosc42filter}([\S\s]*){\/nosc42filter})/U';

    $text = preg_replace_callback(
        $noscPattern,
        function ($innerMatches) {
            return base64_decode($innerMatches[2]);
        },
        $text
    );
    if ($text===null) {
        trigger_error('preg_last_error=' . preg_last_error(), E_USER_WARNING);
        return $output;
    }

    return $text;
*/
}
