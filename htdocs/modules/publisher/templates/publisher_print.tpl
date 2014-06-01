<html>
<head>
    <meta http-equiv="content-type" content="text/html; charset=<{$xoops_charset}>"/>
    <meta http-equiv="content-language" content="<{$xoops_langcode}>"/>
    <meta name="robots" content="<{$xoops_meta_robots}>"/>
    <meta name="keywords" content="<{$xoops_meta_keywords}>"/>
    <meta name="description" content="<{$xoops_meta_description}>"/>
    <meta name="rating" content="<{$xoops_meta_rating}>"/>
    <meta name="author" content="<{$xoops_meta_author}>"/>
    <meta name="copyright" content="<{$xoops_meta_copyright}>"/>
    <meta name="generator" content="XOOPS"/>
    <title><{$printtitle}></title>
</head>

<body>

<{if !$doNotStartPrint}>
<script for=window event=onload language="javascript">
    if (window.print)
        window.print();
</script>
<{/if}>

<div id="pagelayer">
    <div style="text-align: center;">
        <img src="<{$printlogourl}>" border="0" alt=""/></div>
    <div style="text-align: right; margin-top: 10px; border: 1px solid; padding: 2px;"><{$printheader}></div>
    <{$item.image}>

    <{if !$noTitle}>
    <div style="padding-top: 5px; font-size: 14px; font-weight: bold"><{$item.title}></div>
    <{/if}>

    <{if !$noCategory}>
    <div style="padding-top: 2px; "><{$lang_category}> : <{$item.categoryname}></div>
    <{/if}>

    <{if $display_whowhen_link}>
    <div style="padding-top: 2px; "><{$lang_author_date}></div>
    <{/if}>

    <{if $item.body}>
    <div style="padding-top: 8px; text-align: justify"><{$item.body}></div>
    <{/if}>

    <{if $itemfooter}>    <!--<div style="text-align: left; font-weight: bold; padding-top: 10px;"><{$itemfooter}></div>-->
    <div style="text-align: center; font-weight: bold; border: 1px solid; padding: 2px; margin-top: 10px;"><{$itemfooter}></div>
    <{/if}> <br/><br/><br/> <{if $indexfooter}>
    <div style="text-align: center; margin-top: 10px; border: 1px solid; padding: 2px;"><{$indexfooter}></div>
    <{/if}>

    <{if $smartPopup}>
    <div style="text-align: center">
        <a href="javascript:window.close();"><{$smarty.const._MD_PUBLISHER_PRINT_CLOSE}></a>
    </div>
    <{/if}>
</div>


</body>

</html>
