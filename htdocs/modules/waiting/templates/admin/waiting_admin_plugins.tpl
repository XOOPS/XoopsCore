{include file="admin:system/admin_navigation.tpl"}
{include file="admin:system/admin_tips.tpl"}
{include file="admin:system/admin_buttons.tpl"}

{if $count|default:false}
<h4>{translate key="PLUGINS" dirname='waiting'}</h4>
    <table id="plugins-list-sorter" class="table table-bordered">
    <thead>
        <tr>
            <th class="txtcenter width10">{translate key="PLUGIN_MODULE_NAME" dirname='waiting'}</th>
            <th class="txtcenter width10">{translate key="PLUGIN_MODULE_DIR_NAME" dirname='waiting'}</th>
            <th class="txtcenter width5">{translate key="PLUGIN_WAITING_ITEMS" dirname='waiting'}</th>
        </tr>
    </thead>

    <tbody>
        {foreach item=$content from=$contents}
        <tr>
            <td class="txtcenter width10">{$content.pluginName}</td>
            <td class="txtcenter width10">{$content.pluginDirName}</td>
            <td class="txtcenter width5">
                {if $content.pluginItems.count}
                    <a href="{$content.pluginItems.link}">{$content.pluginItems.name} ({$content.pluginItems.count})</a>
                {else}
                    {translate key="PLUGIN_NO_RESULTS" dirname='waiting'}
                {/if}
            </td>
        </tr>
        {/foreach}
    </tbody>
</table>
{else}
    <p class="alert alert-warning">{translate key="NO_PLUGINS_FOUND" dirname='waiting'}</p>
{/if}
