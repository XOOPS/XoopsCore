<style>
<{foreach from=$block.languages name=lang_if item=lang}>
.ico-<{$lang.xlanguage_name}> {
    display: inline-block;
    background: url('<{$lang.xlanguage_image}>') no-repeat 0 0;
    background-size: 16px 16px;
    margin-right: 0.5em;
    vertical-align: text-top;
}
<{/foreach}>
</style>

<ul class="nav pull-right">
    <li class="divider-vertical"></li>
    <li class="dropdown">
        <a class="dropdown-toggle" data-toggle="dropdown" href="#"><i class="ico-language"></i>&nbsp;<{translateTheme key='CHOOSE_YOUR_LANGUAGE'}><i class="caret"></i></a>
        <ul class="dropdown-menu">
            <{foreach from=$block.languages name=lang_if item=lang}>
                <li>
                    <a href="<{$block.url}><{$lang.xlanguage_name}>" title="<{$lang.xlanguage_description}>"><i class="ico-<{$lang.xlanguage_name}>"></i><{$lang.xlanguage_description}></a>
                </li>
            <{/foreach}>
        </ul>
    </li>
</ul>
<script type="text/javascript">
    $('.dropdown-toggle').dropdown();
</script>
