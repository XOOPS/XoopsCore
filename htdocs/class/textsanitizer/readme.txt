Usage of custom xoopscode

Step 1, build the class for a custom code or an extension, e.g. mycode:
in /class/textsanitizer/mycode/mycode.php (see below)

Step 2, enable the extension in textsanitizer:
in /class/textsanitizer/config.custom.php


mycode.php:
class MytsMycode extends MyTextSanitizerExtension
{
    // The encode function for dhtml editor
    function encode($textarea_id)
    {
        // If the extension has config data, load it
        $config = parent::loadConfig(dirname(__FILE__));
        // Make sure that the icon is available /images/form/mycode.gif
        $code = "<img src='{$this->image_path}/mycode.gif' alt='" . _XOOPS_FORM_ALTMYCODE . "' onclick='xoopsCodeMycode(\"{$textarea_id}\",\"" . htmlspecialchars(_XOOPS_FORM_ENTERMYCODETERM, ENT_QUOTES) . "\");'  onmouseover='style.cursor=\"hand\"'/>&nbsp;";
        $javascript = <<<EOH
            function xoopsCodeMycode(id, enterMycodePhrase){
                if (enterMycodePhrase == null) {
                    enterMycodePhrase = "Enter the content for the code.";
                }
                var selection = xoopsGetSelect(id);
                if (selection.length > 0) {
                    var text = selection;
                }else {
                    var text = prompt(enterMycodePhrase, "");
                }
                var domobj = xoopsGetElementById(id);
                if ( text != null && text != "" ) {
                    var result = "[mycode]" + text + "[/mycode]";
                    xoopsInsertText(domobj, result);
                }
                domobj.focus();
            }
EOH;
        // Return the scripts to be displayed in editor form and the javascript for relevant actions
        return array($code, $javascript);
    }
    
    // The code parser
    function load(&$ts) 
    {
        $ts->patterns[] = "/\[mycode\]([^\]]*)\[\/mycode\]/esU";
        $ts->replacements[] = __CLASS__."::decode( '\\1' )"; 
    }
    
    // Processing the text
    function decode($text)
    {
        // Load config data if any
        $config = parent::loadConfig( dirname(__FILE__) );
        if ( empty($text) || empty($config['enabled']) ) return $text;
        $ret = someFunctionToConvertTheTextToDefinedFormat($text);
        return $ret;
    }
}

config.custom.php: 
return $config = array(
        "extensions" => array(
                        "iframe"    => 0,
                        "image"     => 1,
                        "flash"     => 1,
                        "youtube"   => 1,
                        "mp3"       => 1,
                        "wmp"       => 0,
                        "wiki"      => 1,
                        "mms"       => 0,
                        "rtsp"      => 0,
                        "mycode"    => 1,   // Enable the extension
                        ),
    );
