<!DOCTYPE html>
<html lang="{$xoops_langcode}">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- Title and meta -->
    <meta http-equiv="content-type" content="text/html; charset={$xoops_charset}" />
    <title>{if $xoops_pagetitle !=''}{$xoops_pagetitle} - {/if}{$xoops_sitename}</title>
    <meta name="robots" content="{$xoops_meta_robots}" />
    <meta name="keywords" content="{$xoops_meta_keywords}" />
    <meta name="description" content="{$xoops_meta_description}" />
    <meta name="rating" content="{$xoops_meta_rating}" />
    <meta name="author" content="{$xoops_meta_author}" />
    <meta name="generator" content="XOOPS" />

    <!-- Rss -->
    <link rel="alternate" type="application/rss+xml" title="" href="{xoAppUrl 'backend.php'}" />

    <!-- Favicon -->
    <link rel="shortcut icon" type="image/ico" href="{xoImgUrl 'assets/img/favicon.ico'}" />
    <link rel="icon" type="image/png" href="{xoImgUrl 'assets/img/favicon.png'}" />

    <!-- Xoops and theme style sheets -->
    <link rel="stylesheet" type="text/css" media="screen" href="{xoAppUrl 'media/xoops/css/icons.css'}" />

    <!-- customized header contents -->
    {$xoops_module_header}

</head>
<body id="{$xoops_dirname}" class="{$xoops_langcode}" role="document">
{include file="$theme_tpl/nav-menu.tpl"}

<!-- { include file="$theme_tpl/slider.tpl" } -->

<div class="container maincontainer">

    <div class="row">
        {include file="$theme_tpl/leftBlock.tpl"}

        {include file="$theme_tpl/content-zone.tpl"}

        {include file="$theme_tpl/rightBlock.tpl"}
    </div>

</div><!-- .maincontainer -->

{if $xoBlocks.page_bottomcenter || $xoBlocks.page_bottomright || $xoBlocks.page_bottomleft}
    <div class="bottom-blocks">
        <div class="container">
            <div class="row">
                {include file="$theme_tpl/leftBottom.tpl"}

                {include file="$theme_tpl/centerBottom.tpl"}

                {include file="$theme_tpl/rightBottom.tpl"}
            </div>
        </div>
    </div><!-- .bottom-blocks -->
    {/if}

<footer class="footer">
    <div class="aligncenter">
        {$xoops_footer}
        <a href="https://xoops.org" title="Design by: XOOPS UI/UX Team" target="_blank" class="credits visible-md visible-sm visible-lg">
            <img src="{xoImgUrl}assets/img/favicon.png" alt="Design by: XOOPS UI/UX Team">
        </a>
    </div>
</footer>
</body>
</html>
