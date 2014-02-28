<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "DTD/xhtml1-transitional.dtd">
<html>
<head>
	<title>Security Information About PHP</title>
	<meta http-equiv="Content-Type" content="text/html;charset=utf-8" />
	<meta name="robots" content="noindex,nofollow" />
	<style type="text/css">
	/*
	.phpblue { #777BB4 }
	#706464
	#C7C6B3
	#7B8489
	#646B70
	*/

	BODY {
		background-color:#C7C6B3;
		color: #333333;
		margin: 0;
		padding: 0;
		text-align:center;
	}

	BODY, TD, TH, H1, H2 {
		font-family: Helvetica, Arial, Sans-serif;
	}

	DIV.logo {
		float:right;
	}

	A:link, A:hover, A:visited {
		color: #000099;
		text-decoration: none;
	}

	A:hover {
		text-decoration: underline !important;
	}

	DIV.container {
		text-align:center;
		width:100%;
		margin-left:auto;
		margin-right:auto;
	}

	DIV.header {
		width:100%;
		text-align: left;
		border-collapse: collapse;
	}

	DIV.header {
		background-color:#4C5B74;
		color:white;
		border-bottom: 3px solid #333333;
		padding:.5em;
	}

	DIV.header H1, DIV.header H2 {
		padding:0;
		margin: 0;
	}

	DIV.header H2 {
		font-size: 0.9em;
	}

	DIV.header a:link, DIV.header a:visited, DIV.header a:hover {
		color:#ffff99;
	}

	H2.result-header {
		margin:1em 0 .5em 0;
	}

	TABLE.results {
		border-collapse:collapse;
		width:100%;
		text-align: left;
	}

	TD, TH {
		padding:0.5em;
		border: 2px solid #333333;
	}

	TR.header {
		background-color:#706464;
		color:white;
	}

	TD.label {
		font-weight:bold;
		background-color:#7B8489;
		border:2px solid #333333;
	}

	TD.value {
		border:2px solid #333333;
	}

	.centered {
		text-align: center;
	}
	.centered TABLE {
		text-align: left;
	}
	.centered TH { text-align: center; }

	.result { font-size:1.2em; font-weight:bold; margin-bottom:.5em;}

	.message { line-height:1.4em; }

	TABLE.values {
		padding:.5em;
		margin:.5em;
		text-align:left;
		margin:0;
		width:90%;
	}
	TABLE.values TD {
		font-size:.9em;
		border:none;
		padding:.4em;
	}
	TABLE.values TD.label {
		font-weight:bold;
		text-align:right;
		width:40%;
	}

	DIV.moreinfo {
		text-align:right;
	}

	.value-ok {background-color:#009900;color:#ffffff;}
	.value-ok a:link, .value-ok a:hover, .value-ok a:visited {color:#FFFF99;font-weight:bold;background-color:transparent;text-decoration:none;}
	.value-ok table td {background-color:#33AA33; color:#ffffff;}

	.value-notice {background-color:#FFA500;color:#000000;}
	.value-notice a:link, .value-notice a:hover, .value-notice a:visited {color:#000099;font-weight:bold;background-color:transparent;text-decoration:none;}
	.value-notice td {background-color:#FFC933; color:#000000;}

	.value-warn {background-color:#990000;color:#ffffff;}
	.value-warn a:link, .value-warn a:hover, .value-warn a:visited {color:#FFFF99;font-weight:bold;background-color:transparent;text-decoration:none;}
	.value-warn td {background-color:#AA3333; color:#ffffff;}

	.value-notrun {background-color:#cccccc;color:#000000;}
	.value-notrun a:link, .value-notrun a:hover, .value-notrun a:visited {color:#000099;font-weight:bold;background-color:transparent;text-decoration:none;}
	.value-notrun td {background-color:#dddddd; color:#000000;}

	.value-error {background-color:#F6AE15;color:#000000;font-weight:bold;}
	.value-error td {background-color:#F6AE15; color:#000000;}
	</style>

</head>
<body>
	<div class="header">
		<h1>Security Information About PHP</h1>
		<h2>PhpSecInfo Version <?php echo PHPSECINFO_VERSION ?>; build <?php echo PHPSECINFO_BUILD ?> &middot; <a href="<?php echo PHPSECINFO_URL ?>">Project Homepage</a></h2>
	</div>

	<div class="container">


		<?php
			// This is kind of a lot of logic for a view, but...
			foreach ($this->test_results as $group_name=>$group_results) {
				$this->_outputRenderTable($group_name, $group_results);
			}

			$this->_outputRenderNotRunTable();

			$this->_outputRenderStatsTable();

		?>

	</div>
</body>
</html>
