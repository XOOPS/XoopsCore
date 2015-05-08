<{if $type == 'horizontal'}>
<form class="form-horizontal" name="<{$name}>" id="<{$name}>" action="<{$action}>" method="<{$method}>" onsubmit="return xoopsFormValidate_<{$name}>();"<{$extra}>>
    <fieldset>
        <{if $title != ''}>
        <legend><{$title}></legend>
        <{/if}>
        <{foreach item=input from=$xo_input|default:[]}>
            <{if $input.datalist != ''}>
                <{$input.datalist}>
            <{/if}>
            <div class="control-group">
                <label class="control-label"><{$input.caption}><{if $input.required}><span class="caption-required">*</span><{/if}></label>
                <div class="controls">
                    <{$input.ele}>
                    <span class="dsc_pattern_horizontal"><{$input.pattern_description}></span>
                    <{if $input.description != ''}>
                    <p class="help-block"><{$input.description}></p>
                    <{/if}>
                </div>
            </div>
        <{/foreach}>
        <{$hidden}>
    </fieldset>
</form>
<{/if}>
<{if $type == 'vertical'}>
<form class="form-vertical" name="<{$name}>" id="<{$name}>" action="<{$action}>" method="<{$method}>" onsubmit="return xoopsFormValidate_<{$name}>();"<{$extra}>>
    <fieldset>
        <{if $title != ''}>
        <legend><{$title}></legend>
        <{/if}>
        <{foreach item=input from=$xo_input|default:[]}>
            <{if $input.datalist != ''}>
                <{$input.datalist}>
            <{/if}>
            <label><{$input.caption}><{if $input.required}><span class="caption-required">*</span><{/if}></label>
            <{$input.ele}>
            <{if $input.description != ''}>
            <span class="help-inline"><{$input.description}></span>
            <{/if}>
            <p class="dsc_pattern_vertical"><{$input.pattern_description}></p>
        <{/foreach}>
        <{$hidden}>
    </fieldset>
</form>
<{/if}>

<{if $type == 'inline'}>
<form class="well form-inline" name="<{$name}>" id="<{$name}>" action="<{$action}>" method="<{$method}>" onsubmit="return xoopsFormValidate_<{$name}>();"<{$extra}>>
    <fieldset>
        <{if $title != ''}>
        <legend><{$title}></legend>
        <{/if}>
        <{foreach item=input from=$xo_input|default:[]}>
            <{if $input.datalist != ''}>
                <{$input.datalist}>
            <{/if}>
            <{if $input.caption}>
            <label><{$input.caption}><{if $input.required}><span class="caption-required">*</span><{/if}></label>
            <{/if}>
            <{$input.ele}>
            <{if $input.description != ''}>
            <span class="help-inline"><{$input.description}></span>
            <{/if}>
        <{/foreach}>
        <{$hidden}>
    </fieldset>
</form>
<{/if}>
<{$validationJS}>