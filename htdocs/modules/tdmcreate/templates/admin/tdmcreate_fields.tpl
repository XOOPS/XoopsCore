<{include file="admin:system/admin_navigation.tpl"}>
<{include file="admin:system/admin_tips.tpl"}>
<{include file="admin:system/admin_buttons.tpl"}>
<{if $fields_list}>
	<{if $tables_count}>
		<table width='100%' cellspacing='1' class='outer'>
			<thead>
				<tr>
					<th class='txtcenter'><{translate key="ID"}></th>
					<th class='txtcenter'><{translate key="NAME"}></th>
					<th class='txtcenter'><{translate key="FIELDS"}></th>
					<th class='txtcenter'><{translate key="IMAGE"}></th>
					<th class='txtcenter'><{translate key="ADMIN"}></th>
					<th class='txtcenter'><{translate key="USER"}></th>
					<th class='txtcenter'><{translate key="SUBMENU"}></th>
					<th class='txtcenter'><{translate key="SEARCH"}></th>
					<th class='txtcenter'><{translate key="COMMENTS"}></th>
					<th class='txtcenter'><{translate key="NOTIFICATIONS"}></th>
					<th class='txtcenter'><{translate key="ACTION"}></th>
				</tr>
			</thead>
			<tbody>
				<{foreach item=table from=$tables}>
					<tr class="<{cycle values='even,odd'}>">
						<td class='txtcenter'><{$table.id}></td>
						<td class='txtcenter'><{$table.name}></td>
						<td class='txtcenter'><{$table.nbfields}></td> <!-- uploads/tdmcreate/images/tables -->
						<td class='txtcenter'><img src="<{xoAppUrl modules/tdmcreate/icons/32}>/<{$table.image}>" height='25px' title='<{$table.name}>' alt='<{$table.name}>' /></td>
						<td class='center'><img src="<{if $table.admin}><{xoAppUrl 'modules/tdmcreate/icons/16/green.gif'}>
										<{else}><{xoAppUrl 'modules/tdmcreate/icons/16/red.gif'}><{/if}>" /></td>
						<td class='center'><img src="<{if $table.user}><{xoAppUrl 'modules/tdmcreate/icons/16/green.gif'}>
										<{else}><{xoAppUrl 'modules/tdmcreate/icons/16/red.gif'}><{/if}>" /></td>
						<td class='center'><img src="<{if $table.submenu}><{xoAppUrl 'modules/tdmcreate/icons/16/green.gif'}>								        
										<{else}><{xoAppUrl 'modules/tdmcreate/icons/16/red.gif'}><{/if}>" /></td>
						<td class='center'><img src="<{if $table.search}><{xoAppUrl 'modules/tdmcreate/icons/16/green.gif'}>
										<{else}><{xoAppUrl 'modules/tdmcreate/icons/16/red.gif'}><{/if}>" /></td>
						<td class='center'><img src="<{if $table.comments}><{xoAppUrl 'modules/tdmcreate/icons/16/green.gif'}>
										<{else}><{xoAppUrl 'modules/tdmcreate/icons/16/red.gif'}><{/if}>" /></td>
						<td class='center'>
									<img src="<{if $table.notifications}><{xoAppUrl 'modules/tdmcreate/icons/16/green.gif'}>
										<{else}><{xoAppUrl 'modules/tdmcreate/icons/16/red.gif'}><{/if}>" />
						</td>
						<td class='xo-actions txtcenter width6'>
							<a href='tables.php?op=edit&amp;id=<{$table.id}>' title='<{translate key="A_EDIT"}>'>
								<img src="<{xoAdminIcons 'edit.png'}>" alt='<{translate key="A_EDIT"}>' /></a>
							<a href='tables.php?op=delete&amp;id=<{$table.id}>' title='<{translate key="A_DELETE"}>'>
								<img src="<{xoAdminIcons 'delete.png'}>" alt='<{translate key="A_DELETE"}>' /></a>
						</td>
					</tr>
					<{if $fields_count > 0}>
						<{foreach item=field from=$fields}>
							<tr class='<{cycle values='even,odd'}>'>
								<td class='txtcenter'><{$field.id}></td>
								<td class='txtcenter'><{$field.tid}></td>
								<td class='txtcenter'><{$field.name}></td>
								<td class='txtcenter' colspan='3'>&nbsp;</td>
								<td class='txtcenter'><{$field.nbfields}></td>
								<td class='txtcenter'><img src="<{if $mod.admin}><{xoAppUrl 'modules/tdmcreate/icons/16/green.gif'}>
								          <{else}><{xoAppUrl 'modules/tdmcreate/icons/16/red.gif'}><{/if}>" /><{$field.blocks}></td>
								<td class='txtcenter'><img src="<{if $mod.admin}><{xoAppUrl 'modules/tdmcreate/icons/16/green.gif'}>
								          <{else}><{xoAppUrl 'modules/tdmcreate/icons/16/red.gif'}><{/if}>" /><{$field.admin}></td>
								<td class='txtcenter'><img src="<{if $mod.admin}><{xoAppUrl 'modules/tdmcreate/icons/16/green.gif'}>
								          <{else}><{xoAppUrl 'modules/tdmcreate/icons/16/red.gif'}><{/if}>" /><{$field.user}></td>
								<td class='xo-actions txtcenter width6'>
									<a href='fields.php?op=edit&amp;id=<{$field.id}>' title='<{translate key="A_EDIT"}>'>
										<img src="<{xoAdminIcons 'edit.png'}>" alt='<{translate key="A_EDIT"}>' /></a>	    
									<a href='fields.php?op=delete&amp;id=<{$field.id}>' title='<{translate key="A_DELETE"}>'>
										<img src="<{xoAdminIcons 'delete.png'}>" alt='<{translate key="A_DELETE"}>' /></a>
								</td>
							</tr>
						<{/foreach}>
					<{/if}>
				<{/foreach}>
			</tbody>
		</table><br />
		<{if $pagenav != ''}>
			<{$pagenav}>	   
		<{/if}>	
	<{/if}>
<{else}>    
	<!-- Display form (add,edit) -->
	<{$form}>
<{/if}>
<{if $error_message}>
<div class="alert alert-error">
    <strong><{$error_message}></strong>
</div>
<{/if}>