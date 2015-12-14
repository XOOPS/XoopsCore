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
            return $content;
        }
    );

    // break out the body content
    $bodyPattern = '/<body[^>]*>(.*?)<\/body>/is';

    // breaks out form elements
    $scPattern = '/((<textarea[\S\s]*\/textarea>)|(<input[\S\s]*>)|(<select[\S\s]*\/select>)|(<script[\S\s]*\/script>)|(<style[\S\s]*\/style>))/U';

    $text = preg_replace_callback(
        $bodyPattern,
        function ($matches) use ($scPattern, $shortcodes) {
            $element = preg_replace_callback(
                $scPattern,
                function ($innerMatches) {
                    return '[nosc42]' . $innerMatches[0] . '[/nosc42]';
                },
                $matches[1]
            );
            if ($element===null) {
                trigger_error('preg_last_error=' . preg_last_error(), E_USER_WARNING);
                return $matches[1];
            }
            return $element;
        },
        $output
    );

    if ($text===null) {
        trigger_error('preg_last_error=' . preg_last_error(), E_USER_WARNING);
        return $output;
    }
    $text = $shortcodes->process($text);
    return $text;
}
