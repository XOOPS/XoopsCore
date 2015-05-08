<{include file="admin:system/admin_navigation.tpl"}>
<{include file="admin:system/admin_tips.tpl"}>
<{include file="admin:system/admin_buttons.tpl"}>
<{if $modules_count}>	
	<table width='100%' cellspacing='1' class='outer'>
		<thead>
			<tr>
				<th class='txtcenter'><{translate key="ID"}></th>
				<th class='txtcenter'><{translate key="NAME"}></th>
				<th class='txtcenter'><{translate key="IMAGE"}></th>
				<th class='txtcenter'><{translate key="FIELDS"}></th>
                <th class='txtcenter'><{translate key="BLOCKS"}></th>
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
			<{foreach item=module from=$modules}>
				<tr class="<{cycle values='even,odd'}>">
					<td class='center'><{$module.id}></td>
					<td class='center'><{$module.name}></td>
					<td class='center'><img src="<{xoAppUrl uploads/tdmcreate/images/modules}>/<{$module.image}>" height='20px' title='<{$module.name}>' alt='<{$module.name}>' /></td>
					<td class='center'><img src="<{xoAppUrl 'modules/tdmcreate/icons/16/fields.png'}>" /></td>
					<td class='center'><img src="<{xoAppUrl 'modules/tdmcreate/icons/16/blocks.png'}>" /></td>
					<td class='center'><img src="<{if $module.admin}><{xoAppUrl 'modules/tdmcreate/icons/16/green.png'}>
								          <{else}><{xoAppUrl 'modules/tdmcreate/icons/16/red.png'}><{/if}>" /></td>
					<td class='center'><img src="<{if $module.user}><{xoAppUrl 'modules/tdmcreate/icons/16/green.png'}>
								          <{else}><{xoAppUrl 'modules/tdmcreate/icons/16/red.png'}><{/if}>" /></td>
					<td class='center'><img src="<{if $module.submenu}><{xoAppUrl 'modules/tdmcreate/icons/16/green.png'}>
								          <{else}><{xoAppUrl 'modules/tdmcreate/icons/16/red.png'}><{/if}>" /></td>
					<td class='center'><img src="<{if $module.search}><{xoAppUrl 'modules/tdmcreate/icons/16/green.png'}>
								          <{else}><{xoAppUrl 'modules/tdmcreate/icons/16/red.png'}><{/if}>" /></td>
					<td class='center'><img src="<{if $module.comments}><{xoAppUrl 'modules/tdmcreate/icons/16/green.png'}>
								          <{else}><{xoAppUrl 'modules/tdmcreate/icons/16/red.png'}><{/if}>" /></td>
					<td class='center'><img src="<{if $module.notifications}><{xoAppUrl 'modules/tdmcreate/icons/16/green.png'}>
								          <{else}><{xoAppUrl 'modules/tdmcreate/icons/16/red.png'}><{/if}>" /></td>
					<td class='xo-actions txtcenter width6'>
						<a href='modules.php?op=edit&amp;mod_id=<{$module.id}>' title='<{translate key="A_EDIT"}>'>
							<img src="<{xoAdminIcons 'edit.png'}>" alt='<{translate key="A_EDIT"}>' /></a>
						<a href='modules.php?op=delete&amp;mod_id=<{$module.id}>' title='<{translate key="A_DELETE"}>'>
							<img src="<{xoAdminIcons 'delete.png'}>" alt='<{translate key="A_DELETE"}>' /></a>
					</td>
				</tr>
				<{if $tables_count > 0}>
					<{foreach item=table from=$module.tables}>
						<tr class="<{cycle values='even,odd'}>">
							<td class='center'><{$table.id}></td>						
							<td class='center'><{$table.name}></td>
						<!-- uploads/tdmcreate/images/tables -->
							<td class='center'><img src="<{xoAppUrl media/xoops/images/icons/32}>/<{$table.image}>" title='<{$table.name}>' alt='<{$table.name}>' height='20px' /></td> 
							<td class='center'><{$table.nbfields}></td>
							<td class='center'><img src="<{if $table.blocks}><{xoAppUrl 'modules/tdmcreate/icons/16/green.png'}>
								          <{else}><{xoAppUrl 'modules/tdmcreate/icons/16/red.png'}><{/if}>" /></td>
							<td class='center'><img src="<{if $table.admin}><{xoAppUrl 'modules/tdmcreate/icons/16/green.png'}>
								          <{else}><{xoAppUrl 'modules/tdmcreate/icons/16/red.png'}><{/if}>" /></td>
							<td class='center'><img src="<{if $table.user}><{xoAppUrl 'modules/tdmcreate/icons/16/green.png'}>
								          <{else}><{xoAppUrl 'modules/tdmcreate/icons/16/red.png'}><{/if}>" /></td>
							<td class='center'><img src="<{if $table.submenu}><{xoAppUrl 'modules/tdmcreate/icons/16/green.png'}>								        
								          <{else}><{xoAppUrl 'modules/tdmcreate/icons/16/red.png'}><{/if}>" /></td>
							<td class='center'><img src="<{if $table.search}><{xoAppUrl 'modules/tdmcreate/icons/16/green.png'}>
								          <{else}><{xoAppUrl 'modules/tdmcreate/icons/16/red.png'}><{/if}>" /></td>
							<td class='center'><img src="<{if $table.comments}><{xoAppUrl 'modules/tdmcreate/icons/16/green.png'}>
								          <{else}><{xoAppUrl 'modules/tdmcreate/icons/16/red.png'}><{/if}>" /></td>
							<td class='center'><img src="<{if $table.notifications}><{xoAppUrl 'modules/tdmcreate/icons/16/green.png'}>
								          <{else}><{xoAppUrl 'modules/tdmcreate/icons/16/red.png'}><{/if}>" />
							</td>
							<td class='xo-actions txtcenter width5'>
								<a href='tables.php?op=edit&amp;table_id=<{$table.id}>' title='<{translate key="A_EDIT"}>'>
									<img src="<{xoAdminIcons 'edit.png'}>" alt='<{translate key="A_EDIT"}>' /></a>								
								<a href='tables.php?op=delete&amp;table_id=<{$table.id}>' title='<{translate key="A_DELETE"}>'>
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
<!-- Display form (add,edit) -->
<{if $error_message}>
<div class="alert alert-error">
    <strong><{$error_message}></strong>
</div>
<{/if}>
<{$form}>