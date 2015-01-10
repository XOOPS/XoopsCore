<{if $collapsable_heading|default:false}>
<script type="text/javascript"><!--
function goto_URL(object)
{
    window.location.href = object.options[object.selectedIndex].value;
}

function toggle(id)
{
    if (document.getElementById) {
        obj = document.getElementById(id);
    }
    if (document.all) {
        obj = document.all[id];
    }
    if (document.layers) {
        obj = document.layers[id];
    }
    if (obj) {
        if (obj.style.display == "none") {
            obj.style.display = "";
        } else {
            obj.style.display = "none";
        }
    }
    return false;
}

var iconClose = new Image();
iconClose.src = 'images/links/close12.gif';
var iconOpen = new Image();
iconOpen.src = 'images/links/open12.gif';

function toggleIcon(iconName)
{
    if (document.images[iconName].src == window.iconOpen.src) {
        document.images[iconName].src = window.iconClose.src;
    } else if (document.images[iconName].src == window.iconClose.src) {
        document.images[iconName].src = window.iconOpen.src;
    }
    return;
}

//-->
</script><{/if}>

<{if $publisher_display_breadcrumb|default:false}>

<div class="publisher_headertable">
    <{if $module_home|default:false}>
    <span class="publisher_modulename"><{$module_home}></span> <{if $title_and_welcome|default:false}>
    <span><{$lang_mainintro}></span> <{/if}> <{/if}> <{if $categoryPath|default:false}>
			<span class="publisher_breadcrumb">
			<{if $module_home|default:false}>
				&gt;
			<{/if}>
		  	<{$categoryPath}></span>

    <{/if}>
</div><{/if}>
