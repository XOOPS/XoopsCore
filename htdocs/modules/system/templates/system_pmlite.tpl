<div style="margin: 20px;">
    <{if $form|default:false}>
        <{$form}>
    <{/if}>
    <{if $error_message|default:false}>
    <div class="alert alert-error" style="text-align:center;">
        <strong><{$error_message}></strong>
    </div>
    <{/if}>
    <{if $info_message|default:false}>
    <div class="alert alert-info" style="text-align:center;">
        <strong><{$info_message}></strong>
    </div>
    <{/if}>
</div>