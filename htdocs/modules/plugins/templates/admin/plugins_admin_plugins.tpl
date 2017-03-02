<{include file="admin:system/admin_navigation.tpl"}>
<{include file="admin:system/admin_tips.tpl"}>
<{include file="admin:system/admin_buttons.tpl"}>

<{$infoMsg|default:''}>
<{$errorMsg|default:''}>

<{if $pluginsCount|default:false}>
<h4><{translate key="PLUGINS_MANAGER" dirname='plugins'}></h4>
    <table id="plugins-list-sorter" class="table table-bordered">
    <thead>
        <tr>
            <th class="txtcenter width10"><{translate key="OBJECT_PLUGIN_CALLER" dirname='plugins'}><{$callersForm}></th>
            <th class="txtcenter width10"><{translate key="OBJECT_PLUGIN_LISTENER" dirname='plugins'}><{$listenersForm}></th>
            <th class="txtcenter width5"><{translate key="OBJECT_PLUGIN_STATUS" dirname='plugins'}></th>
            <th class="txtcenter width5"><{translate key="OBJECT_PLUGIN_ORDER" dirname='plugins'}></th>
        </tr>
    </thead>
    </table>
    <form name="plugins-list-form" id="plugins-list-form" action="plugins.php" method="post">
        <table id="plugins-list" class="table table-bordered">
            <tbody>
                <{foreach item=$plugin from=$plugins}>
                <tr class="<{if $plugin.plugin_status == 0}>alert alert-danger<{else}>alert alert-success<{/if}>">
                    <td class="txtcenter width10"><{$plugin.plugin_caller_name}></td>
                    <td class="txtcenter width10"><{$plugin.plugin_listener_name}></td>
                    <td class="txtcenter width5"><{$plugin.plugin_status_field}></td>
                    <td class="txtcenter width5"><{$plugin.plugin_order_field}></td>
                </tr>
                <{/foreach}>
            </tbody>
        </table>
        <{$hiddenFields}>
        <{$submitButton}>
    </form>
<{/if}>
