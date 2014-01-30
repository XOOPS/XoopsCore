<?php

class MytsSoundcloud extends MyTextSanitizerExtension
{
    public function encode($textarea_id)
    {
        $config = parent::loadConfig(dirname(__FILE__));
        $alttxt = htmlspecialchars('SoundCloud', ENT_QUOTES);
        $prompt = htmlspecialchars('Enter SoundCloud Profile URL', ENT_QUOTES);
        $code = "<img src='{$this->image_path}/soundcloud.png' "
            . " alt='{$alttxt}' title='{$alttxt}' "
            . " onclick='xoopsCodeSoundCloud(\"{$textarea_id}\",\"{$prompt}\");' "
            . " onmouseover='style.cursor=\"hand\"'/>&nbsp;";
        $javascript = <<<EOH
            function xoopsCodeSoundCloud(id, enterSoundCloud)
            {
                var selection = xoopsGetSelect(id);
                if (selection.length > 0) {
                    var text = selection;
                } else {
                    var text = prompt(enterSoundCloud, "");
                }

                var domobj = xoopsGetElementById(id);
                if (text.length > 0) {
                    xoopsInsertText(domobj, "[soundcloud]"+text+"[/soundcloud]");
                }
                domobj.focus();
            }
EOH;

        return array($code, $javascript);
    }

    public function load(&$ts)
    {
        $ts->callbackPatterns[] = "/\[soundcloud\](http[s]?:\/\/[^\"'<>]*)(.*)\[\/soundcloud\]/sU";
        $ts->callbacks[] = __CLASS__ . "::myCallback";
    }

    public static function myCallback($match)
    {
        $url = $match[1] . $match[2];
        $config = parent::loadConfig(dirname(__FILE__));
        if (!preg_match("/^http[s]?:\/\/(www\.)?soundcloud\.com\/(.*)/i", $url, $matches)) {
            trigger_error("Not matched: {$url}", E_USER_WARNING);

            return "";
        }

        $code = '<object height="81" width="100%"><param name="movie" '
            . 'value="http://player.soundcloud.com/player.swf?url='.$url.'&amp;g=bb">'
            . '</param><param name="allowscriptaccess" value="always"></param>'
            . '<embed allowscriptaccess="always" height="81" '
            . 'src="http://player.soundcloud.com/player.swf?url=' . $url
            . '&amp;g=bb" type="application/x-shockwave-flash" width="100%"></embed></object>'
            . '<a href="'.$url.'">'.$url.'</a>';

        return $code;
    }
}
