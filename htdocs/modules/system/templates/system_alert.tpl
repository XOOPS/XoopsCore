<div class="alert {$alert_type} alert-dismissible" role="alert">
    <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
    {if $alert_title|default:false}
    <h4>{$alert_title}</h4>
    {/if}
    {$alert_msg}
</div>
