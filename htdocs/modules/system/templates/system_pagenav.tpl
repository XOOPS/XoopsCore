<{if $pagination_nav}>
<div class="pagination<{$size}><{$align}>">
    <ul>
        <{if $prev_text|default:false}>
        <li><a href="<{$prev_url}>"><{$prev_text}></a></li>
        <{/if}>
        <{if $first == 1}>
            <li>
                <a href="<{$first_url}>"><{$first_text}></a>
            </li>
        <{/if}>
        <{foreach item=nav from=$xo_nav}>
            <li <{if $nav.active == 0}>class="disabled"<{/if}>>
                <a href="<{$nav.url}>"><{$nav.text}></a>
            </li>
        <{/foreach}>
        <{if $last == 1}>
            <li>
                <a href="<{$last_url}>"><{$last_text}></a>
            </li>
        <{/if}>
        <{if $next_text}>
        <li><a href="<{$next_url}>"><{$next_text}></a></li>
        <{/if}>
    </ul>
</div>
<{/if}>
<{if $pagination_select|default:false}>
<div class="pagination<{$align}>">
    <form class="form-inline" action="" name="pagenavform">
        <{if $showbutton == 1}>
        <div class="input-append">
        <{/if}>
            <select class="input-small" id="appendedSelectButton" name="pagenavselect" onchange="<{$onchange}>">
                <{foreach item=select from=$xo_select}>
                    <option value="<{$select.value}>" <{if $select.selected == 1}>selected="selected"<{/if}>><{$select.text}></option>
                <{/foreach}>
            </select>
            <{if $showbutton == 1}>
            <button class="btn" type="submit"><{$smarty.const._GO}></button>
        </div>
        <{/if}>
    </form>
</div>
<{/if}>