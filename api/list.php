<?php
	header("Content-type: application/json");
	header('Access-Control-Allow-Origin: *');

	$year = $_GET['year'];
	$count = $_GET['count'];

	echo (file_get_contents("http://koivu.luomus.fi/talvilinnut/census.php?year=$year&census=$count&json"));
?>