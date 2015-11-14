<?php
/*
 * Smarty plugin to enable shortcodes in templates
 */
function smarty_outputfilter_shortcodes($output, Smarty_Internal_Template $template)
{
    $shortcodes = \Xoops\Core\Text\Sanitizer::getInstance()->getShortCodes();

    // break out the body content
    $bodyPattern = '/(([\S\s]*)((?><body[\S\s]*>)))([\S\s]*)((?><\/body>)([\S\s]*)$)/U';

    // breaks out sections of string which are not part of form elements
    $scPattern = '/(([\S\s]*)((?><textarea[\S\s]*\/textarea>)+|(?><input[\S\s]*>)+|(?><select[\S\s]*\/select>)+|(?><script[\S\s]*\/script>+)|(?><style[\S\s]*\/style>)+))/U';

    $text = preg_replace_callback(
        $bodyPattern,
        function ($matches) use ($scPattern, $shortcodes) {
            $body = preg_replace_callback(
                $scPattern,
                function ($innerMatches) use ($shortcodes) {
                    $text = $shortcodes->process($innerMatches[2]) . $innerMatches[3];
                    return $text;
                },
                $matches[4] . '<input>'
            );
            return $matches[1] . substr($body, 0, -7) . $matches[5];
        },
        $output
    );

    return $text;
}
