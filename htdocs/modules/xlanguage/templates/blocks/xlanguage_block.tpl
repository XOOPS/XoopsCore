<link rel="stylesheet" href="<{xoImgUrl 'modules/xlanguage/css/block.css'}>" type="text/css" media="screen" />

<{if $block.display|default:false}>
<{if $block.display == "text"}>
    <{include file="block:xlanguage/xlanguage_block_text.tpl"}>
<{elseif $block.display == "select"}>
    <{include file="block:xlanguage/xlanguage_block_select.tpl"}>
<{elseif $block.display == "jquery"}>
    <{include file="block:xlanguage/xlanguage_block_jquery.tpl"}>
<{elseif $block.display == "bootstrap"}>
    <{include file="block:xlanguage/xlanguage_block_bootstrap.tpl"}>
<{else}>
    <{include file="block:xlanguage/xlanguage_block_images.tpl"}>
<{/if}>
<{/if}>