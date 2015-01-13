<{if $form}>
    <{$form}>
<{else}>
    <div class="txtcenter"><h4><{$message}></h4></div>;
<{/if}>
<{if $closebutton}>
    <br />
    <div class="txtcenter">
        <input class="btn btn-primary" value="<{translate key='A_CLOSE'}>" type="button" onclick="javascript:window.close();" />
    </div>
<{/if}>
