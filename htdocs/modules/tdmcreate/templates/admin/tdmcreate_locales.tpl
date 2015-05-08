<{include file="admin:system/admin_navigation.tpl"}>
<{include file="admin:system/admin_tips.tpl"}>
<{include file="admin:system/admin_buttons.tpl"}>
<{if $locales_count}>
	<table width='100%' cellspacing='1' class='outer'>
		<thead>
			<tr>
				<th class='txtcenter'><{translate key="ID"}></th>
				<th class='txtcenter'><{translate key="LOCALE_MID"}></th>
				<th class='txtcenter'><{translate key="LOCALE_FILE_NAME"}></th>
				<th class='txtcenter'><{translate key="LOCALE_DEFINE"}></th>
				<th class='txtcenter'><{translate key="LOCALE_DESCRIPTION"}></th>
				<th class='txtcenter'><{translate key="ACTION"}></th>
			</tr>
		</thead>
		<tbody>
			<{foreach item=locale from=$locales}>
				<tr class="<{cycle values='even,odd'}>">
					<td class='txtcenter'><{$locale.id}></td>
					<td class='txtcenter'><{$locale.mid}></td>
					<td class='txtcenter'><{$locale.file}></td>					
					<td class='txtcenter'><{$locale.define}></td>
					<td class='txtcenter'><{$locale.description}></td>
					<td class='xo-actions txtcenter width6'>
						<a href='locale.php?op=edit&amp;id=<{$locale.id}>' title='<{translate key="A_EDIT"}>'>
							<img src="<{xoAdminIcons 'edit.png'}>" alt='<{translate key="A_EDIT"}>' /></a>
						<a href='locale.php?op=delete&amp;id=<{$locale.id}>' title='<{translate key="A_DELETE"}>'>
							<img src="<{xoAdminIcons 'delete.png'}>" alt='<{translate key="A_DELETE"}>' /></a>
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