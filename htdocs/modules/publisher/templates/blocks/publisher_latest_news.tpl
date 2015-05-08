<{if $block.template == 'normal'}><{if $block.latestnews_scroll }>
<marquee behavior='scroll' align='center' direction='<{$block.scrolldir}>' height='<{$block.scrollheight}>' scrollamount='3' scrolldelay='<{$block.scrollspeed}>' onmouseover='this.stop()' onmouseout='this.start()'>
    <{/if}> <{section name=i loop=$block.columns}>
    <ul>
        <{foreach item=item from=$block.columns[i]}>
        <li><{if $item.posttime }>[ <{$item.posttime}> ]:<{/if}> <{$item.topic_title}> <{$item.title}></li>
        <{/foreach}>
    </ul>
    <{/section}> <{if $block.latestnews_scroll }>
</marquee><{/if}><{/if}>

<{if $block.template == 'extended'}>

<{php}>global $xoTheme;$xoTheme->addStylesheet(PUBLISHER_URL . '/css/publisher.css');<{/php}>

<{if $block.latestnews_scroll }>
<marquee behavior='scroll' align='center' direction='<{$block.scrolldir}>' height='<{$block.scrollheight}>' scrollamount='3' scrolldelay='<{$block.scrollspeed}>' onmouseover='this.stop()' onmouseout='this.start()'>
    <{/if}>

    <table width='100%' border='0'>
        <tr>
            <{section name=i loop=$block.columns}>
            <td width="<{$block.spec.columnwidth}>%">
                <{foreach item=item from=$block.columns[i]}>

                <div class="itemHead"><{$item.admin}>
                    <span class="itemTitle"><{$item.topic_title}><{$item.title}></span>
                </div>
                <{if $block.poster or $item.posttime or $item.read }>
                <div class="itemInfo">
                    <span class="itemPoster"><{$item.poster}></span>
                    <span class="itemPostDate"><{$item.posttime}><{$item.read}></span>
                </div>
                <{/if}>
                <{$item.image}>
                <{if $block.letters != 0}>
                <div style="text-align:justify; padding:5px">
                    <{$item.text}>
                    <div style="clear:both;"></div>
                </div>
				<{/if}>
                <div class="itemFoot">
                    <span class="itemPermaLink"><{$item.more}><{$item.comment}><{$item.print}><{$item.pdf}><{$item.email}></span>
                </div>

                <{/foreach}>
            </td>
            <{/section}>
        </tr>
    </table>

    <{if $block.latestnews_scroll }>
</marquee><{/if}>

<div><{$block.morelink}><{$block.topiclink}><{$block.archivelink}><{$block.submitlink}></div><{/if}>

<{if $block.template == 'ticker'}>
<marquee behavior='scroll' align='middle' direction='<{$block.scrolldir}>' height='<{$block.scrollheight}>' scrollamount='3' scrolldelay='<{$block.scrollspeed}>' onmouseover='this.stop()' onmouseout='this.start()'>
    <{section name=i loop=$block.columns}>
    <div style="padding:10px">
        <{foreach item=item from=$block.columns[i]}> &nbsp;<{$item.title}>&nbsp; <{/foreach}>
    </div>
    <{/section}>
</marquee><{/if}>

<{if $block.template == 'slider1'}>

<{php}>global $xoTheme;$xoTheme->addScript('media/jquery/jquery.js');$xoTheme->addStylesheet(PUBLISHER_URL . '/css/publisher.css');<{/php}>

<script type="text/javascript">
    jQuery(document).ready(function()
    {

        //Execute the slideShow, set 4 seconds for each images
        slideShow(5000);

    });

    function slideShow(speed)
    {


        //append a LI item to the UL list for displaying caption
        $('ul.pub_slideshow1').append('<LI id=pub_slideshow1-caption class=caption><DIV class=pub_slideshow1-caption-container><H3></H3><P></P></DIV></LI>');

        //Set the opacity of all images to 0
        $('ul.pub_slideshow1 li').css({opacity: 0.0});

        //Get the first image and display it (set it to full opacity)
        $('ul.pub_slideshow1 li:first').css({opacity: 1.0});

        //Get the caption of the first image from REL attribute and display it
        $('#pub_slideshow1-caption h3').html($('ul.pub_slideshow1 a:first').find('img').attr('title'));
        $('#pub_slideshow1-caption p').html($('ul.pub_slideshow1 a:first').find('img').attr('alt'));

        //Display the caption
        $('#pub_slideshow1-caption').css({opacity: 0.7, bottom:0});

        //Call the gallery function to run the slideshow
        var timer = setInterval('gallery()', speed);

        //pause the slideshow on mouse over
        $('ul.pub_slideshow1').hover(
                function ()
                {
                    clearInterval(timer);
                },
                function ()
                {
                    timer = setInterval('gallery()', speed);
                }
                );

    }

    function gallery()
    {


        //if no IMGs have the show class, grab the first image
        var current = ($('ul.pub_slideshow1 li.show') ? $('ul.pub_slideshow1 li.show') : $('#ul.pub_slideshow1 li:first'));

        //Get next image, if it reached the end of the slideshow, rotate it back to the first image
        var next = ((current.next().length) ? ((current.next().attr('id') == 'pub_slideshow1-caption') ? $('ul.pub_slideshow1 li:first') : current.next()) : $('ul.pub_slideshow1 li:first'));

        //Get next image caption
        var title = next.find('img').attr('title');
        var desc = next.find('img').attr('alt');

        //Set the fade in effect for the next image, show class has higher z-index
        next.css({opacity: 0.0}).addClass('show').animate({opacity: 1.0}, 1000);

        //Hide the caption first, and then set and display the caption
        $('#pub_slideshow1-caption').animate({bottom:-70}, 300, function ()
        {
            //Display the content
            $('#pub_slideshow1-caption h3').html(title);
            $('#pub_slideshow1-caption p').html(desc);
            $('#pub_slideshow1-caption').animate({bottom:0}, 500);
        });

        //Hide the current image
        current.animate({opacity: 0.0}, 1000).removeClass('show');

    }
</script>

<{section name=i}>

<ul class="pub_slideshow1">
    <{foreach item=item from=$block.columns[i]}>
    <li>
        <a href="<{$item.itemurl}>"><img src="<{$item.item_image}>" width="100%" height="<{$block.imgheight}>" title="<{$item.alt}>" alt="<{$item.text}>" /></a>
    </li>
    <{/foreach}>
</ul><{/section}>

<{/if}>

<{if $block.template == 'slider2'}>

<{php}>global $xoTheme;$xoTheme->addScript('media/jquery/jquery.js');$xoTheme->addStylesheet(PUBLISHER_URL . '/css/publisher.css');$xoTheme->addScript(PUBLISHER_URL . '/js/jquery.easing.js');$xoTheme->addScript(PUBLISHER_URL . '/js/script.easing.js');<{/php}>

<script type="text/javascript">
    jQuery(document).ready(function()
    {
        jQuery('#lofslidecontent45').lofJSidernews({interval:4000,
            direction:'opacity',
            duration:1000,
            easing:'easeInOutSine'});
    });

</script>

<{section name=i}>
<div id="lofslidecontent45" class="lof-slidecontent">

    <div class="lof-main-outer">
        <ul class="lof-main-wapper">
            <{foreach item=item from=$block.columns[i]}>
            <li>
                <img src="<{$item.item_image}>" alt="<{$item.alt}>" width="<{$block.imgwidth}>" height="<{$block.imgheight}>" />
            </li>
            <{/foreach}>
        </ul>
    </div>

    <div class="lof-navigator-outer">
        <ul class="lof-navigator">
            <{foreach item=item from=$block.columns[i]}>
            <li>
                <div>
                    <img src="<{$item.item_image}>" alt="" width="60" height="60" />
                    <h3><a href="<{$item.itemurl}>"> <{$item.alt}> </a></h3>
                </div>
            </li>
            <{/foreach}>
        </ul>
    </div>
</div>
<script type="text/javascript">

</script><{/section}>

<{/if}>
