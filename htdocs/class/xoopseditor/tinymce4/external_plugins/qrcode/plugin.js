/**
 * QR code - a TinyMCE 4 QR code wizzard
 * qrcode/plugin.js
 *
 *
 * Plugin info: http://www.cfconsultancy.nl/
 * Author: Ceasar Feijen
 *
 * Version: 1.1.1 released 2013-10-14
 */

tinymce.PluginManager.add('qrcode', function(editor) {

    function openmanager() {
        var title="Create QRcode";
        if (typeof tinymce.settings.qrcode_title !== "undefined" && tinymce.settings.qrcode_title) {
            title=tinymce.settingsqrcode_title;
        }
        win = editor.windowManager.open({
            title: title,
            file: tinyMCE.baseURL + '/plugins/qrcode/qrcode.html',
            filetype: 'image',
	    	width: 650,
            height: 510,
            inline: 1,
            buttons: [{
                text: 'cancel',
                onclick: function() {
                    this.parent()
                        .parent()
                        .close();
                }
            }]
        });

    }
	editor.addButton('qrcode', {
		icon: true,
		image: tinyMCE.baseURL + '/plugins/qrcode/icon.png',
		tooltip: 'Create QRcode',
		shortcut: 'Ctrl+QR',
		onclick: openmanager
	});

	editor.addShortcut('Ctrl+QR', '', openmanager);

	editor.addMenuItem('qrcode', {
		icon:'media',
		text: 'Create QRcode',
		shortcut: 'Ctrl+QR',
		onclick: openmanager,
		context: 'insert'
	});
});
