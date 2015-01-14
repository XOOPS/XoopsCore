<{$profile_breadcrumbs}>

<{if $stop|default:false}>
    <{$stop}>
<{/if}>

<{include file="module:profile/profile_form.tpl" xoForm=$userinfo}>
