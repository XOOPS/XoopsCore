<!DOCTYPE html>
<html lang="<{$xoops_langcode}>">
<head>
    <!-- Assign Theme variables -->
    <{include file="$theme_tpl/theme_vars.html" scope=parent}>
    <!-- Title, meta, CSS and javascript -->
    <{include file="$theme_tpl/theme_head.html"}>
</head>
<body id="<{$xoops_dirname}>" class="<{$xoops_langcode}>">
<div class="navbar navbar-fixed-top">
    <div class="navbar-inner">
        <div class="container">
            <a class="brand" href="<{xoAppUrl }>" title="<{$xoops_sitename}>">
                <img src="<{xoImgUrl 'img/logo.png'}>" alt="<{$xoops_sitename}>" />
            </a>
            <!-- Navigation bar menu -->
            <{include file="$theme_tpl/theme_menu.html"}>
            <!-- User menu -->
            <{include file="$theme_tpl/theme_user.html"}>
            <!-- Language menu -->
            <{include file="$theme_tpl/theme_language.html"}>
        </div>
    </div>
</div>
<div class="xo-hero">
    <div class="container xo-hero-content">
        <div class="row">
            <div class="span7">
                <h1>XOOPS. Powered by You!</h1>
                <p>easy to use dynamic web content management system written in PHP</p>
                <p>
                    <a class="btn btn-warning btn-large">
                        Learn more
                     </a>
                </p>
            </div>
            <div class="span5">
                <{$xoops_banner}>
            </div>
        </div>
    </div>
</div>
<div class="container">
    <div class="row">
        <{if $xoops_showlblock}>
        <div class="span3 xo-block-left">
            <{foreach item=block from=$xoBlocks.canvas_left}>
            <{include file="$theme_tpl/block_left.html"}>
            <{/foreach}>
        </div>
        <{/if}>
        <div class="span<{$col_span}> xo-block-center">
            <{if $xoBlocks.page_topleft or $xoBlocks.page_topcenter or $xoBlocks.page_topright}>
            <!-- Display center blocks if any -->
            <div class="row">
                <div class="span<{$col_span}> xo-top">
                    <div class="row">
                        <div class="span<{$col_span}> xo-center">
                            <!-- Start center-center blocks loop -->
                            <{foreach item=block from=$xoBlocks.page_topcenter}>
                            <{include file="$theme_tpl/block_center_c.html"}>
                            <{/foreach}>
                            <!-- End center-center blocks loop -->
                        </div>
                    </div>
                    <div class="row">
                        <div class="span<{$col_span_mid}> xo-left">
                            <!-- Start center-left blocks loop -->
                            <{foreach item=block from=$xoBlocks.page_topleft}>
                            <{include file="$theme_tpl/block_center_l.html"}>
                            <{/foreach}>
                            <!-- End center-left blocks loop -->
                        </div>
                        <{if $col_span_mid == 4}><div class="span1">&nbsp;</div><{/if}>
                        <div class="span<{$col_span_mid}> xo-right">
                            <!-- Start center-right blocks loop -->
                            <{foreach item=block from=$xoBlocks.page_topright}>
                            <{include file="$theme_tpl/block_center_r.html"}>
                            <{/foreach}>
                            <!-- End center-right blocks loop -->
                        </div>
                    </div>
                </div>
            </div>
            <{/if}>
            <{if $xoops_contents && ($xoops_contents != ' ') }>
            <!-- Start content module page -->
            <div class="row">
                <div class="span<{$col_span}>">
                    <{$xoops_contents}>
                </div>
            </div>
            <!-- End content module -->
            <{/if}>
            <{if $xoBlocks.page_bottomleft or $xoBlocks.page_bottomcenter or $xoBlocks.page_bottomright}>
            <div class="row">
                <div class="span<{$col_span}> xo-bottom">
                    <div class="row">
                        <div class="span<{$col_span}> xo-center">
                            <!-- Start center-center blocks loop -->
                            <{foreach item=block from=$xoBlocks.page_bottomcenter}>
                            <{include file="$theme_tpl/block_center_c.html"}>
                            <{/foreach}>
                            <!-- End center-center blocks loop -->
                        </div>
                    </div>
                    <div class="row">
                        <div class="span<{$col_span_mid}> xo-left">
                            <!-- Start center-left blocks loop -->
                            <{foreach item=block from=$xoBlocks.page_bottomleft}>
                            <{include file="$theme_tpl/block_center_l.html"}>
                            <{/foreach}>
                            <!-- End center-left blocks loop -->
                        </div>
                        <{if $col_span_mid == 4}><div class="span1">&nbsp;</div><{/if}>
                        <div class="span<{$col_span_mid}> xo-right">
                            <!-- Start center-right blocks loop -->
                            <{foreach item=block from=$xoBlocks.page_bottomright}>
                            <{include file="$theme_tpl/block_center_r.html"}>
                            <{/foreach}>
                            <!-- End center-right blocks loop -->
                        </div>
                    </div>
                </div>
            </div>
            <{/if}>
        </div>
        <{if $xoops_showrblock}>
        <div class="span3 xo-block-right">
            <{foreach item=block from=$xoBlocks.canvas_right}>
            <{include file="$theme_tpl/block_right.html"}>
            <{/foreach}>
        </div>
        <{/if}>
    </div>
</div>
<footer>
    <div class="container">
        <div class="row">
            <div class="container">
                <div class="span12">
                    <p class="pull-right"><a href="#"><{translate key='BACK_TO_TOP'}></a></p>
                    <div class="pagination-centered">
                        <{$xoops_footer}>
                    </div>
                    <!--{xo-logger-output}-->
                </div>
            </div>
        </div>
    </div>
</footer>
</body>
</html>