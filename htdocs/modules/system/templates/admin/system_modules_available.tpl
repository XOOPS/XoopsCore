<{foreach item=install from=$modules_available}>
<div class="xo-module rounded">
    <div class="module_card">
        <div class="module_image">
            <a class="rounded" style="background: url('<{$xoops_url}>/modules/<{$install->getInfo(dirname)}>/<{$install->getInfo(image)}>')  no-repeat scroll center center transparent;" href="<{$xoops_url}>/modules/system/admin.php?fct=modulesadmin&amp;op=install&amp;module=<{$install->getInfo(dirname)}>">
                <span class="rounded">&nbsp;</span>
            </a>
        </div>
        <div class="module_data">
            <div class="name"><a class="xo-tooltip" href="javascript:" onclick="module_Install('<{$install->getInfo(dirname)}>')" title="<{translate key='A_INSTALL'}> <{$install->getInfo(name)}>"><{$install->getInfo(name)}></a></div>
            <div class="data">
                <div class="hide install-<{$install->getInfo(mid)}>"><span class="bold"><{translate key='C_VERSION'}></span>&nbsp;<{$install->getInfo(version)}></div>
                <div class="hide install-<{$install->getInfo(mid)}>"><span class="bold"><{translate key='C_AUTHOR'}></span>&nbsp;<{$install->getInfo(author)}></div>
                <div class="hide install-<{$install->getInfo(mid)}>"><span class="bold"><{translate key='C_LICENSE'}></span>&nbsp;<{$install->getInfo(license)}></div>
                <div class="hide install-<{$install->getInfo(mid)}>"><{$install->getInfo(description)}></div>
            </div>
        </div>
    </div>
    <div class="module_options">
        <a class="xo-tooltip" href="javascript:" onclick="$('.install-<{$install->getInfo(mid)}>').toggle('slow');" title="<{translate key='DETAILS'}>"><span class="ico-magnifier"></span><{translate key='DETAILS'}></a>
        <a class="xo-tooltip" href="javascript:" onclick="module_Install('<{$install->getInfo(dirname)}>')" title="<{translate key='A_INSTALL'}> <{$install->getInfo(name)}>"><span class="ico-application-go"></span><{translate key='A_INSTALL'}></a>
        <!--<a href="<{$xoops_url}>/modules/system/admin.php?fct=modulesadmin&op=delete&module=<{$install->getInfo(dirname)}>"><span class="ico-delete"></span><{translate key='A_DELETE'}></a>-->
    </div>
</div>
<div id="data-<{$install->getInfo(dirname)}>" class="hide">
    <div class="module_card">
        <div class="module_image">
            <a class="rounded xo-tooltip" style="background: url('<{$xoops_url}>/modules/<{$install->getInfo(dirname)}>/<{$install->getInfo(image)}>')  no-repeat scroll center center transparent;" title="<{$install->getInfo(name)}>">
                <span class="rounded">&nbsp;</span>
            </a>
        </div>
        <div class="module_data">
            <div class="name"><{$install->getInfo(name)}></div>
            <div class="data">
                <div><span class="bold"><{translate key='C_VERSION'}></span>&nbsp;<{$install->getInfo(version)}></div>
                <div><span class="bold"><{translate key='C_AUTHOR'}></span>&nbsp;<{$install->getInfo(author)}></div>
                <div><span class="bold"><{translate key='C_LICENSE'}></span>&nbsp;<{$install->getInfo(license)}></div>
            </div>
        </div>
        <div class="module_detail">
            <{$install->getInfo(description)}>
        </div>
    </div>
</div>
<{/foreach}>