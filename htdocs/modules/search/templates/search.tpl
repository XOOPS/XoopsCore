<fieldset>
    <{if $search|default:false}>
        <legend><{$smarty.const._MD_SEARCH_SEARCHRESULTS}></legend>
        <div>
            <{$smarty.const._MD_SEARCH_KEYWORDS}>&nbsp;:&nbsp;
            <span class="bold">
            <{foreach from=$queries item=query name=foo}>
                <{$query}><{if !$smarty.foreach.foo.last}>,<{/if}>
            <{/foreach}>
            </span>
            <{if $sr_showing|default:false}>
                <br />
                <{$sr_showing}>
            <{/if}>
        </div>
        <{if count($ignored_queries) != 0}>
            <div>
                <{$ignored_words}>&nbsp;:&nbsp;
                <span class="bold">
                <{foreach from=$ignored_queries item=query name=foo}>
                    <{$query}><{if !$smarty.foreach.foo.last}>,<{/if}>
                <{/foreach}>
                </span>
            </div>
        <{/if}>
    <{/if}>

    <{if count($modules) > 0}>
        <{foreach from=$modules item=module name=foo}>
            <div class="searchModule">
                <div class="searchIcon floatleft">
                    <img src="<{$module.image}>" alt="<{$module.name}>">
                </div>
                <div class="searchTitle floatleft">
                    <{$module.name}>
                </div>
                <{if $module.search_url|default:false}>
                    <div class="floatright">
                        <a href="<{$module.search_url}>" title="<{$smarty.const._MD_SEARCH_SHOWALLR}>"><{$smarty.const._MD_SEARCH_SHOWALLR}></a>
                    </div>
                <{/if}>

                <{if $module.showall_link|default:false}>
                    <div class="floatright">
                        <{$module.showall_link}>
                    </div>
                <{/if}>
                <div class="clear"></div>

                <{foreach from=$module.result item=result}>
                    <div class="searchItem">
                        <div class="bold"><a href="<{$result.link}>" title="<{$result.title}>"><{$result.title_highligh}></a></div>
                        <div><{$result.content|default:''}></div>
                        <span class='x-small'>
                            <{if $result.uid}>
                               <a href="<{$xoops_url}>/userinfo.php?uid=<{$result.uid}>" title="<{$result.uname}>"><{$result.uname}></a>
                            <{/if}>
                            <{if $result.time}>
                                &nbsp;(<{$result.time}>)
                            <{/if}>
                        </span>
                    </div>
                <{/foreach}>
            </div>

            <!-- prev / next -->
            <{if $module.prev|default:false || $module.next|default:false}>
                <div>
                    <{if $module.prev|default:false}>
                        <div class="floatleft">
                            <a href="<{$module.prev}>" title="<{$smarty.const._MD_SEARCH_PREVIOUS}>"><{$smarty.const._MD_SEARCH_PREVIOUS}></a>
                        </div>
                    <{/if}>
                    <{if $module.next|default:false}>
                        <div class="floatright">
                            <a href="<{$module.next}>" title="<{$smarty.const._MD_SEARCH_NEXT}>"><{$smarty.const._MD_SEARCH_NEXT}></a>
                        </div>
                    <{/if}>
                </div>
            <{/if}>
        <{/foreach}>
    <{else}>
        <div class="searchModule bold">
            <{$smarty.const._MD_SEARCH_NOMATCH}>
        </div>
    <{/if}>
</fieldset>