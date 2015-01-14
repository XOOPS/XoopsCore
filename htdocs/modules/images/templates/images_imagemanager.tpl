<title><{$sitename}>-<{$smarty.const._IMAGES_MANAGER}></title>
<script type="text/javascript">
<!--//
function appendCode(addCode) {
	var targetDom = window.opener.xoopsGetElementById('<{$target}>');
	if (targetDom.createTextRange && targetDom.caretPos){
  		var caretPos = targetDom.caretPos;
		caretPos.text = caretPos.text.charAt(caretPos.text.length - 1) == ' ' ? addCode + ' ' : addCode;
	} else if (targetDom.getSelection && targetDom.caretPos) {
		var caretPos = targetDom.caretPos;
		caretPos.text = caretPos.text.charat(caretPos.text.length - 1) == ' ' ? addCode + ' ' : addCode;
	} else {
		targetDom.value = targetDom.value + addCode;
  	}
	window.close();
	return;
}
//-->
</script>

</head>

<body onload="window.resizeTo(<{$xsize}>, <{$ysize}>);">

    <{if $form}>
        <{$form}>
    <{else}>
        <{$form_category}>

        <{if count($images) > 0}>
            <div id="pagenav"><{$pagenav}></div>
            <table cellspacing="0" id="imagemain">
                <tr>
                    <th><{$smarty.const._IMAGES_NAME}></th>
                    <th><{$smarty.const._IMAGES_IMAGE}><{$lang_image}></th>
                    <th><{$smarty.const._IMAGES_MIME}></th>
                    <th><{$smarty.const._IMAGES_ALIGN}></th>
                </tr>

                <{section name=i loop=$images}>
                    <tr class="txtcenter">
                        <td><input type="hidden" name="image_id[]" value="<{$images[i].id}>" /><{$images[i].nicename}></td>
                        <td><img style="max-width:200px;" src="<{$images[i].src}>" alt="<{$images[i].nicename}>" /></td>
                        <td><{$images[i].mimetype}></td>
                        <td><a href="#" title="" onclick="javascript:appendCode('<{$images[i].lxcode}>');" title=""><img src="<{xoAppUrl 'images/alignleft.gif'}>" alt="Left" /></a> <a href="#" title="" onclick="javascript:appendCode('<{$images[i].xcode}>');" title=""><img src="<{xoAppUrl 'images/aligncenter.gif'}>" alt="Center" /></a> <a href="#" title="" onclick="javascript:appendCode('<{$images[i].rxcode}>');" title=""><img src="<{xoAppUrl 'images/alignright.gif'}>" alt="Right" /></a></td>
                    </tr>
                <{/section}>
            </table>
            <div id="pagenav"><{$pagenav}></div>
        <{/if}>
    <{/if}>


    <div class="txtcenter">
        <br />
        <input class="btn btn-primary" value="<{$smarty.const._IMAGES_CLOSE}>" type="button" onclick="javascript:window.close();" />
    </div>
