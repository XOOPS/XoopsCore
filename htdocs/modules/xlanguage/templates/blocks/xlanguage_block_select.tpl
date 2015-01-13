<select name="selectlang" onChange="if(this.options[this.selectedIndex].value.length >0 )    { window.document.location=this.options[this.selectedIndex].value;}">
    <{foreach from=$block.languages name=lang_if item=lang}>
        <option value="<{$block.url}><{$lang.xlanguage_name}>">
            <{if $block.selected == $lang.xlanguage_name}>
                selected
            <{/if}>
            >
            <{$lang.xlanguage_description}>
        </option>
    <{/foreach}>
</select>
