<?php
/**
 * @todo write RSS view
 */
// header('Content-type: text/plain');
echo '<?xml version="1.0"?>';
?>
<rss version="2.0">
  <channel>
    <title>PhpSecInfo</title>
    <link></link>
    <description>Security Information About PHP</description>
    <!-- <language>en-us</language> -->
    <pubDate>Tue, 10 Jun 2003 04:00:00 GMT</pubDate>
    <lastBuildDate>Tue, 10 Jun 2003 09:41:01 GMT</lastBuildDate>
    <generator>PHPSecInfo</generator>

	<?php
	 	// This is kind of a lot of logic for a view, but...
		foreach ($this->test_results as $group_name=>$group_results) {

			$this->_outputRenderTable($group_name, $group_results);
		}

		$this->_outputRenderNotRunTable();
	?>

  </channel>
</rss>