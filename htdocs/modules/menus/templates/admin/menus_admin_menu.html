<{include file="admin:system/admin_navigation.tpl"}>
<{include file="admin:system/admin_tips.tpl"}>
<{include file="admin:system/admin_buttons.tpl"}>




<{if $select}>
<div style="margin-bottom:10px; float: right">
    <form class="form-inline" action="admin_menu.php?op=list" method="POST">
      <select class="span3" name="menu_id" id="menu_id">
      <{foreach item=title from=$menus_list key=id }>
        <option value="<{$id}>"<{if $menu_id == $id}> selected='selected'<{/if}>><{$title}></option>
      <{/foreach}>
      </select>
      <button type="submit" class="btn" name="btn" ><{$smarty.const._AM_MENUS_ACTION_GOTO_MENU}></button>
    </form>
</div>
<{/if}>

<{if $count}>
<table id="xo-menu-sorter" class="outer tablesorter">
    <thead>
        <tr>
            <th class="txtleft"><{$smarty.const._AM_MENUS_MENU_TITLE}></th>
            <th class="txtleft"><{$smarty.const._AM_MENUS_MENU_LINK}></th>
            <th class="txtcenter"><{$smarty.const._AM_MENUS_MENU_HOOKS}></th>
            <th class="txtcenter"><{$smarty.const._AM_MENUS_MENU_GROUPS}></th>
            <th class="txtcenter"><{$smarty.const._AM_MENUS_MENU_VISIBLE}></th>
            <th class="txtcenter width10"><{translate key='OPTIONS'}></th>
        </tr>
    </thead>
    <tbody>
        <{foreach item=menu from = $menus}>
            <{assign var='padding' value=''}>
            <{section name=foo loop=$menu.level}>
                <{assign var='padding' value="$padding-> "}>
            <{/section}>
            <tr class="odd txtleft">
                <td class="txtleft"><{$padding}><{$menu.title}></td>
                <td class="txtleft"><{$padding}><{$menu.link}></td>
                <td class="txtcenter">
                <{foreach item=hook from=$menu.hooks name=hooksloop}>
                    <{$hook}><{if !$smarty.foreach.hooksloop.last}>,<{/if}>
                <{/foreach}>
                </td>
                <td class="txtcenter">
                <{foreach item=group from=$menu.groups name=groupsloop}>
                    <{$group}><{if !$smarty.foreach.groupsloop.last}>,<{/if}>
                <{/foreach}>
                </td>
                <td class="txtcenter">
                    <a href="admin_menu.php?menu_id=<{$menu_id}>&amp;op=toggle&amp;visible=<{$menu.visible}>&amp;id=<{$menu.id}>"><img src="<{xoModuleIcons16}>/<{$menu.visible}>.png" title="<{$smarty.const._AM_MENUS_ACTION_TOGGLE}>" alt="<{$smarty.const._AM_MENUS_ACTION_TOGGLE}>" /></a>
                </td>
                <td>
                    <a href="admin_menu.php?menu_id=<{$menu_id}>&amp;op=add&amp;pid=<{$menu.id}>"><img src="<{xoModuleIcons16 add.png}>" title="<{translate key='A_ADD'}>" alt="<{translate key='A_ADD'}>" /></a>
                    <{if $menu.up_weight}>
                    <a href="admin_menu.php?menu_id=<{$menu_id}>&amp;op=move&amp;weight=<{$menu.up_weight}>&amp;id=<{$menu.id}>"><img src="<{xoModuleIcons16 up.png}>" title="<{$smarty.const._AM_MENUS_ACTION_UP}>" alt="<{$smarty.const._AM_MENUS_ACTION_UP}>" /></a>
                    <{else}>
                    <img src="<{xoModuleIcons16 up_off.png}>" title="<{$smarty.const._AM_MENUS_ACTION_UP}>" alt="<{$smarty.const._AM_MENUS_ACTION_UP}>" />
                    <{/if}>

                  <{if $menu.down_weight}>
                    <a href="admin_menu.php?menu_id=<{$menu_id}>&amp;op=move&amp;weight=<{$menu.down_weight}>&amp;id=<{$menu.id}>"><img src="<{xoModuleIcons16 down.png}>" title="<{$smarty.const._AM_MENUS_ACTION_DOWN}>" alt="<{$smarty.const._AM_MENUS_ACTION_DOWN}>" /></a>
                  <{else}>
                    <img src="<{xoModuleIcons16 down_off.png}>" title="<{$smarty.const._AM_MENUS_ACTION_DOWN}>" alt="<{$smarty.const._AM_MENUS_ACTION_DOWN}>" />
                  <{/if}>

                  <a href="admin_menu.php?menu_id=<{$menu_id}>&amp;op=edit&amp;id=<{$menu.id}>"><img src="<{xoModuleIcons16 edit.png}>" title="<{translate key ='A_EDIT'}>" alt="<{translate key ='A_EDIT'}>" /></a>
                  <a href="admin_menu.php?menu_id=<{$menu_id}>&amp;op=del&amp;id=<{$menu.id}>"><img src="<{xoModuleIcons16 delete.png}>" title="<{translate key ='A_DELETE'}>" alt="<{translate key ='A_DELETE'}>" /></a>

                </td>
              </tr>
        <{/foreach}>
    </tbody>
</table>
<{/if}>
<{if $error_message|default?false}>
<div class="clear"></div>
<div class="alert alert-error">
    <strong><{$error_message}></strong>
</div>
<{/if}>
<{$form|default:''}>
