<?php
foreach($group_results as $test_name=>$test_results) {

	echo strtoupper($group_name).',';
	echo strtoupper($test_name).',';
	if ($group_name != 'Test Results Summary'):
		echo $this->_outputGetResultTypeFromCode($test_results['result']).',';
	endif;

	
	echo $test_results['value_current'].',';
	echo $test_results['value_recommended'].',';
	
	echo '"'.str_replace('"', '""',
					strip_tags(
						trim(
							preg_replace("/(\s+)/im", ' ', $test_results['message'])
						)
					)
				) . '",';
	echo $test_results['moreinfo_url'];

	echo "\n";
}
