<{include file="admin:system/admin_navigation.tpl"}>
<{include file="admin:system/admin_tips.tpl"}>
<{include file="admin:system/admin_buttons.tpl"}>
<{if $extensions_count}>	
	<table width='100%' cellspacing='1' class='outer'>
		<thead>
			<tr>
				<th class='txtcenter'><{translate key="CH_NUMBER_ID"}></th>
				<th class='txtcenter'><{translate key="NAME"}></th>
				<th class='txtcenter'><{translate key="VERSION"}></th>
				<th class='txtcenter'><{translate key="IMAGE"}></th>
				<th class='txtcenter'><{translate key="RELEASE"}></th>
				<th class='txtcenter'><{translate key="STATUS"}></th>
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
			<{foreach item=ext from=$extensions}>
				<tr class='<{cycle values='even,odd'}>'>
					<td class='txtcenter'><{$ext.id}></td>
					<td class='txtcenter'><{$ext.name}></td>
					<td class='txtcenter'><{$ext.version}></td>
					<td class='txtcenter'><img src='<{xoAppUrl uploads/tdmcreate/images/extensions}>/<{$ext.image}>' height='25px' title='<{$ext.name}>' alt='<{$ext.name}>' /></td>
					<td class='txtcenter'><{$ext.release}></td>
					<td class='txtcenter'><{$ext.status}></td>
					<td class='txtcenter'><{$ext.admin}></td>
					<td class='txtcenter'><{$ext.user}></td>
					<td class='txtcenter'><{$ext.submenu}></td>
					<td class='txtcenter'><{$ext.search}></td>
					<td class='txtcenter'><{$ext.comments}></td>
					<td class='txtcenter'><{$ext.notifications}></td>
					<td class='xo-actions txtcenter width6'>
						<a href='extensions.php?op=edit&amp;id=<{$ext.id}>' title='<{translate key="A_EDIT"}>'>
							<img src='<{xoAdminIcons edit.png}>' alt='<{translate key="A_EDIT"}>' /></a>
						<a href='extensions.php?op=delete&amp;id=<{$ext.id}>' title='<{translate key="A_DELETE"}>'>
							<img src='<{xoAdminIcons delete.png}>' alt='<{translate key="A_DELETE"}>' /></a>
					</td>
				</tr>
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