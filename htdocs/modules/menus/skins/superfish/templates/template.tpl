<{foreach item=menu from = $block}>
    <{if $menu.oul}>
        <{if $menu.level == 0}>
        <ul class="sf-menu">
        <{else}>
        <ul>
        <{/if}>
    <{/if}>
    
    <{if $menu.oli}>
        <li<{if $menu.selected}> class="current"<{/if}><{if $menu.css}> style="<{$menu.css}>"<{/if}>>
    <{/if}>
            <a href="<{$menu.link}>" target="<{$menu.target}>" alt="<{$menu.alt_title}>" title="<{$menu.alt_title}>">
            <{if $main.image}><img src="<{$menu.image}>" /><{/if}>
            <{$menu.title}>
            </a>
    <{if $menu.close != ''}><{$menu.close}><{/if}>
<{/foreach}>
