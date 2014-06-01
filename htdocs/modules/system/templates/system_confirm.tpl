<div class="alert alert-error" style="text-align: center">
    <{$msg}>
    <br />
    <form name="confirm" id="confirm" action="<{$action}>" method="post">
        <{$hiddens}>
        <{$token}>
        <input class="btn btn-danger" type="submit" name="confirm_submit" value="<{$submit}>" title="<{$submit}>">
        <input class="btn" type="button" name="confirm_back" value="<{translate key='A_CANCEL'}>" onclick="javascript:history.go(-1);" title="<{translate key='A_CANCEL'}>">

    </form>
</div>