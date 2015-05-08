<script type="text/javascript" src="<{xoImgUrl 'modules/xlanguage/media/jquery/jquery.language/jquery.language.js'}>"></script>
<link rel="stylesheet" href="<{xoImgUrl 'modules/xlanguage/media/jquery/jquery.language/jquery.language.css'}>" type="text/css" media="screen" />

<style>
<{foreach from=$block.languages name=lang_if item=lang}>
li.<{$lang.xlanguage_code}> a span {
background-image: url('<{$lang.xlanguage_image}>') !important;
background-size: 24px 24px;
}
.<{$lang.xlanguage_code}> a span {
background-image: url('<{$lang.xlanguage_image}>') !important;
background-size: 24px 24px;
}

<{/foreach}>
</style>

<div id="country-select">
    <form action="">
        <select id="country-options" name="country-options">
            <{foreach from=$block.languages name=lang_if item=lang}>
                <option value="<{$lang.xlanguage_code}>" title="<{$block.url}><{$lang.xlanguage_name}>"><{$lang.xlanguage_description}></option>
            <{/foreach}>
        </select>
    </form>
</div>