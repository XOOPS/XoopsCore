// created 2005-1-12 by Martin Sadera (sadera@e-d-a.info)
// ported to Xoops CMS by ralf57
// updated to TinyMCE v3.0.1 / 2008-02-29 / by luciorota

var Xoops_codeDialog = {
    init : function()
    {
        // Get the selected contents as text and place it in the input
        text_id.value = tinyMCEPopup.editor.selection.getContent({format : 'text'});
    },
    insert : function()
    {
        var ctext = text_id.value;
        ctext.replace(new RegExp("<",'g'), "&lt;");
        ctext.replace(new RegExp(">",'g'), "&gt;");
        var html = '<div class="xoopsCode">' + ctext + '</div><br />';
        tinyMCEPopup.editor.execCommand('mceInsertContent', true, html);
        tinyMCEPopup.close();
    }
};

tinyMCEPopup.onInit.add(Xoops_codeDialog.init, Xoops_codeDialog);
