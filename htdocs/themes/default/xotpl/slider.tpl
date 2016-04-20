{if $xoops_dirname == "system"}
<div id="myCarousel" class="carousel slide slideshow" data-ride="carousel">
<!-- Indicators -->
<ol class="carousel-indicators">
<li class="active" data-slide-to="0" data-target="#myCarousel"></li>
<li data-slide-to="1" data-target="#myCarousel" class=""></li>
</ol>
<div class="carousel-inner">
<div class="item active"> <img alt="XOOPS" src="{$xoops_imageurl}images/slider1.jpg">
    <div class="carousel-caption hidden-xs">
      <h1>Lorem ipsum dolor sit amet</h1>
      <p>Cras justo odio, dapibus ac facilisis in, egestas eget quam. Donec id elit non mi porta gravida at eget metus. Nullam id dolor id nibh ultricies vehicula ut id elit.</p>
      <p><a href="javascript:;" class="btn btn-large btn-primary">{$smarty.const.THEME_READMORE}</a></p>
    </div>
</div>
<div class="item"> <img alt="XOOPS" src="{$xoops_imageurl}images/slider2.jpg">
    <div class="carousel-caption hidden-xs">
      <h1>Lorem ipsum dolor sit amet</h1>
      <p>Cras justo odio, dapibus ac facilisis in, egestas eget quam. Donec id elit non mi porta gravida at eget metus. Nullam id dolor id nibh ultricies vehicula ut id elit.</p>
      <p><a href="javascript:;" class="btn btn-large btn-primary">{$smarty.const.THEME_READMORE}</a></p>
    </div>
</div>
</div>
<a class="left carousel-control" href="#myCarousel" data-slide="prev"><span class="icon-prev"></span></a>
<a data-slide="next" href="#myCarousel" class="right carousel-control"><span class="icon-next"></span></a>
</div><!-- .carousel -->
{/if}
