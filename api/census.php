<?php
	header("Content-type: text/xml");
	header('Access-Control-Allow-Origin: *');

	if (isset($_GET["id"]))	{
		$id = $_GET["id"];
	}

	echo (file_get_contents("http://hatikka.fi/?page=view&id=" . $id . "&source=2&xsl=false"));
?>