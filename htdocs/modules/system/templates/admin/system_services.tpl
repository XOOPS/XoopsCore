<script>
$(function() {
    $( "#accordion" )
        .accordion({
            header: "> div > h3",
            active: false,
            collapsible: true,
            heightStyle: "content"
        })
        .sortable({
            axis: "y",
            handle: "h3",
            stop: function( event, ui ) {
                // IE doesn't register the blur when sorting
                // so trigger focusout handlers to remove .ui-state-focus
                ui.item.children( "h3" ).triggerHandler( "focusout" );
            }
        });
    var request;
    $('#saveseq').on('click', function (e) {
        var sortedList = $("#accordion");
        $(sortedList).sortable();
        var orderedData = $(sortedList).sortable('serialize');
        request = $.ajax({
            url: "?fct=services",
            type: "post",
            data: 'op=order&service=<{$selected_service}>&token=<{$token|default:''}>&'+orderedData
        });

        // callback handler that will be called on success
        request.done(function (response, textStatus, jqXHR){
            // log a message to the console
            //window.location.href = "admin.php?fct=services";
            $.jGrowl("Provider preferences saved", {  life:3000 , position: "center", speed: "slow" });
        });

        // callback handler that will be called on failure
        request.fail(function (jqXHR, textStatus, errorThrown){
            console.error(
                "The following error occured: "+
                textStatus, errorThrown
            );
            $.jGrowl("Error during save: " + textStatus, {  life:3000 , position: "center", speed: "slow" });
        });
    });
});
</script>

<{include file="admin:system/admin_breadcrumb.tpl"}>
<{include file="admin:system/admin_tips.tpl"}>

<div class="row">
    <div class="span3">
    <h4>Services</h4>
    <ul class="nav nav-pills nav-stacked">
    <{foreach item=service from=$service_list}>
    <{if $service.active}>
    <li class="active"><a href="?fct=services&service=<{$service.name}>"><{$service.display}></a></li>
    <{else}>
    <li><a href="?fct=services&service=<{$service.name}>"><{$service.display}></a></li>
    <{/if}>
    <{/foreach}>
    </ul>
    </div>

<div class="span8">
<{$message|default:''}>
<{if isset($provider_list) }>
<script>
setTimeout(function () {
   window.location.href = "admin.php?fct=services";
}, 900000); //will redirect after 900 secs
</script>
    <div class="span4 well">
    <h4>Providers</h4>
    <p><em>Drag and Drop</em> to change priority.</p>
    <div id="accordion">
    <{foreach item=provider from=$provider_list}>
    <div class="group" id="<{$selected_service}>_<{$provider.name}>">
    <h3><{$provider.name}></h3>
    <div><{$provider.description}></div>
    </div>
    <{/foreach}>
    </div>
    <div><br /><br />
    <button id="saveseq" type="button" class="btn btn-primary">Save</button>
    </div>
    </div>
<{/if}>
</div>
</div>