<{include file="admin:system/admin_navigation.tpl"}>
<{include file="admin:system/admin_tips.tpl"}>
<{include file="admin:system/admin_buttons.tpl"}>
<{if $building_count}>
	<table width='100%' cellspacing='1' class='outer'>
		<thead>
			<tr>
			    <th class='center'><{translate key="CH_NUMBER_ID"}>
				<th class='center'><{translate key="BUILDED_LIST"}></th>
				<th class='center'><{translate key="DONE"}></th>
				<th class='center'><{translate key="NOT_DONE"}></th>				
			</tr>
		</thead>
		<tbody>
			<{foreach item=build from=$building}>
				<tr class='<{cycle values='even,odd'}>'>
					<td class='center'><{$build.number}></td>
					<td class='center'><{$build.buildedlist}></td>
					<td class='center'><{$build.done}></td>
					<td class='center'><{$build.notdone}></td>
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