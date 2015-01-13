<table class="xo-moduleadmin-about">
    <tr>
        <td class="width45" valign="top">
            <table>
                <tr>
                    <td class="xo-module-img" valign="top">
                        <img src="<{$xoops_url}>/modules/<{$module->getVar('dirname')}>/<{$module->getInfo('image')}>" alt="<{$module->getVar('name')}>" title="<{$module->getVar('name')}>" />
                    </td>
                    <td>
                        <div class="spacer xo-module-name"><{$module->getVar('name')}>&nbsp;<{$module->getInfo('version')}>&nbsp;<{$module->getInfo('module_status')}>&nbsp;(<{$module->getInfo('release_date')}>)</div>
                        <div class="spacer marg5 bold"><{$module->getInfo('author_list')}></div>
                        <{if $module->getInfo('license_url')}>
                        <div class="spacer marg5"><a href="<{$module->getInfo('license_url')}>" target="_blank"><{$module->getInfo('license')}></a></div>
                        <{/if}>
                        <{if $module->getInfo('website')}>
                        <div class="spacer marg5"><a href="http://<{$module->getInfo('website')}>" target="_blank"><{$module->getInfo('website')}></a></div>
                        <{/if}>
                        <{if $module->getInfo('paypal')}>
                            <form id="paypal-form" name="_xclick" action="https://www.paypal.com/fr/cgi-bin/webscr" method="post">
                                <input type="hidden" name="cmd" value="_xclick">
                                <{foreach from=$module->getInfo('paypal') item=value key=key}>
                                        <{if is_numeric($value)}>
                                    <input type="hidden" name="<{$key}>" value=<{$value}>>
                                        <{else}>
                                    <input type="hidden" name="<{$key}>" value="<{$value}>">
                                    <{/if}>
                                <{/foreach}>
                                <img src="https://www.paypal.com/en_US/i/btn/btn_donate_LG.gif" onclick="$('#paypal-form').submit()" alt="PayPal - The safer, easier way to pay online!" />
                            </form>
                        <{/if}>
                        <{include file="admin:system/admin_infobox.tpl" class="width100"}>
                    </td>
                </tr>
            </table>
        </td>
        <td class="width2"></td>
        <td class="width45">
            <div class="xo-moduleadmin-infobox outer">
                <div class="xo-window">
                    <div class="xo-window-title">
                        <span class="ico ico-page-white-gear"></span>&nbsp;<{translate key="CHANGE_LOG"}>
                    </div>
                    <div class="xo-window-data">
                        <div class="txtchangelog">
                            <{$module->getInfo('changelog')}>
                        </div>
                    </div>
                </div>
                <div class="clear"></div>
            </div>
        </td>
    </tr>
</table>
<{if $xoops_logo}>
<br />
<div align="center">
    <a href="http://www.xoops.org" target="_blank">
        <img src="<{$xoops_url}>/media/xoops/images/xoopsmicrobutton.gif" alt="XOOPS" title="XOOPS">
   </a>
</div>
<{/if}>
