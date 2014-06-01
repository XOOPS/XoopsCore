<div class="xo-block-online">
    <ul class="nav nav-list">
        <li class="nav-header">
            <{$block.online_total}>
        </li>
        <li>
            <i class="icon-user"></i>
            <{$block.lang_members}>: <{$block.online_members}>
        </li>
        <li>
            <i class="icon-globe"></i>
            <{$block.lang_guests}>: <{$block.online_guests}>
        </li>
    </ul>
    <hr />
    <{$block.online_names}>
    <a href="javascript:openWithSelfMain('<{$xoops_url}>/misc.php?action=showpopups&amp;type=online','Online',420,350);" title="<{$block.lang_more}>">
        <{$block.lang_more}>
    </a>
</div>