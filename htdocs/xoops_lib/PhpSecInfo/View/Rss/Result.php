<?php foreach ($group_results as $test_name=>$test_results): ?>
<item>
	
	<title><?php echo htmlspecialchars($test_name, ENT_QUOTES) ?></title>
	<!-- <link>http://liftoff.msfc.nasa.gov/news/2003/news-starcity.asp</link> -->
	<description>
		<![CDATA[
		<?php if ($group_name != 'Test Results Summary'): ?>
			<div class="result"><?php echo $this->_outputGetResultTypeFromCode($test_results['result']) ?></div>
		<?php endif; ?>
		<div class="message"><?php echo $test_results['message'] ?></div>

		<?php if ( isset($test_results['value_current'] ) || isset($test_results['value_recommended']) ): ?>
			<table class="values">
			<?php if (isset($test_results['value_current'])): ?>
				<tr>
					<td class="label">Current Value:</td>
					<td><?php echo $test_results['value_current'] ?></td>
				</tr>
			<?php endif;?>
			<?php if (isset($test_results['value_recommended'])): ?>
				<tr>
					<td class="label">Recommended Value:</td>
					<td><?php echo $test_results['value_recommended'] ?></td>
				</tr>
			<?php endif; ?>
			</table>
		<?php endif; ?>

		<?php if (isset($test_results['moreinfo_url']) && $test_results['moreinfo_url']): ?>
			<div class="moreinfo"><a href="<?php echo $test_results['moreinfo_url']; ?>">More information &raquo;</a></div>
		<?php endif; ?>
		]]>
	</description>
	<!--<pubDate>Tue, 03 Jun 2003 09:39:21 GMT</pubDate>-->
	<!--<guid>http://liftoff.msfc.nasa.gov/2003/06/03.html#item573</guid>-->
</item>
<?php endforeach; ?>
