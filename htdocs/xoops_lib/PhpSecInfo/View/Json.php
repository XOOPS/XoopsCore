<?php
header('Content-type: application/json');
header('Content-Disposition: attachment; filename="phpsecinfo.json"');


echo json_encode($this->test_results);

// This is kind of a lot of logic for a view, but...
// foreach ($this->test_results as $group_name=>$group_results) {
// 	
// 	$this->_outputRenderTable($group_name, $group_results);
// }
// 
// $this->_outputRenderNotRunTable();

/* stats will probably be handled by the app reading the csv */
//$this->_outputRenderStatsTable();

?>