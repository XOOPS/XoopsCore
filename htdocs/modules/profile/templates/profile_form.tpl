
    <{$xoForm.javascript}>
    <form id="<{$xoForm.name}>" name="<{$xoForm.name}>" action="<{$xoForm.action}>" method="<{$xoForm.method}>" <{$xoForm.extra}> >
        <table class="profile-form" id="profile-form-<{$xoForm.name}>">
            <{foreach item=element from=$xoForm.elements}>
                <{if !$element.hidden}>
                    <tr>
                        <td class="head">
				            <div class='xoops-form-element-caption<{if $element.required}>-required<{/if}>'>
				                <span class='caption-text'><{$element.caption}></span>
				                <span class='caption-marker'>*</span>
				            </div>
                            <{if $element.description != ""}>
                                <div class='xoops-form-element-help'><{$element.description}></div>
                            <{/if}>
                        </td>
                        <td class="<{cycle values='odd, even'}>">
                            <{$element.body}>
                        </td>
                    </tr>
                <{/if}>
            <{/foreach}>
        </table>
        <{foreach item=element from=$xoForm.elements}>
            <{if $element.hidden}>
                <{$element.body}>
            <{/if}>
        <{/foreach}>
    </form>
