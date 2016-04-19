<div class="alert alert-block {$alert_type}" role="alert">
    <button type="button" class="close" data-dismiss="alert">&times;</button>
    {if $alert_title|default:false}
    <h4>{$alert_title}</h4>
    {/if}
    {$alert_msg}
</div>
