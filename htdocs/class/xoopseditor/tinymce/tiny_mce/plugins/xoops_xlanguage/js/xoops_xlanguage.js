/**
 * @author          Laurent JEN (aka DuGris)
 * @version         $Id$
 */

tinyMCEPopup.requireLangPack();

var Xoops_xlanguageDialog = {
    init : function()
    {
        tinyMCEPopup.resizeToInnerSize();

        var f = document.forms[0];
        // Get the selected contents as text and place it in the input
        text = tinyMCEPopup.editor.selection.getContent({format : 'text'});
        f.text_language.value = text.replace(/\[(.*?)\](.*?)\[\/(.*?)\]/ig, "$2");
        f.select_language.value = text.replace(/\[(.*?)\](.*?)\[\/(.*?)\]/ig, "$1");
        Xoops_xlanguageDialog.onkeyupMLC(this);
    },

    insertMLC : function()
    {
        var f = document.forms[0];

        var mltext = f.text_language.value;
        var selectlang = f.select_language.value;
        if ( selectlang != '' ) {
            if ( mltext != '' ) {
                mltext.replace(new RegExp("<",'g'), "&lt;");
                mltext.replace(new RegExp(">",'g'), "&gt;");
                var html = '['+selectlang+']';
                html += mltext+'[/'+selectlang+']';

                // Insert the contents from the input into the document
                tinyMCEPopup.editor.execCommand('mceInsertContent', true, html);
            }
            tinyMCEPopup.close();
        } else if (selectlang == '' && mltext != '') {
            alert( tinyMCEPopup.getLang('xoops_xlanguage_dlg.chooselang') );
        } else {
            tinyMCEPopup.close();
        }
    },

    // limit to 10000 caracters to prevent preg_replace bug
    onkeyupMLC : function()
    {
        var f = document.forms[0];
        var str = new String(f.text_language.value);
        var len = str.length;
        var maxKeys = 10000;

        if ( len > maxKeys ) {
            alert( tinyMCEPopup.getLang('xoops_xlanguage_dlg.alertmaxstring') );
            f.text_language.value = str.substr(0, maxKeys);
            var str = new String(f.text_language.value);
            var len = str.length;
        }

        var maxText = tinyMCEPopup.getLang('xoops_xlanguage_dlg.maxstring');
        maxText = len + maxText.replace('%maxchar%', maxKeys);
        document.getElementById("text_language_msg").innerHTML = maxText;

    }
}

tinyMCEPopup.onInit.add(Xoops_xlanguageDialog.init, Xoops_xlanguageDialog);