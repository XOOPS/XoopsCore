<{includeq file="admin:system|admin_navigation.tpl"}>
<{includeq file="admin:system|admin_tips.tpl"}>
<{includeq file="admin:system|admin_buttons.tpl"}>
<{if $errors}>
<div class="alert alert-error">
    <{$errors}>
</div>
<{/if}>
<{if $sucess}>
<div class="alert alert-success">
    <{$sucess}>
</div>
<{/if}>
<{$form}>
