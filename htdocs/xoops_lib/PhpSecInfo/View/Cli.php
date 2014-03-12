<?php
define ('CLI_DIVIDER_1', "===============================================================================\n");
define ('CLI_DIVIDER_2', "-------------------------------------------------------------------------------\n");


echo CLI_DIVIDER_1;
echo "SECURITY INFORMATION ABOUT PHP\n";
echo "PhpSecInfo Version ".PHPSECINFO_VERSION ." build ".PHPSECINFO_BUILD."\n";
echo "Project Homepage: ".PHPSECINFO_URL."\n";
echo CLI_DIVIDER_1;

// This is kind of a lot of logic for a view, but...
foreach ($this->test_results as $group_name=>$group_results) {
	$this->_outputRenderTable($group_name, $group_results);
}

$this->_outputRenderNotRunTable();

$this->_outputRenderStatsTable();

echo CLI_DIVIDER_1;
?>