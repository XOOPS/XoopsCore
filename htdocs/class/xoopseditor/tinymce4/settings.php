<?php
/*
 You may not change or alter any portion of this comment or credits
 of supporting developers from this source code or any supporting source code
 which is considered copyrighted (c) material of the original comment or credit authors.
 This program is distributed in the hope that it will be useful,
 but WITHOUT ANY WARRANTY; without even the implied warranty of
 MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
*/
/**
 *  TinyMCE 4.x settings for XOOPS
 *
 * @copyright       The XOOPS Project http://sourceforge.net/projects/xoops/
 * @license         http://www.fsf.org/copyleft/gpl.html GNU public license
 * @package         class
 * @subpackage      editor
 * @since           2.3.0
 * @author          Taiwen Jiang <phppp@users.sourceforge.net>
 * @author          Lucio Rota <lucio.rota@gmail.com>
 * @author          Laurent JEN <dugris@frxoops.org>
 * @version         $Id: settings.php 8290 2011-11-15 01:57:18Z beckmi $
 */
return array(
    "selector" => "textarea",
    "theme"     => "modern",

// default skin 
// "skin" 		=> "lightgray",
// "skin_url" 		=> "/class/xoopseditor/tinymce4/external_skins/charcoal",
// "skin_url" 		=> "/class/xoopseditor/tinymce4/external_skins/pepper-grinder",
// "skin_url" 		=> "/class/xoopseditor/tinymce4/external_skins/tundora",
// "skin_url" 		=> "/class/xoopseditor/tinymce4/external_skins/tundora",
// "skin_url" 		=> "/class/xoopseditor/tinymce4/external_skins/xenmce",


    // language code of the default language pack to use with TinyMCE. These codes are in ISO-639-1 format
    "language" => "en",
    /* possible values exemple, get from: http://wiki.moxiecode.com/examples/tinymce/installation_example_13.php */
    "mode" => "exact",
//    "convert_urls" => false,
//    "force_p_newlines" => true,
    "forced_root_block" => false,
    "force_hex_style_colors" => true,
    // to prevent new line after tags (really useful with Xoops)
//    "apply_source_formatting" => false,
    // get more W3C compatible code, since font elements are deprecated
    "convert_fonts_to_spans" => true,
    // XHTML: list elements UL/OL will be converted to valid XHTML
    "fix_list_elements" => true,
    // XHTML: table elements will be moved outside paragraphs or other block elements
//    "fix_table_elements" => true, //Removed in 3.4, this is now the default behavior.
    // XHTML strict: attributes gets converted into CSS style attribute
//    "inline_styles" => true, //This option is enabled by default as of 3.0a1.
    // if true, some accessibility focus will be available to all buttons: you will be able to tab through them all
    "accessibility_focus" => true,
    // if true, some accessibility warnings will be presented to the user
    "accessibility_warnings" => true,
	//image_advtab: True/false option if the advanced tab should be displayed or not.
	"image_advtab" => true,

	// Display or not the menu bar"
	"menubar" => true,
	

// load plugins
	"plugins" => "advlist,autolink,lists,link,image,preview,hr,anchor,".
				 "searchreplace,wordcount,visualblocks,visualchars,code,fullscreen,".
				 "insertdatetime,media,nonbreaking,save,table,contextmenu,directionality,".
				 "emoticons,template,paste,textcolor,".
				 "xoops_quote,xoops_code,xoops_tagextgal,".
				 "filemanager,responsivefilemanager,youtube,qrcode,alignbtn,chartextbtn",


//	"exclude_plugins" => "autosave,bbcode,example,fullpage",
//  "content_css" => "editor_xoops.css",

    "toolbar1" => "chartext align table | forecolor backcolor bullist numlist | styleselect formatselect",
	"toolbar2" => "responsivefilemanager image media youtube | link unlink anchor | qrcode emoticons hr xoops_emoticons xoops_quote xoops_code xoops_tagextgal template",
	"toolbar3" => "undo redo preview fullscreen removeformat visualblocks code",

    // Full XHTML rule set
    "valid_elements" => ""
        ."a[accesskey|charset|class|coords|dir<ltr?rtl|href|hreflang|id|lang|name"
        ."|onblur|onclick|ondblclick|onfocus|onkeydown|onkeypress|onkeyup"
        ."|onmousedown|onmousemove|onmouseout|onmouseover|onmouseup|rel|rev"
        ."|shape<circle?default?poly?rect|style|tabindex|title|target|type],"
        ."abbr[class|dir<ltr?rtl|id|lang|onclick|ondblclick|onkeydown|onkeypress"
        ."|onkeyup|onmousedown|onmousemove|onmouseout|onmouseover|onmouseup|style"
        ."|title],"
        ."acronym[class|dir<ltr?rtl|id|id|lang|onclick|ondblclick|onkeydown|onkeypress"
        ."|onkeyup|onmousedown|onmousemove|onmouseout|onmouseover|onmouseup|style"
        ."|title],"
        ."address[class|align|dir<ltr?rtl|id|lang|onclick|ondblclick|onkeydown"
        ."|onkeypress|onkeyup|onmousedown|onmousemove|onmouseout|onmouseover"
        ."|onmouseup|style|title],"
        ."applet[align<bottom?left?middle?right?top|alt|archive|class|code|codebase"
        ."|height|hspace|id|name|object|style|title|vspace|width],"
        ."area[accesskey|alt|class|coords|dir<ltr?rtl|href|id|lang|nohref<nohref"
        ."|onblur|onclick|ondblclick|onfocus|onkeydown|onkeypress|onkeyup"
        ."|onmousedown|onmousemove|onmouseout|onmouseover|onmouseup"
        ."|shape<circle?default?poly?rect|style|tabindex|title|target],"
        ."base[href|target],"
        ."basefont[color|face|id|size],"
        ."bdo[class|dir<ltr?rtl|id|lang|style|title],"
        ."big[class|dir<ltr?rtl|id|lang|onclick|ondblclick|onkeydown|onkeypress"
        ."|onkeyup|onmousedown|onmousemove|onmouseout|onmouseover|onmouseup|style"
        ."|title],"
        ."blockquote[cite|class|dir<ltr?rtl|id|lang|onclick|ondblclick"
        ."|onkeydown|onkeypress|onkeyup|onmousedown|onmousemove|onmouseout"
        ."|onmouseover|onmouseup|style|title],"
        ."body[alink|background|bgcolor|class|dir<ltr?rtl|id|lang|link|onclick"
        ."|ondblclick|onkeydown|onkeypress|onkeyup|onload|onmousedown|onmousemove"
        ."|onmouseout|onmouseover|onmouseup|onunload|style|title|text|vlink],"
        ."br[class|clear<all?left?none?right|id|style|title],"
        ."button[accesskey|class|dir<ltr?rtl|disabled<disabled|id|lang|name|onblur"
        ."|onclick|ondblclick|onfocus|onkeydown|onkeypress|onkeyup|onmousedown"
        ."|onmousemove|onmouseout|onmouseover|onmouseup|style|tabindex|title|type"
        ."|value],"
        ."caption[align<bottom?left?right?top|class|dir<ltr?rtl|id|lang|onclick"
        ."|ondblclick|onkeydown|onkeypress|onkeyup|onmousedown|onmousemove"
        ."|onmouseout|onmouseover|onmouseup|style|title],"
        ."center[class|dir<ltr?rtl|id|lang|onclick|ondblclick|onkeydown|onkeypress"
        ."|onkeyup|onmousedown|onmousemove|onmouseout|onmouseover|onmouseup|style"
        ."|title],"
        ."cite[class|dir<ltr?rtl|id|lang|onclick|ondblclick|onkeydown|onkeypress"
        ."|onkeyup|onmousedown|onmousemove|onmouseout|onmouseover|onmouseup|style"
        ."|title],"
        ."code[class|dir<ltr?rtl|id|lang|onclick|ondblclick|onkeydown|onkeypress"
        ."|onkeyup|onmousedown|onmousemove|onmouseout|onmouseover|onmouseup|style"
        ."|title],"
        ."col[align<center?char?justify?left?right|char|charoff|class|dir<ltr?rtl|id"
        ."|lang|onclick|ondblclick|onkeydown|onkeypress|onkeyup|onmousedown"
        ."|onmousemove|onmouseout|onmouseover|onmouseup|span|style|title"
        ."|valign<baseline?bottom?middle?top|width],"
        ."colgroup[align<center?char?justify?left?right|char|charoff|class|dir<ltr?rtl"
        ."|id|lang|onclick|ondblclick|onkeydown|onkeypress|onkeyup|onmousedown"
        ."|onmousemove|onmouseout|onmouseover|onmouseup|span|style|title"
        ."|valign<baseline?bottom?middle?top|width],"
        ."dd[class|dir<ltr?rtl|id|lang|onclick|ondblclick|onkeydown|onkeypress|onkeyup"
        ."|onmousedown|onmousemove|onmouseout|onmouseover|onmouseup|style|title],"
        ."del[cite|class|datetime|dir<ltr?rtl|id|lang|onclick|ondblclick|onkeydown"
        ."|onkeypress|onkeyup|onmousedown|onmousemove|onmouseout|onmouseover"
        ."|onmouseup|style|title],"
        ."dfn[class|dir<ltr?rtl|id|lang|onclick|ondblclick|onkeydown|onkeypress"
        ."|onkeyup|onmousedown|onmousemove|onmouseout|onmouseover|onmouseup|style"
        ."|title],"
        ."dir[class|compact<compact|dir<ltr?rtl|id|lang|onclick|ondblclick|onkeydown"
        ."|onkeypress|onkeyup|onmousedown|onmousemove|onmouseout|onmouseover"
        ."|onmouseup|style|title],"
        ."div[align<center?justify?left?right|class|dir<ltr?rtl|id|lang|onclick"
        ."|ondblclick|onkeydown|onkeypress|onkeyup|onmousedown|onmousemove"
        ."|onmouseout|onmouseover|onmouseup|style|title],"
        ."dl[class|compact<compact|dir<ltr?rtl|id|lang|onclick|ondblclick|onkeydown"
        ."|onkeypress|onkeyup|onmousedown|onmousemove|onmouseout|onmouseover"
        ."|onmouseup|style|title],"
        ."dt[class|dir<ltr?rtl|id|lang|onclick|ondblclick|onkeydown|onkeypress|onkeyup"
        ."|onmousedown|onmousemove|onmouseout|onmouseover|onmouseup|style|title],"
        ."em/i[class|dir<ltr?rtl|id|lang|onclick|ondblclick|onkeydown|onkeypress"
        ."|onkeyup|onmousedown|onmousemove|onmouseout|onmouseover|onmouseup|style"
        ."|title],"
        ."fieldset[class|dir<ltr?rtl|id|lang|onclick|ondblclick|onkeydown|onkeypress"
        ."|onkeyup|onmousedown|onmousemove|onmouseout|onmouseover|onmouseup|style"
        ."|title],"
        ."font[class|color|dir<ltr?rtl|face|id|lang|size|style|title],"
        ."form[accept|accept-charset|action|class|dir<ltr?rtl|enctype|id|lang"
        ."|method<get?post|name|onclick|ondblclick|onkeydown|onkeypress|onkeyup"
        ."|onmousedown|onmousemove|onmouseout|onmouseover|onmouseup|onreset|onsubmit"
        ."|style|title|target],"
        ."frame[class|frameborder|id|longdesc|marginheight|marginwidth|name"
        ."|noresize<noresize|scrolling<auto?no?yes|src|style|title],"
        ."frameset[class|cols|id|onload|onunload|rows|style|title],"
        ."h1[align<center?justify?left?right|class|dir<ltr?rtl|id|lang|onclick"
        ."|ondblclick|onkeydown|onkeypress|onkeyup|onmousedown|onmousemove"
        ."|onmouseout|onmouseover|onmouseup|style|title],"
        ."h2[align<center?justify?left?right|class|dir<ltr?rtl|id|lang|onclick"
        ."|ondblclick|onkeydown|onkeypress|onkeyup|onmousedown|onmousemove"
        ."|onmouseout|onmouseover|onmouseup|style|title],"
        ."h3[align<center?justify?left?right|class|dir<ltr?rtl|id|lang|onclick"
        ."|ondblclick|onkeydown|onkeypress|onkeyup|onmousedown|onmousemove"
        ."|onmouseout|onmouseover|onmouseup|style|title],"
        ."h4[align<center?justify?left?right|class|dir<ltr?rtl|id|lang|onclick"
        ."|ondblclick|onkeydown|onkeypress|onkeyup|onmousedown|onmousemove"
        ."|onmouseout|onmouseover|onmouseup|style|title],"
        ."h5[align<center?justify?left?right|class|dir<ltr?rtl|id|lang|onclick"
        ."|ondblclick|onkeydown|onkeypress|onkeyup|onmousedown|onmousemove"
        ."|onmouseout|onmouseover|onmouseup|style|title],"
        ."h6[align<center?justify?left?right|class|dir<ltr?rtl|id|lang|onclick"
        ."|ondblclick|onkeydown|onkeypress|onkeyup|onmousedown|onmousemove"
        ."|onmouseout|onmouseover|onmouseup|style|title],"
        ."head[dir<ltr?rtl|lang|profile],"
        ."hr[align<center?left?right|class|dir<ltr?rtl|id|lang|noshade<noshade|onclick"
        ."|ondblclick|onkeydown|onkeypress|onkeyup|onmousedown|onmousemove"
        ."|onmouseout|onmouseover|onmouseup|size|style|title|width],"
        ."html[dir<ltr?rtl|lang|version],"
        ."iframe[align<bottom?left?middle?right?top|class|frameborder|height|id"
        ."|longdesc|marginheight|marginwidth|name|scrolling<auto?no?yes|src|style"
        ."|title|width],"
        ."img[align<bottom?left?middle?right?top|alt|border|class|dir<ltr?rtl|height"
        ."|hspace|id|ismap<ismap|lang|longdesc|name|onclick|ondblclick|onkeydown"
        ."|onkeypress|onkeyup|onmousedown|onmousemove|onmouseout|onmouseover"
        ."|onmouseup|src|style|title|usemap|vspace|width],"
        ."input[accept|accesskey|align<bottom?left?middle?right?top|alt"
        ."|checked<checked|class|dir<ltr?rtl|disabled<disabled|id|ismap<ismap|lang"
        ."|maxlength|name|onblur|onclick|ondblclick|onfocus|onkeydown|onkeypress"
        ."|onkeyup|onmousedown|onmousemove|onmouseout|onmouseover|onmouseup|onselect"
        ."|readonly<readonly|size|src|style|tabindex|title"
        ."|type<button?checkbox?file?hidden?image?password?radio?reset?submit?text"
        ."|usemap|value],"
        ."ins[cite|class|datetime|dir<ltr?rtl|id|lang|onclick|ondblclick|onkeydown"
        ."|onkeypress|onkeyup|onmousedown|onmousemove|onmouseout|onmouseover"
        ."|onmouseup|style|title],"
        ."isindex[class|dir<ltr?rtl|id|lang|prompt|style|title],"
        ."kbd[class|dir<ltr?rtl|id|lang|onclick|ondblclick|onkeydown|onkeypress"
        ."|onkeyup|onmousedown|onmousemove|onmouseout|onmouseover|onmouseup|style"
        ."|title],"
        ."label[accesskey|class|dir<ltr?rtl|for|id|lang|onblur|onclick|ondblclick"
        ."|onfocus|onkeydown|onkeypress|onkeyup|onmousedown|onmousemove|onmouseout"
        ."|onmouseover|onmouseup|style|title],"
        ."legend[align<bottom?left?right?top|accesskey|class|dir<ltr?rtl|id|lang"
        ."|onclick|ondblclick|onkeydown|onkeypress|onkeyup|onmousedown|onmousemove"
        ."|onmouseout|onmouseover|onmouseup|style|title],"
        ."li[class|dir<ltr?rtl|id|lang|onclick|ondblclick|onkeydown|onkeypress|onkeyup"
        ."|onmousedown|onmousemove|onmouseout|onmouseover|onmouseup|style|title|type"
        ."|value],"
        ."link[charset|class|dir<ltr?rtl|href|hreflang|id|lang|media|onclick"
        ."|ondblclick|onkeydown|onkeypress|onkeyup|onmousedown|onmousemove"
        ."|onmouseout|onmouseover|onmouseup|rel|rev|style|title|target|type],"
        ."map[class|dir<ltr?rtl|id|lang|name|onclick|ondblclick|onkeydown|onkeypress"
        ."|onkeyup|onmousedown|onmousemove|onmouseout|onmouseover|onmouseup|style"
        ."|title],"
        ."menu[class|compact<compact|dir<ltr?rtl|id|lang|onclick|ondblclick|onkeydown"
        ."|onkeypress|onkeyup|onmousedown|onmousemove|onmouseout|onmouseover"
        ."|onmouseup|style|title],"
        ."meta[content|dir<ltr?rtl|http-equiv|lang|name|scheme],"
        ."noframes[class|dir<ltr?rtl|id|lang|onclick|ondblclick|onkeydown|onkeypress"
        ."|onkeyup|onmousedown|onmousemove|onmouseout|onmouseover|onmouseup|style"
        ."|title],"
        ."noscript[class|dir<ltr?rtl|id|lang|style|title],"
        ."object[align<bottom?left?middle?right?top|archive|border|class|classid"
        ."|codebase|codetype|data|declare|dir<ltr?rtl|height|hspace|id|lang|name"
        ."|onclick|ondblclick|onkeydown|onkeypress|onkeyup|onmousedown|onmousemove"
        ."|onmouseout|onmouseover|onmouseup|standby|style|tabindex|title|type|usemap"
        ."|vspace|width],"
        ."ol[class|compact<compact|dir<ltr?rtl|id|lang|onclick|ondblclick|onkeydown"
        ."|onkeypress|onkeyup|onmousedown|onmousemove|onmouseout|onmouseover"
        ."|onmouseup|start|style|title|type],"
        ."optgroup[class|dir<ltr?rtl|disabled<disabled|id|label|lang|onclick"
        ."|ondblclick|onkeydown|onkeypress|onkeyup|onmousedown|onmousemove"
        ."|onmouseout|onmouseover|onmouseup|style|title],"
        ."option[class|dir<ltr?rtl|disabled<disabled|id|label|lang|onclick|ondblclick"
        ."|onkeydown|onkeypress|onkeyup|onmousedown|onmousemove|onmouseout"
        ."|onmouseover|onmouseup|selected<selected|style|title|value],"
        ."p[align<center?justify?left?right|class|dir<ltr?rtl|id|lang|onclick"
        ."|ondblclick|onkeydown|onkeypress|onkeyup|onmousedown|onmousemove"
        ."|onmouseout|onmouseover|onmouseup|style|title],"
        ."param[id|name|type|value|valuetype<DATA?OBJECT?REF],"
        ."pre/listing/plaintext/xmp[align|class|dir<ltr?rtl|id|lang|onclick|ondblclick"
        ."|onkeydown|onkeypress|onkeyup|onmousedown|onmousemove|onmouseout"
        ."|onmouseover|onmouseup|style|title|width],"
        ."q[cite|class|dir<ltr?rtl|id|lang|onclick|ondblclick|onkeydown|onkeypress"
        ."|onkeyup|onmousedown|onmousemove|onmouseout|onmouseover|onmouseup|style"
        ."|title],"
        ."s[class|dir<ltr?rtl|id|lang|onclick|ondblclick|onkeydown|onkeypress|onkeyup"
        ."|onmousedown|onmousemove|onmouseout|onmouseover|onmouseup|style|title],"
        ."samp[class|dir<ltr?rtl|id|lang|onclick|ondblclick|onkeydown|onkeypress"
        ."|onkeyup|onmousedown|onmousemove|onmouseout|onmouseover|onmouseup|style"
        ."|title],"
        ."script[charset|defer|language|src|type],"
        ."select[class|dir<ltr?rtl|disabled<disabled|id|lang|multiple<multiple|name"
        ."|onblur|onchange|onclick|ondblclick|onfocus|onkeydown|onkeypress|onkeyup"
        ."|onmousedown|onmousemove|onmouseout|onmouseover|onmouseup|size|style"
        ."|tabindex|title],"
        ."small[class|dir<ltr?rtl|id|lang|onclick|ondblclick|onkeydown|onkeypress"
        ."|onkeyup|onmousedown|onmousemove|onmouseout|onmouseover|onmouseup|style"
        ."|title],"
        ."span[align<center?justify?left?right|class|dir<ltr?rtl|id|lang|onclick|ondblclick|onkeydown"
        ."|onkeypress|onkeyup|onmousedown|onmousemove|onmouseout|onmouseover"
        ."|onmouseup|style|title],"
        ."strike[class|class|dir<ltr?rtl|id|lang|onclick|ondblclick|onkeydown"
        ."|onkeypress|onkeyup|onmousedown|onmousemove|onmouseout|onmouseover"
        ."|onmouseup|style|title],"
        ."strong/b[class|dir<ltr?rtl|id|lang|onclick|ondblclick|onkeydown|onkeypress"
        ."|onkeyup|onmousedown|onmousemove|onmouseout|onmouseover|onmouseup|style"
        ."|title],"
        ."style[dir<ltr?rtl|lang|media|title|type],"
        ."sub[class|dir<ltr?rtl|id|lang|onclick|ondblclick|onkeydown|onkeypress"
        ."|onkeyup|onmousedown|onmousemove|onmouseout|onmouseover|onmouseup|style"
        ."|title],"
        ."sup[class|dir<ltr?rtl|id|lang|onclick|ondblclick|onkeydown|onkeypress"
        ."|onkeyup|onmousedown|onmousemove|onmouseout|onmouseover|onmouseup|style"
        ."|title],"
        ."table[align<center?left?right|bgcolor|border|cellpadding|cellspacing|class"
        ."|dir<ltr?rtl|frame|height|id|lang|onclick|ondblclick|onkeydown|onkeypress"
        ."|onkeyup|onmousedown|onmousemove|onmouseout|onmouseover|onmouseup|rules"
        ."|style|summary|title|width],"
        ."tbody[align<center?char?justify?left?right|char|class|charoff|dir<ltr?rtl|id"
        ."|lang|onclick|ondblclick|onkeydown|onkeypress|onkeyup|onmousedown"
        ."|onmousemove|onmouseout|onmouseover|onmouseup|style|title"
        ."|valign<baseline?bottom?middle?top],"
        ."td[abbr|align<center?char?justify?left?right|axis|bgcolor|char|charoff|class"
        ."|colspan|dir<ltr?rtl|headers|height|id|lang|nowrap<nowrap|onclick"
        ."|ondblclick|onkeydown|onkeypress|onkeyup|onmousedown|onmousemove"
        ."|onmouseout|onmouseover|onmouseup|rowspan|scope<col?colgroup?row?rowgroup"
        ."|style|title|valign<baseline?bottom?middle?top|width],"
        ."textarea[accesskey|class|cols|dir<ltr?rtl|disabled<disabled|id|lang|name"
        ."|onblur|onclick|ondblclick|onfocus|onkeydown|onkeypress|onkeyup"
        ."|onmousedown|onmousemove|onmouseout|onmouseover|onmouseup|onselect"
        ."|readonly<readonly|rows|style|tabindex|title],"
        ."tfoot[align<center?char?justify?left?right|char|charoff|class|dir<ltr?rtl|id"
        ."|lang|onclick|ondblclick|onkeydown|onkeypress|onkeyup|onmousedown"
        ."|onmousemove|onmouseout|onmouseover|onmouseup|style|title"
        ."|valign<baseline?bottom?middle?top],"
        ."th[abbr|align<center?char?justify?left?right|axis|bgcolor|char|charoff|class"
        ."|colspan|dir<ltr?rtl|headers|height|id|lang|nowrap<nowrap|onclick"
        ."|ondblclick|onkeydown|onkeypress|onkeyup|onmousedown|onmousemove"
        ."|onmouseout|onmouseover|onmouseup|rowspan|scope<col?colgroup?row?rowgroup"
        ."|style|title|valign<baseline?bottom?middle?top|width],"
        ."thead[align<center?char?justify?left?right|char|charoff|class|dir<ltr?rtl|id"
        ."|lang|onclick|ondblclick|onkeydown|onkeypress|onkeyup|onmousedown"
        ."|onmousemove|onmouseout|onmouseover|onmouseup|style|title"
        ."|valign<baseline?bottom?middle?top],"
        ."title[dir<ltr?rtl|lang],"
        ."tr[abbr|align<center?char?justify?left?right|bgcolor|char|charoff|class"
        ."|rowspan|dir<ltr?rtl|id|lang|onclick|ondblclick|onkeydown|onkeypress"
        ."|onkeyup|onmousedown|onmousemove|onmouseout|onmouseover|onmouseup|style"
        ."|title|valign<baseline?bottom?middle?top],"
        ."tt[class|dir<ltr?rtl|id|lang|onclick|ondblclick|onkeydown|onkeypress|onkeyup"
        ."|onmousedown|onmousemove|onmouseout|onmouseover|onmouseup|style|title],"
        ."u[class|dir<ltr?rtl|id|lang|onclick|ondblclick|onkeydown|onkeypress|onkeyup"
        ."|onmousedown|onmousemove|onmouseout|onmouseover|onmouseup|style|title],"
        ."ul[class|compact<compact|dir<ltr?rtl|id|lang|onclick|ondblclick|onkeydown"
        ."|onkeypress|onkeyup|onmousedown|onmousemove|onmouseout|onmouseover"
        ."|onmouseup|style|title|type],"
        ."var[class|dir<ltr?rtl|id|lang|onclick|ondblclick|onkeydown|onkeypress"
        ."|onkeyup|onmousedown|onmousemove|onmouseout|onmouseover|onmouseup|style"
        ."|title]",

);
