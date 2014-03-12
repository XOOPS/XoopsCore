		<h2 class="result-header"><?php echo htmlspecialchars($group_name, ENT_QUOTES) ?></h2>

		<table class="results">
		<tr class="header">
			<th>Test</th>
			<th>Result</th>
		</tr>
		<?php foreach ($group_results as $test_name=>$test_results): ?>

		<tr>
			<td class="label"><?php echo htmlspecialchars($test_name, ENT_QUOTES) ?></td>
			<td class="value <?php echo $this->_outputGetCssClassFromResult($test_results['result']) ?>">
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
			</td>
		</tr>

		<?php endforeach; ?>
		</table><br />
