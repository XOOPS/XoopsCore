{if $type != 'inline'}
<form name="{$name}" id="{$name}" action="{$action}" method="{$method}" onsubmit="return xoopsFormValidate_{$name}();" {$extra} >
    {if $title != ''}<h3>{$title}</h3>{/if}
    {foreach item=input from=$xo_input|default:[]}
        {if $input.datalist != ''}{$input.datalist}{/if}
        <div class="form-group">
            <label>{$input.caption}{if $input.required}<span class="caption-required">*</span>{/if}</label>
            {$input.ele}
            <small class="text-muted">{$input.description}</small>
            <p class="dsc_pattern_vertical">{$input.pattern_description}</p>
        </div>
    {/foreach}
    {$hidden}
</form>
{else}
<form class="well form-inline" name="{$name}" id="{$name}" action="{$action}" method="{$method}" onsubmit="return xoopsFormValidate_{$name}();"{$extra}>
    <fieldset>
        {if $title != ''}
        <legend>{$title}</legend>
        {/if}
        {foreach item=input from=$xo_input|default:[]}
            {if $input.datalist != ''}
                {$input.datalist}
            {/if}
            {if $input.caption}
            <label>{$input.caption}{if $input.required}<span class="caption-required">*</span>{/if}</label>
            {/if}
            {$input.ele}
            {if $input.description != ''}
            <span class="help-inline">{$input.description}</span>
            {/if}
        {/foreach}
        {$hidden}
    </fieldset>
</form>
{/if}
{$validationJS}
