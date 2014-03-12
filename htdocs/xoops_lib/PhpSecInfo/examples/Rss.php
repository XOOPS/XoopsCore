<?php

require_once('../PhpSecInfo/PhpSecInfo.php');

$opts['format'] = 'Rss'; // the name of the main view file, minus .php

// instantiate the class
$psi = new PhpSecInfo($opts);

// load and run all tests
$psi->loadAndRun();

// grab the standard results output as a string
$output = $psi->getOutput();

// send it to the browser
echo $output;


?>