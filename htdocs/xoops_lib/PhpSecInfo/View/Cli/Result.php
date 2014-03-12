<?php
echo CLI_DIVIDER_2;
echo "| ".strtoupper($group_name)."\n";
echo CLI_DIVIDER_2;

foreach ($group_results as $test_name=>$test_results):
	echo strtoupper($test_name).": ";
	if ($group_name != 'Test Results Summary'):
		echo $this->_outputGetResultTypeFromCode($test_results['result'])."\n";
	endif;

	if ( isset($test_results['value_current'] ) || isset($test_results['value_recommended']) ):
		if (isset($test_results['value_current'])):
			echo "Current: {$test_results['value_current']}\n";
		endif;
		if (isset($test_results['value_recommended'])):
			echo "Recommended: {$test_results['value_recommended']}\n";
		endif;
	endif;

	echo wordwrap("Message: ".strip_tags(trim( preg_replace("/(\s+)/", ' ', $test_results['message']) ))."\n", 78);
	if (isset($test_results['moreinfo_url']) && $test_results['moreinfo_url']):
		echo "More: ".$test_results['moreinfo_url']."\n";
	endif;
	echo "\n";
endforeach;
?>