<div class="wrapper1">
	<div class="wrapper2">
		<div class="nav-wrapper">
			<div class="nav-left"></div>
			<div class="nav">
				<ul id="navigation">
					<{foreach item=main key=key from=$block.menus name=mainloop}>
						<{if $main.selected}>
							<li class="active">
						<{else}>
							<li>
						<{/if}>
								<a href="<{$main.link}>">
									<span class="menu-left"></span>
									<span class="menu-mid"><{$main.title}></span>
									<span class="menu-right"></span>
								</a>
													
								<{if $main.submenus}>
									<div class="sub">
										<ul>
											<{foreach item=sub key=subkey from=$main.submenus name=subloop}>
												<li><a href="<{$sub.link}>"><{$sub.title}></a></li>
											<{/foreach}>
										</ul>
									<div class="btm-bg"></div>
									</div>	
									
								<{/if}>
							</li>
					<{/foreach}>
				</ul>
			</div>
			<div class="nav-right"></div>
		</div>
	</div>
</div>